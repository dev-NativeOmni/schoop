<?php

namespace App\Http\Requests\Cashless;

use App\Services\Cashless\CashlessAccessService;
use Illuminate\Foundation\Http\FormRequest;

class StoreWalletTopUpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return app(CashlessAccessService::class)->canFinance($this->user());
    }

    public function rules(): array
    {
        return [
            'cashless_wallet_id' => ['required', 'exists:cashless_wallets,id'],
            'amount' => ['required', 'integer', 'min:1000'],
            'note' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
