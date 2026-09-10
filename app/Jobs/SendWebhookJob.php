<?php

namespace App\Jobs;

use App\Models\WebhookDelivery;
use App\Services\DeveloperPortal\WebhookDeliveryService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected WebhookDelivery $delivery
    ) {}

    /**
     * Execute the job.
     */
    public function handle(WebhookDeliveryService $service): void
    {
        $service->deliver($this->delivery);
    }
}
