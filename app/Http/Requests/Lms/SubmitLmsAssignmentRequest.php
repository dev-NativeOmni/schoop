<?php

namespace App\Http\Requests\Lms;

use Illuminate\Foundation\Http\FormRequest;

class SubmitLmsAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'submitted_text' => 'nullable|string',
            'file' => 'nullable|file|max:10240', // Limit 10MB
        ];
    }
}
