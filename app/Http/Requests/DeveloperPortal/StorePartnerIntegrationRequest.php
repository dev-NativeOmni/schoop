<?php

namespace App\Http\Requests\DeveloperPortal;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePartnerIntegrationRequest extends FormRequest
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
            'api_client_id' => ['nullable', 'integer', 'exists:api_clients,id'],
            'name' => ['required', 'string', 'max:255'],
            'integration_type' => ['required', 'string', 'in:sis,finance,attendance,content_partner,analytics_internal,custom'],
            'provider_name' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:draft,active,suspended,disabled'],
            'configuration' => ['nullable', 'json', 'max:4000'],
            'notes' => ['nullable', 'string', 'max:4000'],
        ];
    }
}
