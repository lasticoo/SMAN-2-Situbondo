<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Tampilkan form login.
     */
    public function create()
    {
        // Jika sudah login, langsung ke dashboard
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.banners.index');
        }

        return view('admin.auth.login');
    }

    /**
     * Autentikasi user (admin).
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $throttleKey = Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());

        // Check rate limiting (max 5 attempts per minute)
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }

        // Coba autentikasi dengan guard admin
        if (!Auth::guard('admin')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            // Hit failed attempt
            RateLimiter::hit($throttleKey);

            // Tampilkan pesan error generik sesuai kriteria
            throw ValidationException::withMessages([
                'email' => __('Email atau password salah'),
            ]);
        }

        // Clear login attempts on success
        RateLimiter::clear($throttleKey);

        $request->session()->regenerate();

        return redirect()->intended(route('admin.banners.index'));
    }

    /**
     * Logout user (admin).
     */
    public function destroy(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
