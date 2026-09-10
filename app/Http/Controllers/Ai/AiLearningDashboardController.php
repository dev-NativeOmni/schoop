<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use App\Models\AiFeatureFlag;
use App\Models\AiLearningProfile;
use App\Models\AiLearningRecommendation;
use App\Models\AiPracticePlan;
use App\Models\AiSafetyEvent;
use App\Models\AiTeacherReviewQueue;
use App\Services\Tenancy\TenantContextService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AiLearningDashboardController extends Controller
{
    protected TenantContextService $tenantContext;

    public function __construct(TenantContextService $tenantContext)
    {
        $this->tenantContext = $tenantContext;
    }

    public function index(Request $request): View
    {
        $schoolId = $this->tenantContext->activeSchoolId();

        $profileDraftsCount = AiLearningProfile::query()
            ->where('school_id', $schoolId)
            ->where('profile_status', 'draft')
            ->count();

        $recsCount = AiLearningRecommendation::query()
            ->where('school_id', $schoolId)
            ->where('status', 'draft')
            ->count();

        $plansCount = AiPracticePlan::query()
            ->where('school_id', $schoolId)
            ->where('status', 'draft')
            ->count();

        $reviewQueueCount = AiTeacherReviewQueue::query()
            ->where('school_id', $schoolId)
            ->where('status', 'pending')
            ->count();

        $safetyEventsCount = AiSafetyEvent::query()
            ->where('school_id', $schoolId)
            ->whereNull('resolved_at')
            ->count();

        $featureFlags = AiFeatureFlag::query()
            ->where('school_id', $schoolId)
            ->get();

        return view('ai.dashboard', compact(
            'profileDraftsCount',
            'recsCount',
            'plansCount',
            'reviewQueueCount',
            'safetyEventsCount',
            'featureFlags'
        ));
    }
}
