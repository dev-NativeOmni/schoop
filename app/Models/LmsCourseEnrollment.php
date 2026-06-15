<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LmsCourseEnrollment extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'school_id',
        'course_id',
        'student_id',
        'progress_percentage',
        'status',
        'enrolled_at',
        'completed_at',
    ];

    protected $casts = [
        'progress_percentage' => 'decimal:2',
        'enrolled_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(LmsCourse::class, 'course_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
