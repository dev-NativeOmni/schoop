<?php

namespace App\Http\Controllers\SaasOps;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaasOps\StoreSlaPolicyRequest;
use App\Models\SlaPolicy;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SlaPolicyController extends Controller
{
    public function index(): View { return view('saas-ops.sla-policies.index', ['policies' => SlaPolicy::query()->orderBy('priority')->paginate(20)]); }
    public function create(): View { return view('saas-ops.sla-policies.create', ['policy' => null]); }
    public function store(StoreSlaPolicyRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);
        $policy = SlaPolicy::query()->updateOrCreate(['priority' => $data['priority']], $data);
        return redirect()->route('saas-ops.sla-policies.show', $policy)->with('success', 'SLA policy disimpan.');
    }
    public function show(SlaPolicy $slaPolicy): View { return view('saas-ops.sla-policies.show', ['policy' => $slaPolicy]); }
    public function edit(SlaPolicy $slaPolicy): View { return view('saas-ops.sla-policies.edit', ['policy' => $slaPolicy]); }
    public function update(StoreSlaPolicyRequest $request, SlaPolicy $slaPolicy): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $slaPolicy->update($data);
        return redirect()->route('saas-ops.sla-policies.show', $slaPolicy)->with('success', 'SLA policy diperbarui.');
    }
    public function destroy(SlaPolicy $slaPolicy): RedirectResponse { $slaPolicy->update(['is_active' => false]); return back()->with('success', 'SLA dinonaktifkan.'); }
}
