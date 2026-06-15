<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashlessProduct extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'school_id',
        'cashless_merchant_id',
        'name',
        'sku',
        'price',
        'stock',
        'track_stock',
        'status',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'stock' => 'integer',
            'track_stock' => 'boolean',
        ];
    }

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(CashlessMerchant::class, 'cashless_merchant_id');
    }
}
