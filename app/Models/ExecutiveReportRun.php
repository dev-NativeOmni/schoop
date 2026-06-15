<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExecutiveReportRun extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'report_type',
        'status',
        'period_start',
        'period_end',
        'title',
        'summary',
        'highlights',
        'risks',
        'recommendations',
        'generated_by',
        'generated_at',
        'published_at',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'highlights' => 'array',
        'risks' => 'array',
        'recommendations' => 'array',
        'generated_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(ExecutiveReportSection::class);
    }
}
