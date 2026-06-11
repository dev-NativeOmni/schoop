<?php

namespace App\Services\Attendance;

use App\Models\AttendanceQrToken;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Str;

class AttendanceQrTokenService
{
    public function ensureActiveToken(Student $student, ?User $createdBy = null): AttendanceQrToken
    {
        $existing = AttendanceQrToken::query()
            ->where('student_id', $student->id)
            ->where('is_active', true)
            ->first();

        if ($existing) {
            return $existing;
        }

        return $this->createToken($student, $createdBy);
    }

    public function rotateToken(Student $student, ?User $createdBy = null): AttendanceQrToken
    {
        AttendanceQrToken::query()
            ->where('student_id', $student->id)
            ->where('is_active', true)
            ->update([
                'is_active' => false,
                'rotated_at' => now(),
            ]);

        return $this->createToken($student, $createdBy);
    }

    public function qrPayload(AttendanceQrToken $qrToken): string
    {
        return 'HFP-ATT:' . $qrToken->token;
    }

    public function parsePayload(string $payload): string
    {
        $payload = trim($payload);

        if (str_starts_with($payload, 'HFP-ATT:')) {
            return Str::after($payload, 'HFP-ATT:');
        }

        return $payload;
    }

    private function createToken(Student $student, ?User $createdBy = null): AttendanceQrToken
    {
        do {
            $token = Str::random(64);
        } while (AttendanceQrToken::query()->where('token', $token)->exists());

        return AttendanceQrToken::query()->create([
            'school_id' => $student->school_id ?? null,
            'student_id' => $student->id,
            'token' => $token,
            'is_active' => true,
            'created_by' => $createdBy?->id,
        ]);
    }
}
