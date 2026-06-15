<?php

namespace App\Services\SaasOps;

use App\Models\User;
use App\Services\Tenancy\TenantAccessService;
use App\Services\Tenancy\TenantContextService;

class SaasOperationsAccessService
{
    public function canViewDashboard(User $user): bool
    {
        return $user->hasRole(['super_admin', 'operations_manager', 'support_staff', 'customer_success', 'sales']);
    }

    public function canManageSubscriptions(User $user): bool
    {
        return $user->hasRole(['super_admin', 'operations_manager']);
    }

    public function canManageTenantInvoices(User $user): bool
    {
        return $user->hasRole(['super_admin', 'operations_manager']);
    }

    public function canManageSupport(User $user): bool
    {
        return $user->hasRole(['super_admin', 'operations_manager', 'support_staff', 'customer_success']);
    }

    public function canManageIncidents(User $user): bool
    {
        return $user->hasRole(['super_admin', 'operations_manager', 'support_staff']);
    }

    public function canManageKnowledge(User $user): bool
    {
        return $user->hasRole(['super_admin', 'operations_manager', 'support_staff', 'customer_success']);
    }

    public function canAccessSchoolOps(User $user, int $schoolId): bool
    {
        if ($user->hasRole(['super_admin', 'operations_manager', 'support_staff', 'customer_success', 'sales'])) {
            return true;
        }

        return app(TenantAccessService::class)->userCanAccessSchool($user, $schoolId);
    }

    public function activeSchoolId(User $user): ?int
    {
        return app(TenantContextService::class)->resolveForUser($user) ?: $user->school_id;
    }

    public function assertCanAccessSchool(User $user, int $schoolId): void
    {
        abort_unless($this->canAccessSchoolOps($user, $schoolId), 403, 'Tidak memiliki akses operasional tenant ini.');
    }
}
