<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BoardingRoom extends Model
{
    use BelongsToTenant;
    use SoftDeletes;

    protected $fillable = [
        'boarding_dormitory_id',
        'name',
        'floor',
        'capacity',
        'is_active',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'capacity' => 'integer',
    ];

    public function dormitory(): BelongsTo
    {
        return $this->belongsTo(BoardingDormitory::class, 'boarding_dormitory_id');
    }

    public function beds(): HasMany
    {
        return $this->hasMany(BoardingBed::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(BoardingStudentAssignment::class);
    }
}
