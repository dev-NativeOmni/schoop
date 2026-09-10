<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Services\Tenancy\TenantContextService;
use App\Services\WhiteLabel\SchoolThemeService;
use App\Services\WhiteLabel\WhiteLabelPublicationService;
use Illuminate\Http\Request;

class TenantPublicLandingController extends Controller
{
    protected TenantContextService $tenantContext;

    protected WhiteLabelPublicationService $publicationService;

    protected SchoolThemeService $themeService;

    public function __construct(
        TenantContextService $tenantContext,
        WhiteLabelPublicationService $publicationService,
        SchoolThemeService $themeService
    ) {
        $this->tenantContext = $tenantContext;
        $this->publicationService = $publicationService;
        $this->themeService = $themeService;
    }

    public function index(Request $request)
    {
        $schoolId = $this->tenantContext->activeSchoolId();

        if (! $schoolId) {
            return redirect()->route('login');
        }

        $school = School::findOrFail($schoolId);
        $settings = $this->publicationService->getActivePublishedSettings($schoolId);

        $brand = $settings['brand'];
        $theme = $settings['theme'];
        $cssVariables = $this->themeService->generateCssVariables($theme);

        // If user is already authenticated, provide quick access to dashboard
        $isAuthenticated = auth()->check();

        return view('public.tenant.landing', compact(
            'school',
            'brand',
            'theme',
            'cssVariables',
            'isAuthenticated'
        ));
    }
}
