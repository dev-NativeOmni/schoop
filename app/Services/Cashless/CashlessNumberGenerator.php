<?php

namespace App\Services\Cashless;

use Illuminate\Support\Str;

class CashlessNumberGenerator
{
    public function walletNumber(int $schoolId, int $studentId): string
    {
        return 'WL'.$schoolId.str_pad((string) $studentId, 8, '0', STR_PAD_LEFT);
    }

    public function transaction(string $prefix): string
    {
        return $prefix.now()->format('YmdHis').strtoupper(Str::random(6));
    }
}
