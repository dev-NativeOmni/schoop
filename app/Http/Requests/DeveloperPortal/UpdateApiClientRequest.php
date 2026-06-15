<?php

namespace App\Http\Requests\DeveloperPortal;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateApiClientRequest extends FormRequest
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
            'school_id' => ['nullable', 'integer', 'exists:schools,id'],
            'name' => ['required', 'string', 'max:255'],
            'client_code' => ['nullable', 'string', 'max:120', 'unique:api_clients,client_code,'.$this->route('api_client')?->id],
            'description' => ['nullable', 'string', 'max:4000'],
            'owner_name' => ['nullable', 'string', 'max:255'],
            'owner_email' => ['nullable', 'email', 'max:255'],
            'status' => ['required', 'string', 'in:draft,active,suspended,revoked'],
            'rate_limit_per_minute' => ['required', 'integer', 'min:1', 'max:1000'],
            'allowed_ips' => ['nullable', 'string', 'max:4000'],
            'scope_ids' => ['nullable', 'array'],
            'scope_ids.*' => ['integer', 'exists:api_scopes,id'],
        ];
    }
}
