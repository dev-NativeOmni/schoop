<?php

namespace App\Http\Requests\SaasOps;

use App\Services\SaasOps\SaasOperationsAccessService;
use Illuminate\Foundation\Http\FormRequest;

class StoreSupportTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return app(SaasOperationsAccessService::class)->canManageSupport($this->user()) || $this->user()->hasRole(['admin', 'admin_sekolah', 'principal', 'kepala_sekolah', 'teacher', 'merchant']);
    }

    public function rules(): array
    {
        return [
            'school_id' => ['nullable', 'exists:schools,id'],
            'category' => ['required', 'in:bug,access,data_correction,training,feature_request,billing,incident'],
            'priority' => ['required', 'in:critical,high,medium,low'],
            'subject' => ['required', 'string', 'max:200'],
            'description' => ['required', 'string', 'max:5000'],
        ];
    }
}
