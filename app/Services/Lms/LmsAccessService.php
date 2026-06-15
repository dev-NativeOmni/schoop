<?php

namespace App\Services\Lms;

use App\Models\User;
use App\Models\LmsCourse;
use App\Models\LmsLesson;
use App\Models\LmsAssignment;
use App\Models\LmsQuiz;
use App\Models\Student;

class LmsAccessService
{
    public function canCreateCourse(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin() || $user->isTeacher();
    }

    public function canViewCourse(User $user, LmsCourse $course): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Verify school boundary
        if ($course->school_id !== null && $course->school_id !== $user->school_id) {
            return false;
        }

        if ($user->isAdmin() || $user->isPrincipal()) {
            return true;
        }

        if ($user->isTeacher()) {
            // Teacher can see all school courses, but can only edit assigned ones
            return true;
        }

        if ($user->isStudent()) {
            // Student can view if enrolled and course is published
            if ($course->visibility !== 'published') {
                return false;
            }

            $student = $user->studentProfile;
            if (!$student) {
                return false;
            }

            return $course->students()->where('students.id', $student->id)->exists();
        }

        if ($user->isParent()) {
            // Parent can view if one of their children is enrolled and course is published
            if ($course->visibility !== 'published') {
                return false;
            }

            $parent = $user->parentProfile;
            if (!$parent) {
                return false;
            }

            $childrenIds = $parent->students()->pluck('students.id')->toArray();
            if (empty($childrenIds)) {
                return false;
            }

            return $course->students()->whereIn('students.id', $childrenIds)->exists();
        }

        return false;
    }

    public function canManageCourse(User $user, LmsCourse $course): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Verify school boundary
        if ($course->school_id !== null && $course->school_id !== $user->school_id) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isTeacher()) {
            // Check if teacher is assigned to this course
            $teacherProfile = $user->teacherProfile;
            if (!$teacherProfile) {
                return false;
            }
            return $course->instructors()->where('teacher_profiles.id', $teacherProfile->id)->exists();
        }

        return false;
    }

    public function canViewLesson(User $user, LmsLesson $lesson): bool
    {
        if ($lesson->visibility !== 'published' && !$user->isSuperAdmin() && !$user->isAdmin() && !$user->isTeacher() && !$user->isPrincipal()) {
            return false;
        }
        return $this->canViewCourse($user, $lesson->course);
    }

    public function canManageLesson(User $user, LmsLesson $lesson): bool
    {
        return $this->canManageCourse($user, $lesson->course);
    }

    public function canSubmitAssignment(User $user, LmsAssignment $assignment): bool
    {
        if (!$user->isStudent()) {
            return false;
        }

        // Check if lesson is published and accessible
        if (!$this->canViewLesson($user, $assignment->lesson)) {
            return false;
        }

        // Check enrollment
        $student = $user->studentProfile;
        if (!$student) {
            return false;
        }

        return $assignment->course->students()->where('students.id', $student->id)->exists();
    }

    public function canAttemptQuiz(User $user, LmsQuiz $quiz): bool
    {
        if (!$user->isStudent()) {
            return false;
        }

        // Check if lesson is published and accessible
        if (!$this->canViewLesson($user, $quiz->lesson)) {
            return false;
        }

        // Check enrollment
        $student = $user->studentProfile;
        if (!$student) {
            return false;
        }

        return $quiz->course->students()->where('students.id', $student->id)->exists();
    }

    public function canViewChildProgress(User $user, Student $student): bool
    {
        if (!$user->isParent()) {
            return false;
        }

        $parent = $user->parentProfile;
        if (!$parent) {
            return false;
        }

        return $parent->students()->where('students.id', $student->id)->exists();
    }
}
