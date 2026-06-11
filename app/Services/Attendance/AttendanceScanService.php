<?php

namespace App\Services\Attendance;

use App\Models\AttendanceQrToken;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class AttendanceScanService
{
    public function __construct(
        private readonly AttendanceQrTokenService $tokenService
    ) {
    }

    public function scan(string $payload, AttendanceSession $session, User $scanner): AttendanceRecord
    {
        if ($session->status !== 'active') {
            throw ValidationException::withMessages([
                'qr_payload' => 'Session presensi tidak aktif.',
            ]);
        }

        $token = $this->tokenService->parsePayload($payload);

        $qrToken = AttendanceQrToken::query()
            ->with('student')
            ->where('token', $token)
            ->where('is_active', true)
            ->first();

        if (! $qrToken || ! $qrToken->student) {
            throw ValidationException::withMessages([
                'qr_payload' => 'QR tidak valid atau sudah tidak aktif.',
            ]);
        }

        $student = $qrToken->student;

        if ($session->class_room_id && (int) $student->class_room_id !== (int) $session->class_room_id) {
            throw ValidationException::withMessages([
                'qr_payload' => 'Santri tidak termasuk kelas pada session presensi ini.',
            ]);
        }

        $now = now();
        $status = $this->resolveStatus($session, $now);

        $record = AttendanceRecord::query()
            ->where('attendance_session_id', $session->id)
            ->where('student_id', $student->id)
            ->first();

        if (! $record) {
            $record = AttendanceRecord::query()->create([
                'school_id' => $student->school_id ?? $session->school_id,
                'attendance_session_id' => $session->id,
                'student_id' => $student->id,
                'attendance_date' => $session->attendance_date,
                'status' => $status,
                'check_in_at' => $now,
                'source' => 'qr',
                'scanned_by' => $scanner->id,
            ]);

            $qrToken->update(['last_used_at' => $now]);

            return $record;
        }

        if ($record->check_in_at && ! $record->check_out_at) {
            $record->update([
                'check_out_at' => $now,
                'source' => 'qr',
                'scanned_by' => $scanner->id,
            ]);

            $qrToken->update(['last_used_at' => $now]);

            return $record->fresh();
        }

        throw ValidationException::withMessages([
            'qr_payload' => 'Presensi santri ini sudah lengkap untuk session ini.',
        ]);
    }

    private function resolveStatus(AttendanceSession $session, Carbon $now): string
    {
        if (! $session->late_after_at) {
            return 'present';
        }

        $lateAfter = Carbon::parse($session->attendance_date->toDateString() . ' ' . $session->late_after_at);

        return $now->greaterThan($lateAfter) ? 'late' : 'present';
    }
}
