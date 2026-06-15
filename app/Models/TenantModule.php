<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'module_key',
        'module_name',
        'is_enabled',
        'configuration',
        'updated_by',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'configuration' => 'array',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
