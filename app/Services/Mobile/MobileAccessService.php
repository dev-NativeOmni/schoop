<?php

namespace App\Services\Mobile;

use App\Models\CashlessMerchant;
use App\Models\Student;
use App\Models\User;
use App\Services\Cashless\CashlessAccessService;
use App\Services\Portal\ParentStudentAccessService;
use App\Services\Tenancy\TenantContextService;

class MobileAccessService
{
    public function __construct(
        private readonly ParentStudentAccessService $parentAccess,
        private readonly CashlessAccessService $cashlessAccess,
    ) {}

    public function activeSchoolId(): int
    {
        $schoolId = app(TenantContextService::class)->activeSchoolId();
        abort_unless($schoolId, 403, 'Tenant sekolah belum dipilih.');

        return (int) $schoolId;
    }

    public function ensureRole(User $user, array $roles, string $message = 'Role tidak diizinkan mengakses endpoint ini.'): void
    {
        abort_unless($user->hasRole($roles), 403, $message);
    }

    public function ensureStudentInActiveSchool(Student $student): void
    {
        abort_unless((int) $student->school_id === $this->activeSchoolId(), 403, 'Data santri berada di tenant berbeda.');
    }

    public function ensureParentCanAccess(User $parent, Student $student): void
    {
        $this->ensureRole($parent, ['parent'], 'Endpoint ini hanya untuk orang tua.');
        $this->ensureStudentInActiveSchool($student);
        $this->parentAccess->abortIfCannotAccess($parent, $student);
    }

    public function studentForUser(User $user): Student
    {
        $this->ensureRole($user, ['student'], 'Endpoint ini hanya untuk santri.');

        $student = Student::query()
            ->where('user_id', $user->id)
            ->first();

        abort_unless($student, 404, 'Profil santri tidak ditemukan.');
        $this->ensureStudentInActiveSchool($student);

        return $student;
    }

    public function ensureTeacherCanAccessStudent(User $teacher, Student $student): void
    {
        $this->ensureRole($teacher, ['super_admin', 'admin', 'teacher'], 'Endpoint ini hanya untuk guru.');
        $this->ensureStudentInActiveSchool($student);
    }

    public function ensureMerchantAccess(User $user, CashlessMerchant $merchant): void
    {
        $this->cashlessAccess->assertMerchantAccess($user, $merchant);
    }
}
