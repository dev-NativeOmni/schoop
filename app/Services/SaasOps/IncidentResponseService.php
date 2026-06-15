<?php

namespace App\Services\SaasOps;

use App\Models\IncidentReport;
use App\Models\User;

class IncidentResponseService
{
    public function __construct(private readonly SaasOpsNumberGenerator $numbers, private readonly SaasOpsAuditService $audit) {}

    public function createIncident(array $data, User $actor): IncidentReport
    {
        $incident = IncidentReport::query()->create(array_merge($data, [
            'incident_number' => $this->numbers->make('INC'),
            'detected_at' => $data['detected_at'] ?? now(),
            'timeline' => [['time' => now()->toDateTimeString(), 'event' => 'Incident created']],
        ]));

        $this->audit->log($incident->school_id ? (int) $incident->school_id : null, $actor, 'saas.incident.created', $incident);

        return $incident;
    }

    public function addTimelineEvent(IncidentReport $incident, string $event, User $actor): IncidentReport
    {
        $timeline = $incident->timeline ?: [];
        $timeline[] = ['time' => now()->toDateTimeString(), 'event' => $event, 'actor_id' => $actor->id];
        $incident->update(['timeline' => $timeline]);
        $this->audit->log($incident->school_id ? (int) $incident->school_id : null, $actor, 'saas.incident.timeline_added', $incident);

        return $incident;
    }

    public function closeIncident(IncidentReport $incident, User $actor): IncidentReport
    {
        $incident->update(['status' => 'closed', 'closed_at' => now()]);
        $this->audit->log($incident->school_id ? (int) $incident->school_id : null, $actor, 'saas.incident.closed', $incident);

        return $incident;
    }
}
