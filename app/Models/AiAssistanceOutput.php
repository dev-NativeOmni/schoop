<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiAssistanceOutput extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'school_id',
        'ai_assistance_request_id',
        'student_id',
        'output_type',
        'status',
        'title',
        'body',
        'structured_output',
        'evidence',
        'safety_checks',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'structured_output' => 'array',
        'evidence' => 'array',
        'safety_checks' => 'array',
        'reviewed_at' => 'datetime',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function request(): BelongsTo
    {
        return $this->belongsTo(AiAssistanceRequest::class, 'ai_assistance_request_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function reviewQueueItems(): HasMany
    {
        return $this->hasMany(AiTeacherReviewQueue::class, 'ai_assistance_output_id');
    }
}
