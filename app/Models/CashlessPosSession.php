<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CashlessPosSession extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'school_id',
        'cashless_merchant_id',
        'cashier_id',
        'session_number',
        'shift_name',
        'opened_at',
        'closed_at',
        'opening_note',
        'closing_note',
        'total_sales',
        'total_refunds',
        'sales_count',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'opened_at' => 'datetime',
            'closed_at' => 'datetime',
            'total_sales' => 'integer',
            'total_refunds' => 'integer',
            'sales_count' => 'integer',
        ];
    }

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(CashlessMerchant::class, 'cashless_merchant_id');
    }

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function sales(): HasMany
    {
        return $this->hasMany(CashlessSale::class);
    }
}
