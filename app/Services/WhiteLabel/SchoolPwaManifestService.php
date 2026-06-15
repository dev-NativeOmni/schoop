<?php

namespace App\Services\WhiteLabel;

use App\Models\School;
use App\Models\SchoolPwaSetting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class SchoolPwaManifestService
{
    /**
     * Get or create a default PWA settings for a school.
     */
    public function getOrCreatePwaSetting(int $schoolId): SchoolPwaSetting
    {
        $setting = SchoolPwaSetting::query()
            ->where('school_id', $schoolId)
            ->first();

        if (!$setting) {
            $school = School::query()->findOrFail($schoolId);
            $setting = SchoolPwaSetting::query()->create([
                'school_id' => $schoolId,
                'app_name' => $school->name,
                'short_name' => substr($school->name, 0, 12), // short_name recommended < 12 chars
                'theme_color' => '#2563eb',
                'background_color' => '#ffffff',
                'start_url' => '/',
                'display_mode' => 'standalone',
                'is_enabled' => true,
            ]);
        }

        return $setting;
    }

    /**
     * Update PWA settings.
     */
    public function updatePwaSetting(int $schoolId, array $data, ?UploadedFile $icon192 = null, ?UploadedFile $icon512 = null, ?int $userId = null): SchoolPwaSetting
    {
        $setting = $this->getOrCreatePwaSetting($schoolId);

        if ($userId) {
            $data['updated_by'] = $userId;
        }

        // Handle 192px icon
        if ($icon192) {
            if ($setting->icon_192_path) {
                Storage::disk('public')->delete($setting->icon_192_path);
            }
            $filename = 'icon_192_' . time() . '.' . $icon192->getClientOriginalExtension();
            $path = $icon192->storeAs("white-label/{$schoolId}/pwa", $filename, 'public');
            $data['icon_192_path'] = $path;
        }

        // Handle 512px icon
        if ($icon512) {
            if ($setting->icon_512_path) {
                Storage::disk('public')->delete($setting->icon_512_path);
            }
            $filename = 'icon_512_' . time() . '.' . $icon512->getClientOriginalExtension();
            $path = $icon512->storeAs("white-label/{$schoolId}/pwa", $filename, 'public');
            $data['icon_512_path'] = $path;
        }

        $setting->update($data);

        // Clear manifest cache
        Cache::forget('pwa_manifest_school_' . $schoolId);

        return $setting;
    }

    /**
     * Generate the manifest array for a school.
     */
    public function generateManifest(int $schoolId): array
    {
        return Cache::remember('pwa_manifest_school_' . $schoolId, now()->addHours(24), function () use ($schoolId) {
            $setting = $this->getOrCreatePwaSetting($schoolId);

            $manifest = [
                'name' => $setting->app_name,
                'short_name' => $setting->short_name ?: substr($setting->app_name, 0, 12),
                'theme_color' => $setting->theme_color,
                'background_color' => $setting->background_color,
                'start_url' => $setting->start_url,
                'display' => $setting->display_mode,
                'icons' => [],
            ];

            // Build icons
            $logoUrl192 = $setting->icon_192_path 
                ? Storage::disk('public')->url($setting->icon_192_path)
                : asset('images/logo_pwa.svg');

            $logoUrl512 = $setting->icon_512_path 
                ? Storage::disk('public')->url($setting->icon_512_path)
                : asset('images/logo_pwa.svg');

            $icon192Type = $setting->icon_192_path ? 'image/png' : 'image/svg+xml';
            $icon512Type = $setting->icon_512_path ? 'image/png' : 'image/svg+xml';

            $manifest['icons'][] = [
                'src' => $logoUrl192,
                'sizes' => '192x192',
                'type' => $icon192Type,
                'purpose' => 'any maskable'
            ];

            $manifest['icons'][] = [
                'src' => $logoUrl512,
                'sizes' => '512x512',
                'type' => $icon512Type,
                'purpose' => 'any maskable'
            ];

            return $manifest;
        });
    }
}
