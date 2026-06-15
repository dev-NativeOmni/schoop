<?php

namespace App\Http\Requests\Analytics;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMetricDefinitionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $metricId = $this->route('metric_dictionary') ? $this->route('metric_dictionary')->id : '';

        return [
            'metric_key' => ['required', 'string', "unique:analytics_metric_definitions,metric_key,{$metricId}", 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'formula' => ['nullable', 'string'],
            'unit' => ['nullable', 'string', 'max:255'],
            'aggregation_type' => ['nullable', 'string', 'max:255'],
            'is_sensitive' => ['boolean'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_sensitive' => $this->boolean('is_sensitive'),
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
