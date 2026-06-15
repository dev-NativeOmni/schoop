<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantAuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'user_id',
        'action',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
