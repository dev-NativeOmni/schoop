<?php

namespace App\Http\Middleware;

use App\Services\DeveloperPortal\ApiAccessService;
use App\Services\DeveloperPortal\ApiClientTokenService;
use App\Services\DeveloperPortal\ApiRequestLogger;
use App\Services\DeveloperPortal\ApiResponseFormatter;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiClient
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $plainToken = $request->bearerToken();

        if (! $plainToken) {
            return app(ApiResponseFormatter::class)->error('Unauthorized', 401);
        }

        $token = app(ApiClientTokenService::class)->findUsableToken($plainToken);

        if (! $token || ! $token->client) {
            return app(ApiResponseFormatter::class)->error('Unauthorized', 401);
        }

        $client = $token->client;

        if ($client->allowed_ips && ! in_array($request->ip(), $client->allowed_ips, true)) {
            return app(ApiResponseFormatter::class)->error('IP address is not allowed for this API client.', 403);
        }

        app(ApiAccessService::class)->bindApiTenant($client);
        app(ApiRequestLogger::class)->markClientUsed($client);

        $client->tokens()->whereKey($token->id)->update(['last_used_at' => now()]);
        $request->attributes->set('api_client', $client);
        $request->attributes->set('api_client_token', $token);

        return $next($request);
    }
}
