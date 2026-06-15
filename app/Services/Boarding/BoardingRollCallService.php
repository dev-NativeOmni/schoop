<?php

namespace App\Services\Boarding;

use App\Models\BoardingRollCallSession;
use App\Models\BoardingRollCallRecord;
use App\Models\BoardingStudentAssignment;
use Illuminate\Support\Facades\DB;
use Exception;

class BoardingRollCallService
{
    /**
     * Create a new roll call session and pre-populate records with 'present' status.
     */
    public function createSession(
        ?int $dormitoryId,
        ?int $roomId,
        string $date,
        string $type,
        int $creatorId
    ): BoardingRollCallSession {
        return DB::transaction(function () use ($dormitoryId, $roomId, $date, $type, $creatorId) {
            // Check for existing open session of same type, date, and scope
            $existing = BoardingRollCallSession::query()
                ->where('session_date', $date)
                ->where('session_type', $type)
                ->where('boarding_dormitory_id', $dormitoryId)
                ->where('boarding_room_id', $roomId)
                ->where('status', 'open')
                ->first();

            if ($existing) {
                throw new Exception("Sesi absen sejenis yang masih terbuka sudah ada untuk tanggal tersebut.");
            }

            // Create session
            $session = BoardingRollCallSession::create([
                'boarding_dormitory_id' => $dormitoryId,
                'boarding_room_id' => $roomId,
                'session_date' => $date,
                'session_type' => $type,
                'started_at' => now(),
                'status' => 'open',
                'created_by' => $creatorId,
            ]);

            // Query active students in this scope
            $query = BoardingStudentAssignment::query()
                ->where('status', 'active');

            if ($roomId) {
                $query->where('boarding_room_id', $roomId);
            } elseif ($dormitoryId) {
                $query->where('boarding_dormitory_id', $dormitoryId);
            }

            $activeAssignments = $query->get();

            // Populate records with default 'present'
            foreach ($activeAssignments as $assignment) {
                BoardingRollCallRecord::create([
                    'boarding_roll_call_session_id' => $session->id,
                    'student_id' => $assignment->student_id,
                    'recorded_by_user_id' => $creatorId,
                    'status' => 'present',
                    'note' => null,
                    'recorded_at' => now(),
                ]);
            }

            return $session;
        });
    }

    /**
     * Update a student's roll call record in a session.
     */
    public function recordAttendance(
        int $sessionId,
        int $studentId,
        string $status,
        ?string $note,
        int $recordedBy,
        bool $bypassClosedCheck = false
    ): BoardingRollCallRecord {
        $session = BoardingRollCallSession::findOrFail($sessionId);

        // Enforce closed lock, unless bypassed by admins
        if ($session->status === 'closed' && !$bypassClosedCheck) {
            throw new Exception("Sesi absen sudah ditutup dan tidak dapat diedit.");
        }

        // Save or update attendance record
        return BoardingRollCallRecord::updateOrCreate(
            [
                'boarding_roll_call_session_id' => $sessionId,
                'student_id' => $studentId,
            ],
            [
                'recorded_by_user_id' => $recordedBy,
                'status' => $status,
                'note' => $note,
                'recorded_at' => now(),
            ]
        );
    }

    /**
     * Close a roll call session.
     */
    public function closeSession(int $sessionId): void
    {
        $session = BoardingRollCallSession::findOrFail($sessionId);
        
        if ($session->status === 'closed') {
            throw new Exception("Sesi absen sudah ditutup.");
        }

        $session->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);
    }
}
