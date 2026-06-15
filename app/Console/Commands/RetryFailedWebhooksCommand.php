<?php

namespace App\Console\Commands;

use App\Services\DeveloperPortal\WebhookDeliveryService;
use Illuminate\Console\Command;

class RetryFailedWebhooksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:retry-failed-webhooks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retry due failed webhook deliveries.';

    /**
     * Execute the console command.
     */
    public function handle(WebhookDeliveryService $deliveries): int
    {
        $count = $deliveries->retryDue();
        $this->info("Retried {$count} webhook deliveries.");

        return self::SUCCESS;
    }
}
