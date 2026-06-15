<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MobileAccessToken extends Model
{
    protected $fillable = [
        'school_id',
        'user_id',
        'mobile_device_id',
        'name',
        'token_hash',
        'abilities',
        'last_used_at',
        'expires_at',
        'revoked_at',
    ];

    protected $casts = [
        'abilities' => 'array',
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    protected $hidden = [
        'token_hash',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function mobileDevice(): BelongsTo
    {
        return $this->belongsTo(MobileDevice::class);
    }

    public function isUsable(): bool
    {
        if ($this->revoked_at) {
            return false;
        }

        return ! $this->expires_at || $this->expires_at->isFuture();
    }
}
