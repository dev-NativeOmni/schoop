<?php

namespace App\Http\Requests\Boarding;

use Illuminate\Foundation\Http\FormRequest;

class StoreBoardingHealthLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:students,id'],
            'severity' => ['required', 'string', 'in:low,medium,high,critical'],
            'condition_title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'action_taken' => ['nullable', 'string'],
            'logged_at' => ['required', 'date_format:Y-m-d\TH:i'],
        ];
    }
}
