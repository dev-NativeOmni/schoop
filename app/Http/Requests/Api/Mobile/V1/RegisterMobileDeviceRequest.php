<?php

namespace App\Http\Requests\Api\Mobile\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterMobileDeviceRequest extends FormRequest
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
            'device_uuid' => ['required', 'string', 'max:255'],
            'platform' => ['required', 'string', 'in:android,ios,web'],
            'platform_version' => ['nullable', 'string', 'max:100'],
            'app_version' => ['nullable', 'string', 'max:50'],
            'device_name' => ['nullable', 'string', 'max:255'],
            'push_token' => ['nullable', 'string', 'max:4096'],
            'push_provider' => ['nullable', 'string', 'in:fcm,apns,web_push'],
        ];
    }
}
