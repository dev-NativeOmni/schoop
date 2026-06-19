<?php

namespace App\Http\Controllers\SchoolOs;

use App\Http\Controllers\Controller;
use App\Http\Requests\SchoolOs\UpdateSystemModuleRequest;
use App\Models\SystemModule;
use App\Services\SchoolOs\ModuleRegistryService;
use App\Services\SchoolOs\SchoolOsAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SystemModuleController extends Controller
{
    public function index(
        Request $request,
        SchoolOsAccessService $accessService,
        ModuleRegistryService $moduleRegistryService
    ): View {
        abort_unless(
            $accessService->canManageSettings($request->user()) || $accessService->isPrincipal($request->user()),
            403
        );

        $modules = $moduleRegistryService->allModules();

        return view('schoolos.modules.index', compact('modules'));
    }

    public function update(
        UpdateSystemModuleRequest $request,
        SystemModule $systemModule
    ): RedirectResponse {
        $systemModule->update([
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $request->input('sort_order', $systemModule->sort_order),
        ]);

        return redirect()
            ->route('schoolos.modules.index')
            ->with('success', 'Status modul berhasil diperbarui.');
    }
}
