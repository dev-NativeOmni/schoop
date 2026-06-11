<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAttendanceSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $roleValue = $this->user()?->role ?? null;
        $role = is_object($roleValue) ? ($roleValue->name ?? $roleValue->slug ?? null) : $roleValue;

        return in_array($role, [
            'super_admin',
            'admin',
            'admin_sekolah',
        ], true);
    }

    public function rules(): array
    {
        return [
            'class_room_id' => ['nullable', 'exists:class_rooms,id'],
            'name' => ['required', 'string', 'max:255'],
            'attendance_date' => ['required', 'date'],
            'check_in_starts_at' => ['nullable', 'date_format:H:i'],
            'late_after_at' => ['nullable', 'date_format:H:i'],
            'check_in_ends_at' => ['nullable', 'date_format:H:i'],
            'check_out_starts_at' => ['nullable', 'date_format:H:i'],
            'check_out_ends_at' => ['nullable', 'date_format:H:i'],
            'status' => ['required', Rule::in(['draft', 'active', 'closed'])],
            'note' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
