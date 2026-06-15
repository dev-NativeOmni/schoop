<?php

namespace App\Http\Requests\Api\Mobile\V1;

use App\Models\TahsinAssessment;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMobileTahsinAssessmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['super_admin', 'admin', 'teacher']) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'teacher_id' => ['nullable', 'integer', 'exists:users,id'],
            'tahsin_level_id' => ['nullable', 'integer', 'exists:tahsin_levels,id'],
            'assessment_date' => ['required', 'date'],
            'assessment_type' => ['required', 'string', Rule::in([
                TahsinAssessment::TYPE_PLACEMENT,
                TahsinAssessment::TYPE_DAILY,
                TahsinAssessment::TYPE_WEEKLY,
                TahsinAssessment::TYPE_MONTHLY,
                TahsinAssessment::TYPE_FINAL,
            ])],
            'status' => ['nullable', 'string', 'max:100'],
            'note' => ['nullable', 'string', 'max:4000'],
            'recommendation' => ['nullable', 'string', 'max:4000'],
            'items' => ['required', 'array', 'min:1', 'max:100'],
            'items.*.tahsin_skill_id' => ['required', 'integer', 'exists:tahsin_skills,id'],
            'items.*.score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'items.*.status' => ['nullable', 'string', 'in:mastered,progress,weak,not_tested'],
            'items.*.note' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
