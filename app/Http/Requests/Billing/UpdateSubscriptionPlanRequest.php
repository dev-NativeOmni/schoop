<?php

namespace App\Http\Requests\Billing;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubscriptionPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['super_admin', 'admin', 'admin_sekolah']) ?? false;
    }

    public function rules(): array
    {
        $planId = $this->route('plan')?->id ?? $this->route('subscription_plan')?->id ?? $this->route('id');

        return [
            'code' => ['required', 'string', 'max:100', 'unique:subscription_plans,code,' . $planId],
            'name' => ['required', 'string', 'max:150'],
            'monthly_price' => ['nullable', 'integer', 'min:0'],
            'yearly_price' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'limits' => ['nullable', 'array'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
