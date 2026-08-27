<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BoardingLeaveRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'student_id',
        'requested_by_user_id',
        'approved_by_user_id',
        'type',
        'status',
        'destination',
        'reason',
        'leave_start_at',
        'leave_end_at',
        'returned_at',
        'approval_note',
    ];

    protected $casts = [
        'leave_start_at' => 'datetime',
        'leave_end_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by_user_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }
}
