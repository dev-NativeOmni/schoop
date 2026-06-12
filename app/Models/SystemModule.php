<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemModule extends Model
{
    protected $fillable = [
        'school_id',
        'module_key',
        'name',
        'description',
        'route_name',
        'icon',
        'sort_order',
        'is_enabled',
        'is_core',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_enabled' => 'boolean',
        'is_core' => 'boolean',
    ];
}
