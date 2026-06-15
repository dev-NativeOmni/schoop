<?php

namespace App\Http\Requests\SaasOps;

use App\Services\SaasOps\SaasOperationsAccessService;
use Illuminate\Foundation\Http\FormRequest;

class StoreSaasSchoolSubscriptionRequest extends FormRequest
{
    public function authorize(): bool { return app(SaasOperationsAccessService::class)->canManageSubscriptions($this->user()); }
    public function rules(): array
    {
        return [
            'school_id' => ['required', 'exists:schools,id'],
            'saas_subscription_plan_id' => ['required', 'exists:saas_subscription_plans,id'],
            'status' => ['required', 'in:trial,active,grace_period,suspended,cancelled,expired'],
            'billing_cycle' => ['required', 'in:monthly,yearly'],
            'starts_at' => ['nullable', 'date'],
            'trial_ends_at' => ['nullable', 'date'],
            'current_period_start' => ['nullable', 'date'],
            'current_period_end' => ['nullable', 'date'],
            'internal_note' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
