<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiLearningSignal extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'school_id',
        'student_id',
        'ai_learning_profile_id',
        'signal_date',
        'source_module',
        'signal_key',
        'severity',
        'score',
        'description',
        'evidence',
    ];

    protected $casts = [
        'signal_date' => 'date',
        'score' => 'decimal:2',
        'evidence' => 'array',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(AiLearningProfile::class, 'ai_learning_profile_id');
    }
}
