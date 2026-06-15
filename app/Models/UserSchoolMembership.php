<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserSchoolMembership extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'school_id',
        'role_id',
        'membership_status',
        'is_default',
        'joined_at',
        'last_accessed_at',
        'created_by',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'joined_at' => 'datetime',
        'last_accessed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
