<?php

namespace App\Services\Analytics;

use App\Models\User;
use App\Services\Tenancy\TenantAccessService;

class AnalyticsAccessService
{
    public function canViewInternalExecutiveAnalytics(User $user): bool
    {
        return $user->hasRole(['super_admin', 'operations_manager', 'customer_success', 'support_staff', 'sales']);
    }

    public function canViewSchoolAnalytics(User $user, ?int $schoolId): bool
    {
        if ($user->hasRole(['super_admin', 'operations_manager', 'customer_success', 'support_staff', 'sales'])) {
            return true;
        }

        if (! $schoolId) {
            return false;
        }

        return $user->hasRole(['admin', 'admin_sekolah', 'principal', 'kepala_sekolah']) 
            && app(TenantAccessService::class)->userCanAccessSchool($user, $schoolId);
    }

    public function canViewTenantHealth(User $user, ?int $schoolId): bool
    {
        if ($user->hasRole(['super_admin', 'operations_manager', 'customer_success', 'support_staff', 'sales'])) {
            return true;
        }

        if (! $schoolId) {
            return false;
        }

        return $user->hasRole(['admin', 'admin_sekolah', 'principal', 'kepala_sekolah']) 
            && app(TenantAccessService::class)->userCanAccessSchool($user, $schoolId);
    }

    public function canManageMetricDictionary(User $user): bool
    {
        return $user->hasRole(['super_admin']);
    }

    public function canGenerateExecutiveReport(User $user, ?int $schoolId): bool
    {
        if ($user->hasRole(['super_admin', 'operations_manager'])) {
            return true;
        }

        if (! $schoolId) {
            return false;
        }

        return $user->hasRole(['admin', 'admin_sekolah']) 
            && app(TenantAccessService::class)->userCanAccessSchool($user, $schoolId);
    }

    public function resolveAccessibleSchoolIds(User $user): array
    {
        if ($user->hasRole(['super_admin', 'operations_manager', 'customer_success', 'support_staff', 'sales'])) {
            return \App\Models\School::query()->pluck('id')->all();
        }

        return app(TenantAccessService::class)->userAccessibleSchoolIds($user);
    }
}
