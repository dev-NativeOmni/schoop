<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BoardingRollCallRecord extends Model
{
    protected $fillable = [
        'boarding_roll_call_session_id',
        'student_id',
        'recorded_by_user_id',
        'status',
        'note',
        'recorded_at',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(BoardingRollCallSession::class, 'boarding_roll_call_session_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by_user_id');
    }
}
