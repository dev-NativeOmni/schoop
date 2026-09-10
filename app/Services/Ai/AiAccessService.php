<?php

namespace App\Services\Ai;

use App\Models\Student;
use App\Models\User;

class AiAccessService
{
    public function canViewStudentAiData(User $user, Student $student): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($student->school_id !== $user->school_id) {
            return false;
        }

        if ($user->isAdmin() || $user->isPrincipal() || $user->isTeacher()) {
            return true;
        }

        if ($user->isParent()) {
            $parentProfile = $user->parentProfile;
            if ($parentProfile && $parentProfile->students()->where('students.id', $student->id)->exists()) {
                return true;
            }
        }

        if ($user->isStudent()) {
            $studentProfile = $user->studentProfile;
            if ($studentProfile && $studentProfile->id === $student->id) {
                return true;
            }
        }

        return false;
    }

    public function canManageAiSettings(User $user, int $schoolId): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->isAdmin() && $user->school_id === $schoolId;
    }

    public function canReviewAiOutput(User $user, Student $student): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($student->school_id !== $user->school_id) {
            return false;
        }

        return $user->isAdmin() || $user->isTeacher();
    }

    public function canViewPublishedAiOutput(User $user, Student $student): bool
    {
        return $this->canViewStudentAiData($user, $student);
    }
}
