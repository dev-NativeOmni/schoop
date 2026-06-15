<?php

namespace App\Http\Resources\Mobile\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MobileMerchantSaleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'transaction_number' => $this->transaction_number,
            'receipt_number' => $this->receipt_number,
            'total_amount' => (int) $this->total_amount,
            'refunded_amount' => (int) $this->refunded_amount,
            'status' => $this->status,
            'posted_at' => $this->posted_at?->toIso8601String(),
            'student' => $this->student ? [
                'id' => $this->student->id,
                'full_name' => $this->student->full_name,
            ] : null,
            'merchant' => $this->merchant ? [
                'id' => $this->merchant->id,
                'name' => $this->merchant->name,
            ] : null,
        ];
    }
}
