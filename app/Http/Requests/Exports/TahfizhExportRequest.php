<?php

namespace App\Http\Requests\Exports;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TahfizhExportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole([
            'super_admin',
            'admin',
            'principal',
            'teacher',
        ]) ?? false;
    }

    public function rules(): array
    {
        return [
            'month' => ['nullable', 'date_format:Y-m'],

            'year' => ['nullable', 'integer', 'min:2020', 'max:2100'],
            'quarter' => ['nullable', 'integer', Rule::in([1, 2, 3, 4])],

            'date_from' => ['nullable', 'date'],
            'date_until' => ['nullable', 'date', 'after_or_equal:date_from'],

            'class_room_id' => ['nullable', 'exists:class_rooms,id'],
            'student_id' => ['nullable', 'exists:students,id'],
            'teacher_id' => ['nullable', 'exists:users,id'],
            'status' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function attributes(): array
    {
        return [
            'month' => 'bulan',
            'year' => 'tahun',
            'quarter' => 'triwulan',
            'date_from' => 'tanggal mulai',
            'date_until' => 'tanggal akhir',
            'class_room_id' => 'kelas',
            'student_id' => 'santri',
            'teacher_id' => 'guru',
            'status' => 'status',
        ];
    }
}
