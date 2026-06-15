<?php

namespace App\Http\Requests\WhiteLabel;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSchoolThemeSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'theme_name' => ['required', 'string', 'max:100'],
            'primary_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'secondary_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'accent_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'text_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'background_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'sidebar_style' => ['required', 'in:default,compact,expanded'],
            'header_style' => ['required', 'in:default,minimal,branded'],
            'login_layout' => ['required', 'in:centered,split,card'],
            'card_radius' => ['required', 'in:none,sm,md,lg,xl'],
            'button_radius' => ['required', 'in:none,sm,md,lg,xl'],
        ];
    }
}
