<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;

class ScanAttendanceQrRequest extends FormRequest
{
    public function authorize(): bool
    {
        $roleValue = $this->user()?->role ?? null;
        $role = is_object($roleValue) ? ($roleValue->name ?? $roleValue->slug ?? null) : $roleValue;

        return in_array($role, [
            'super_admin',
            'admin',
            'admin_sekolah',
            'teacher',
            'guru',
            'guru_tahfidz',
        ], true);
    }

    public function rules(): array
    {
        return [
            'attendance_session_id' => ['required', 'exists:attendance_sessions,id'],
            'qr_payload' => ['required', 'string', 'max:255'],
        ];
    }
}
