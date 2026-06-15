<?php

namespace App\Services\SaasOps;

use App\Models\SlaPolicy;
use App\Models\SupportTicket;

class SupportSlaService
{
    public function applyDueDates(SupportTicket $ticket): SupportTicket
    {
        $policy = SlaPolicy::query()
            ->where('priority', $ticket->priority)
            ->where('is_active', true)
            ->first();

        if (! $policy) {
            return $ticket;
        }

        $ticket->update([
            'first_response_due_at' => now()->addMinutes($policy->first_response_minutes),
            'resolution_due_at' => now()->addMinutes($policy->resolution_minutes),
        ]);

        return $ticket;
    }

    public function markBreaches(): int
    {
        return SupportTicket::query()
            ->whereIn('status', ['open', 'in_progress'])
            ->where(function ($query): void {
                $query->where(function ($q): void {
                    $q->whereNull('first_responded_at')->whereNotNull('first_response_due_at')->where('first_response_due_at', '<', now());
                })->orWhere(function ($q): void {
                    $q->whereNull('resolved_at')->whereNotNull('resolution_due_at')->where('resolution_due_at', '<', now());
                });
            })
            ->update(['sla_breached' => true]);
    }

    public function nearBreach()
    {
        return SupportTicket::query()
            ->whereIn('status', ['open', 'in_progress'])
            ->where('sla_breached', false)
            ->where('resolution_due_at', '<=', now()->addHours(4))
            ->get();
    }
}
