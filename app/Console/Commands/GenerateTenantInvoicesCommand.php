<?php

namespace App\Console\Commands;

use App\Models\SaasSchoolSubscription;
use App\Models\User;
use App\Services\SaasOps\TenantBillingService;
use Illuminate\Console\Command;

class GenerateTenantInvoicesCommand extends Command
{
    protected $signature = 'app:generate-tenant-invoices {--dry-run}';
    protected $description = 'Generate draft tenant invoices for due subscriptions without sending or charging automatically.';

    public function handle(TenantBillingService $billing): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $actor = User::query()->whereHas('role', fn ($q) => $q->where('name', 'super_admin'))->first();
        $count = 0;

        SaasSchoolSubscription::query()
            ->with(['plan', 'school'])
            ->whereIn('status', ['trial', 'active', 'grace_period'])
            ->where(function ($query): void {
                $query->whereNull('current_period_end')->orWhere('current_period_end', '<=', now()->addDays(7)->toDateString());
            })
            ->each(function (SaasSchoolSubscription $subscription) use ($billing, $dryRun, $actor, &$count): void {
                $price = $subscription->billing_cycle === 'yearly' ? $subscription->plan->yearly_price : $subscription->plan->monthly_price;
                $this->line(($dryRun ? '[dry-run] ' : '')."Invoice {$subscription->school?->name} plan {$subscription->plan?->name}: {$price}");
                if (! $dryRun && $actor) {
                    $billing->createDraftInvoice($subscription, [[
                        'description' => 'Subscription '.$subscription->plan->name.' - '.$subscription->billing_cycle,
                        'quantity' => 1,
                        'unit_price' => $price,
                    ]], now()->addDays(14)->toDateString(), $actor, 'Generated manually by command');
                }
                $count++;
            });

        $this->info("Candidate subscriptions: {$count}");

        return self::SUCCESS;
    }
}
