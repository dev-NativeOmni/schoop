<?php

namespace App\Services\Mobile;

use App\Models\MobileApiAuditLog;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MobileApiAuditLogger
{
    public function log(Request $request, ?Response $response = null): void
    {
        if (! str_starts_with($request->path(), 'api/mobile/v1')) {
            return;
        }

        $user = $request->user();
        $token = $request->attributes->get('mobile_access_token');
        $device = $request->attributes->get('mobile_device');

        MobileApiAuditLog::query()->create([
            'school_id' => $request->attributes->get('mobile_school')?->id ?? $token?->school_id,
            'user_id' => $user?->id,
            'mobile_device_id' => $device?->id ?? $token?->mobile_device_id,
            'method' => $request->method(),
            'path' => '/'.$request->path(),
            'status_code' => $response?->getStatusCode(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'action' => $request->route()?->getName(),
            'request_id' => $request->attributes->get('mobile_request_id'),
            'metadata' => [
                'input_keys' => collect($request->except([
                    'password',
                    'current_password',
                    'new_password',
                    'token',
                    'push_token',
                    'qr_payload',
                ]))->keys()->values()->all(),
            ],
        ]);
    }
}
