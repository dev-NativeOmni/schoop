<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiRequestLog extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'api_client_id',
        'school_id',
        'request_id',
        'method',
        'path',
        'scope_checked',
        'response_status',
        'ip_address',
        'user_agent',
        'duration_ms',
        'error_message',
        'created_at',
    ];

    protected $casts = [
        'response_status' => 'integer',
        'duration_ms' => 'integer',
        'created_at' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(ApiClient::class, 'api_client_id');
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
