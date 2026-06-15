<?php

namespace App\Http\Requests\DeveloperPortal;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreApiDocumentationPageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'slug' => [
                'required',
                'string',
                'max:160',
                Rule::unique('api_documentation_pages', 'slug')->ignore($this->route('doc')),
            ],
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:120'],
            'content' => ['required', 'string'],
            'visibility' => ['required', 'string', 'in:internal,partner,public'],
            'status' => ['required', 'string', 'in:draft,published,archived'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ];
    }
}
