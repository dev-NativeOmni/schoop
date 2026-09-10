<?php

namespace App\Http\Controllers\DeveloperPortal;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeveloperPortal\StoreWebhookEndpointRequest;
use App\Http\Requests\DeveloperPortal\UpdateWebhookEndpointRequest;
use App\Models\ApiClient;
use App\Models\School;
use App\Models\WebhookEndpoint;
use App\Services\DeveloperPortal\ApiAccessService;
use App\Services\DeveloperPortal\WebhookEventService;
use App\Services\DeveloperPortal\WebhookSignatureService;

class WebhookEndpointController extends Controller
{
    public function __construct(
        private readonly ApiAccessService $access,
        private readonly WebhookEventService $events,
        private readonly WebhookSignatureService $signatures,
    ) {}

    public function index()
    {
        $this->access->assertDeveloperPortalAccess(request()->user());

        return view('developer-portal.webhooks.index', [
            'webhooks' => WebhookEndpoint::query()->with(['school', 'client'])->latest()->paginate(20),
        ]);
    }

    public function create()
    {
        $this->access->assertCanManageClients(request()->user());

        return view('developer-portal.webhooks.create', $this->formData(null));
    }

    public function store(StoreWebhookEndpointRequest $request)
    {
        $this->access->assertCanManageClients($request->user());
        $payload = $this->payload($request->validated());
        $payload['created_by'] = $request->user()->id;
        $payload['updated_by'] = $request->user()->id;
        $webhook = WebhookEndpoint::query()->create($payload);

        return redirect()->route('developer-portal.webhooks.show', $webhook)->with('success', 'Webhook endpoint dibuat.');
    }

    public function show(WebhookEndpoint $webhook)
    {
        $this->access->assertDeveloperPortalAccess(request()->user());

        return view('developer-portal.webhooks.show', [
            'webhook' => $webhook->load(['school', 'client', 'deliveries' => fn ($query) => $query->latest()->limit(20)]),
        ]);
    }

    public function edit(WebhookEndpoint $webhook)
    {
        $this->access->assertCanManageClients(request()->user());

        return view('developer-portal.webhooks.edit', $this->formData($webhook));
    }

    public function update(UpdateWebhookEndpointRequest $request, WebhookEndpoint $webhook)
    {
        $this->access->assertCanManageClients($request->user());
        $payload = $this->payload($request->validated(), keepExistingSecret: true, current: $webhook);
        $payload['updated_by'] = $request->user()->id;
        $webhook->update($payload);

        return redirect()->route('developer-portal.webhooks.show', $webhook)->with('success', 'Webhook endpoint diperbarui.');
    }

    public function destroy(WebhookEndpoint $webhook)
    {
        $this->access->assertCanManageClients(request()->user());
        $webhook->update(['status' => 'inactive']);

        return redirect()->route('developer-portal.webhooks.index')->with('success', 'Webhook endpoint dinonaktifkan.');
    }

    private function formData(?WebhookEndpoint $webhook): array
    {
        return [
            'webhook' => $webhook,
            'schools' => School::query()->orderBy('name')->get(),
            'clients' => ApiClient::query()->where('status', 'active')->orderBy('name')->get(),
            'events' => $this->events->availableEvents(),
            'statuses' => ['active', 'inactive', 'suspended'],
        ];
    }

    private function payload(array $payload, bool $keepExistingSecret = false, ?WebhookEndpoint $current = null): array
    {
        $secret = $payload['secret'] ?? null;
        $secretHash = $secret
            ? $this->signatures->hashSecret($secret)
            : ($keepExistingSecret ? $current?->secret_hash : null);

        return [
            'school_id' => $payload['school_id'] ?? null,
            'api_client_id' => $payload['api_client_id'],
            'name' => $payload['name'],
            'url' => $payload['url'],
            'secret_hash' => $secretHash,
            'subscribed_events' => $payload['subscribed_events'],
            'status' => $payload['status'],
        ];
    }
}
