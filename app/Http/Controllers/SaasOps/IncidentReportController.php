<?php

namespace App\Http\Controllers\SaasOps;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaasOps\StoreIncidentReportRequest;
use App\Http\Requests\SaasOps\UpdateIncidentReportRequest;
use App\Models\IncidentReport;
use App\Models\School;
use App\Models\User;
use App\Services\SaasOps\IncidentResponseService;
use App\Services\SaasOps\SaasOperationsAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class IncidentReportController extends Controller
{
    public function __construct(private readonly SaasOperationsAccessService $access, private readonly IncidentResponseService $incidents) {}

    public function index(): View
    {
        abort_unless($this->access->canManageIncidents(auth()->user()), 403);
        return view('saas-ops.incident-reports.index', ['incidents' => IncidentReport::query()->with('school')->latest()->paginate(20)]);
    }

    public function create(): View
    {
        return view('saas-ops.incident-reports.create', ['incident' => null, 'schools' => School::query()->orderBy('name')->get(), 'users' => User::query()->orderBy('name')->get()]);
    }

    public function store(StoreIncidentReportRequest $request): RedirectResponse
    {
        $incident = $this->incidents->createIncident($request->validated(), $request->user());
        return redirect()->route('saas-ops.incident-reports.show', $incident)->with('success', 'Incident dibuat.');
    }

    public function show(IncidentReport $incidentReport): View
    {
        if ($incidentReport->school_id) {
            $this->access->assertCanAccessSchool(auth()->user(), (int) $incidentReport->school_id);
        }
        $incidentReport->load('school');
        return view('saas-ops.incident-reports.show', ['incident' => $incidentReport]);
    }

    public function edit(IncidentReport $incidentReport): View
    {
        return view('saas-ops.incident-reports.edit', ['incident' => $incidentReport, 'schools' => School::query()->orderBy('name')->get(), 'users' => User::query()->orderBy('name')->get()]);
    }

    public function update(UpdateIncidentReportRequest $request, IncidentReport $incidentReport): RedirectResponse
    {
        $incidentReport->update($request->validated());
        return redirect()->route('saas-ops.incident-reports.show', $incidentReport)->with('success', 'Incident diperbarui.');
    }

    public function destroy(IncidentReport $incidentReport): RedirectResponse
    {
        $this->incidents->closeIncident($incidentReport, auth()->user());
        return back()->with('success', 'Incident closed.');
    }
}
