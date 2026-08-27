<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BoardingStudentAssignment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'student_id',
        'boarding_dormitory_id',
        'boarding_room_id',
        'boarding_bed_id',
        'start_date',
        'end_date',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function dormitory(): BelongsTo
    {
        return $this->belongsTo(BoardingDormitory::class, 'boarding_dormitory_id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(BoardingRoom::class, 'boarding_room_id');
    }

    public function bed(): BelongsTo
    {
        return $this->belongsTo(BoardingBed::class, 'boarding_bed_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
