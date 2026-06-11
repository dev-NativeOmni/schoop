<?php

namespace App\Http\Requests\Tahsin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTahsinAssessmentRequest extends FormRequest
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
            'student_id' => ['required', 'exists:students,id'],
            'teacher_id' => ['nullable', 'exists:users,id'],
            'tahsin_level_id' => ['nullable', 'exists:tahsin_levels,id'],
            'assessment_date' => ['required', 'date'],
            'assessment_type' => [
                'required',
                Rule::in(['placement', 'daily', 'weekly', 'monthly', 'final']),
            ],
            'status' => [
                'nullable',
                Rule::in(['draft', 'submitted', 'reviewed']),
            ],
            'note' => ['nullable', 'string', 'max:3000'],
            'recommendation' => ['nullable', 'string', 'max:3000'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.tahsin_skill_id' => ['required', 'exists:tahsin_skills,id'],
            'items.*.score' => ['required', 'numeric', 'min:0', 'max:100'],
            'items.*.status' => [
                'required',
                Rule::in(['mastered', 'progress', 'weak', 'not_tested']),
            ],
            'items.*.note' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
