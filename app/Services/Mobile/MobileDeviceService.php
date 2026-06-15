<?php

namespace App\Services\Mobile;

use App\Models\MobileDevice;
use App\Models\User;
use Illuminate\Http\Request;

class MobileDeviceService
{
    public function register(User $user, ?int $schoolId, array $payload, Request $request): MobileDevice
    {
        return MobileDevice::query()->updateOrCreate(
            [
                'user_id' => $user->id,
                'device_uuid' => $payload['device_uuid'],
            ],
            [
                'school_id' => $schoolId,
                'platform' => $payload['platform'],
                'platform_version' => $payload['platform_version'] ?? null,
                'app_version' => $payload['app_version'] ?? null,
                'device_name' => $payload['device_name'] ?? null,
                'push_token' => $payload['push_token'] ?? null,
                'push_provider' => $payload['push_provider'] ?? null,
                'last_ip' => $request->ip(),
                'last_seen_at' => now(),
                'revoked_at' => null,
                'is_active' => true,
            ]
        );
    }

    public function updatePushToken(MobileDevice $device, ?string $pushToken, ?string $provider): MobileDevice
    {
        $device->update([
            'push_token' => $pushToken,
            'push_provider' => $provider,
            'last_seen_at' => now(),
        ]);

        return $device->fresh();
    }

    public function markSeen(MobileDevice $device, Request $request, ?int $schoolId = null): void
    {
        $device->forceFill([
            'school_id' => $schoolId ?: $device->school_id,
            'last_ip' => $request->ip(),
            'last_seen_at' => now(),
        ])->save();
    }

    public function revoke(MobileDevice $device): void
    {
        $device->update([
            'is_active' => false,
            'revoked_at' => now(),
            'push_token' => null,
        ]);

        $device->accessTokens()->update(['revoked_at' => now()]);
    }
}
