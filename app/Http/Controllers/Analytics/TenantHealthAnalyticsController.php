<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use App\Http\Requests\Analytics\TenantHealthFilterRequest;
use App\Models\School;
use App\Models\TenantHealthScore;
use App\Services\Analytics\AnalyticsAccessLogger;
use App\Services\Analytics\AnalyticsAccessService;
use App\Services\Analytics\AnalyticsPrivacyGuard;
use App\Services\Tenancy\TenantContextService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TenantHealthAnalyticsController extends Controller
{
    public function __construct(
        private readonly AnalyticsAccessService $access,
        private readonly AnalyticsAccessLogger $logger,
        private readonly AnalyticsPrivacyGuard $privacyGuard,
        private readonly TenantContextService $tenantContext
    ) {}

    public function index(TenantHealthFilterRequest $request)
    {
        $this->access->canViewTenantHealth($request->user(), null) or abort(403);

        $this->logger->log($request, 'tenant_health_index');

        $today = Carbon::today()->toDateString();

        $scores = TenantHealthScore::query()
            ->with('school')
            ->where('score_date', $today)
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->when($request->filled('school_id'), fn ($query) => $query->where('school_id', $request->input('school_id')))
            ->orderByDesc('score')
            ->paginate(20);

        return view('analytics.tenant-health.index', [
            'scores' => $scores,
            'schools' => School::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function show(Request $request, School $school)
    {
        $this->privacyGuard->assertSchoolScope($school->id, $request->user());
        $this->access->canViewTenantHealth($request->user(), $school->id) or abort(403);

        $this->logger->log($request, 'tenant_health_show', 'view', $school->id);

        $today = Carbon::today()->toDateString();
        $score = TenantHealthScore::query()
            ->with('components')
            ->where('school_id', $school->id)
            ->where('score_date', $today)
            ->first();

        // Fallback to latest score if today is not calculated yet
        if (! $score) {
            $score = TenantHealthScore::query()
                ->with('components')
                ->where('school_id', $school->id)
                ->latest('score_date')
                ->first();
        }

        return view('analytics.tenant-health.show', [
            'school' => $school,
            'score' => $score,
        ]);
    }
}
