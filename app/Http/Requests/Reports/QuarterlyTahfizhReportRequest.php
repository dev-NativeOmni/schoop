<?php

namespace App\Http\Requests\Reports;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuarterlyTahfizhReportRequest extends FormRequest
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
            'year' => ['nullable', 'integer', 'min:2020', 'max:2100'],
            'quarter' => ['nullable', 'integer', Rule::in([1, 2, 3, 4])],
            'class_room_id' => ['nullable', 'exists:class_rooms,id'],
            'student_id' => ['nullable', 'exists:students,id'],
            'teacher_id' => ['nullable', 'exists:users,id'],
            'status' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function attributes(): array
    {
        return [
            'year' => 'tahun',
            'quarter' => 'triwulan',
            'class_room_id' => 'kelas',
            'student_id' => 'santri',
            'teacher_id' => 'guru',
            'status' => 'status',
        ];
    }
}
