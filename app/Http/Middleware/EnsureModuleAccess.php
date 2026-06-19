<?php

namespace App\Http\Middleware;

use App\Models\School;
use App\Services\SaasOps\PlanModuleAccessService;
use App\Services\Tenancy\TenantAccessService;
use App\Services\Tenancy\TenantContextService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureModuleAccess
{
    public function __construct(
        private readonly PlanModuleAccessService $planAccess,
        private readonly TenantAccessService $tenantAccess,
    ) {}

    public function handle(Request $request, Closure $next, string $moduleKey): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        $schoolId = $this->resolveSchoolId($request);

        if (! $schoolId) {
            if ($user->hasRole(['super_admin', 'operations_manager'])) {
                return $next($request);
            }

            abort(403, 'Tenant sekolah belum dipilih.');
        }

        $this->tenantAccess->ensureUserCanAccessSchool($user, $schoolId);

        if (! $this->planAccess->canAccessModule($user, $schoolId, $moduleKey)) {
            abort(403, 'Modul tidak tersedia untuk subscription plan sekolah ini.');
        }

        return $next($request);
    }

    private function resolveSchoolId(Request $request): ?int
    {
        $mobileSchool = $request->attributes->get('mobile_school');

        if ($mobileSchool instanceof School) {
            return (int) $mobileSchool->id;
        }

        $schoolId = app(TenantContextService::class)->resolveForUser($request->user());

        if ($schoolId) {
            return (int) $schoolId;
        }

        return $request->user()?->school_id ? (int) $request->user()->school_id : null;
    }
}
