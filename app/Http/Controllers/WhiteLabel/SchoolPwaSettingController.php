<?php

namespace App\Http\Controllers\WhiteLabel;

use App\Http\Controllers\Controller;
use App\Http\Requests\WhiteLabel\UpdateSchoolPwaSettingRequest;
use App\Models\School;
use App\Services\Tenancy\TenantContextService;
use App\Services\WhiteLabel\SchoolPwaManifestService;
use App\Services\WhiteLabel\WhiteLabelAccessService;
use Illuminate\Http\Request;

class SchoolPwaSettingController extends Controller
{
    protected TenantContextService $tenantContext;

    protected SchoolPwaManifestService $pwaService;

    protected WhiteLabelAccessService $accessService;

    public function __construct(
        TenantContextService $tenantContext,
        SchoolPwaManifestService $pwaService,
        WhiteLabelAccessService $accessService
    ) {
        $this->tenantContext = $tenantContext;
        $this->pwaService = $pwaService;
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

    public function show(Request $request)
    {
        $schoolId = $this->resolveSchoolId($request);
        $this->accessService->ensureCanManage($request->user(), $schoolId);

        $school = School::findOrFail($schoolId);
        $pwa = $this->pwaService->getOrCreatePwaSetting($schoolId);

        return view('white-label.pwa.show', compact('school', 'pwa'));
    }

    public function edit(Request $request)
    {
        $schoolId = $this->resolveSchoolId($request);
        $this->accessService->ensureCanManage($request->user(), $schoolId);

        $school = School::findOrFail($schoolId);
        $pwa = $this->pwaService->getOrCreatePwaSetting($schoolId);

        return view('white-label.pwa.edit', compact('school', 'pwa'));
    }

    public function update(UpdateSchoolPwaSettingRequest $request)
    {
        $schoolId = $this->resolveSchoolId($request);
        $this->accessService->ensureCanManage($request->user(), $schoolId);

        $data = $request->validated();

        $icon192 = $request->file('icon_192');
        $icon512 = $request->file('icon_512');

        // Cast is_enabled to boolean explicitly
        $data['is_enabled'] = $request->has('is_enabled');

        $this->pwaService->updatePwaSetting($schoolId, $data, $icon192, $icon512, $request->user()->id);

        return redirect()
            ->route('white-label.pwa.show', ['school_id' => $schoolId])
            ->with('success', 'Pengaturan PWA sekolah berhasil diperbarui.');
    }
}
