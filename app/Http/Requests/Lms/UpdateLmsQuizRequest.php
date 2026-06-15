<?php

namespace App\Http\Requests\Lms;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLmsQuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'time_limit_minutes' => 'nullable|integer|min:0',
            'max_attempts' => 'nullable|integer|min:0',
            'passing_score' => 'nullable|numeric|min:0|max:100',
            'is_randomized' => 'nullable|boolean',
        ];
    }
}
