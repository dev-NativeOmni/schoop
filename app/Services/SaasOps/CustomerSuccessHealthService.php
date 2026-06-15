<?php

namespace App\Services\SaasOps;

use App\Models\ImplementationProject;
use App\Models\IncidentReport;
use App\Models\SaasSchoolSubscription;
use App\Models\SupportTicket;

class CustomerSuccessHealthService
{
    public function calculate(int $schoolId, array $activityMetrics = []): array
    {
        $score = 70;
        $subscription = SaasSchoolSubscription::query()->where('school_id', $schoolId)->latest()->first();
        $openTickets = SupportTicket::query()->where('school_id', $schoolId)->whereIn('status', ['open', 'in_progress'])->count();
        $criticalTickets = SupportTicket::query()->where('school_id', $schoolId)->where('priority', 'critical')->whereIn('status', ['open', 'in_progress'])->count();
        $openIncidents = IncidentReport::query()->where('school_id', $schoolId)->whereIn('status', ['open', 'investigating'])->count();
        $projectProgress = ImplementationProject::query()->where('school_id', $schoolId)->latest()->value('progress_percent');

        if (! $subscription) {
            $score -= 20;
        } elseif (in_array($subscription->status, ['active', 'trial'], true)) {
            $score += 10;
        } elseif ($subscription->status === 'grace_period') {
            $score -= 10;
        } else {
            $score -= 30;
        }

        $score -= min(25, $openTickets * 3);
        $score -= min(30, $criticalTickets * 15);
        $score -= min(25, $openIncidents * 20);
        if ($projectProgress !== null) {
            $score += (int) round(((int) $projectProgress - 50) / 10);
        }
        $score += min(10, (int) (($activityMetrics['daily_activity_count'] ?? 0) / 10));
        $score = max(0, min(100, $score));

        return ['score' => $score, 'status' => $this->statusFromScore($score)];
    }

    public function statusFromScore(int $score): string
    {
        return match (true) {
            $score >= 80 => 'healthy',
            $score >= 60 => 'watch',
            $score >= 40 => 'risk',
            default => 'critical',
        };
    }
}
