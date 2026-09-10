<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BoardingRollCallSession extends Model
{
    use BelongsToTenant;
    use SoftDeletes;

    protected $fillable = [
        'boarding_dormitory_id',
        'boarding_room_id',
        'session_date',
        'session_type',
        'started_at',
        'closed_at',
        'status',
        'created_by',
    ];

    protected $casts = [
        'session_date' => 'date',
        'started_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function dormitory(): BelongsTo
    {
        return $this->belongsTo(BoardingDormitory::class, 'boarding_dormitory_id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(BoardingRoom::class, 'boarding_room_id');
    }

    public function records(): HasMany
    {
        return $this->hasMany(BoardingRollCallRecord::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
