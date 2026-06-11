<?php

namespace App\Http\Requests\Mutabaah;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMutabaahDailyInputRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return $user && $user->hasRole(['super_admin', 'admin', 'teacher']);
    }

    public function rules(): array
    {
        return [
            'student_id'                         => ['required', 'exists:students,id'],
            'record_date'                        => ['required', 'date', 'before_or_equal:today'],
            'records'                            => ['required', 'array', 'min:1'],
            'records.*.mutabaah_activity_id'     => ['required', 'exists:mutabaah_activities,id'],
            'records.*.status'                   => ['required', Rule::in(['done', 'not_done', 'excused'])],
            'records.*.score'                    => ['nullable', 'integer', 'min:0', 'max:100'],
            'records.*.count_value'              => ['nullable', 'integer', 'min:0'],
            'records.*.text_value'               => ['nullable', 'string', 'max:1000'],
            'records.*.note'                     => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'records.required'                       => 'Data aktivitas mutabaah wajib diisi.',
            'records.*.mutabaah_activity_id.required' => 'Aktivitas mutabaah wajib dipilih.',
            'records.*.status.required'              => 'Status mutabaah wajib diisi.',
            'records.*.status.in'                    => 'Status mutabaah tidak valid.',
            'records.*.score.min'                    => 'Skor tidak boleh negatif.',
            'records.*.score.max'                    => 'Skor maksimal 100.',
        ];
    }
}
