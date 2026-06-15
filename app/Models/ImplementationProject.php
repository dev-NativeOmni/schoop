<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ImplementationProject extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'school_id', 'project_number', 'title', 'stage', 'status', 'owner_id', 'start_date',
        'target_go_live_date', 'actual_go_live_date', 'progress_percent', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'target_go_live_date' => 'date',
            'actual_go_live_date' => 'date',
            'progress_percent' => 'integer',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function records(): HasMany
    {
        return $this->hasMany(OnboardingChecklistRecord::class);
    }
}
