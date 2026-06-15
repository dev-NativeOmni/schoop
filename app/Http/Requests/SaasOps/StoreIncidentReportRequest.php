<?php

namespace App\Http\Requests\SaasOps;

use App\Services\SaasOps\SaasOperationsAccessService;
use Illuminate\Foundation\Http\FormRequest;

class StoreIncidentReportRequest extends FormRequest
{
    public function authorize(): bool { return app(SaasOperationsAccessService::class)->canManageIncidents($this->user()); }
    public function rules(): array
    {
        return [
            'school_id' => ['nullable', 'exists:schools,id'],
            'severity' => ['required', 'in:sev1,sev2,sev3,sev4'],
            'status' => ['required', 'in:open,investigating,mitigated,resolved,closed'],
            'title' => ['required', 'string', 'max:200'],
            'impact' => ['nullable', 'string', 'max:5000'],
            'root_cause' => ['nullable', 'string', 'max:5000'],
            'mitigation' => ['nullable', 'string', 'max:5000'],
            'corrective_action' => ['nullable', 'string', 'max:5000'],
            'owner_id' => ['nullable', 'exists:users,id'],
        ];
    }
}
