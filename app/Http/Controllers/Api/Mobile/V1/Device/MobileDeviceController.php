<?php

namespace App\Http\Controllers\Api\Mobile\V1\Device;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Mobile\V1\RegisterMobileDeviceRequest;
use App\Services\Mobile\MobileAuthService;
use App\Services\Mobile\MobileDeviceService;
use App\Support\MobileApiResponse;
use Illuminate\Http\Request;

class MobileDeviceController extends Controller
{
    public function __construct(
        private readonly MobileDeviceService $devices,
        private readonly MobileAuthService $auth,
    ) {}

    public function store(RegisterMobileDeviceRequest $request)
    {
        $device = $this->devices->register(
            $request->user(),
            $request->attributes->get('mobile_school')?->id,
            $request->validated(),
            $request
        );

        return MobileApiResponse::ok([
            'device' => $this->auth->devicePayload($device),
        ], 'Device terdaftar.');
    }

    public function pushToken(Request $request)
    {
        $payload = $request->validate([
            'push_token' => ['nullable', 'string', 'max:4096'],
            'push_provider' => ['nullable', 'string', 'in:fcm,apns,web_push'],
        ]);

        $device = $request->attributes->get('mobile_device');
        abort_unless($device, 404, 'Device mobile tidak ditemukan.');

        $device = $this->devices->updatePushToken($device, $payload['push_token'] ?? null, $payload['push_provider'] ?? null);

        return MobileApiResponse::ok([
            'device' => $this->auth->devicePayload($device),
        ], 'Push token diperbarui.');
    }

    public function revoke(Request $request)
    {
        $device = $request->attributes->get('mobile_device');
        abort_unless($device, 404, 'Device mobile tidak ditemukan.');

        $this->devices->revoke($device);

        return MobileApiResponse::ok([], 'Device dicabut.');
    }
}
