<?php

namespace App\Http\Requests\Tahsin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTahsinStudentProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        $roleValue = $this->user()?->role ?? null;
        $role = is_object($roleValue) ? ($roleValue->name ?? $roleValue->slug ?? null) : $roleValue;

        return in_array($role, ['super_admin', 'admin', 'admin_sekolah'], true);
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:students,id'],
            'current_tahsin_level_id' => ['nullable', 'exists:tahsin_levels,id'],
            'assigned_teacher_id' => ['nullable', 'exists:users,id'],
            'status' => [
                'required',
                Rule::in(['not_started', 'in_progress', 'passed', 'needs_attention']),
            ],
            'placement_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'started_at' => ['nullable', 'date'],
            'completed_at' => ['nullable', 'date', 'after_or_equal:started_at'],
            'note' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
