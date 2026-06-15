<?php

namespace App\Http\Requests\DeveloperPortal;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWebhookEndpointRequest extends FormRequest
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
            'api_client_id' => ['required', 'integer', 'exists:api_clients,id'],
            'name' => ['required', 'string', 'max:255'],
            'url' => ['required', 'url', 'max:2048'],
            'secret' => ['nullable', 'string', 'min:16', 'max:255'],
            'subscribed_events' => ['required', 'array', 'min:1'],
            'subscribed_events.*' => ['string', 'max:120'],
            'status' => ['required', 'string', 'in:active,inactive,suspended'],
        ];
    }
}
