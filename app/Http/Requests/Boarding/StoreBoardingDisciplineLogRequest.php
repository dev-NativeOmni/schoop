<?php

namespace App\Http\Requests\Boarding;

use Illuminate\Foundation\Http\FormRequest;

class StoreBoardingDisciplineLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:students,id'],
            'type' => ['required', 'string', 'in:violation,warning,achievement,note'],
            'category' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'points' => ['required', 'integer'],
            'action_taken' => ['nullable', 'string'],
            'logged_at' => ['required', 'date_format:Y-m-d\TH:i'],
        ];
    }
}
