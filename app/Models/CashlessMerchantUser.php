<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashlessMerchantUser extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'school_id',
        'cashless_merchant_id',
        'user_id',
        'role',
        'can_refund',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'can_refund' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(CashlessMerchant::class, 'cashless_merchant_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
