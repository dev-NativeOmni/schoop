<?php

namespace App\Http\Middleware;

use App\Services\Tenancy\TenantContextService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenantContext
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            app(TenantContextService::class)->resolveForUser($request->user());
        }

        return $next($request);
    }
}
