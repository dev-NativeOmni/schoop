<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhiteLabelPublication extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'school_id',
        'status',
        'brand_snapshot',
        'theme_snapshot',
        'pwa_snapshot',
        'published_by',
        'published_at',
        'notes',
    ];

    protected $casts = [
        'brand_snapshot' => 'array',
        'theme_snapshot' => 'array',
        'pwa_snapshot' => 'array',
        'published_at' => 'datetime',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'published_by');
    }
}
