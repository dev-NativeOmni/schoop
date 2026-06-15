<?php

namespace App\Http\Requests\Analytics;

use Illuminate\Foundation\Http\FormRequest;

class SchoolAnalyticsFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'school_id' => ['nullable', 'exists:schools,id'],
            'date_from' => ['nullable', 'date'],
            'date_until' => ['nullable', 'date', 'after_or_equal:date_from'],
            'period_type' => ['nullable', 'in:daily,weekly,monthly,quarterly,yearly'],
            'module' => ['nullable', 'in:tahfizh,mutabaah,attendance,tahsin,finance,cashless,boarding,mobile,api,support'],
        ];
    }
}
