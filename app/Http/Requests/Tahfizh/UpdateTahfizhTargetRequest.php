<?php

namespace App\Http\Requests\Tahfizh;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTahfizhTargetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['super_admin', 'admin']) ?? false;
    }

    public function rules(): array
    {
        return [
            'school_id' => ['required', 'exists:schools,id'],
            'class_room_id' => ['nullable', 'exists:class_rooms,id'],
            'student_id' => ['nullable', 'exists:students,id'],
            'name' => ['required', 'string', 'max:255'],
            'program_type' => ['nullable', 'string', 'max:100'],
            'daily_target_lines' => ['required', 'integer', 'min:0', 'max:300'],
            'weekly_target_lines' => ['required', 'integer', 'min:0', 'max:1500'],
            'monthly_target_lines' => ['required', 'integer', 'min:0', 'max:6000'],
            'effective_from' => ['nullable', 'date'],
            'effective_until' => ['nullable', 'date', 'after_or_equal:effective_from'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'school_id' => 'sekolah',
            'class_room_id' => 'kelas',
            'student_id' => 'santri',
            'name' => 'nama target',
            'program_type' => 'jenis program',
            'daily_target_lines' => 'target harian',
            'weekly_target_lines' => 'target mingguan',
            'monthly_target_lines' => 'target bulanan',
            'effective_from' => 'tanggal mulai berlaku',
            'effective_until' => 'tanggal akhir berlaku',
            'is_active' => 'status aktif',
        ];
    }
}
