<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TahsinStudentProfile extends Model
{
    public const STATUS_NOT_STARTED = 'not_started';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_PASSED = 'passed';
    public const STATUS_NEEDS_ATTENTION = 'needs_attention';

    protected $fillable = [
        'school_id',
        'student_id',
        'current_tahsin_level_id',
        'assigned_teacher_id',
        'status',
        'placement_score',
        'started_at',
        'completed_at',
        'note',
    ];

    protected $casts = [
        'placement_score' => 'decimal:2',
        'started_at' => 'date',
        'completed_at' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function currentLevel(): BelongsTo
    {
        return $this->belongsTo(TahsinLevel::class, 'current_tahsin_level_id');
    }

    public function assignedTeacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_teacher_id');
    }
}
