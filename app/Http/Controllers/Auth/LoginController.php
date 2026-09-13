<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Tenancy\TenantContextService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $throttleKey = 'login|'.$request->ip().'|'.mb_strtolower($credentials['login']);

        if (RateLimiter::tooManyAttempts($throttleKey, 8)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()
                ->withErrors([
                    'login' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.",
                ])
                ->onlyInput('login');
        }

        // Determine if the login field is an email or username
        $loginField = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $attemptCredentials = [
            $loginField => $credentials['login'],
            'password' => $credentials['password'],
            'is_active' => true,
        ];

        if (! Auth::attempt($attemptCredentials, $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey, 60);

            return back()
                ->withErrors([
                    'login' => 'Login gagal. Periksa username/email dan password.',
                ])
                ->onlyInput('login');
        }

        RateLimiter::clear($throttleKey);

        $request->session()->regenerate();

        // Update user's last login timestamp
        if ($user = $request->user()) {
            $user->forceFill([
                'last_login_at' => now(),
            ])->save();

            // Resolve active school context
            $schoolId = app(TenantContextService::class)->resolveForUser($user);

            if (! $schoolId && ! $user->hasRole(['super_admin', 'operations_manager', 'support_staff', 'customer_success', 'sales'])) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()
                    ->withErrors([
                        'login' => 'Akun Anda belum terhubung ke sekolah. Hubungi admin.',
                    ])
                    ->onlyInput('login');
            }
        }

        return redirect()->intended(route('dashboard'));
    }
}
