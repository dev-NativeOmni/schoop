<?php

namespace App\Services\Boarding;

use App\Models\User;
use App\Models\Student;
use App\Models\BoardingDormitory;
use App\Models\BoardingRoom;
use App\Models\BoardingLeaveRequest;
use App\Models\BoardingRollCallSession;

class BoardingAccessService
{
    /**
     * Determine if a user can access the internal boarding dashboard.
     */
    public function canAccessDashboard(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin() || $user->isPrincipal() || $user->isBoardingSupervisor();
    }

    /**
     * Determine if a user can perform CRUD operations on master data (dormitories, rooms, beds, supervisors).
     */
    public function canManageMasterData(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin();
    }

    /**
     * Determine if a user can read boarding master data.
     */
    public function canViewMasterData(User $user): bool
    {
        return $this->canAccessDashboard($user);
    }

    /**
     * Determine if a supervisor has scope over a specific dormitory.
     */
    public function supervisorHasDormitoryScope(User $user, int $dormitoryId): bool
    {
        if ($user->isSuperAdmin() || $user->isAdmin() || $user->isPrincipal()) {
            return true;
        }

        if (!$user->isBoardingSupervisor()) {
            return false;
        }

        $profile = $user->boardingSupervisorProfile;
        if (!$profile || $profile->status !== 'active') {
            return false;
        }

        // If assigned to this dormitory specifically
        if ($profile->boarding_dormitory_id === $dormitoryId) {
            return true;
        }

        // If assigned to a room, check if the room belongs to this dormitory
        if ($profile->boarding_room_id) {
            $room = BoardingRoom::find($profile->boarding_room_id);
            return $room && $room->boarding_dormitory_id === $dormitoryId;
        }

        return false;
    }

    /**
     * Determine if a supervisor has scope over a specific room.
     */
    public function supervisorHasRoomScope(User $user, BoardingRoom $room): bool
    {
        if ($user->isSuperAdmin() || $user->isAdmin() || $user->isPrincipal()) {
            return true;
        }

        if (!$user->isBoardingSupervisor()) {
            return false;
        }

        $profile = $user->boardingSupervisorProfile;
        if (!$profile || $profile->status !== 'active') {
            return false;
        }

        if ($profile->boarding_room_id === $room->id) {
            return true;
        }

        if ($profile->boarding_dormitory_id === $room->boarding_dormitory_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine if a user can view a student's boarding info.
     */
    public function canViewStudent(User $user, Student $student): bool
    {
        if ($user->isSuperAdmin() || $user->isAdmin() || $user->isPrincipal()) {
            return true;
        }

        if ($user->isBoardingSupervisor()) {
            $activeAssignment = $student->activeBoardingAssignment;
            if (!$activeAssignment) {
                // Supervisors can view unassigned students to assign them, or check if they belong to their school
                return $user->school_id === $student->school_id;
            }
            return $this->supervisorHasDormitoryScope($user, $activeAssignment->boarding_dormitory_id);
        }

        if ($user->isParent()) {
            return $user->parentProfile && $user->parentProfile->students->contains($student->id);
        }

        if ($user->isStudent()) {
            return $student->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine if a user can manage/edit/assign a student.
     */
    public function canManageStudent(User $user, Student $student): bool
    {
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return true;
        }

        if ($user->isBoardingSupervisor()) {
            $activeAssignment = $student->activeBoardingAssignment;
            if (!$activeAssignment) {
                return $user->school_id === $student->school_id;
            }
            return $this->supervisorHasDormitoryScope($user, $activeAssignment->boarding_dormitory_id);
        }

        return false;
    }

    /**
     * Determine if a user can approve/reject leave requests.
     */
    public function canApproveLeave(User $user, BoardingLeaveRequest $request): bool
    {
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return true;
        }

        if ($user->isBoardingSupervisor()) {
            $student = $request->student;
            $activeAssignment = $student->activeBoardingAssignment;
            if (!$activeAssignment) {
                return false;
            }
            return $this->supervisorHasDormitoryScope($user, $activeAssignment->boarding_dormitory_id);
        }

        return false;
    }

    /**
     * Determine if a user can manage roll call sessions.
     */
    public function canManageRollCall(User $user, BoardingRollCallSession $session): bool
    {
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return true;
        }

        if ($session->status === 'closed') {
            return false; // Closed sessions can only be edited by admin/superadmin, handled above
        }

        if ($user->isBoardingSupervisor()) {
            if ($session->boarding_dormitory_id) {
                return $this->supervisorHasDormitoryScope($user, $session->boarding_dormitory_id);
            }
            if ($session->boarding_room_id) {
                $room = BoardingRoom::find($session->boarding_room_id);
                return $room && $this->supervisorHasRoomScope($user, $room);
            }
            return true;
        }

        return false;
    }
}
