<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OnboardingChecklistRecord extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'school_id', 'implementation_project_id', 'onboarding_checklist_item_id', 'status',
        'completed_at', 'completed_by', 'note', 'blocked_reason',
    ];

    protected function casts(): array
    {
        return ['completed_at' => 'datetime'];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(OnboardingChecklistItem::class, 'onboarding_checklist_item_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(ImplementationProject::class, 'implementation_project_id');
    }
}
