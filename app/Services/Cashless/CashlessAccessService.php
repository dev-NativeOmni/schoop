<?php

namespace App\Services\Cashless;

use App\Models\CashlessMerchant;
use App\Models\CashlessMerchantUser;
use App\Models\Student;
use App\Models\User;
use App\Services\Tenancy\TenantContextService;

class CashlessAccessService
{
    public function activeSchoolId(User $user): int
    {
        $schoolId = app(TenantContextService::class)->resolveForUser($user) ?: $user->school_id;

        abort_unless($schoolId, 403, 'Tenant sekolah belum dipilih.');

        return (int) $schoolId;
    }

    public function canManage(User $user): bool
    {
        return $user->hasRole(['super_admin', 'admin', 'admin_sekolah']);
    }

    public function canFinance(User $user): bool
    {
        return $user->hasRole(['super_admin', 'admin', 'admin_sekolah', 'finance']);
    }

    public function canViewReports(User $user): bool
    {
        return $user->hasRole(['super_admin', 'admin', 'admin_sekolah', 'finance', 'principal', 'kepala_sekolah']);
    }

    public function canUsePos(User $user): bool
    {
        return $user->hasRole(['super_admin', 'admin', 'admin_sekolah', 'cashier', 'merchant']);
    }

    public function assertSameSchool(User $user, int $schoolId): void
    {
        if ($user->isSuperAdmin()) {
            return;
        }

        abort_unless($this->activeSchoolId($user) === $schoolId, 403, 'Data cashless berada di tenant berbeda.');
    }

    public function assertMerchantAccess(User $user, CashlessMerchant $merchant): void
    {
        $this->assertSameSchool($user, (int) $merchant->school_id);

        if ($this->canManage($user) || $this->canFinance($user)) {
            return;
        }

        $hasAccess = CashlessMerchantUser::query()
            ->where('school_id', $merchant->school_id)
            ->where('cashless_merchant_id', $merchant->id)
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->exists();

        abort_unless($hasAccess, 403, 'Kasir tidak memiliki akses ke merchant ini.');
    }

    public function assertStudentInSchool(Student $student, int $schoolId): void
    {
        abort_unless((int) $student->school_id === $schoolId, 403, 'Santri berada di tenant berbeda.');
    }
}
