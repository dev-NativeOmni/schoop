<?php

namespace App\Http\Requests\Cashless;

use App\Services\Cashless\CashlessAccessService;
use Illuminate\Foundation\Http\FormRequest;

class StoreCashlessRefundRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return app(CashlessAccessService::class)->canFinance($user) || $user->hasRole(['cashier']);
    }

    public function rules(): array
    {
        return [
            'cashless_sale_id' => ['required', 'exists:cashless_sales,id'],
            'amount' => ['required', 'integer', 'min:1'],
            'reason' => ['required', 'string', 'min:5', 'max:1000'],
        ];
    }
}
