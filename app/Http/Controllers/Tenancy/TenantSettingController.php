<?php

namespace App\Http\Controllers\Tenancy;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenancy\UpdateTenantSettingRequest;
use App\Models\TenantSetting;
use App\Services\Tenancy\TenantContextService;
use App\Services\Tenancy\TenantSettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TenantSettingController extends Controller
{
    protected TenantSettingsService $settingsService;
    protected TenantContextService $contextService;

    public function __construct(TenantSettingsService $settingsService, TenantContextService $contextService)
    {
        $this->settingsService = $settingsService;
        $this->contextService = $contextService;
    }

    public function index(): View
    {
        $schoolId = $this->contextService->activeSchoolId();
        $settings = TenantSetting::query()
            ->where('school_id', $schoolId)
            ->orderBy('setting_key')
            ->get();

        return view('tenancy.settings.index', compact('settings'));
    }

    public function update(UpdateTenantSettingRequest $request): RedirectResponse
    {
        $schoolId = $this->contextService->activeSchoolId();
        $validated = $request->validated();

        foreach ($validated['settings'] as $settingData) {
            $this->settingsService->setSetting(
                $schoolId,
                $settingData['key'],
                $settingData['value'],
                $settingData['type'],
                (bool) ($settingData['is_public'] ?? false),
                $settingData['description'] ?? null
            );
        }

        return redirect()->route('tenancy.settings.index')
            ->with('success', 'Tenant settings berhasil diperbarui.');
    }
}
