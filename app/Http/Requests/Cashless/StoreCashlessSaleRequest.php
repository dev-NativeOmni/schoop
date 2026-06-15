<?php

namespace App\Http\Requests\Cashless;

use App\Services\Cashless\CashlessAccessService;
use Illuminate\Foundation\Http\FormRequest;

class StoreCashlessSaleRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $items = collect($this->input('items', []))
            ->filter(fn ($item) => (int) ($item['quantity'] ?? 0) > 0)
            ->values()
            ->all();

        $this->merge(['items' => $items]);
    }

    public function authorize(): bool
    {
        return app(CashlessAccessService::class)->canUsePos($this->user());
    }

    public function rules(): array
    {
        return [
            'cashless_pos_session_id' => ['required', 'exists:cashless_pos_sessions,id'],
            'student_id' => ['required', 'exists:students,id'],
            'idempotency_key' => ['required', 'string', 'max:120'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.cashless_product_id' => ['required', 'exists:cashless_products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'note' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
