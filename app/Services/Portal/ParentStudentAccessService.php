<?php

namespace App\Services\Portal;

use App\Models\ParentProfile;
use App\Models\Student;
use App\Models\User;

class ParentStudentAccessService
{
    public function parentProfile(User $user): ?ParentProfile
    {
        return $user->parentProfile()
            ->with(['students.classRoom', 'students.school'])
            ->first();
    }

    public function children(User $user)
    {
        $parentProfile = $this->parentProfile($user);

        if (! $parentProfile) {
            return collect();
        }

        return $parentProfile->students()
            ->with(['classRoom', 'school'])
            ->where('students.is_active', true)
            ->orderBy('students.full_name')
            ->get();
    }

    public function canAccessStudent(User $user, Student $student): bool
    {
        $parentProfile = $user->parentProfile;

        if (! $parentProfile) {
            return false;
        }

        return $parentProfile->students()
            ->where('students.id', $student->id)
            ->exists();
    }

    public function abortIfCannotAccess(User $user, Student $student): void
    {
        if (! $this->canAccessStudent($user, $student)) {
            abort(403, 'Orang tua hanya boleh melihat data anak sendiri.');
        }
    }
}
