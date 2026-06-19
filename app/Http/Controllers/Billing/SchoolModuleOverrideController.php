<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Http\Requests\Billing\StoreSchoolModuleOverrideRequest;
use App\Models\School;
use App\Models\SchoolModuleOverride;
use App\Models\SystemModule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SchoolModuleOverrideController extends Controller
{
    public function index(): View
    {
        $overrides = SchoolModuleOverride::query()
            ->with(['school', 'module', 'creator'])
            ->latest('id')
            ->paginate(20);

        return view('billing.module-overrides.index', compact('overrides'));
    }

    public function create(): View
    {
        $schools = School::query()->orderBy('name')->get();
        $modules = SystemModule::query()->where('is_active', true)->orderBy('name')->get();

        return view('billing.module-overrides.create', compact('schools', 'modules'));
    }

    public function store(StoreSchoolModuleOverrideRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()?->id;

        // Ensure unique override constraint
        SchoolModuleOverride::query()->updateOrCreate(
            [
                'school_id' => $data['school_id'],
                'system_module_id' => $data['system_module_id'],
            ],
            [
                'is_enabled' => (bool)$data['is_enabled'],
                'reason' => $data['reason'] ?? null,
                'expires_at' => $data['expires_at'] ?? null,
                'created_by' => $data['created_by'],
            ]
        );

        return redirect()
            ->route('billing.module-overrides.index')
            ->with('success', 'Manual Module Override berhasil dibuat/diperbarui.');
    }

    public function edit(SchoolModuleOverride $moduleOverride): View
    {
        $schools = School::query()->orderBy('name')->get();
        $modules = SystemModule::query()->where('is_active', true)->orderBy('name')->get();

        return view('billing.module-overrides.edit', compact('moduleOverride', 'schools', 'modules'));
    }

    public function update(Request $request, SchoolModuleOverride $moduleOverride): RedirectResponse
    {
        $request->validate([
            'is_enabled' => ['required', 'boolean'],
            'reason' => ['nullable', 'string', 'max:100'],
            'expires_at' => ['nullable', 'date'],
        ]);

        $moduleOverride->update([
            'is_enabled' => (bool)$request->input('is_enabled'),
            'reason' => $request->input('reason'),
            'expires_at' => $request->input('expires_at'),
        ]);

        return redirect()
            ->route('billing.module-overrides.index')
            ->with('success', 'Manual Module Override berhasil diperbarui.');
    }

    public function destroy(SchoolModuleOverride $moduleOverride): RedirectResponse
    {
        $moduleOverride->delete();

        return redirect()
            ->route('billing.module-overrides.index')
            ->with('success', 'Manual Module Override berhasil dihapus.');
    }
}
