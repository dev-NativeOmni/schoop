<?php

namespace App\Http\Requests\DeveloperPortal;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ApiRequestLogFilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'api_client_id' => ['nullable', 'integer', 'exists:api_clients,id'],
            'status' => ['nullable', 'integer', 'min:100', 'max:599'],
            'date_from' => ['nullable', 'date'],
            'date_until' => ['nullable', 'date'],
        ];
    }
}
