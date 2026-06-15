<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiClientToken extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'api_client_id',
        'token_name',
        'token_prefix',
        'token_hash',
        'status',
        'expires_at',
        'last_used_at',
        'created_by',
        'revoked_at',
        'revoked_by',
        'revoked_reason',
    ];

    protected $hidden = [
        'token_hash',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'last_used_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(ApiClient::class, 'api_client_id');
    }

    public function isUsable(): bool
    {
        if ($this->status !== 'active' || $this->revoked_at) {
            return false;
        }

        return ! $this->expires_at || $this->expires_at->isFuture();
    }
}
