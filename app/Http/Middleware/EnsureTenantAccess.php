<?php

namespace App\Http\Middleware;

use App\Services\Tenancy\TenantAccessService;
use App\Services\Tenancy\TenantContextService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        $schoolId = app(TenantContextService::class)->activeSchoolId();

        if (! $schoolId) {
            abort(403, 'Tenant sekolah belum dipilih.');
        }

        app(TenantAccessService::class)->ensureUserCanAccessSchool($user, $schoolId);

        return $next($request);
    }
}
