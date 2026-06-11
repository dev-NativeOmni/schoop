<?php

namespace App\Http\Requests\Mutabaah;

use Illuminate\Foundation\Http\FormRequest;

class MutabaahReportFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // access controlled at controller level
    }

    public function rules(): array
    {
        return [
            'start_date'    => ['nullable', 'date'],
            'end_date'      => ['nullable', 'date', 'after_or_equal:start_date'],
            'class_room_id' => ['nullable', 'exists:class_rooms,id'],
            'student_id'    => ['nullable', 'exists:students,id'],
            'year'          => ['nullable', 'integer', 'min:2020', 'max:2099'],
            'month'         => ['nullable', 'integer', 'min:1', 'max:12'],
        ];
    }
}
