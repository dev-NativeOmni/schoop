<?php

namespace App\Http\Requests\Lms;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLmsLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'lesson_type' => 'sometimes|required|in:text,file,link,embed,assignment,quiz',
            'content' => 'nullable|string',
            'external_url' => 'nullable|url|max:255',
            'embed_code' => 'nullable|string',
            'estimated_minutes' => 'nullable|integer|min:0',
            'is_required' => 'nullable|boolean',
            'visibility' => 'sometimes|required|in:draft,published,archived',
            'available_from' => 'nullable|date',
            'available_until' => 'nullable|date|after_or_equal:available_from',
            'sort_order' => 'nullable|integer',
        ];
    }
}
