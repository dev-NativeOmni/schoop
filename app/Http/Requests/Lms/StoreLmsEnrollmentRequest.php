<?php

namespace App\Http\Requests\Lms;

use Illuminate\Foundation\Http\FormRequest;

class StoreLmsEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_id' => 'required|exists:lms_courses,id',
            'enrollment_type' => 'required|in:student,class_room',
            'student_id' => 'required_if:enrollment_type,student|nullable|exists:students,id',
            'class_room_id' => 'required_if:enrollment_type,class_room|nullable|exists:class_rooms,id',
        ];
    }
}
