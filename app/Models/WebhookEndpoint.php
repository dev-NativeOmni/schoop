<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class WebhookEndpoint extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'api_client_id',
        'name',
        'url',
        'secret_hash',
        'subscribed_events',
        'status',
        'last_success_at',
        'last_failure_at',
        'created_by',
        'updated_by',
    ];

    protected $hidden = [
        'secret_hash',
    ];

    protected $casts = [
        'subscribed_events' => 'array',
        'last_success_at' => 'datetime',
        'last_failure_at' => 'datetime',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(ApiClient::class, 'api_client_id');
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(WebhookDelivery::class);
    }
}
