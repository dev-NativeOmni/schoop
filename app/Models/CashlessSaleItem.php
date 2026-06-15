<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashlessSaleItem extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'school_id',
        'cashless_sale_id',
        'cashless_product_id',
        'product_name',
        'product_sku',
        'unit_price',
        'quantity',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'integer',
            'quantity' => 'integer',
            'subtotal' => 'integer',
        ];
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(CashlessSale::class, 'cashless_sale_id');
    }
}
