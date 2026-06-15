<?php

namespace App\Http\Requests\Boarding;

use Illuminate\Foundation\Http\FormRequest;

class StoreBoardingStudentAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:students,id'],
            'boarding_dormitory_id' => ['required', 'exists:boarding_dormitories,id'],
            'boarding_room_id' => ['required', 'exists:boarding_rooms,id'],
            'boarding_bed_id' => ['nullable', 'exists:boarding_beds,id'],
            'start_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
