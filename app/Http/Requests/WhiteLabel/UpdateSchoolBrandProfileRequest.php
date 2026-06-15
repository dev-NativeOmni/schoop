<?php

namespace App\Http\Requests\WhiteLabel;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSchoolBrandProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'display_name' => ['required', 'string', 'max:150'],
            'short_name' => ['nullable', 'string', 'max:50'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp,svg', 'max:2048'],
            'favicon' => ['nullable', 'image', 'mimes:png,ico,svg', 'max:512'],
            'login_background' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:4096'],
            'public_contact_email' => ['nullable', 'email', 'max:150'],
            'public_contact_phone' => ['nullable', 'string', 'max:50'],
            'public_address' => ['nullable', 'string', 'max:1000'],
            'public_website_url' => ['nullable', 'url', 'max:255'],
        ];
    }
}
