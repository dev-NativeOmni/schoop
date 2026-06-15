<?php

namespace App\Http\Middleware;

use App\Services\DeveloperPortal\ApiResponseFormatter;
use App\Services\DeveloperPortal\ApiScopeService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiScope
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $scope): Response
    {
        $client = $request->attributes->get('api_client');
        $request->attributes->set('api_scope_checked', $scope);

        if (! $client || ! app(ApiScopeService::class)->hasScope($client, $scope)) {
            return app(ApiResponseFormatter::class)->error('API scope is not granted.', 403, [], [
                'required_scope' => $scope,
            ]);
        }

        return $next($request);
    }
}
