<?php

namespace App\Http\Requests\Lms;

use Illuminate\Foundation\Http\FormRequest;

class StoreLmsLessonResourceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'lesson_id' => 'required|exists:lms_lessons,id',
            'title' => 'required|string|max:255',
            'resource_type' => 'required|in:file,link',
            'file' => 'nullable|file|max:10240', // Validate max 10MB in Controller service level too
            'external_url' => 'nullable|url|max:255',
            'sort_order' => 'nullable|integer',
        ];
    }
}
