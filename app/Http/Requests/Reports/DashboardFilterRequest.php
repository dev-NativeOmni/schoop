<?php

namespace App\Http\Requests\Reports;

use Illuminate\Foundation\Http\FormRequest;

class DashboardFilterRequest extends FormRequest
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
            'date_from' => 'tanggal mulai',
            'date_until' => 'tanggal akhir',
            'class_room_id' => 'kelas',
            'student_id' => 'santri',
            'teacher_id' => 'guru',
            'status' => 'status',
        ];
    }
}
