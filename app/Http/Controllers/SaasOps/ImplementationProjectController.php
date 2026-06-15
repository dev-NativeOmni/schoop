<?php

namespace App\Http\Controllers\SaasOps;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaasOps\StoreImplementationProjectRequest;
use App\Http\Requests\SaasOps\UpdateImplementationProjectRequest;
use App\Models\ImplementationProject;
use App\Models\School;
use App\Models\User;
use App\Services\SaasOps\OnboardingWorkflowService;
use App\Services\SaasOps\SaasOperationsAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ImplementationProjectController extends Controller
{
    public function __construct(private readonly SaasOperationsAccessService $access, private readonly OnboardingWorkflowService $workflow) {}

    public function index(): View
    {
        abort_unless($this->access->canViewDashboard(auth()->user()), 403);

        return view('saas-ops.implementation-projects.index', ['projects' => ImplementationProject::query()->with('school')->latest()->paginate(20)]);
    }

    public function create(): View
    {
        return view('saas-ops.implementation-projects.create', ['schools' => School::query()->orderBy('name')->get(), 'users' => User::query()->orderBy('name')->get(), 'project' => null]);
    }

    public function store(StoreImplementationProjectRequest $request): RedirectResponse
    {
        $project = $this->workflow->createProject($request->validated(), $request->user());

        return redirect()->route('saas-ops.implementation-projects.show', $project)->with('success', 'Implementation project dibuat.');
    }

    public function show(ImplementationProject $implementationProject): View
    {
        $this->access->assertCanAccessSchool(auth()->user(), (int) $implementationProject->school_id);
        $implementationProject->load(['school', 'records.item']);

        return view('saas-ops.implementation-projects.show', ['project' => $implementationProject]);
    }

    public function edit(ImplementationProject $implementationProject): View
    {
        return view('saas-ops.implementation-projects.edit', ['project' => $implementationProject, 'schools' => School::query()->orderBy('name')->get(), 'users' => User::query()->orderBy('name')->get()]);
    }

    public function update(UpdateImplementationProjectRequest $request, ImplementationProject $implementationProject): RedirectResponse
    {
        $oldStage = $implementationProject->stage;
        $implementationProject->update($request->validated());
        if ($oldStage !== $implementationProject->stage) {
            $this->workflow->changeStage($implementationProject, $implementationProject->stage, $request->user());
        }

        return redirect()->route('saas-ops.implementation-projects.show', $implementationProject)->with('success', 'Project diperbarui.');
    }

    public function destroy(ImplementationProject $implementationProject): RedirectResponse
    {
        $implementationProject->update(['status' => 'cancelled']);

        return redirect()->route('saas-ops.implementation-projects.index')->with('success', 'Project dibatalkan.');
    }
}
