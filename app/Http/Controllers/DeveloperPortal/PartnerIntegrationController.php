<?php

namespace App\Http\Controllers\DeveloperPortal;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeveloperPortal\StorePartnerIntegrationRequest;
use App\Http\Requests\DeveloperPortal\UpdatePartnerIntegrationRequest;
use App\Models\ApiClient;
use App\Models\PartnerIntegration;
use App\Models\School;
use App\Services\DeveloperPortal\ApiAccessService;
use App\Services\DeveloperPortal\PartnerIntegrationService;
use Illuminate\Http\Request;

class PartnerIntegrationController extends Controller
{
    public function __construct(
        private readonly ApiAccessService $access,
        private readonly PartnerIntegrationService $integrations,
    ) {}

    public function index()
    {
        $this->access->assertDeveloperPortalAccess(request()->user());

        return view('developer-portal.partner-integrations.index', [
            'integrations' => PartnerIntegration::query()->with(['school', 'client'])->latest()->paginate(20),
        ]);
    }

    public function create()
    {
        $this->access->assertCanManageClients(request()->user());

        return view('developer-portal.partner-integrations.create', $this->formData(null));
    }

    public function store(StorePartnerIntegrationRequest $request)
    {
        $this->access->assertCanManageClients($request->user());
        $payload = $this->payload($request->validated());
        $payload['created_by'] = $request->user()->id;
        $payload['updated_by'] = $request->user()->id;

        $integration = PartnerIntegration::query()->create($payload);

        return redirect()->route('developer-portal.partner-integrations.show', $integration)->with('success', 'Partner integration dibuat.');
    }

    public function show(PartnerIntegration $partnerIntegration)
    {
        $this->access->assertDeveloperPortalAccess(request()->user());

        return view('developer-portal.partner-integrations.show', [
            'integration' => $partnerIntegration->load(['school', 'client']),
        ]);
    }

    public function edit(PartnerIntegration $partnerIntegration)
    {
        $this->access->assertCanManageClients(request()->user());

        return view('developer-portal.partner-integrations.edit', $this->formData($partnerIntegration));
    }

    public function update(UpdatePartnerIntegrationRequest $request, PartnerIntegration $partnerIntegration)
    {
        $this->access->assertCanManageClients($request->user());
        $payload = $this->payload($request->validated());
        $payload['updated_by'] = $request->user()->id;
        $partnerIntegration->update($payload);

        return redirect()->route('developer-portal.partner-integrations.show', $partnerIntegration)->with('success', 'Partner integration diperbarui.');
    }

    public function destroy(PartnerIntegration $partnerIntegration)
    {
        $this->access->assertCanManageClients(request()->user());
        $partnerIntegration->update(['status' => 'disabled']);

        return redirect()->route('developer-portal.partner-integrations.index')->with('success', 'Partner integration dinonaktifkan.');
    }

    public function approve(Request $request, PartnerIntegration $partnerIntegration)
    {
        $this->access->assertCanManageClients($request->user());
        $this->integrations->approve($partnerIntegration, $request->user());

        return redirect()->route('developer-portal.partner-integrations.show', $partnerIntegration)->with('success', 'Partner integration disetujui.');
    }

    private function formData(?PartnerIntegration $integration): array
    {
        return [
            'integration' => $integration,
            'schools' => School::query()->orderBy('name')->get(),
            'clients' => ApiClient::query()->orderBy('name')->get(),
            'types' => ['sis', 'finance', 'attendance', 'content_partner', 'analytics_internal', 'custom'],
            'statuses' => ['draft', 'active', 'suspended', 'disabled'],
        ];
    }

    private function payload(array $payload): array
    {
        return [
            'school_id' => $payload['school_id'] ?? null,
            'api_client_id' => $payload['api_client_id'] ?? null,
            'name' => $payload['name'],
            'integration_type' => $payload['integration_type'],
            'provider_name' => $payload['provider_name'] ?? null,
            'status' => $payload['status'],
            'configuration' => $this->json($payload['configuration'] ?? null),
            'notes' => $payload['notes'] ?? null,
        ];
    }

    private function json(?string $value): ?array
    {
        if (! $value) {
            return null;
        }

        return json_decode($value, true) ?: null;
    }
}
