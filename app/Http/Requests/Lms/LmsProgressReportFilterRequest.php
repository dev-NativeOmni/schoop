<?php

namespace App\Http\Requests\Lms;

use Illuminate\Foundation\Http\FormRequest;

class LmsProgressReportFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_id' => 'nullable|exists:lms_courses,id',
            'class_room_id' => 'nullable|exists:class_rooms,id',
        ];
    }
}
