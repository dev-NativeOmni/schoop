<?php

namespace App\Http\Middleware;

use App\Services\Tenancy\TenantAccessService;
use App\Services\Tenancy\TenantContextService;
use App\Services\WhiteLabel\TenantDomainResolver;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class ResolveTenantFromDomain
{
    protected TenantDomainResolver $domainResolver;
    protected TenantAccessService $tenantAccess;

    public function __construct(TenantDomainResolver $domainResolver, TenantAccessService $tenantAccess)
    {
        $this->domainResolver = $domainResolver;
        $this->tenantAccess = $tenantAccess;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $school = $this->domainResolver->resolve($host);

        if ($school) {
            // Bind resolved school ID to container for request-level absolute enforcement
            app()->instance('resolved_domain_school_id', $school->id);

            // Also synchronize with session context
            Session::put(TenantContextService::SESSION_KEY, $school->id);

            // Enforce access boundary if user is authenticated
            $user = $request->user();
            if ($user && !$user->isSuperAdmin()) {
                if (!$this->tenantAccess->userCanAccessSchool($user, $school->id)) {
                    // Clear active school from session so they are not stuck on invalid context
                    Session::forget(TenantContextService::SESSION_KEY);
                    abort(403, 'Anda tidak memiliki akses ke sekolah ini melalui domain ' . $host);
                }
            }
        }

        return $next($request);
    }
}
