<?php

namespace App\Services\WhiteLabel;

use App\Models\User;
use App\Models\School;
use App\Services\Tenancy\TenantAccessService;
use App\Services\Tenancy\TenantContextService;

class WhiteLabelAccessService
{
    protected TenantAccessService $tenantAccess;
    protected TenantContextService $tenantContext;

    public function __construct(TenantAccessService $tenantAccess, TenantContextService $tenantContext)
    {
        $this->tenantAccess = $tenantAccess;
        $this->tenantContext = $tenantContext;
    }

    /**
     * Check if user can view white label builder menu.
     */
    public function canView(User $user): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Must have access to the active school and be admin or principal
        $schoolId = $this->tenantContext->activeSchoolId();
        if (!$schoolId) {
            return false;
        }

        if (!$this->tenantAccess->userCanAccessSchool($user, $schoolId)) {
            return false;
        }

        return $user->hasRole(['admin', 'admin_sekolah', 'principal']);
    }

    /**
     * Check if user can edit white-label builder parameters.
     */
    public function canManage(User $user, int $schoolId): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Check if user has access to this school
        if (!$this->tenantAccess->userCanAccessSchool($user, $schoolId)) {
            return false;
        }

        // Only school admin can modify
        return $user->hasRole(['admin', 'admin_sekolah']);
    }

    /**
     * Ensure user can manage branding details for the school.
     */
    public function ensureCanManage(User $user, int $schoolId): void
    {
        if (!$this->canManage($user, $schoolId)) {
            abort(403, 'Anda tidak memiliki wewenang untuk mengelola White-Label sekolah ini.');
        }
    }
}
