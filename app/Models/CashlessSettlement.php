<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashlessSettlement extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'school_id',
        'cashless_merchant_id',
        'created_by',
        'approved_by',
        'settlement_number',
        'period_start',
        'period_end',
        'total_sales',
        'total_refunds',
        'net_sales',
        'sales_count',
        'status',
        'note',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'period_end' => 'date',
            'total_sales' => 'integer',
            'total_refunds' => 'integer',
            'net_sales' => 'integer',
            'sales_count' => 'integer',
            'approved_at' => 'datetime',
        ];
    }

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(CashlessMerchant::class, 'cashless_merchant_id');
    }
}
