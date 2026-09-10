<?php

namespace App\Http\Requests\Ai;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAiFeatureFlagRequest extends FormRequest
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
            'feature_key' => 'required|string',
            'label' => 'required|string',
            'is_enabled' => 'boolean',
            'requires_teacher_review' => 'boolean',
            'visible_to_parent' => 'boolean',
            'visible_to_student' => 'boolean',
            'settings' => 'nullable|array',
        ];
    }
}
