<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LmsLessonProgress extends Model
{
    use BelongsToTenant;

    protected $table = 'lms_lesson_progress';

    protected $fillable = [
        'school_id',
        'lesson_id',
        'student_id',
        'status',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(LmsLesson::class, 'lesson_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
