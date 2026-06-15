<?php

namespace App\Http\Controllers\SaasOps;

use App\Http\Controllers\Controller;
use App\Models\ImplementationProject;
use App\Models\ProductUsageSnapshot;
use App\Models\SaasSchoolSubscription;
use App\Models\SaasTenantInvoice;
use App\Models\SupportTicket;
use App\Services\SaasOps\SaasOperationsAccessService;
use Illuminate\View\View;

class ProductScaleDashboardController extends Controller
{
    public function __construct(private readonly SaasOperationsAccessService $access) {}

    public function index(): View
    {
        abort_unless($this->access->canViewDashboard(auth()->user()), 403);

        return view('saas-ops.dashboard.index', [
            'summary' => [
                'active_subscriptions' => SaasSchoolSubscription::query()->whereIn('status', ['trial', 'active', 'grace_period'])->count(),
                'open_tickets' => SupportTicket::query()->whereIn('status', ['open', 'in_progress'])->count(),
                'open_projects' => ImplementationProject::query()->where('status', 'open')->count(),
                'invoice_balance' => SaasTenantInvoice::query()->whereNotIn('status', ['paid', 'void'])->sum('balance_amount'),
            ],
            'snapshots' => ProductUsageSnapshot::query()->with('school')->latest('snapshot_date')->limit(10)->get(),
            'tickets' => SupportTicket::query()->with('school')->latest()->limit(10)->get(),
        ]);
    }
}
