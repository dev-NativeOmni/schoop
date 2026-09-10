<?php

namespace App\Services\Finance;

use App\Models\StudentBill;

class InvoiceNumberGenerator
{
    public function generate(): string
    {
        $prefix = 'INV-'.now()->format('Ymd');

        $count = StudentBill::query()
            ->where('invoice_number', 'like', $prefix.'%')
            ->count() + 1;

        return $prefix.'-'.str_pad((string) $count, 5, '0', STR_PAD_LEFT);
    }
}
