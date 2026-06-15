<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\Tenancy\TenantContextService;
use App\Services\WhiteLabel\SchoolPwaManifestService;
use Illuminate\Http\Request;

class TenantPwaManifestController extends Controller
{
    protected TenantContextService $tenantContext;
    protected SchoolPwaManifestService $pwaService;

    public function __construct(TenantContextService $tenantContext, SchoolPwaManifestService $pwaService)
    {
        $this->tenantContext = $tenantContext;
        $this->pwaService = $pwaService;
    }

    public function show(Request $request)
    {
        $schoolId = $this->tenantContext->activeSchoolId();

        if ($schoolId) {
            $manifest = $this->pwaService->generateManifest($schoolId);
        } else {
            // Default global app manifest
            $manifest = [
                'name' => 'HafizPlus School Platform',
                'short_name' => 'HafizPlus',
                'theme_color' => '#1e293b',
                'background_color' => '#f8fafc',
                'start_url' => '/',
                'display' => 'standalone',
                'icons' => [
                    [
                        'src' => asset('images/logo_pwa.svg'),
                        'sizes' => '192x192',
                        'type' => 'image/svg+xml',
                        'purpose' => 'any maskable'
                    ],
                    [
                        'src' => asset('images/logo_pwa.svg'),
                        'sizes' => '512x512',
                        'type' => 'image/svg+xml',
                        'purpose' => 'any maskable'
                    ]
                ]
            ];
        }

        return response()->json($manifest)
            ->header('Content-Type', 'application/manifest+json')
            ->header('Cache-Control', 'public, max-age=3600');
    }
}
