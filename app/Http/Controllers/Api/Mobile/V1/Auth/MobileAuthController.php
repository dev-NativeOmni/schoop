<?php

namespace App\Http\Controllers\Api\Mobile\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Mobile\V1\LoginMobileRequest;
use App\Services\Mobile\MobileAuthService;
use App\Support\MobileApiResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class MobileAuthController extends Controller
{
    public function __construct(
        private readonly MobileAuthService $auth,
    ) {}

    public function login(LoginMobileRequest $request)
    {
        return MobileApiResponse::ok(
            $this->auth->login($request->validated(), $request),
            'Login berhasil.'
        );
    }

    public function logout(Request $request)
    {
        $this->auth->logout($request->attributes->get('mobile_access_token'));

        return MobileApiResponse::ok([], 'Logout berhasil.');
    }

    public function me(Request $request)
    {
        return MobileApiResponse::ok([
            'user' => $this->auth->userPayload($request->user()),
            'active_tenant' => $request->attributes->get('mobile_school')
                ? $this->auth->schoolPayload($request->attributes->get('mobile_school'))
                : null,
        ]);
    }

    public function refresh(Request $request)
    {
        return MobileApiResponse::ok(
            $this->auth->refresh($request->attributes->get('mobile_access_token')),
            'Token diperbarui.'
        );
    }

    public function changePassword(Request $request)
    {
        $payload = $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $this->auth->changePassword($request->user(), $payload['current_password'], $payload['new_password']);

        return MobileApiResponse::ok([], 'Password berhasil diubah. Silakan login kembali.');
    }

    public function requestPasswordReset(Request $request)
    {
        $payload = $request->validate([
            'email' => ['required', 'email'],
        ]);

        return MobileApiResponse::ok([], $this->auth->requestPasswordReset($payload['email']));
    }

    public function resetPassword(Request $request)
    {
        $payload = $request->validate([
            'email' => ['required', 'email'],
            'token' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        return MobileApiResponse::ok([], $this->auth->resetPassword($payload));
    }
}
