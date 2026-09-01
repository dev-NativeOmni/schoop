<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Services\Tenancy\TenantContextService;
use App\Services\WhiteLabel\SchoolThemeService;
use App\Services\WhiteLabel\WhiteLabelPublicationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        try {
            $schoolId = $this->tenantContext->activeSchoolId();

            if (!$schoolId) {
                return view('welcome');
            }

            $school = School::find($schoolId);
            if (!$school) {
                return view('welcome');
            }

            $settings = $this->publicationService->getActivePublishedSettings($schoolId);

            $brand = $settings['brand'] ?? null;
            $theme = $settings['theme'] ?? null;
            $cssVariables = $theme ? $this->themeService->generateCssVariables($theme) : '';
            $isAuthenticated = false;

            return view('public.tenant.landing', compact(
                'school',
                'brand',
                'theme',
                'cssVariables',
                'isAuthenticated'
            ));
        } catch (\Throwable $e) {
            Log::warning('Fallback to root welcome landing: ' . $e->getMessage());
            return view('welcome');
        }
    }
}
