<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobileAppVersion extends Model
{
    protected $fillable = [
        'platform',
        'version',
        'build_number',
        'minimum_supported_version',
        'is_force_update',
        'is_active',
        'release_notes',
        'released_at',
    ];

    protected $casts = [
        'build_number' => 'integer',
        'is_force_update' => 'boolean',
        'is_active' => 'boolean',
        'released_at' => 'datetime',
    ];
}
