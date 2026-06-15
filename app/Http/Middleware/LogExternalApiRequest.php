<?php

namespace App\Http\Middleware;

use App\Services\DeveloperPortal\ApiRequestLogger;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class LogExternalApiRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request->attributes->set('external_api_started_at', microtime(true));
        $request->attributes->set('external_api_request_id', (string) Str::uuid());

        try {
            $response = $next($request);
            $response->headers->set('X-HafizPlus-Request-Id', $request->attributes->get('external_api_request_id'));
            app(ApiRequestLogger::class)->log($request, $response);

            return $response;
        } catch (Throwable $exception) {
            app(ApiRequestLogger::class)->log($request, null, $exception->getMessage());

            throw $exception;
        }
    }
}
