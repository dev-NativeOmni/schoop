<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiClient extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'name',
        'client_code',
        'description',
        'owner_name',
        'owner_email',
        'status',
        'rate_limit_per_minute',
        'allowed_ips',
        'last_used_at',
        'created_by',
        'updated_by',
        'revoked_at',
        'revoked_by',
        'revoked_reason',
    ];

    protected $casts = [
        'allowed_ips' => 'array',
        'rate_limit_per_minute' => 'integer',
        'last_used_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function tokens(): HasMany
    {
        return $this->hasMany(ApiClientToken::class);
    }

    public function scopes(): BelongsToMany
    {
        return $this->belongsToMany(ApiScope::class, 'api_client_scope')
            ->withPivot(['granted_by', 'granted_at'])
            ->withTimestamps();
    }

    public function requestLogs(): HasMany
    {
        return $this->hasMany(ApiRequestLog::class);
    }

    public function webhooks(): HasMany
    {
        return $this->hasMany(WebhookEndpoint::class);
    }

    public function integrations(): HasMany
    {
        return $this->hasMany(PartnerIntegration::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && ! $this->revoked_at;
    }
}
