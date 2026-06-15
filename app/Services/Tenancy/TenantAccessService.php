<?php

namespace App\Services\Tenancy;

use App\Models\School;
use App\Models\User;
use App\Models\UserSchoolMembership;

class TenantAccessService
{
    public function userCanAccessSchool(User $user, int $schoolId): bool
    {
        if ($this->isSuperAdmin($user)) {
            return School::query()
                ->where('id', $schoolId)
                ->exists();
        }

        $hasMembership = UserSchoolMembership::query()
            ->where('user_id', $user->id)
            ->where('school_id', $schoolId)
            ->where('membership_status', 'active')
            ->exists();

        if ($hasMembership) {
            return true;
        }

        return (int) ($user->school_id ?? 0) === $schoolId;
    }

    public function ensureUserCanAccessSchool(User $user, int $schoolId): void
    {
        if (! $this->userCanAccessSchool($user, $schoolId)) {
            abort(403, 'Akses tenant ditolak.');
        }

        $school = School::query()->findOrFail($schoolId);

        if (! $this->isSuperAdmin($user) && ($school->is_tenant_enabled === false || $school->tenant_status === 'suspended')) {
            abort(403, 'Tenant sekolah sedang tidak aktif.');
        }
    }

    public function isSuperAdmin(User $user): bool
    {
        return method_exists($user, 'hasRole') && $user->hasRole(['super_admin']);
    }

    public function isTenantAdmin(User $user): bool
    {
        return method_exists($user, 'hasRole') && $user->hasRole(['super_admin', 'admin', 'admin_sekolah']);
    }
}
