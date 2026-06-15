<?php

namespace App\Http\Requests\Lms;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLmsAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'instructions' => 'nullable|string',
            'max_score' => 'nullable|integer|min:0',
            'passing_score' => 'nullable|integer|min:0',
            'due_date' => 'nullable|date',
            'allowed_file_types' => 'nullable|string|max:255',
            'max_file_size_kb' => 'nullable|integer|min:1',
        ];
    }
}
