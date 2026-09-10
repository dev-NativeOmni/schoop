<?php

namespace App\Services\WhiteLabel;

use App\Models\SchoolBrandProfile;
use App\Models\SchoolPwaSetting;
use App\Models\SchoolThemeSetting;
use App\Models\WhiteLabelPublication;
use Illuminate\Support\Facades\DB;

class WhiteLabelPublicationService
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

    /**
     * Create snapshots of current brand, theme, and PWA settings, and save them.
     */
    public function publish(int $schoolId, ?string $notes = null, ?int $userId = null): WhiteLabelPublication
    {
        return DB::transaction(function () use ($schoolId, $notes, $userId) {
            $brand = $this->brandingService->getOrCreateProfile($schoolId);
            $theme = $this->themeService->getOrCreateTheme($schoolId);
            $pwa = $this->pwaService->getOrCreatePwaSetting($schoolId);

            // Create snapshots of Eloquent models as arrays
            $brandSnapshot = $brand->toArray();
            $themeSnapshot = $theme->toArray();
            $pwaSnapshot = $pwa->toArray();

            // Set older publications to 'rolled_back' or similar if needed, or keep history
            WhiteLabelPublication::query()
                ->where('school_id', $schoolId)
                ->where('status', 'published')
                ->update(['status' => 'rolled_back']);

            // Create new publication entry
            $publication = WhiteLabelPublication::query()->create([
                'school_id' => $schoolId,
                'status' => 'published',
                'brand_snapshot' => $brandSnapshot,
                'theme_snapshot' => $themeSnapshot,
                'pwa_snapshot' => $pwaSnapshot,
                'published_by' => $userId,
                'published_at' => now(),
                'notes' => $notes,
            ]);

            return $publication;
        });
    }

    /**
     * Rollback configurations to a previous publication snapshot.
     */
    public function rollback(int $schoolId, int $publicationId, ?int $userId = null): void
    {
        DB::transaction(function () use ($schoolId, $publicationId, $userId) {
            $publication = WhiteLabelPublication::query()
                ->where('school_id', $schoolId)
                ->findOrFail($publicationId);

            $brandSnapshot = $publication->brand_snapshot;
            $themeSnapshot = $publication->theme_snapshot;
            $pwaSnapshot = $publication->pwa_snapshot;

            if ($brandSnapshot) {
                $brand = $this->brandingService->getOrCreateProfile($schoolId);
                // Exclude auto-incrementing key, school_id, and timestamps
                $filtered = collect($brandSnapshot)->except(['id', 'school_id', 'created_at', 'updated_at'])->toArray();
                $filtered['updated_by'] = $userId;
                $brand->update($filtered);
            }

            if ($themeSnapshot) {
                $theme = $this->themeService->getOrCreateTheme($schoolId);
                $filtered = collect($themeSnapshot)->except(['id', 'school_id', 'created_at', 'updated_at'])->toArray();
                $filtered['updated_by'] = $userId;
                $theme->update($filtered);
            }

            if ($pwaSnapshot) {
                $pwa = $this->pwaService->getOrCreatePwaSetting($schoolId);
                $filtered = collect($pwaSnapshot)->except(['id', 'school_id', 'created_at', 'updated_at'])->toArray();
                $filtered['updated_by'] = $userId;
                $pwa->update($filtered);
            }

            // Mark this publication as published, and set others to rolled_back
            WhiteLabelPublication::query()
                ->where('school_id', $schoolId)
                ->where('status', 'published')
                ->update(['status' => 'rolled_back']);

            $publication->update([
                'status' => 'published',
                'published_at' => now(),
                'published_by' => $userId,
                'notes' => 'Rolled back to publication #'.$publicationId,
            ]);
        });
    }

    /**
     * Get the currently active published branding profiles, themes, and PWA settings for a school.
     * Returns an array with resolved profile, theme, and pwa settings.
     */
    public function getActivePublishedSettings(int $schoolId): array
    {
        $publication = WhiteLabelPublication::query()
            ->where('school_id', $schoolId)
            ->where('status', 'published')
            ->orderByDesc('published_at')
            ->first();

        if ($publication) {
            return [
                'brand' => new SchoolBrandProfile($publication->brand_snapshot ?? []),
                'theme' => new SchoolThemeSetting($publication->theme_snapshot ?? []),
                'pwa' => new SchoolPwaSetting($publication->pwa_snapshot ?? []),
                'is_live' => true,
            ];
        }

        // Fallback: If no publication has occurred, use current working settings
        return [
            'brand' => $this->brandingService->getOrCreateProfile($schoolId),
            'theme' => $this->themeService->getOrCreateTheme($schoolId),
            'pwa' => $this->pwaService->getOrCreatePwaSetting($schoolId),
            'is_live' => false,
        ];
    }
}
