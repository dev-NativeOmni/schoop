<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LmsAssignmentSubmission extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'school_id',
        'assignment_id',
        'student_id',
        'submitted_text',
        'file_path',
        'file_name',
        'file_size',
        'score',
        'graded_by',
        'graded_at',
        'teacher_feedback',
        'status',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'score' => 'decimal:2',
        'graded_at' => 'datetime',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(LmsAssignment::class, 'assignment_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function grader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'graded_by');
    }
}
