<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BoardingBed extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'boarding_room_id',
        'code',
        'status',
        'description',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(BoardingRoom::class, 'boarding_room_id');
    }
}
