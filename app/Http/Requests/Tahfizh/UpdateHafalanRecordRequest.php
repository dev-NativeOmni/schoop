<?php

namespace App\Http\Requests\Tahfizh;

use App\Models\HafalanRecord;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateHafalanRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $record = $this->route('hafalan_record');

        if (! $user || ! $record) {
            return false;
        }

        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        if ($user->hasRole('teacher')) {
            return (int) $record->teacher_id === (int) $user->id;
        }

        return false;
    }

    public function rules(): array
    {
        return [
            'school_id' => ['required', 'exists:schools,id'],
            'student_id' => ['required', 'exists:students,id'],
            'teacher_id' => ['nullable', 'exists:users,id'],
            'tahfizh_target_id' => ['nullable', 'exists:tahfizh_targets,id'],
            'record_date' => ['required', 'date'],

            'start_surah_id' => ['nullable', 'exists:quran_surahs,id'],
            'start_ayah' => ['nullable', 'integer', 'min:1'],
            'end_surah_id' => ['nullable', 'exists:quran_surahs,id'],
            'end_ayah' => ['nullable', 'integer', 'min:1'],

            'start_page' => ['required', 'integer', 'min:1', 'max:604'],
            'start_line' => ['required', 'integer', 'min:1', 'max:15'],
            'end_page' => ['required', 'integer', 'min:1', 'max:604'],
            'end_line' => ['required', 'integer', 'min:1', 'max:15'],

            'status' => [
                'required',
                'string',
                Rule::in([
                    HafalanRecord::STATUS_LUNAS,
                    HafalanRecord::STATUS_KURANG,
                    HafalanRecord::STATUS_LEBIH,
                ]),
            ],

            'quality_score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'school_id' => 'sekolah',
            'student_id' => 'santri',
            'teacher_id' => 'guru',
            'tahfizh_target_id' => 'target tahfizh',
            'record_date' => 'tanggal setoran',
            'start_page' => 'halaman awal',
            'start_line' => 'baris awal',
            'end_page' => 'halaman akhir',
            'end_line' => 'baris akhir',
            'status' => 'status setoran',
            'quality_score' => 'nilai kualitas',
            'notes' => 'catatan',
        ];
    }
}
