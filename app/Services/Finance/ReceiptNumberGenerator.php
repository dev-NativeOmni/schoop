<?php

namespace App\Services\Finance;

use App\Models\StudentPayment;

class ReceiptNumberGenerator
{
    public function generate(): string
    {
        $prefix = 'RCPT-' . now()->format('Ymd');

        $count = StudentPayment::query()
            ->where('receipt_number', 'like', $prefix . '%')
            ->count() + 1;

        return $prefix . '-' . str_pad((string) $count, 5, '0', STR_PAD_LEFT);
    }
}
