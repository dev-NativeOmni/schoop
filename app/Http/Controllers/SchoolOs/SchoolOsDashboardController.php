<?php

namespace App\Http\Controllers\SchoolOs;

use App\Http\Controllers\Controller;
use App\Services\SchoolOs\ModuleRegistryService;
use App\Services\SchoolOs\SchoolContextService;
use App\Services\SchoolOs\SchoolOsAccessService;
use App\Services\SchoolOs\SchoolOsDashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SchoolOsDashboardController extends Controller
{
    public function index(
        Request $request,
        SchoolOsAccessService $accessService,
        SchoolContextService $contextService,
        ModuleRegistryService $moduleRegistry,
        SchoolOsDashboardService $dashboardService
    ): View {
        abort_unless($accessService->canViewSchoolOs($request->user()), 403);

        return view('schoolos.dashboard', [
            'roleName' => $accessService->roleName($request->user()),
            'activeAcademicYear' => $contextService->activeAcademicYear(),
            'activeTerm' => $contextService->activeTerm(),
            'modules' => $moduleRegistry->enabledModules(),
            'summary' => $dashboardService->summary(),
        ]);
    }
}
