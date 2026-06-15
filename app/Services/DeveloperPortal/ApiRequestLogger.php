<?php

namespace App\Services\DeveloperPortal;

use App\Models\ApiClient;
use App\Models\ApiRequestLog;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiRequestLogger
{
    public function log(Request $request, ?Response $response = null, ?string $errorMessage = null): void
    {
        if (! str_starts_with($request->path(), 'api/v1')) {
            return;
        }

        $startedAt = $request->attributes->get('external_api_started_at');
        $duration = $startedAt ? (int) round((microtime(true) - $startedAt) * 1000) : null;
        $client = $request->attributes->get('api_client');

        ApiRequestLog::query()->create([
            'api_client_id' => $client?->id,
            'school_id' => $client?->school_id,
            'request_id' => $request->attributes->get('external_api_request_id'),
            'method' => $request->method(),
            'path' => '/'.$request->path(),
            'scope_checked' => $request->attributes->get('api_scope_checked'),
            'response_status' => $response?->getStatusCode(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'duration_ms' => $duration,
            'error_message' => $errorMessage,
            'created_at' => now(),
        ]);
    }

    public function markClientUsed(ApiClient $client): void
    {
        $client->forceFill(['last_used_at' => now()])->save();
    }
}
