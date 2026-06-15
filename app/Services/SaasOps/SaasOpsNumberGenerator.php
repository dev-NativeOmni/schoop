<?php

namespace App\Services\SaasOps;

use Illuminate\Support\Str;

class SaasOpsNumberGenerator
{
    public function make(string $prefix): string
    {
        return $prefix.now()->format('YmdHis').strtoupper(Str::random(5));
    }
}
