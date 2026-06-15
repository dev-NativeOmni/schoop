<?php

namespace App\Services\SaasOps;

use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use App\Models\User;

class SupportTicketService
{
    public function __construct(
        private readonly SaasOpsNumberGenerator $numbers,
        private readonly SupportSlaService $sla,
        private readonly SaasOpsAuditService $audit,
    ) {}

    public function createTicket(array $data, User $actor): SupportTicket
    {
        $ticket = SupportTicket::query()->create(array_merge($data, [
            'ticket_number' => $this->numbers->make('TKT'),
            'requester_id' => $actor->id,
            'status' => 'open',
        ]));

        $this->sla->applyDueDates($ticket);
        $this->audit->log($ticket->school_id ? (int) $ticket->school_id : null, $actor, 'saas.support.created', $ticket);

        return $ticket;
    }

    public function addMessage(SupportTicket $ticket, User $actor, string $message, string $visibility = 'public'): SupportTicketMessage
    {
        $firstResponse = ! $ticket->first_responded_at && $actor->id !== $ticket->requester_id;
        $row = SupportTicketMessage::query()->create([
            'support_ticket_id' => $ticket->id,
            'user_id' => $actor->id,
            'visibility' => $visibility,
            'message' => $message,
            'is_first_response' => $firstResponse,
        ]);

        if ($firstResponse) {
            $ticket->update(['first_responded_at' => now(), 'status' => 'in_progress']);
        }

        $this->audit->log($ticket->school_id ? (int) $ticket->school_id : null, $actor, 'saas.support.message_added', $ticket);

        return $row;
    }

    public function assign(SupportTicket $ticket, User $assignee, User $actor): SupportTicket
    {
        $ticket->update(['assigned_to' => $assignee->id, 'status' => 'in_progress']);
        $this->audit->log($ticket->school_id ? (int) $ticket->school_id : null, $actor, 'saas.support.assigned', $ticket);

        return $ticket;
    }

    public function resolve(SupportTicket $ticket, User $actor): SupportTicket
    {
        $ticket->update(['status' => 'resolved', 'resolved_at' => now()]);
        $this->audit->log($ticket->school_id ? (int) $ticket->school_id : null, $actor, 'saas.support.resolved', $ticket);

        return $ticket;
    }

    public function close(SupportTicket $ticket, User $actor): SupportTicket
    {
        $ticket->update(['status' => 'closed', 'closed_at' => now()]);
        $this->audit->log($ticket->school_id ? (int) $ticket->school_id : null, $actor, 'saas.support.closed', $ticket);

        return $ticket;
    }
}
