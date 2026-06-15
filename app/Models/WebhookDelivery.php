<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebhookDelivery extends Model
{
    protected $fillable = [
        'webhook_endpoint_id',
        'school_id',
        'event_type',
        'payload',
        'status',
        'attempt_count',
        'last_attempt_at',
        'next_retry_at',
        'response_status',
        'response_body_excerpt',
        'error_message',
    ];

    protected $casts = [
        'payload' => 'array',
        'attempt_count' => 'integer',
        'last_attempt_at' => 'datetime',
        'next_retry_at' => 'datetime',
        'response_status' => 'integer',
    ];

    public function endpoint(): BelongsTo
    {
        return $this->belongsTo(WebhookEndpoint::class, 'webhook_endpoint_id');
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
