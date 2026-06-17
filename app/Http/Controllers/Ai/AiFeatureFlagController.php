<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use App\Models\AiFeatureFlag;
use App\Http\Requests\Ai\UpdateAiFeatureFlagRequest;
use App\Services\Ai\AiAccessService;
use App\Services\Ai\AiAuditLogger;
use App\Services\Tenancy\TenantContextService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class AiFeatureFlagController extends Controller
{
    protected AiAccessService $accessService;
    protected AiAuditLogger $auditLogger;
    protected TenantContextService $tenantContext;

    public function __construct(
        AiAccessService $accessService,
        AiAuditLogger $auditLogger,
        TenantContextService $tenantContext
    ) {
        $this->accessService = $accessService;
        $this->auditLogger = $auditLogger;
        $this->tenantContext = $tenantContext;
    }

    public function index(Request $request): View
    {
        $schoolId = $this->tenantContext->activeSchoolId();
        if (!$this->accessService->canManageAiSettings(Auth::user(), $schoolId)) {
            abort(403, 'Unauthorized to view AI settings.');
        }

        $flags = AiFeatureFlag::query()
            ->where('school_id', $schoolId)
            ->get();

        return view('ai.feature-flags.index', compact('flags'));
    }

    public function edit(AiFeatureFlag $featureFlag): View
    {
        $schoolId = $this->tenantContext->activeSchoolId();
        if (!$this->accessService->canManageAiSettings(Auth::user(), $schoolId) || $featureFlag->school_id !== $schoolId) {
            abort(403, 'Unauthorized to edit AI settings.');
        }

        return view('ai.feature-flags.edit', compact('featureFlag'));
    }

    public function update(UpdateAiFeatureFlagRequest $request, AiFeatureFlag $featureFlag): RedirectResponse
    {
        $schoolId = $this->tenantContext->activeSchoolId();
        if (!$this->accessService->canManageAiSettings(Auth::user(), $schoolId) || $featureFlag->school_id !== $schoolId) {
            abort(403, 'Unauthorized to update AI settings.');
        }

        $before = $featureFlag->toArray();
        
        $featureFlag->update([
            'is_enabled' => $request->boolean('is_enabled'),
            'requires_teacher_review' => $request->boolean('requires_teacher_review'),
            'visible_to_parent' => $request->boolean('visible_to_parent'),
            'visible_to_student' => $request->boolean('visible_to_student'),
            'updated_by' => Auth::id(),
        ]);

        $this->auditLogger->log(
            'update_feature_flag',
            Auth::user(),
            null,
            get_class($featureFlag),
            $featureFlag->id,
            $before,
            $featureFlag->fresh()->toArray()
        );

        return redirect()
            ->route('ai-learning.feature-flags.index')
            ->with('success', 'Pengaturan fitur asisten AI berhasil diperbarui.');
    }
}
