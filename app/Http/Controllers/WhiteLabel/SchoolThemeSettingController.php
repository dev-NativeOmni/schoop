<?php

namespace App\Http\Controllers\WhiteLabel;

use App\Http\Controllers\Controller;
use App\Http\Requests\WhiteLabel\UpdateSchoolThemeSettingRequest;
use App\Models\School;
use App\Services\Tenancy\TenantContextService;
use App\Services\WhiteLabel\SchoolThemeService;
use App\Services\WhiteLabel\WhiteLabelAccessService;
use Illuminate\Http\Request;

class SchoolThemeSettingController extends Controller
{
    protected TenantContextService $tenantContext;

    protected SchoolThemeService $themeService;

    protected WhiteLabelAccessService $accessService;

    public function __construct(
        TenantContextService $tenantContext,
        SchoolThemeService $themeService,
        WhiteLabelAccessService $accessService
    ) {
        $this->tenantContext = $tenantContext;
        $this->themeService = $themeService;
        $this->accessService = $accessService;
    }

    protected function resolveSchoolId(Request $request): int
    {
        $user = $request->user();
        if ($user->isSuperAdmin() && $request->has('school_id')) {
            return (int) $request->input('school_id');
        }

        return $this->tenantContext->activeSchoolId() ?? abort(403, 'Context sekolah tidak ditemukan.');
    }

    public function edit(Request $request)
    {
        $schoolId = $this->resolveSchoolId($request);
        $this->accessService->ensureCanManage($request->user(), $schoolId);

        $school = School::findOrFail($schoolId);
        $theme = $this->themeService->getOrCreateTheme($schoolId);

        return view('white-label.themes.edit', compact('school', 'theme'));
    }

    public function update(UpdateSchoolThemeSettingRequest $request)
    {
        $schoolId = $this->resolveSchoolId($request);
        $this->accessService->ensureCanManage($request->user(), $schoolId);

        $data = $request->validated();
        $this->themeService->updateTheme($schoolId, $data, $request->user()->id);

        return redirect()
            ->route('white-label.themes.edit', ['school_id' => $schoolId])
            ->with('success', 'Tema sekolah berhasil diperbarui.');
    }

    public function preview(Request $request)
    {
        $schoolId = $this->resolveSchoolId($request);
        $this->accessService->ensureCanManage($request->user(), $schoolId);

        $school = School::findOrFail($schoolId);
        $theme = $this->themeService->getOrCreateTheme($schoolId);
        $cssVariables = $this->themeService->generateCssVariables($theme);

        return view('white-label.themes.preview', compact('school', 'theme', 'cssVariables'));
    }
}
