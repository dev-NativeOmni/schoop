<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Services\Ai\ParentGuidanceDigestService;
use App\Services\Ai\AiFeatureFlagService;
use App\Services\Ai\AiAuditLogger;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ParentAiLearningPortalController extends Controller
{
    protected ParentGuidanceDigestService $digestService;
    protected AiFeatureFlagService $flagService;
    protected AiAuditLogger $auditLogger;

    public function __construct(
        ParentGuidanceDigestService $digestService,
        AiFeatureFlagService $flagService,
        AiAuditLogger $auditLogger
    ) {
        $this->digestService = $digestService;
        $this->flagService = $flagService;
        $this->auditLogger = $auditLogger;
    }

    public function index(Request $request): View
    {
        $user = Auth::user();
        $schoolId = $user->school_id;

        // Check feature flag
        if (!$this->flagService->isEnabled($schoolId, 'parent_guidance_digest')) {
            abort(403, 'Fitur asisten rekomendasi AI dinonaktifkan oleh sekolah.');
        }

        $parentProfile = $user->parentProfile;
        $children = $parentProfile ? $parentProfile->students()->get() : collect();

        $digests = collect();
        foreach ($children as $child) {
            $digests->put($child->id, $this->digestService->getDigestForChild($child));

            $this->auditLogger->log(
                'parent_view_guidance_digest',
                $user,
                $child
            );
        }

        return view('portal.parent.ai-learning', compact('digests', 'children'));
    }
}
