<?php

namespace App\Services\WhiteLabel;

use App\Models\School;
use App\Models\SchoolBrandProfile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SchoolBrandingService
{
    /**
     * Get or create a default brand profile for a school.
     */
    public function getOrCreateProfile(int $schoolId): SchoolBrandProfile
    {
        $profile = SchoolBrandProfile::query()
            ->where('school_id', $schoolId)
            ->first();

        if (!$profile) {
            $school = School::query()->findOrFail($schoolId);
            $profile = SchoolBrandProfile::query()->create([
                'school_id' => $schoolId,
                'display_name' => $school->name,
                'short_name' => substr($school->name, 0, 50),
                'tagline' => 'Tahfizh Monitoring & School Platform',
                'is_active' => true,
            ]);
        }

        return $profile;
    }

    /**
     * Update the brand profile, optionally uploading assets.
     */
    public function updateProfile(int $schoolId, array $data, ?UploadedFile $logo = null, ?UploadedFile $favicon = null, ?UploadedFile $loginBg = null, ?int $userId = null): SchoolBrandProfile
    {
        $profile = $this->getOrCreateProfile($schoolId);

        if ($userId) {
            $data['updated_by'] = $userId;
        }

        // Handle logo upload
        if ($logo) {
            if ($profile->logo_path) {
                try { Storage::disk('public')->delete($profile->logo_path); } catch (\Throwable $e) {}
                \App\Models\SystemAsset::where('key', $profile->logo_path)->delete();
            }
            $filename = 'logo_' . time() . '.' . $logo->getClientOriginalExtension();
            try {
                $path = $logo->storeAs("white-label/{$schoolId}/brand", $filename, 'public');
            } catch (\Throwable $e) {
                $path = "white-label/{$schoolId}/brand/{$filename}";
            }
            \App\Models\SystemAsset::put($path, file_get_contents($logo->getRealPath()), $logo->getClientMimeType() ?: 'image/png');
            $data['logo_path'] = $path;
        }

        // Handle favicon upload
        if ($favicon) {
            if ($profile->favicon_path) {
                try { Storage::disk('public')->delete($profile->favicon_path); } catch (\Throwable $e) {}
                \App\Models\SystemAsset::where('key', $profile->favicon_path)->delete();
            }
            $filename = 'favicon_' . time() . '.' . $favicon->getClientOriginalExtension();
            try {
                $path = $favicon->storeAs("white-label/{$schoolId}/brand", $filename, 'public');
            } catch (\Throwable $e) {
                $path = "white-label/{$schoolId}/brand/{$filename}";
            }
            \App\Models\SystemAsset::put($path, file_get_contents($favicon->getRealPath()), $favicon->getClientMimeType() ?: 'image/png');
            $data['favicon_path'] = $path;
        }

        // Handle login background upload
        if ($loginBg) {
            if ($profile->login_background_path) {
                try { Storage::disk('public')->delete($profile->login_background_path); } catch (\Throwable $e) {}
                \App\Models\SystemAsset::where('key', $profile->login_background_path)->delete();
            }
            $filename = 'login_bg_' . time() . '.' . $loginBg->getClientOriginalExtension();
            try {
                $path = $loginBg->storeAs("white-label/{$schoolId}/brand", $filename, 'public');
            } catch (\Throwable $e) {
                $path = "white-label/{$schoolId}/brand/{$filename}";
            }
            \App\Models\SystemAsset::put($path, file_get_contents($loginBg->getRealPath()), $loginBg->getClientMimeType() ?: 'image/jpeg');
            $data['login_background_path'] = $path;
        }

        $profile->update($data);

        return $profile;
    }

    /**
     * Get visual asset URL with cache-busting.
     */
    public function getAssetUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        return \App\Models\SystemAsset::url($path);
    }
}
