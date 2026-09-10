<?php

namespace App\Http\Controllers\WhiteLabel;

use App\Http\Controllers\Controller;
use App\Http\Requests\WhiteLabel\PublishWhiteLabelRequest;
use App\Models\School;
use App\Services\Tenancy\TenantContextService;
use App\Services\WhiteLabel\SchoolBrandingService;
use App\Services\WhiteLabel\SchoolPwaManifestService;
use App\Services\WhiteLabel\SchoolThemeService;
use App\Services\WhiteLabel\WhiteLabelAccessService;
use App\Services\WhiteLabel\WhiteLabelPublicationService;
use Illuminate\Http\Request;

class WhiteLabelPreviewController extends Controller
{
    protected TenantContextService $tenantContext;

    protected SchoolThemeService $themeService;

    protected WhiteLabelAccessService $accessService;

    protected WhiteLabelPublicationService $publicationService;

    public function __construct(
        TenantContextService $tenantContext,
        SchoolThemeService $themeService,
        WhiteLabelAccessService $accessService,
        WhiteLabelPublicationService $publicationService
    ) {
        $this->tenantContext = $tenantContext;
        $this->themeService = $themeService;
        $this->accessService = $accessService;
        $this->publicationService = $publicationService;
    }

    protected function resolveSchoolId(Request $request): int
    {
        $user = $request->user();
        if ($user->isSuperAdmin() && $request->has('school_id')) {
            return (int) $request->input('school_id');
        }

        return $this->tenantContext->activeSchoolId() ?? abort(403, 'Context sekolah tidak ditemukan.');
    }

    public function show(Request $request)
    {
        $schoolId = $this->resolveSchoolId($request);
        $this->accessService->ensureCanManage($request->user(), $schoolId);

        $school = School::findOrFail($schoolId);

        // Fetch working (draft) settings for previewing
        $brand = app(SchoolBrandingService::class)->getOrCreateProfile($schoolId);
        $theme = $this->themeService->getOrCreateTheme($schoolId);
        $pwa = app(SchoolPwaManifestService::class)->getOrCreatePwaSetting($schoolId);

        $cssVariables = $this->themeService->generateCssVariables($theme);

        return view('white-label.preview.show', compact('school', 'brand', 'theme', 'pwa', 'cssVariables'));
    }

    public function publish(PublishWhiteLabelRequest $request)
    {
        $schoolId = $this->resolveSchoolId($request);
        $this->accessService->ensureCanManage($request->user(), $schoolId);

        $notes = $request->input('notes');
        $this->publicationService->publish($schoolId, $notes, $request->user()->id);

        return redirect()
            ->route('white-label.dashboard', ['school_id' => $schoolId])
            ->with('success', 'Konfigurasi branding sekolah berhasil dipublikasikan secara live.');
    }

    public function rollback(Request $request, $id)
    {
        $schoolId = $this->resolveSchoolId($request);
        $this->accessService->ensureCanManage($request->user(), $schoolId);

        try {
            $this->publicationService->rollback($schoolId, $id, $request->user()->id);

            return redirect()
                ->route('white-label.dashboard', ['school_id' => $schoolId])
                ->with('success', 'Tema dan profil berhasil di-rollback ke snapshot #'.$id);
        } catch (\Exception $e) {
            return redirect()
                ->route('white-label.dashboard', ['school_id' => $schoolId])
                ->with('error', $e->getMessage());
        }
    }
}
