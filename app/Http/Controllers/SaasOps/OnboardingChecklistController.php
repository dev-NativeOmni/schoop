<?php

namespace App\Http\Controllers\SaasOps;

use App\Http\Controllers\Controller;
use App\Models\ImplementationProject;
use App\Models\OnboardingChecklistRecord;
use App\Services\SaasOps\OnboardingWorkflowService;
use App\Services\SaasOps\SaasOperationsAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OnboardingChecklistController extends Controller
{
    public function __construct(private readonly SaasOperationsAccessService $access, private readonly OnboardingWorkflowService $workflow) {}

    public function show(ImplementationProject $project): View
    {
        $this->access->assertCanAccessSchool(auth()->user(), (int) $project->school_id);
        $project->load(['school', 'records.item']);

        return view('saas-ops.onboarding.show', compact('project'));
    }

    public function complete(Request $request, ImplementationProject $project, OnboardingChecklistRecord $record): RedirectResponse
    {
        $this->access->assertCanAccessSchool($request->user(), (int) $project->school_id);
        abort_unless((int) $record->implementation_project_id === (int) $project->id, 404);
        $this->workflow->completeRecord($record, $request->user(), $request->input('note'));

        return back()->with('success', 'Checklist ditandai selesai.');
    }
}
