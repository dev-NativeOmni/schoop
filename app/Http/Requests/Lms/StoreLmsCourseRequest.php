<?php

namespace App\Http\Requests\Lms;

use Illuminate\Foundation\Http\FormRequest;

class StoreLmsCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'course_code' => 'required|string|max:50',
            'type' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'level' => 'nullable|string|max:50',
            'visibility' => 'nullable|in:draft,published,archived',
            'enrollment_mode' => 'nullable|in:manual,class_room,school_wide',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_required' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
            'instructor_ids' => 'nullable|array',
            'instructor_ids.*' => 'exists:teacher_profiles,id',
            'primary_instructor_id' => 'nullable|integer',
        ];
    }
}
