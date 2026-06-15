<?php

namespace App\Http\Middleware;

use App\Services\DeveloperPortal\ApiResponseFormatter;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ApplyApiRateLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $client = $request->attributes->get('api_client');
        $limit = max(1, (int) ($client?->rate_limit_per_minute ?? 60));
        $key = 'external-api:'.($client?->id ?? $request->ip()).':'.now()->format('YmdHi');

        if (RateLimiter::tooManyAttempts($key, $limit)) {
            return app(ApiResponseFormatter::class)->error('API rate limit exceeded.', 429, [], [
                'limit_per_minute' => $limit,
            ]);
        }

        RateLimiter::hit($key, 60);

        return $next($request);
    }
}
