<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiTeacherReviewQueue extends Model
{
    use BelongsToTenant;

    protected $table = 'ai_teacher_review_queue';

    protected $fillable = [
        'school_id',
        'student_id',
        'ai_assistance_output_id',
        'review_type',
        'status',
        'assigned_to',
        'reviewed_by',
        'review_note',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function output(): BelongsTo
    {
        return $this->belongsTo(AiAssistanceOutput::class, 'ai_assistance_output_id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
