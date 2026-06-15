<?php

namespace App\Http\Resources\Mobile\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MobileFinanceSummaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'outstanding_amount' => (int) ($this['outstanding_amount'] ?? 0),
            'paid_this_month' => (int) ($this['paid_this_month'] ?? 0),
        ];
    }
}
