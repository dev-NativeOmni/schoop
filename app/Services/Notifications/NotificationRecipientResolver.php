<?php

namespace App\Services\Notifications;

use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Collection;

class NotificationRecipientResolver
{
    public function allActiveUsers(): Collection
    {
        return User::query()
            ->where('is_active', true)
            ->get();
    }

    public function usersByRoles(array $roles): Collection
    {
        return User::query()
            ->where('is_active', true)
            ->whereHas('role', function ($query) use ($roles): void {
                $query->whereIn('name', $roles);
            })
            ->get();
    }

    public function specificUsers(array $userIds): Collection
    {
        return User::query()
            ->where('is_active', true)
            ->whereIn('id', $userIds)
            ->get();
    }

    public function parentsOfStudent(Student $student): Collection
    {
        return $student->parents()
            ->with('user')
            ->get()
            ->pluck('user')
            ->filter()
            ->unique('id')
            ->values();
    }

    public function parentsOfClassRoom(ClassRoom $classRoom): Collection
    {
        return $classRoom->students()
            ->with('parents.user')
            ->where('is_active', true)
            ->get()
            ->flatMap(function (Student $student) {
                return $student->parents
                    ->pluck('user')
                    ->filter();
            })
            ->unique('id')
            ->values();
    }

    public function resolveFromAnnouncementPayload(array $payload): Collection
    {
        $recipientType = $payload['recipient_type'] ?? null;

        return match ($recipientType) {
            'all' => $this->allActiveUsers(),

            'role' => $this->usersByRoles($payload['roles'] ?? []),

            'specific_users' => $this->specificUsers($payload['user_ids'] ?? []),

            'student_parents' => $this->parentsOfStudent(
                Student::query()->findOrFail($payload['student_id'])
            ),

            'class_room_parents' => $this->parentsOfClassRoom(
                ClassRoom::query()->findOrFail($payload['class_room_id'])
            ),

            default => collect(),
        };
    }
}
