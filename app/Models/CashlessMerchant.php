<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CashlessMerchant extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'school_id',
        'name',
        'code',
        'type',
        'status',
        'phone',
        'description',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(CashlessMerchantUser::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(CashlessProduct::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(CashlessPosSession::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(CashlessSale::class);
    }
}
