<?php

namespace App\Http\Controllers\WhiteLabel;

use App\Http\Controllers\Controller;
use App\Http\Requests\WhiteLabel\UpdateSchoolBrandProfileRequest;
use App\Models\School;
use App\Services\Tenancy\TenantContextService;
use App\Services\WhiteLabel\SchoolBrandingService;
use App\Services\WhiteLabel\WhiteLabelAccessService;
use Illuminate\Http\Request;

class SchoolBrandProfileController extends Controller
{
    protected TenantContextService $tenantContext;

    protected SchoolBrandingService $brandingService;

    protected WhiteLabelAccessService $accessService;

    public function __construct(
        TenantContextService $tenantContext,
        SchoolBrandingService $brandingService,
        WhiteLabelAccessService $accessService
    ) {
        $this->tenantContext = $tenantContext;
        $this->brandingService = $brandingService;
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
        $profile = $this->brandingService->getOrCreateProfile($schoolId);

        return view('white-label.brand.show', compact('school', 'profile'));
    }

    public function edit(Request $request)
    {
        $schoolId = $this->resolveSchoolId($request);
        $this->accessService->ensureCanManage($request->user(), $schoolId);

        $school = School::findOrFail($schoolId);
        $profile = $this->brandingService->getOrCreateProfile($schoolId);

        return view('white-label.brand.edit', compact('school', 'profile'));
    }

    public function update(UpdateSchoolBrandProfileRequest $request)
    {
        $schoolId = $this->resolveSchoolId($request);
        $this->accessService->ensureCanManage($request->user(), $schoolId);

        $data = $request->validated();

        $logo = $request->file('logo');
        $favicon = $request->file('favicon');
        $loginBg = $request->file('login_background');

        $this->brandingService->updateProfile($schoolId, $data, $logo, $favicon, $loginBg, $request->user()->id);

        return redirect()
            ->route('white-label.brand.show', ['school_id' => $schoolId])
            ->with('success', 'Profil brand sekolah berhasil diperbarui.');
    }
}
