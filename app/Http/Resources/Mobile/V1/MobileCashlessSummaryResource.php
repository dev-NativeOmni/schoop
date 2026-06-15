<?php

namespace App\Http\Resources\Mobile\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MobileCashlessSummaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'wallet_number' => $this['wallet_number'] ?? null,
            'balance' => (int) ($this['balance'] ?? 0),
            'status' => $this['status'] ?? null,
            'last_transaction_at' => $this['last_transaction_at'] ?? null,
        ];
    }
}
