<?php

namespace App\Services\Mutabaah;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class MutabaahAccessService
{
    public function roleName(?User $user): ?string
    {
        if (! $user) {
            return null;
        }

        $roleValue = $user->role ?? null;

        if (is_object($roleValue)) {
            return $roleValue->name ?? $roleValue->slug ?? null;
        }

        return is_string($roleValue) ? $roleValue : null;
    }

    public function isAdmin(?User $user): bool
    {
        return in_array($this->roleName($user), [
            'super_admin',
            'admin',
            'admin_sekolah',
        ], true);
    }

    public function isPrincipal(?User $user): bool
    {
        return in_array($this->roleName($user), [
            'kepala_sekolah',
            'principal',
        ], true);
    }

    public function isTeacher(?User $user): bool
    {
        return in_array($this->roleName($user), [
            'teacher',
            'guru',
            'guru_tahfidz',
        ], true);
    }

    public function isParent(?User $user): bool
    {
        return $this->roleName($user) === 'parent';
    }

    public function isStudent(?User $user): bool
    {
        return $this->roleName($user) === 'student';
    }

    public function canManageTemplate(?User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function canViewInternalReport(?User $user): bool
    {
        return $this->isAdmin($user)
            || $this->isPrincipal($user)
            || $this->isTeacher($user);
    }

    public function canInputDailyRecord(?User $user): bool
    {
        return $this->isAdmin($user)
            || $this->isTeacher($user);
    }

    public function applyStudentScope(Builder $query, User $user): Builder
    {
        if ($this->isAdmin($user) || $this->isPrincipal($user)) {
            return $query;
        }

        if ($this->isTeacher($user)) {
            return $this->applyTeacherStudentScope($query, $user);
        }

        if ($this->isParent($user)) {
            return $this->applyParentStudentScope($query, $user);
        }

        if ($this->isStudent($user)) {
            return $query->where('user_id', $user->id);
        }

        return $query->whereRaw('1 = 0');
    }

    public function applyTeacherStudentScope(Builder $query, User $user): Builder
    {
        /*
         * Teacher scope: jika teacher punya class_room_id di profil, filter by class.
         * Jika tidak, tampilkan semua santri dari school yang sama.
         */
        $teacherProfile = $user->teacherProfile ?? null;

        if ($teacherProfile && isset($teacherProfile->class_room_id)) {
            return $query->where('class_room_id', $teacherProfile->class_room_id);
        }

        // fallback: semua santri di sekolah yang sama
        if ($user->school_id) {
            return $query->where('school_id', $user->school_id);
        }

        return $query;
    }

    public function applyParentStudentScope(Builder $query, User $user): Builder
    {
        $parentProfile = $user->parentProfile ?? null;

        if (! $parentProfile) {
            return $query->whereRaw('1 = 0');
        }

        return $query->whereHas('parents', function (Builder $parentQuery) use ($parentProfile): void {
            $parentQuery->where('parent_profiles.id', $parentProfile->id);
        });
    }

    public function canViewStudent(User $user, Student $student): bool
    {
        if ($this->isAdmin($user) || $this->isPrincipal($user)) {
            return true;
        }

        if ($this->isTeacher($user)) {
            $query = Student::query()->whereKey($student->id);

            return $this->applyTeacherStudentScope($query, $user)->exists();
        }

        if ($this->isParent($user)) {
            $query = Student::query()->whereKey($student->id);

            return $this->applyParentStudentScope($query, $user)->exists();
        }

        if ($this->isStudent($user)) {
            return (int) $student->user_id === (int) $user->id;
        }

        return false;
    }
}
