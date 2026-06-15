<?php

namespace App\Http\Requests\Api\Mobile\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class MobileMerchantCheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['super_admin', 'admin', 'finance', 'cashier', 'merchant']) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cashless_pos_session_id' => ['required', 'integer', 'exists:cashless_pos_sessions,id'],
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'idempotency_key' => ['required', 'string', 'max:120'],
            'items' => ['required', 'array', 'min:1', 'max:100'],
            'items.*.cashless_product_id' => ['required', 'integer', 'exists:cashless_products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:999'],
            'note' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
