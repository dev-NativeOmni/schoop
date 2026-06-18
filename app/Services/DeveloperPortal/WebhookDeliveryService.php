<?php

namespace App\Services\DeveloperPortal;

use App\Models\WebhookDelivery;
use Illuminate\Support\Facades\Http;
use Throwable;

class WebhookDeliveryService
{
    public function __construct(
        private readonly WebhookSignatureService $signatures,
    ) {}

    public function deliver(WebhookDelivery $delivery): void
    {
        $delivery->loadMissing('endpoint');
        $endpoint = $delivery->endpoint;

        if (! $endpoint || $endpoint->status !== 'active') {
            $this->markFailed($delivery, 'Webhook endpoint inactive.');

            return;
        }

        $payload = [
            'event' => $delivery->event_type,
            'delivery_id' => $delivery->id,
            'created_at' => $delivery->created_at?->toIso8601String(),
            'data' => $delivery->payload,
        ];
        $timestamp = now()->toIso8601String();

        try {
            $headers = [
                'User-Agent' => 'HafizPlus-Webhooks/1.0',
                'X-HafizPlus-Event' => $delivery->event_type,
                'X-HafizPlus-Delivery' => (string) $delivery->id,
                'X-HafizPlus-Timestamp' => $timestamp,
            ];

            if ($endpoint->secret_hash) {
                $headers['X-HafizPlus-Signature'] = $this->signatures->sign($payload, $endpoint->secret_hash);
            }

            $response = Http::timeout(5)->withHeaders($headers)->post($endpoint->url, $payload);
            $delivery->increment('attempt_count');

            if ($response->successful()) {
                $delivery->update([
                    'status' => 'delivered',
                    'last_attempt_at' => now(),
                    'next_retry_at' => null,
                    'response_status' => $response->status(),
                    'response_body_excerpt' => substr($response->body(), 0, 1000),
                    'error_message' => null,
                ]);
                $endpoint->update(['last_success_at' => now()]);

                return;
            }

            $this->markFailed($delivery, 'HTTP '.$response->status(), $response->status(), substr($response->body(), 0, 1000));
        } catch (Throwable $exception) {
            $delivery->increment('attempt_count');
            $this->markFailed($delivery, $exception->getMessage());
        }
    }

    public function markFailed(WebhookDelivery $delivery, string $message, ?int $status = null, ?string $body = null): void
    {
        $delivery->loadMissing('endpoint');

        $delivery->update([
            'status' => 'failed',
            'last_attempt_at' => now(),
            'next_retry_at' => now()->addMinutes(min(60, max(5, ($delivery->attempt_count + 1) * 5))),
            'response_status' => $status,
            'response_body_excerpt' => $body,
            'error_message' => substr($message, 0, 1000),
        ]);

        $delivery->endpoint?->update(['last_failure_at' => now()]);
    }

    public function retryDue(): int
    {
        $deliveries = WebhookDelivery::query()
            ->whereIn('status', ['failed', 'retrying'])
            ->where(function ($query): void {
                $query->whereNull('next_retry_at')
                    ->orWhere('next_retry_at', '<=', now());
            })
            ->limit(100)
            ->get();

        foreach ($deliveries as $delivery) {
            $delivery->update(['status' => 'retrying']);
            \App\Jobs\SendWebhookJob::dispatch($delivery);
        }

        return $deliveries->count();
    }
}
