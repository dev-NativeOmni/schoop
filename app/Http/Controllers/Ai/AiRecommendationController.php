<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use App\Models\AiLearningRecommendation;
use App\Services\Ai\AiAccessService;
use App\Services\Tenancy\TenantContextService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class AiRecommendationController extends Controller
{
    protected AiAccessService $accessService;
    protected TenantContextService $tenantContext;

    public function __construct(AiAccessService $accessService, TenantContextService $tenantContext)
    {
        $this->accessService = $accessService;
        $this->tenantContext = $tenantContext;
    }

    public function index(Request $request): View
    {
        $schoolId = $this->tenantContext->activeSchoolId();

        $recommendations = AiLearningRecommendation::query()
            ->where('school_id', $schoolId)
            ->with(['student.user'])
            ->latest()
            ->paginate(15);

        return view('ai.recommendations.index', compact('recommendations'));
    }

    public function show(AiLearningRecommendation $recommendation): View
    {
        if (!$this->accessService->canViewStudentAiData(Auth::user(), $recommendation->student)) {
            abort(403, 'Unauthorized access to recommendation data.');
        }

        $recommendation->load(['student.user', 'profile']);

        return view('ai.recommendations.show', compact('recommendation'));
    }
}
