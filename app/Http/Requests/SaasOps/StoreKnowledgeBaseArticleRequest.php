<?php

namespace App\Http\Requests\SaasOps;

use App\Services\SaasOps\SaasOperationsAccessService;
use Illuminate\Foundation\Http\FormRequest;

class StoreKnowledgeBaseArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return app(SaasOperationsAccessService::class)->canManageKnowledge($this->user());
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:200'],
            'category' => ['required', 'string', 'max:80'],
            'visibility' => ['required', 'in:internal,school,public'],
            'body' => ['required', 'string'],
            'status' => ['required', 'in:draft,published'],
        ];
    }
}
