<?php

namespace App\Http\Controllers\WhiteLabel;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\SchoolDomainMapping;
use App\Models\WhiteLabelPublication;
use App\Services\Tenancy\TenantContextService;
use App\Services\WhiteLabel\WhiteLabelAccessService;
use App\Services\WhiteLabel\WhiteLabelPublicationService;
use Illuminate\Http\Request;

class WhiteLabelDashboardController extends Controller
{
    protected TenantContextService $tenantContext;

    protected WhiteLabelAccessService $accessService;

    protected WhiteLabelPublicationService $publicationService;

    public function __construct(
        TenantContextService $tenantContext,
        WhiteLabelAccessService $accessService,
        WhiteLabelPublicationService $publicationService
    ) {
        $this->tenantContext = $tenantContext;
        $this->accessService = $accessService;
        $this->publicationService = $publicationService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $activeSchoolId = $this->tenantContext->activeSchoolId();

        if ($user->isSuperAdmin() && $request->has('school_id')) {
            $schoolId = (int) $request->input('school_id');
        } else {
            $schoolId = $activeSchoolId;
        }

        if (! $schoolId) {
            if ($user->isSuperAdmin()) {
                // Fetch first school to switch to
                $firstSchool = School::first();
                if ($firstSchool) {
                    return redirect()->route('white-label.dashboard', ['school_id' => $firstSchool->id]);
                }
            }
            abort(403, 'Silakan pilih sekolah/tenant terlebih dahulu.');
        }

        $this->accessService->ensureCanManage($user, $schoolId);

        $school = School::findOrFail($schoolId);
        $settings = $this->publicationService->getActivePublishedSettings($schoolId);

        $domainCount = SchoolDomainMapping::query()->where('school_id', $schoolId)->count();
        $publications = WhiteLabelPublication::query()
            ->where('school_id', $schoolId)
            ->with('publisher')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $schools = $user->isSuperAdmin() ? School::all() : collect([$school]);

        return view('white-label.dashboard', compact(
            'school',
            'settings',
            'domainCount',
            'publications',
            'schools'
        ));
    }
}
