<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CashlessRefund extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'school_id',
        'cashless_sale_id',
        'cashless_wallet_id',
        'actor_id',
        'refund_number',
        'amount',
        'type',
        'status',
        'reason',
        'posted_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'posted_at' => 'datetime',
        ];
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(CashlessSale::class, 'cashless_sale_id');
    }

    public function walletTransactions(): HasMany
    {
        return $this->hasMany(CashlessWalletTransaction::class);
    }
}
