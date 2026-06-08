<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Mail\LoginOtpMail;
use App\Mail\RegisterOtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

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
            'email.unique'       => 'Email ini sudah terdaftar. Silakan gunakan email lain atau masuk dengan Google.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Generate OTP 6 digit (kriptografis aman)
        $otp      = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $cacheKey = 'register_otp_' . md5($request->email);

        // Simpan data pendaftaran + OTP di cache (berlaku 15 menit)
        // Data BELUM disimpan ke DB — mencegah akun sampah dari email yang belum terverifikasi
        Cache::put($cacheKey, [
            'otp'      => $otp,
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'attempts' => 0,
        ], now()->addMinutes(15));

        // Simpan email sementara di session untuk validasi halaman OTP
        $request->session()->put('register_otp_pending_email', $request->email);
        $request->session()->put('register_otp_pending_name',  $request->name);

        // Kirim OTP ke email
        try {
            Mail::to($request->email)->send(new RegisterOtpMail($otp, $request->name));
        } catch (\Exception $e) {
            \Log::error('Gagal kirim OTP registrasi: ' . $e->getMessage());
        }

        ActivityLogger::log('REGISTER_OTP_SENT', "OTP registrasi dikirim ke email: {$request->email}");

        return redirect()->route('register.otp');
    }

    // ─────────────────────────────────────────
    // REGISTER OTP VERIFICATION
    // ─────────────────────────────────────────

    public function showRegisterOtp()
    {
        if (!session('register_otp_pending_email')) {
            return redirect()->route('register');
        }
        return view('auth.register_otp');
    }

    public function verifyRegisterOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $email = session('register_otp_pending_email');

        if (!$email) {
            return redirect()->route('register')->with('error', 'Sesi pendaftaran habis. Silakan daftar ulang.');
        }

        $cacheKey = 'register_otp_' . md5($email);
        $data     = Cache::get($cacheKey);

        // OTP tidak ada / sudah kedaluwarsa
        if (!$data) {
            session()->forget(['register_otp_pending_email', 'register_otp_pending_name']);
            return redirect()->route('register')->with('error', 'Kode OTP pendaftaran sudah kedaluwarsa. Silakan daftar ulang.');
        }

        // Terlalu banyak percobaan OTP
        if ($data['attempts'] >= 3) {
            Cache::forget($cacheKey);
            session()->forget(['register_otp_pending_email', 'register_otp_pending_name']);
            ActivityLogger::log('REGISTER_OTP_FAILED', "Melebihi batas percobaan OTP registrasi untuk: {$email}");
            return redirect()->route('register')->with('error', 'Terlalu banyak percobaan OTP. Silakan daftar ulang.');
        }

        // OTP salah
        if ($request->otp !== $data['otp']) {
            $data['attempts']++;
            Cache::put($cacheKey, $data, now()->addMinutes(15));
            $sisa = 3 - $data['attempts'];
            return back()->withErrors(['otp' => "Kode OTP salah. Sisa percobaan: {$sisa}."]);
        }

        // ✅ OTP BENAR — Buat akun sekarang
        Cache::forget($cacheKey);

        $user = User::create([
            'name'              => $data['name'],
            'email'             => $data['email'],
            'password'          => $data['password'],
            'role'              => 'mahasiswa',
            'status'            => 'active',
            'email_verified_at' => now(),
        ]);

        session()->forget(['register_otp_pending_email', 'register_otp_pending_name']);

        // Login otomatis setelah verifikasi berhasil
        Auth::login($user);
        $request->session()->regenerate();

        ActivityLogger::log('REGISTER', "Akun baru terverifikasi & dibuat: {$user->name} ({$user->email}) dengan role mahasiswa.");

        return redirect()->route('dashboard')->with('success', 'Selamat datang, ' . $user->name . '! Akun Anda telah berhasil diverifikasi.');
    }

    public function resendRegisterOtp(Request $request)
    {
        $email = session('register_otp_pending_email');
        $name  = session('register_otp_pending_name');

        if (!$email) {
            return redirect()->route('register');
        }

        $cacheKey = 'register_otp_' . md5($email);
        $data     = Cache::get($cacheKey);

        if (!$data) {
            return redirect()->route('register')->with('error', 'Sesi pendaftaran habis. Silakan daftar ulang.');
        }

        // Generate OTP baru & reset percobaan
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $data['otp']      = $otp;
        $data['attempts'] = 0;
        Cache::put($cacheKey, $data, now()->addMinutes(15));

        try {
            Mail::to($email)->send(new RegisterOtpMail($otp, $name));
        } catch (\Exception $e) {
            \Log::error('Gagal kirim ulang OTP registrasi: ' . $e->getMessage());
        }

        ActivityLogger::log('REGISTER_OTP_RESENT', "OTP registrasi dikirim ulang ke: {$email}");

        return back()->with('success', 'Kode OTP baru telah dikirim ke email Anda.');
    }

    // ─────────────────────────────────────────
    // GOOGLE OAUTH
    // ─────────────────────────────────────────

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            \Log::error('Google OAuth error: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('login')->with('error', 'Gagal masuk dengan Google. Silakan coba lagi.');
        }

        // Cari user berdasarkan google_id atau email
        $user = User::where('google_id', $googleUser->getId())
                    ->orWhere('email', $googleUser->getEmail())
                    ->first();

        if ($user) {
            // Jika user sudah ada tapi belum punya google_id, hubungkan
            if (!$user->google_id) {
                $user->update(['google_id' => $googleUser->getId()]);
            }

            // Cek status akun
            if ($user->status !== 'active') {
                ActivityLogger::log('LOGIN_FAILED', "Akun dinonaktifkan mencoba login via Google: {$user->email}");
                return redirect()->route('login')->with('error', 'Akun Anda telah dinonaktifkan. Silakan hubungi administrator.');
            }

            Auth::login($user, true);
            request()->session()->regenerate();

            ActivityLogger::log('LOGIN_SUCCESS', "User {$user->name} ({$user->role}) berhasil masuk via Google.");

            return redirect()->intended(route('dashboard'));
        }

        // User belum ada — buat akun baru via Google
        $newUser = User::create([
            'name'              => $googleUser->getName(),
            'email'             => $googleUser->getEmail(),
            'google_id'         => $googleUser->getId(),
            'password'          => Hash::make(Str::random(32)), // password random — tidak digunakan untuk login
            'role'              => 'mahasiswa',
            'status'            => 'active',
            'email_verified_at' => now(), // Email sudah terverifikasi oleh Google
        ]);

        Auth::login($newUser, true);
        request()->session()->regenerate();

        ActivityLogger::log('REGISTER', "Akun baru dibuat via Google: {$newUser->name} ({$newUser->email}) dengan role mahasiswa.");

        return redirect()->route('dashboard')->with('success', 'Selamat datang, ' . $newUser->name . '! Akun Anda berhasil didaftarkan via Google.');
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
