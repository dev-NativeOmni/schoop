<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncidentReport extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'school_id', 'support_ticket_id', 'owner_id', 'incident_number', 'severity', 'status', 'title',
        'impact', 'timeline', 'root_cause', 'mitigation', 'corrective_action', 'detected_at',
        'resolved_at', 'closed_at',
    ];

    protected function casts(): array
    {
        return ['timeline' => 'array', 'detected_at' => 'datetime', 'resolved_at' => 'datetime', 'closed_at' => 'datetime'];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
