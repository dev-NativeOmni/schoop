<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CashlessSale extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'school_id',
        'cashless_merchant_id',
        'cashless_pos_session_id',
        'cashier_id',
        'student_id',
        'cashless_wallet_id',
        'transaction_number',
        'receipt_number',
        'idempotency_key',
        'total_amount',
        'refunded_amount',
        'status',
        'note',
        'posted_at',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'integer',
            'refunded_amount' => 'integer',
            'posted_at' => 'datetime',
        ];
    }

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(CashlessMerchant::class, 'cashless_merchant_id');
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(CashlessPosSession::class, 'cashless_pos_session_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(CashlessWallet::class, 'cashless_wallet_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(CashlessSaleItem::class);
    }

    public function walletTransactions(): HasMany
    {
        return $this->hasMany(CashlessWalletTransaction::class);
    }
}
