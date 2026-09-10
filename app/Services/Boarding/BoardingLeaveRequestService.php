<?php

namespace App\Services\Boarding;

use App\Models\BoardingLeaveRequest;
use Exception;

class BoardingLeaveRequestService
{
    /**
     * Create a new leave request.
     */
    public function createRequest(
        int $studentId,
        string $type,
        ?string $destination,
        ?string $reason,
        string $startAt,
        ?string $endAt,
        int $userId,
        string $status = 'submitted'
    ): BoardingLeaveRequest {
        return BoardingLeaveRequest::create([
            'student_id' => $studentId,
            'requested_by_user_id' => $userId,
            'type' => $type,
            'status' => $status,
            'destination' => $destination,
            'reason' => $reason,
            'leave_start_at' => $startAt,
            'leave_end_at' => $endAt,
        ]);
    }

    /**
     * Approve a leave request.
     */
    public function approve(int $requestId, int $approverId, ?string $note): void
    {
        $request = BoardingLeaveRequest::findOrFail($requestId);

        if ($request->status !== 'submitted') {
            throw new Exception("Hanya pengajuan dengan status 'submitted' yang dapat disetujui.");
        }

        $request->update([
            'status' => 'approved',
            'approved_by_user_id' => $approverId,
            'approval_note' => $note,
        ]);
    }

    /**
     * Reject a leave request.
     */
    public function reject(int $requestId, int $approverId, ?string $note): void
    {
        $request = BoardingLeaveRequest::findOrFail($requestId);

        if ($request->status !== 'submitted') {
            throw new Exception("Hanya pengajuan dengan status 'submitted' yang dapat ditolak.");
        }

        $request->update([
            'status' => 'rejected',
            'approved_by_user_id' => $approverId,
            'approval_note' => $note,
        ]);
    }

    /**
     * Mark the student as returned from leave.
     */
    public function markReturned(int $requestId): void
    {
        $request = BoardingLeaveRequest::findOrFail($requestId);

        if ($request->status !== 'approved') {
            throw new Exception("Santri hanya bisa ditandai kembali dari izin yang berstatus 'approved'.");
        }

        $request->update([
            'status' => 'returned',
            'returned_at' => now(),
        ]);
    }

    /**
     * Cancel a leave request.
     */
    public function cancel(int $requestId): void
    {
        $request = BoardingLeaveRequest::findOrFail($requestId);

        if (! in_array($request->status, ['draft', 'submitted'], true)) {
            throw new Exception('Pengajuan izin tidak dapat dibatalkan pada status saat ini.');
        }

        $request->update([
            'status' => 'cancelled',
        ]);
    }
}
