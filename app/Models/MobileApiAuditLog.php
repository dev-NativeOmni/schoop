<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MobileApiAuditLog extends Model
{
    protected $fillable = [
        'school_id',
        'user_id',
        'mobile_device_id',
        'method',
        'path',
        'status_code',
        'ip_address',
        'user_agent',
        'action',
        'request_id',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'status_code' => 'integer',
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
}
