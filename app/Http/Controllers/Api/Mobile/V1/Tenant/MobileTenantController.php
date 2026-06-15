<?php

namespace App\Http\Controllers\Api\Mobile\V1\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Mobile\V1\SwitchMobileTenantRequest;
use App\Services\Mobile\MobileAuthService;
use App\Services\Mobile\MobileTenantService;
use App\Support\MobileApiResponse;
use Illuminate\Http\Request;

class MobileTenantController extends Controller
{
    public function __construct(
        private readonly MobileTenantService $tenants,
        private readonly MobileAuthService $auth,
    ) {}

    public function index(Request $request)
    {
        return MobileApiResponse::ok([
            'tenants' => $this->tenants->accessibleSchools($request->user())
                ->map(fn ($school): array => $this->auth->schoolPayload($school))
                ->values(),
        ]);
    }

    public function switch(SwitchMobileTenantRequest $request)
    {
        $school = $this->tenants->switchTenant($request->user(), (int) $request->validated('school_id'));

        $token = $request->attributes->get('mobile_access_token');
        $token?->forceFill(['school_id' => $school->id])->save();

        return MobileApiResponse::ok([
            'active_tenant' => $this->auth->schoolPayload($school),
        ], 'Tenant aktif diperbarui.');
    }

    public function current(Request $request)
    {
        return MobileApiResponse::ok([
            'active_tenant' => $this->auth->schoolPayload($request->attributes->get('mobile_school')),
        ]);
    }
}
