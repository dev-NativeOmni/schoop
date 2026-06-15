<?php

namespace App\Services\Mobile;

use App\Models\MobileAccessToken;
use App\Models\MobileDevice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MobileTokenService
{
    public function createToken(User $user, ?MobileDevice $device, ?int $schoolId = null, array $abilities = ['*']): array
    {
        $plainToken = 'hpm_'.Str::random(80);

        $token = MobileAccessToken::query()->create([
            'school_id' => $schoolId,
            'user_id' => $user->id,
            'mobile_device_id' => $device?->id,
            'name' => 'mobile',
            'token_hash' => $this->hashToken($plainToken),
            'abilities' => $abilities,
            'expires_at' => now()->addDays(30),
        ]);

        return [$plainToken, $token];
    }

    public function resolveFromRequest(Request $request): ?MobileAccessToken
    {
        $plainToken = $request->bearerToken();

        if (! $plainToken) {
            return null;
        }

        $token = MobileAccessToken::query()
            ->with(['user.role', 'user.school', 'mobileDevice'])
            ->where('token_hash', $this->hashToken($plainToken))
            ->first();

        if (! $token || ! $token->isUsable()) {
            return null;
        }

        if ($token->mobileDevice && (! $token->mobileDevice->is_active || $token->mobileDevice->revoked_at)) {
            return null;
        }

        $token->forceFill(['last_used_at' => now()])->save();

        return $token;
    }

    public function revoke(MobileAccessToken $token): void
    {
        $token->forceFill(['revoked_at' => now()])->save();
    }

    public function hashToken(string $plainToken): string
    {
        return hash('sha256', $plainToken);
    }
}
