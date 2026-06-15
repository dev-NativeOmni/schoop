<?php

namespace App\Services\SaasOps;

use Illuminate\Support\Str;

class KnowledgeBaseService
{
    public function slug(string $title): string
    {
        return Str::slug($title).'-'.Str::lower(Str::random(4));
    }
}
