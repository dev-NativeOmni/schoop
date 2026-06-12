<?php

namespace App\Http\Requests\SchoolOs;

use Illuminate\Foundation\Http\FormRequest;

class SchoolOsSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'q' => ['required', 'string', 'min:2', 'max:100'],
        ];
    }
}
