<?php

namespace App\Services\SchoolOs;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class SchoolOsAccessService
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

    public function canManageSettings(?User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function canViewSchoolOs(?User $user): bool
    {
        return $this->isAdmin($user)
            || $this->isPrincipal($user)
            || $this->isTeacher($user);
    }

    public function applyStudentScope(Builder $query, User $user): Builder
    {
        if ($this->isAdmin($user) || $this->isPrincipal($user)) {
            return $query;
        }

        if ($this->isTeacher($user)) {
            $teacherProfile = $user->teacherProfile ?? null;

            if (! $teacherProfile) {
                return $query->whereRaw('1 = 0');
            }

            if (! empty($teacherProfile->class_room_id)) {
                return $query->where(['class_room_id' => $teacherProfile->class_room_id]);
            }

            return $query->where(function (Builder $teacherScope) use ($user): void {
                $teacherScope
                    ->whereHas('classRoom', function (Builder $classRoomQuery) use ($user): void {
                        $classRoomQuery->where(['homeroom_teacher_id' => $user->id]);
                    })
                    ->orWhereHas('hafalanRecords', function (Builder $recordQuery) use ($user): void {
                        $recordQuery->where(['teacher_id' => $user->id]);
                    })
                    ->orWhereHas('tahsinProfile', function (Builder $profileQuery) use ($user): void {
                        $profileQuery->where(['assigned_teacher_id' => $user->id]);
                    })
                    ->orWhereHas('tahsinAssessments', function (Builder $assessmentQuery) use ($user): void {
                        $assessmentQuery->where(['teacher_id' => $user->id]);
                    });
            });
        }

        if ($this->isParent($user)) {
            $parentProfile = $user->parentProfile ?? null;

            if (! $parentProfile) {
                return $query->whereRaw('1 = 0');
            }

            return $query->whereHas('parents', function (Builder $parentQuery) use ($parentProfile): void {
                $parentQuery->where(['parent_profiles.id' => $parentProfile->id]);
            });
        }

        if ($this->isStudent($user)) {
            return $query->where(['user_id' => $user->id]);
        }

        return $query->whereRaw('1 = 0');
    }

    public function canViewStudent(User $user, Student $student): bool
    {
        return $this->applyStudentScope(
            Student::query()->whereKey($student->id),
            $user
        )->exists();
    }
}
