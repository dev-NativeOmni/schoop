<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashlessWalletTransaction extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'school_id',
        'cashless_wallet_id',
        'student_id',
        'cashless_sale_id',
        'cashless_refund_id',
        'actor_id',
        'transaction_number',
        'type',
        'direction',
        'amount',
        'balance_before',
        'balance_after',
        'note',
        'status',
        'posted_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'balance_before' => 'integer',
            'balance_after' => 'integer',
            'posted_at' => 'datetime',
        ];
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(CashlessWallet::class, 'cashless_wallet_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
