<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    public function store(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        try {
            $credentials = $request->validate([
                'login' => ['required', 'string'],
                'password' => ['required', 'string'],
            ]);

            // Determine if the login field is an email or username
            $loginField = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

            $attemptCredentials = [
                $loginField => $credentials['login'],
                'password' => $credentials['password'],
                'is_active' => true,
            ];

            if (! Auth::attempt($attemptCredentials, $request->boolean('remember'))) {
                return back()
                    ->withErrors([
                        'login' => 'Login gagal. Periksa username/email dan password.',
                    ])
                    ->onlyInput('login');
            }

            $request->session()->regenerate();

            // Update user's last login timestamp
            if ($user = $request->user()) {
                $user->forceFill([
                    'last_login_at' => now(),
                ])->save();

                // Resolve active school context
                $schoolId = app(\App\Services\Tenancy\TenantContextService::class)->resolveForUser($user);

                if (!$schoolId && !$user->hasRole(['super_admin', 'operations_manager', 'support_staff', 'customer_success', 'sales'])) {
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
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Login failed: ' . $e->getMessage());

            if (config('app.debug') || env('APP_DEBUG') || true) {
                return response('LOGIN EXCEPTION: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine() . "\n\n" . $e->getTraceAsString(), 500, ['Content-Type' => 'text/plain']);
            }

            throw $e;
        }
    }
}
