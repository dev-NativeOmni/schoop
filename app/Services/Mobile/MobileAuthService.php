<?php

namespace App\Services\Mobile;

use App\Models\MobileAccessToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class MobileAuthService
{
    public function __construct(
        private readonly MobileDeviceService $devices,
        private readonly MobileTenantService $tenants,
        private readonly MobileTokenService $tokens,
    ) {}

    public function login(array $payload, Request $request): array
    {
        $login = $payload['login'];

        $user = User::query()
            ->with(['role', 'school'])
            ->where(function ($query) use ($login): void {
                $query->where('email', $login)
                    ->orWhere('username', $login)
                    ->orWhere('phone', $login);
            })
            ->first();

        if (! $user || ! Hash::check($payload['password'], $user->password)) {
            throw ValidationException::withMessages([
                'login' => 'Kredensial tidak valid.',
            ]);
        }

        if (! $user->is_active) {
            throw ValidationException::withMessages([
                'login' => 'Akun tidak aktif. Silakan hubungi admin sekolah.',
            ]);
        }

        $school = $this->tenants->defaultSchoolForUser($user, isset($payload['school_id']) ? (int) $payload['school_id'] : null);
        $schoolId = $school?->id;

        $device = $this->devices->register($user, $schoolId, $payload, $request);
        [$plainToken, $token] = $this->tokens->createToken($user, $device, $schoolId);

        $user->forceFill(['last_login_at' => now()])->save();

        return [
            'access_token' => $plainToken,
            'token_type' => 'Bearer',
            'expires_at' => $token->expires_at?->toIso8601String(),
            'user' => $this->userPayload($user),
            'active_tenant' => $school ? $this->schoolPayload($school) : null,
            'device' => $this->devicePayload($device),
        ];
    }

    public function logout(MobileAccessToken $token): void
    {
        $this->tokens->revoke($token);
    }

    public function refresh(MobileAccessToken $currentToken): array
    {
        $user = $currentToken->user;
        $device = $currentToken->mobileDevice;
        $schoolId = $currentToken->school_id;

        $this->tokens->revoke($currentToken);
        [$plainToken, $token] = $this->tokens->createToken($user, $device, $schoolId);

        return [
            'access_token' => $plainToken,
            'token_type' => 'Bearer',
            'expires_at' => $token->expires_at?->toIso8601String(),
        ];
    }

    public function changePassword(User $user, string $currentPassword, string $newPassword): void
    {
        if (! Hash::check($currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Password saat ini tidak sesuai.',
            ]);
        }

        $user->forceFill([
            'password' => Hash::make($newPassword),
            'remember_token' => Str::random(60),
        ])->save();

        MobileAccessToken::query()
            ->where('user_id', $user->id)
            ->whereNull('revoked_at')
            ->update(['revoked_at' => now()]);
    }

    public function requestPasswordReset(string $email): string
    {
        Password::sendResetLink(['email' => $email]);

        return 'Jika email terdaftar, instruksi reset password akan dikirim.';
    }

    public function resetPassword(array $payload): string
    {
        $status = Password::reset(
            [
                'email' => $payload['email'],
                'password' => $payload['password'],
                'password_confirmation' => $payload['password_confirmation'],
                'token' => $payload['token'],
            ],
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                MobileAccessToken::query()
                    ->where('user_id', $user->id)
                    ->whereNull('revoked_at')
                    ->update(['revoked_at' => now()]);
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => 'Token reset password tidak valid atau sudah kedaluwarsa.',
            ]);
        }

        return 'Password berhasil direset. Silakan login kembali.';
    }

    public function userPayload(User $user): array
    {
        $user->loadMissing('role');

        return [
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role?->name,
            'role_label' => $user->role?->label,
            'is_active' => (bool) $user->is_active,
        ];
    }

    public function schoolPayload($school): array
    {
        return [
            'id' => $school->id,
            'name' => $school->name,
            'code' => $school->code,
            'slug' => $school->slug,
            'tenant_code' => $school->tenant_code,
            'tenant_status' => $school->tenant_status,
        ];
    }

    public function devicePayload($device): array
    {
        return [
            'id' => $device->id,
            'device_uuid' => $device->device_uuid,
            'platform' => $device->platform,
            'platform_version' => $device->platform_version,
            'app_version' => $device->app_version,
            'device_name' => $device->device_name,
            'last_seen_at' => $device->last_seen_at?->toIso8601String(),
        ];
    }
}
