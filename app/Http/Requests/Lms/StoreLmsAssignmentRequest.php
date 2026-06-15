<?php

namespace App\Http\Requests\Lms;

use Illuminate\Foundation\Http\FormRequest;

class StoreLmsAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_id' => 'required|exists:lms_courses,id',
            'lesson_id' => 'required|exists:lms_lessons,id',
            'title' => 'required|string|max:255',
            'instructions' => 'nullable|string',
            'max_score' => 'nullable|integer|min:0',
            'passing_score' => 'nullable|integer|min:0',
            'due_date' => 'nullable|date',
            'allowed_file_types' => 'nullable|string|max:255',
            'max_file_size_kb' => 'nullable|integer|min:1',
        ];
    }
}
