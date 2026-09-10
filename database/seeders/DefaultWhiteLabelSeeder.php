<?php

namespace Database\Seeders;

use App\Models\School;
use App\Services\WhiteLabel\SchoolBrandingService;
use App\Services\WhiteLabel\SchoolPwaManifestService;
use App\Services\WhiteLabel\SchoolThemeService;
use Illuminate\Database\Seeder;

class DefaultWhiteLabelSeeder extends Seeder
{
    protected SchoolBrandingService $brandingService;

    protected SchoolThemeService $themeService;

    protected SchoolPwaManifestService $pwaService;

    public function __construct(
        SchoolBrandingService $brandingService,
        SchoolThemeService $themeService,
        SchoolPwaManifestService $pwaService
    ) {
        $this->brandingService = $brandingService;
        $this->themeService = $themeService;
        $this->pwaService = $pwaService;
    }

    public function run(): void
    {
        School::query()->each(function (School $school): void {
            // Ensure brand profile exists
            $this->brandingService->getOrCreateProfile($school->id);

            // Ensure theme setting exists
            $this->themeService->getOrCreateTheme($school->id);

            // Ensure PWA setting exists
            $this->pwaService->getOrCreatePwaSetting($school->id);
        });
    }
}
