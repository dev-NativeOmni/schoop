<?php

namespace App\Http\Requests\Tahsin;

use Illuminate\Foundation\Http\FormRequest;

class TahsinReportFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        $roleValue = $this->user()?->role ?? null;
        $role = is_object($roleValue) ? ($roleValue->name ?? $roleValue->slug ?? null) : $roleValue;

        return in_array($role, [
            'super_admin',
            'admin',
            'admin_sekolah',
            'kepala_sekolah',
            'principal',
            'teacher',
            'guru',
            'guru_tahfidz',
            'parent',
            'student',
        ], true);
    }

    public function rules(): array
    {
        return [
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'class_room_id' => ['nullable', 'exists:class_rooms,id'],
            'student_id' => ['nullable', 'exists:students,id'],
            'teacher_id' => ['nullable', 'exists:users,id'],
            'tahsin_level_id' => ['nullable', 'exists:tahsin_levels,id'],
        ];
    }
}
