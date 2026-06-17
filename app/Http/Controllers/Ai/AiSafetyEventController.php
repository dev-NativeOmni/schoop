<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use App\Models\AiSafetyEvent;
use App\Services\Ai\AiAccessService;
use App\Services\Tenancy\TenantContextService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class AiSafetyEventController extends Controller
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
        if (!$this->accessService->canManageAiSettings(Auth::user(), $schoolId)) {
            abort(403, 'Unauthorized to view AI safety events.');
        }

        $safetyEvents = AiSafetyEvent::query()
            ->where('school_id', $schoolId)
            ->with(['user', 'student.user'])
            ->latest()
            ->paginate(15);

        return view('ai.safety-events.index', compact('safetyEvents'));
    }

    public function show(AiSafetyEvent $safetyEvent): View
    {
        $schoolId = $this->tenantContext->activeSchoolId();
        if (!$this->accessService->canManageAiSettings(Auth::user(), $schoolId) || $safetyEvent->school_id !== $schoolId) {
            abort(403, 'Unauthorized to view safety event details.');
        }

        $safetyEvent->load(['user', 'student.user']);

        return view('ai.safety-events.show', compact('safetyEvent'));
    }
}
