<?php

namespace App\Http\Requests\WhiteLabel;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSchoolPwaSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'app_name' => ['required', 'string', 'max:150'],
            'short_name' => ['nullable', 'string', 'max:50'],
            'theme_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'background_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'icon_192' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:1024'],
            'icon_512' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'start_url' => ['required', 'string', 'max:255'],
            'display_mode' => ['required', 'in:standalone,fullscreen,minimal-ui,browser'],
            'is_enabled' => ['nullable', 'boolean'],
        ];
    }
}
