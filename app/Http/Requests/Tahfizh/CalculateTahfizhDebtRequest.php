<?php

namespace App\Http\Requests\Tahfizh;

use App\Models\TahfizhDebt;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CalculateTahfizhDebtRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['super_admin', 'admin']) ?? false;
    }

    public function rules(): array
    {
        return [
            'calculation_date' => ['required', 'date'],
            'period_type' => [
                'required',
                'string',
                Rule::in(TahfizhDebt::periodTypes()),
            ],
            'class_room_id' => ['nullable', 'exists:class_rooms,id'],
            'student_id' => ['nullable', 'exists:students,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'calculation_date' => 'tanggal perhitungan',
            'period_type' => 'jenis periode',
            'class_room_id' => 'kelas',
            'student_id' => 'santri',
        ];
    }
}
