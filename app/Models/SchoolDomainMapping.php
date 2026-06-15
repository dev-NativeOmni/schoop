<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolDomainMapping extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $fillable = [
        'school_id',
        'domain',
        'type',
        'status',
        'verification_token',
        'verified_at',
        'activated_at',
        'disabled_at',
        'notes',
        'created_by',
        'verified_by',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'activated_at' => 'datetime',
        'disabled_at' => 'datetime',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
