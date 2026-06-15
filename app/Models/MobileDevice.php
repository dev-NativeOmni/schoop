<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MobileDevice extends Model
{
    protected $fillable = [
        'school_id',
        'user_id',
        'device_uuid',
        'platform',
        'platform_version',
        'app_version',
        'device_name',
        'push_token',
        'push_provider',
        'last_ip',
        'last_seen_at',
        'revoked_at',
        'is_active',
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
        'revoked_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'push_token',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function accessTokens(): HasMany
    {
        return $this->hasMany(MobileAccessToken::class);
    }
}
