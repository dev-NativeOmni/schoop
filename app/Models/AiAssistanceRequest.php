<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiAssistanceRequest extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'school_id',
        'requested_by',
        'student_id',
        'request_type',
        'status',
        'input_context',
        'sanitized_context',
        'purpose',
        'processed_at',
    ];

    protected $casts = [
        'input_context' => 'array',
        'sanitized_context' => 'array',
        'processed_at' => 'datetime',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function outputs(): HasMany
    {
        return $this->hasMany(AiAssistanceOutput::class, 'ai_assistance_request_id');
    }
}
