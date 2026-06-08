<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Mail\LoginOtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // ─────────────────────────────────────────
    // LOGIN
    // ─────────────────────────────────────────

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $throttleKey = Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());

        // Cek rate limit (maks 5 percobaan)
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            ActivityLogger::log('BRUTE_FORCE_ATTEMPT', "Percobaan login berulang gagal untuk email: " . $request->input('email'));
            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.",
            ]);
        }

        if (Auth::attempt($credentials, false)) {
            $user = Auth::user();
            Auth::logout(); // Belum login penuh — tunggu OTP

            // Cek status akun
            if ($user->status !== 'active') {
                RateLimiter::hit($throttleKey);
                ActivityLogger::log('LOGIN_FAILED', "Akun dinonaktifkan: " . $credentials['email']);
                return back()->withErrors([
                    'email' => 'Akun Anda telah dinonaktifkan. Silakan hubungi administrator.',
                ]);
            }

            RateLimiter::clear($throttleKey);

            // Generate OTP 6 digit
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $cacheKey = 'login_otp_' . md5($user->email);

            // Simpan OTP di cache (berlaku 5 menit)
            Cache::put($cacheKey, [
                'otp'      => $otp,
                'user_id'  => $user->id,
                'attempts' => 0,
            ], now()->addMinutes(5));

            // Simpan data pending di session
            $request->session()->put('otp_pending_email', $user->email);
            $request->session()->put('otp_pending_name', $user->name);
            $request->session()->put('otp_remember', $request->boolean('remember'));

            // Kirim OTP ke email
            try {
                Mail::to($user->email)->send(new LoginOtpMail($otp, $user->name));
            } catch (\Exception $e) {
                // Jika gagal kirim email, OTP tetap ada di cache (bisa dilihat di log)
                \Log::error('Gagal kirim OTP email: ' . $e->getMessage());
            }

            ActivityLogger::log('OTP_SENT', "OTP dikirim ke email: {$user->email}");

            return redirect()->route('login.otp');
        }

        RateLimiter::hit($throttleKey);
        ActivityLogger::log('LOGIN_FAILED', "Login gagal untuk email: " . $credentials['email']);

        return back()->withErrors([
            'email' => 'Email atau kata sandi tidak cocok.',
        ]);
    }

    // ─────────────────────────────────────────
    // OTP VERIFICATION
    // ─────────────────────────────────────────

    public function showOtp()
    {
        if (!session('otp_pending_email')) {
            return redirect()->route('login');
        }
        return view('auth.otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $email    = session('otp_pending_email');
        $remember = session('otp_remember', false);

        if (!$email) {
            return redirect()->route('login')->with('error', 'Sesi habis. Silakan login ulang.');
        }

        $cacheKey = 'login_otp_' . md5($email);
        $data     = Cache::get($cacheKey);

        // OTP tidak ada / sudah kedaluwarsa
        if (!$data) {
            session()->forget(['otp_pending_email', 'otp_pending_name', 'otp_remember']);
            return redirect()->route('login')->with('error', 'Kode OTP sudah kedaluwarsa. Silakan login ulang.');
        }

        // Terlalu banyak percobaan OTP
        if ($data['attempts'] >= 3) {
            Cache::forget($cacheKey);
            session()->forget(['otp_pending_email', 'otp_pending_name', 'otp_remember']);
            ActivityLogger::log('OTP_FAILED', "Melebihi batas percobaan OTP untuk: {$email}");
            return redirect()->route('login')->with('error', 'Terlalu banyak percobaan OTP. Silakan login ulang.');
        }

        // OTP salah
        if ($request->otp !== $data['otp']) {
            $data['attempts']++;
            Cache::put($cacheKey, $data, now()->addMinutes(5));
            $sisa = 3 - $data['attempts'];
            return back()->withErrors(['otp' => "Kode OTP salah. Sisa percobaan: {$sisa}."]);
        }

        // ✅ OTP BENAR — Login penuh
        Cache::forget($cacheKey);
        $user = User::find($data['user_id']);
        Auth::login($user, $remember);
        $request->session()->regenerate();
        session()->forget(['otp_pending_email', 'otp_pending_name', 'otp_remember']);

        ActivityLogger::log('LOGIN_SUCCESS', "User {$user->name} ({$user->role}) berhasil masuk dengan OTP.");

        return redirect()->intended(route('dashboard'));
    }

    public function resendOtp(Request $request)
    {
        $email = session('otp_pending_email');
        $name  = session('otp_pending_name');

        if (!$email) {
            return redirect()->route('login');
        }

        $cacheKey = 'login_otp_' . md5($email);

        // Generate OTP baru
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Cache::put($cacheKey, [
            'otp'      => $otp,
            'user_id'  => User::where('email', $email)->value('id'),
            'attempts' => 0,
        ], now()->addMinutes(5));

        try {
            Mail::to($email)->send(new LoginOtpMail($otp, $name));
        } catch (\Exception $e) {
            \Log::error('Gagal kirim ulang OTP: ' . $e->getMessage());
        }

        ActivityLogger::log('OTP_RESENT', "OTP dikirim ulang ke: {$email}");

        return back()->with('success', 'Kode OTP baru telah dikirim ke email Anda.');
    }

    // ─────────────────────────────────────────
    // REGISTER
    // ─────────────────────────────────────────

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|min:3|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required'      => 'Nama lengkap wajib diisi.',
            'name.min'           => 'Nama minimal 3 karakter.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Email ini sudah terdaftar. Silakan gunakan email lain.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'mahasiswa', // Default role saat registrasi
            'status'   => 'active',
        ]);

        ActivityLogger::log('REGISTER', "Akun baru dibuat: {$user->name} ({$user->email}) dengan role mahasiswa.");

        return redirect()->route('login')
            ->with('success', 'Akun berhasil dibuat! Silakan login menggunakan email dan password Anda.');
    }

    // ─────────────────────────────────────────
    // LOGOUT
    // ─────────────────────────────────────────

    public function logout(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            ActivityLogger::log('LOGOUT', "User {$user->name} berhasil keluar.");
            Auth::logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }
}
