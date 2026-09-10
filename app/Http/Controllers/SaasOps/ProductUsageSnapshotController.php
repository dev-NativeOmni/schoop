<?php

namespace App\Http\Controllers\SaasOps;

use App\Http\Controllers\Controller;
use App\Models\ProductUsageSnapshot;
use App\Services\SaasOps\SaasOperationsAccessService;
use Illuminate\View\View;

class ProductUsageSnapshotController extends Controller
{
    public function __construct(private readonly SaasOperationsAccessService $access) {}

    public function index(): View
    {
        abort_unless($this->access->canViewDashboard(auth()->user()), 403);

        return view('saas-ops.usage-snapshots.index', ['snapshots' => ProductUsageSnapshot::query()->with('school')->latest('snapshot_date')->paginate(30)]);
    }
}
