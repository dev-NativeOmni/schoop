<?php

namespace App\Http\Requests\Api\Mobile\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMobileMutabaahRecordRequest extends FormRequest
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
            'record_date' => ['required', 'date'],
            'records' => ['required', 'array', 'min:1', 'max:100'],
            'records.*.mutabaah_activity_id' => ['required', 'integer', 'exists:mutabaah_activities,id'],
            'records.*.status' => ['nullable', 'string', 'in:done,not_done,excused'],
            'records.*.score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'records.*.count_value' => ['nullable', 'integer', 'min:0'],
            'records.*.text_value' => ['nullable', 'string', 'max:1000'],
            'records.*.note' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
