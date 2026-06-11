<?php

namespace App\Http\Requests\Mutabaah;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMutabaahActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return $user && $user->hasRole(['super_admin', 'admin']);
    }

    public function rules(): array
    {
        return [
            'mutabaah_category_id' => ['nullable', 'exists:mutabaah_categories,id'],
            'name'                 => ['required', 'string', 'max:255'],
            'description'          => ['nullable', 'string'],
            'input_type'           => ['required', Rule::in(['checklist', 'score', 'count', 'text'])],
            'target_score'         => ['nullable', 'integer', 'min:0', 'max:100'],
            'target_count'         => ['nullable', 'integer', 'min:0', 'max:10000'],
            'target_unit'          => ['nullable', 'string', 'max:50'],
            'is_required'          => ['nullable', 'boolean'],
            'is_active'            => ['nullable', 'boolean'],
            'allow_teacher_input'  => ['nullable', 'boolean'],
            'allow_parent_input'   => ['nullable', 'boolean'],
            'allow_student_input'  => ['nullable', 'boolean'],
            'sort_order'           => ['nullable', 'integer', 'min:0', 'max:65535'],
        ];
    }
}
