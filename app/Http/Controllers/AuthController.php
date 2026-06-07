<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
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
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $throttleKey = Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            
            // Log brute force attempt
            ActivityLogger::log('BRUTE_FORCE_ATTEMPT', "Percobaan login berulang kali gagal untuk email: " . $request->input('email'));

            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.",
            ]);
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if ($user->status !== 'active') {
                Auth::logout();
                RateLimiter::hit($throttleKey);
                
                ActivityLogger::log('LOGIN_FAILED', "Mencoba masuk dengan akun dinonaktifkan: " . $credentials['email']);
                
                return back()->withErrors([
                    'email' => 'Akun Anda telah dinonaktifkan. Silakan hubungi administrator.',
                ]);
            }

            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            // Log successful login
            ActivityLogger::log('LOGIN_SUCCESS', "User {$user->name} ({$user->role}) berhasil masuk.");

            return redirect()->intended(route('dashboard'));
        }

        RateLimiter::hit($throttleKey);

        // Log failed login
        ActivityLogger::log('LOGIN_FAILED', "Percobaan login gagal untuk email: " . $credentials['email']);

        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ]);
    }

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
