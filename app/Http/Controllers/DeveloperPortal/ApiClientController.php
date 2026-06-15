<?php

namespace App\Http\Controllers\DeveloperPortal;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeveloperPortal\GenerateApiTokenRequest;
use App\Http\Requests\DeveloperPortal\StoreApiClientRequest;
use App\Http\Requests\DeveloperPortal\UpdateApiClientRequest;
use App\Models\ApiClient;
use App\Models\ApiClientToken;
use App\Models\ApiScope;
use App\Models\School;
use App\Services\DeveloperPortal\ApiAccessService;
use App\Services\DeveloperPortal\ApiClientTokenService;
use App\Services\DeveloperPortal\ApiScopeService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiClientController extends Controller
{
    public function __construct(
        private readonly ApiAccessService $access,
        private readonly ApiClientTokenService $tokens,
        private readonly ApiScopeService $scopes,
    ) {}

    public function index()
    {
        $this->access->assertDeveloperPortalAccess(request()->user());

        return view('developer-portal.api-clients.index', [
            'clients' => ApiClient::query()->with(['school', 'scopes'])->latest()->paginate(20),
        ]);
    }

    public function create()
    {
        $this->access->assertCanManageClients(request()->user());

        return view('developer-portal.api-clients.create', [
            'client' => null,
            'schools' => School::query()->orderBy('name')->get(),
            'scopes' => ApiScope::query()->where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function store(StoreApiClientRequest $request)
    {
        $this->access->assertCanManageClients($request->user());
        $payload = $this->clientPayload($request->validated());
        $payload['created_by'] = $request->user()->id;
        $payload['updated_by'] = $request->user()->id;

        $client = ApiClient::query()->create($payload);
        $this->scopes->syncScopes($client, $request->input('scope_ids', []), $request->user());

        return redirect()->route('developer-portal.api-clients.show', $client)->with('success', 'API client dibuat.');
    }

    public function show(ApiClient $apiClient)
    {
        $this->access->assertClientVisible(request()->user(), $apiClient);

        return view('developer-portal.api-clients.show', [
            'client' => $apiClient->load(['school', 'scopes', 'tokens', 'webhooks']),
            'plainToken' => session('plain_api_token'),
        ]);
    }

    public function edit(ApiClient $apiClient)
    {
        $this->access->assertCanManageClients(request()->user());
        $this->access->assertClientVisible(request()->user(), $apiClient);

        return view('developer-portal.api-clients.edit', [
            'client' => $apiClient->load('scopes'),
            'schools' => School::query()->orderBy('name')->get(),
            'scopes' => ApiScope::query()->where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function update(UpdateApiClientRequest $request, ApiClient $apiClient)
    {
        $this->access->assertCanManageClients($request->user());
        $this->access->assertClientVisible($request->user(), $apiClient);
        $payload = $this->clientPayload($request->validated());
        $payload['updated_by'] = $request->user()->id;

        $apiClient->update($payload);
        $this->scopes->syncScopes($apiClient, $request->input('scope_ids', []), $request->user());

        return redirect()->route('developer-portal.api-clients.show', $apiClient)->with('success', 'API client diperbarui.');
    }

    public function destroy(ApiClient $apiClient)
    {
        $this->access->assertCanManageClients(request()->user());
        $apiClient->update([
            'status' => 'revoked',
            'revoked_at' => now(),
            'revoked_by' => request()->user()->id,
            'revoked_reason' => 'Revoked from Developer Portal.',
        ]);
        $apiClient->tokens()->update([
            'status' => 'revoked',
            'revoked_at' => now(),
            'revoked_by' => request()->user()->id,
            'revoked_reason' => 'Client revoked.',
        ]);

        return redirect()->route('developer-portal.api-clients.show', $apiClient)->with('success', 'API client dicabut.');
    }

    public function generateToken(GenerateApiTokenRequest $request, ApiClient $apiClient)
    {
        $this->access->assertCanManageClients($request->user());
        [$plainToken] = $this->tokens->generate($apiClient, $request->validated('token_name'), $request->user(), $request->validated('expires_at'));

        return redirect()
            ->route('developer-portal.api-clients.show', $apiClient)
            ->with('plain_api_token', $plainToken)
            ->with('success', 'Token dibuat. Salin sekarang; token tidak akan ditampilkan lagi.');
    }

    public function revokeToken(Request $request, ApiClient $apiClient, ApiClientToken $token)
    {
        $this->access->assertCanManageClients($request->user());
        abort_unless((int) $token->api_client_id === (int) $apiClient->id, 404);
        $this->tokens->revoke($token, $request->user(), $request->input('reason', 'Revoked from Developer Portal.'));

        return redirect()->route('developer-portal.api-clients.show', $apiClient)->with('success', 'Token dicabut.');
    }

    public function rotateToken(Request $request, ApiClient $apiClient)
    {
        $this->access->assertCanManageClients($request->user());
        [$plainToken] = $this->tokens->rotate($apiClient, $request->user());

        return redirect()
            ->route('developer-portal.api-clients.show', $apiClient)
            ->with('plain_api_token', $plainToken)
            ->with('success', 'Token dirotasi. Salin token baru sekarang.');
    }

    private function clientPayload(array $payload): array
    {
        $allowedIps = collect(preg_split('/\r\n|\r|\n|,/', (string) ($payload['allowed_ips'] ?? '')))
            ->map(fn (string $ip): string => trim($ip))
            ->filter()
            ->values()
            ->all();

        return [
            'school_id' => $payload['school_id'] ?? null,
            'name' => $payload['name'],
            'client_code' => $payload['client_code'] ?: Str::slug($payload['name']).'-'.Str::lower(Str::random(6)),
            'description' => $payload['description'] ?? null,
            'owner_name' => $payload['owner_name'] ?? null,
            'owner_email' => $payload['owner_email'] ?? null,
            'status' => $payload['status'],
            'rate_limit_per_minute' => $payload['rate_limit_per_minute'],
            'allowed_ips' => $allowedIps ?: null,
        ];
    }
}
