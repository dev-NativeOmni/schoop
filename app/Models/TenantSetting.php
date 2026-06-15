<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'setting_key',
        'setting_value',
        'value_type',
        'is_public',
        'description',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
