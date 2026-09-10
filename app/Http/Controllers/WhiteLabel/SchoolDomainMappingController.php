<?php

namespace App\Http\Controllers\WhiteLabel;

use App\Http\Controllers\Controller;
use App\Http\Requests\WhiteLabel\StoreSchoolDomainMappingRequest;
use App\Http\Requests\WhiteLabel\UpdateSchoolDomainMappingRequest;
use App\Models\School;
use App\Models\SchoolDomainMapping;
use App\Services\Tenancy\TenantContextService;
use App\Services\WhiteLabel\SchoolDomainVerificationService;
use App\Services\WhiteLabel\TenantDomainResolver;
use App\Services\WhiteLabel\WhiteLabelAccessService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SchoolDomainMappingController extends Controller
{
    protected TenantContextService $tenantContext;

    protected SchoolDomainVerificationService $domainService;

    protected WhiteLabelAccessService $accessService;

    public function __construct(
        TenantContextService $tenantContext,
        SchoolDomainVerificationService $domainService,
        WhiteLabelAccessService $accessService
    ) {
        $this->tenantContext = $tenantContext;
        $this->domainService = $domainService;
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

    public function index(Request $request)
    {
        $schoolId = $this->resolveSchoolId($request);
        $this->accessService->ensureCanManage($request->user(), $schoolId);

        $school = School::findOrFail($schoolId);
        $domains = SchoolDomainMapping::query()
            ->where('school_id', $schoolId)
            ->with(['creator', 'verifier'])
            ->get();

        return view('white-label.domains.index', compact('school', 'domains'));
    }

    public function create(Request $request)
    {
        $schoolId = $this->resolveSchoolId($request);
        $this->accessService->ensureCanManage($request->user(), $schoolId);

        $school = School::findOrFail($schoolId);

        return view('white-label.domains.create', compact('school'));
    }

    public function store(StoreSchoolDomainMappingRequest $request)
    {
        $schoolId = $this->resolveSchoolId($request);
        $this->accessService->ensureCanManage($request->user(), $schoolId);

        $data = $request->validated();

        try {
            $this->domainService->createMapping(
                $schoolId,
                $data['domain'],
                $data['type'],
                $data['notes'] ?? null,
                $request->user()->id
            );
        } catch (\InvalidArgumentException $e) {
            return back()
                ->withInput()
                ->withErrors(['domain' => $e->getMessage()]);
        }

        return redirect()
            ->route('white-label.domains.index', ['school_id' => $schoolId])
            ->with('success', 'Mapping domain berhasil didaftarkan dan menunggu verifikasi.');
    }

    public function show(Request $request, $id)
    {
        $schoolId = $this->resolveSchoolId($request);
        $this->accessService->ensureCanManage($request->user(), $schoolId);

        $school = School::findOrFail($schoolId);
        $domain = SchoolDomainMapping::query()
            ->where('school_id', $schoolId)
            ->findOrFail($id);

        return view('white-label.domains.show', compact('school', 'domain'));
    }

    public function edit(Request $request, $id)
    {
        $schoolId = $this->resolveSchoolId($request);
        $this->accessService->ensureCanManage($request->user(), $schoolId);

        $school = School::findOrFail($schoolId);
        $domain = SchoolDomainMapping::query()
            ->where('school_id', $schoolId)
            ->findOrFail($id);

        return view('white-label.domains.edit', compact('school', 'domain'));
    }

    public function update(UpdateSchoolDomainMappingRequest $request, $id)
    {
        $schoolId = $this->resolveSchoolId($request);
        $this->accessService->ensureCanManage($request->user(), $schoolId);

        $domain = SchoolDomainMapping::query()
            ->where('school_id', $schoolId)
            ->findOrFail($id);

        $data = $request->validated();
        $oldDomain = $domain->domain;

        if ($oldDomain !== $data['domain']) {
            $data['status'] = 'pending';
            $data['verification_token'] = 'hp_verification_'.Str::random(32);
            $data['verified_at'] = null;
            $data['verified_by'] = null;
            $data['activated_at'] = null;
            $data['disabled_at'] = null;
        }

        $domain->update($data);

        app(TenantDomainResolver::class)->clearCache($oldDomain);
        app(TenantDomainResolver::class)->clearCache($domain->domain);

        return redirect()
            ->route('white-label.domains.index', ['school_id' => $schoolId])
            ->with('success', 'Mapping domain berhasil diperbarui.');
    }

    public function verify(Request $request, $id)
    {
        $schoolId = $this->resolveSchoolId($request);

        // Super Admin only can verify/approve mappings, OR school admin if authorized
        if (! $request->user()->isSuperAdmin()) {
            abort(403, 'Hanya Super Admin yang dapat melakukan verifikasi domain.');
        }

        SchoolDomainMapping::query()
            ->where('school_id', $schoolId)
            ->findOrFail($id);

        $this->domainService->verifyDomain($id, $request->user()->id);

        return redirect()
            ->route('white-label.domains.index', ['school_id' => $schoolId])
            ->with('success', 'Domain berhasil diverifikasi secara manual.');
    }

    public function activate(Request $request, $id)
    {
        $schoolId = $this->resolveSchoolId($request);
        $this->accessService->ensureCanManage($request->user(), $schoolId);

        SchoolDomainMapping::query()
            ->where('school_id', $schoolId)
            ->findOrFail($id);

        try {
            $this->domainService->activateDomain($id);

            return redirect()
                ->route('white-label.domains.index', ['school_id' => $schoolId])
                ->with('success', 'Domain berhasil diaktifkan.');
        } catch (\Exception $e) {
            return redirect()
                ->route('white-label.domains.index', ['school_id' => $schoolId])
                ->with('error', $e->getMessage());
        }
    }

    public function disable(Request $request, $id)
    {
        $schoolId = $this->resolveSchoolId($request);
        $this->accessService->ensureCanManage($request->user(), $schoolId);

        SchoolDomainMapping::query()
            ->where('school_id', $schoolId)
            ->findOrFail($id);

        $this->domainService->disableDomain($id);

        return redirect()
            ->route('white-label.domains.index', ['school_id' => $schoolId])
            ->with('success', 'Domain berhasil dinonaktifkan.');
    }

    public function destroy(Request $request, $id)
    {
        $schoolId = $this->resolveSchoolId($request);
        $this->accessService->ensureCanManage($request->user(), $schoolId);

        $mapping = SchoolDomainMapping::query()
            ->where('school_id', $schoolId)
            ->findOrFail($id);

        $mapping->delete();
        app(TenantDomainResolver::class)->clearCache($mapping->domain);

        return redirect()
            ->route('white-label.domains.index', ['school_id' => $schoolId])
            ->with('success', 'Mapping domain berhasil dihapus.');
    }
}
