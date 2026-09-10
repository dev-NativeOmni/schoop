<?php

namespace App\Services\Boarding;

use App\Models\BoardingBed;
use App\Models\BoardingStudentAssignment;
use App\Models\Student;
use Exception;
use Illuminate\Support\Facades\DB;

class BoardingAssignmentService
{
    /**
     * Assign a student to a dormitory, room, and bed.
     */
    public function assignStudent(
        int $studentId,
        int $dormitoryId,
        int $roomId,
        ?int $bedId,
        string $startDate,
        ?string $notes,
        ?int $creatorId
    ): BoardingStudentAssignment {
        return DB::transaction(function () use ($studentId, $dormitoryId, $roomId, $bedId, $startDate, $notes, $creatorId) {
            // 1. Validate student active assignment
            $activeAssignment = BoardingStudentAssignment::query()
                ->where('student_id', $studentId)
                ->where('status', 'active')
                ->first();

            if ($activeAssignment) {
                throw new Exception('Santri sudah memiliki penempatan asrama yang aktif.');
            }

            // 2. Validate bed occupancy if a bed is specified
            if ($bedId) {
                $bed = BoardingBed::find($bedId);
                if (! $bed || $bed->boarding_room_id !== $roomId) {
                    throw new Exception('Ranjang tidak ditemukan di kamar tersebut.');
                }
                if ($bed->status !== 'available') {
                    throw new Exception("Ranjang tersebut tidak tersedia (status: {$bed->status}).");
                }

                // Update bed status
                $bed->update(['status' => 'occupied']);
            }

            // 3. Create the assignment record
            return BoardingStudentAssignment::create([
                'student_id' => $studentId,
                'boarding_dormitory_id' => $dormitoryId,
                'boarding_room_id' => $roomId,
                'boarding_bed_id' => $bedId,
                'start_date' => $startDate,
                'status' => 'active',
                'notes' => $notes,
                'created_by' => $creatorId,
            ]);
        });
    }

    /**
     * End a student's active assignment.
     */
    public function endAssignment(int $assignmentId, ?string $endDate = null): void
    {
        DB::transaction(function () use ($assignmentId, $endDate) {
            $assignment = BoardingStudentAssignment::findOrFail($assignmentId);

            if ($assignment->status !== 'active') {
                throw new Exception('Penempatan ini sudah tidak aktif.');
            }

            // 1. Update assignment record
            $assignment->update([
                'status' => 'ended',
                'end_date' => $endDate ?? now()->toDateString(),
            ]);

            // 2. Mark the bed as available
            if ($assignment->boarding_bed_id) {
                $bed = BoardingBed::find($assignment->boarding_bed_id);
                if ($bed) {
                    $bed->update(['status' => 'available']);
                }
            }
        });
    }

    /**
     * Move a student to a new bed/room/dormitory.
     */
    public function moveStudent(
        int $studentId,
        int $newDormitoryId,
        int $newRoomId,
        ?int $newBedId,
        string $moveDate,
        ?string $notes,
        ?int $creatorId
    ): BoardingStudentAssignment {
        return DB::transaction(function () use ($studentId, $newDormitoryId, $newRoomId, $newBedId, $moveDate, $notes, $creatorId) {
            $activeAssignment = BoardingStudentAssignment::query()
                ->where('student_id', $studentId)
                ->where('status', 'active')
                ->first();

            if (! $activeAssignment) {
                throw new Exception('Santri tidak memiliki penempatan aktif untuk dipindahkan.');
            }

            // 1. Update old assignment status to 'moved' and record end date
            $activeAssignment->update([
                'status' => 'moved',
                'end_date' => $moveDate,
            ]);

            // Free the old bed
            if ($activeAssignment->boarding_bed_id) {
                $oldBed = BoardingBed::find($activeAssignment->boarding_bed_id);
                if ($oldBed) {
                    $oldBed->update(['status' => 'available']);
                }
            }

            // 2. Validate new bed if specified
            if ($newBedId) {
                $newBed = BoardingBed::find($newBedId);
                if (! $newBed || $newBed->boarding_room_id !== $newRoomId) {
                    throw new Exception('Ranjang baru tidak ditemukan di kamar tersebut.');
                }
                if ($newBed->status !== 'available') {
                    throw new Exception("Ranjang baru tersebut tidak tersedia (status: {$newBed->status}).");
                }
                // Occupy the new bed
                $newBed->update(['status' => 'occupied']);
            }

            // 3. Create the new assignment record
            return BoardingStudentAssignment::create([
                'student_id' => $studentId,
                'boarding_dormitory_id' => $newDormitoryId,
                'boarding_room_id' => $newRoomId,
                'boarding_bed_id' => $newBedId,
                'start_date' => $moveDate,
                'status' => 'active',
                'notes' => $notes ?? 'Dipindahkan dari kamar sebelumnya.',
                'created_by' => $creatorId,
            ]);
        });
    }
}
