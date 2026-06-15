<?php

namespace App\Http\Requests\Api\Mobile\V1;

use App\Models\HafalanRecord;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMobileTahfizhRecordRequest extends FormRequest
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
            'tahfizh_target_id' => ['nullable', 'integer', 'exists:tahfizh_targets,id'],
            'record_date' => ['required', 'date'],
            'start_surah_id' => ['nullable', 'integer', 'exists:quran_surahs,id'],
            'start_ayah' => ['nullable', 'integer', 'min:1'],
            'end_surah_id' => ['nullable', 'integer', 'exists:quran_surahs,id'],
            'end_ayah' => ['nullable', 'integer', 'min:1'],
            'start_page' => ['required', 'integer', 'min:1', 'max:604'],
            'start_line' => ['required', 'integer', 'min:1', 'max:15'],
            'end_page' => ['required', 'integer', 'min:1', 'max:604'],
            'end_line' => ['required', 'integer', 'min:1', 'max:15'],
            'status' => ['required', 'string', Rule::in([
                HafalanRecord::STATUS_LUNAS,
                HafalanRecord::STATUS_KURANG,
                HafalanRecord::STATUS_LEBIH,
                HafalanRecord::STATUS_TIDAK_HADIR,
                HafalanRecord::STATUS_IZIN,
                HafalanRecord::STATUS_SAKIT,
            ])],
            'quality_score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string', 'max:4000'],
        ];
    }
}
