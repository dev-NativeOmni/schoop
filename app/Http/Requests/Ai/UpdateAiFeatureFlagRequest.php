<?php

namespace App\Http\Requests\Ai;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAiFeatureFlagRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'is_enabled' => 'sometimes|boolean',
            'requires_teacher_review' => 'sometimes|boolean',
            'visible_to_parent' => 'sometimes|boolean',
            'visible_to_student' => 'sometimes|boolean',
            'settings' => 'nullable|array',
        ];
    }
}
