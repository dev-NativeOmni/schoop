<?php

namespace App\Services\DeveloperPortal;

use App\Models\WebhookDelivery;
use App\Models\WebhookEndpoint;
use App\Jobs\SendWebhookJob;

class WebhookEventService
{
    public function availableEvents(): array
    {
        return [
            'student.created',
            'attendance.recorded',
            'tahfizh.recorded',
            'finance.bill.posted',
            'cashless.sale.posted',
            'webhook.test',
        ];
    }

    public function queueEvent(?int $schoolId, string $eventType, array $payload): int
    {
        $endpoints = WebhookEndpoint::query()
            ->where(['status' => 'active'])
            ->when($schoolId, fn ($query) => $query->where(['school_id' => $schoolId]))
            ->get()
            ->filter(fn (WebhookEndpoint $endpoint): bool => in_array($eventType, $endpoint->subscribed_events ?? [], true));

        foreach ($endpoints as $endpoint) {
            $delivery = WebhookDelivery::query()->create([
                'webhook_endpoint_id' => $endpoint->id,
                'school_id' => $endpoint->school_id,
                'event_type' => $eventType,
                'payload' => $payload,
                'status' => 'pending',
            ]);

            SendWebhookJob::dispatch($delivery);
        }

        return $endpoints->count();
    }
}
