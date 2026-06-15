<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LmsAssignment extends Model
{
    use BelongsToTenant;
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'course_id',
        'lesson_id',
        'title',
        'instructions',
        'max_score',
        'passing_score',
        'due_date',
        'allowed_file_types',
        'max_file_size_kb',
    ];

    protected $casts = [
        'max_score' => 'integer',
        'passing_score' => 'integer',
        'due_date' => 'datetime',
        'max_file_size_kb' => 'integer',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(LmsCourse::class, 'course_id');
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(LmsLesson::class, 'lesson_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(LmsAssignmentSubmission::class, 'assignment_id');
    }
}
