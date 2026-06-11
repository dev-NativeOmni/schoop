<?php

namespace App\Http\Requests\Portal;

use Illuminate\Foundation\Http\FormRequest;

class ParentProgressFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('parent') ?? false;
    }

    public function rules(): array
    {
        return [
            'month' => ['nullable', 'date_format:Y-m'],
            'date_from' => ['nullable', 'date'],
            'date_until' => ['nullable', 'date', 'after_or_equal:date_from'],
        ];
    }

    public function attributes(): array
    {
        return [
            'month' => 'bulan',
            'date_from' => 'tanggal mulai',
            'date_until' => 'tanggal akhir',
        ];
    }
}
