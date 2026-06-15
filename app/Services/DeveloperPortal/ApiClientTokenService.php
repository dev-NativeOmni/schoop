<?php

namespace App\Services\DeveloperPortal;

use App\Models\ApiClient;
use App\Models\ApiClientToken;
use App\Models\User;
use Illuminate\Support\Str;

class ApiClientTokenService
{
    public function generate(ApiClient $client, string $tokenName, ?User $actor = null, ?string $expiresAt = null): array
    {
        $plainToken = 'hfp_live_'.Str::random(80);
        $prefix = substr($plainToken, 0, 14);

        $token = ApiClientToken::query()->create([
            'api_client_id' => $client->id,
            'token_name' => $tokenName,
            'token_prefix' => $prefix,
            'token_hash' => $this->hash($plainToken),
            'status' => 'active',
            'expires_at' => $expiresAt,
            'created_by' => $actor?->id,
        ]);

        return [$plainToken, $token];
    }

    public function findUsableToken(string $plainToken): ?ApiClientToken
    {
        $token = ApiClientToken::query()
            ->with(['client.scopes', 'client.school'])
            ->where('token_hash', $this->hash($plainToken))
            ->first();

        if (! $token || ! $token->isUsable() || ! $token->client?->isActive()) {
            return null;
        }

        return $token;
    }

    public function revoke(ApiClientToken $token, ?User $actor = null, ?string $reason = null): void
    {
        $token->update([
            'status' => 'revoked',
            'revoked_at' => now(),
            'revoked_by' => $actor?->id,
            'revoked_reason' => $reason,
        ]);
    }

    public function rotate(ApiClient $client, ?User $actor = null): array
    {
        $client->tokens()
            ->where('status', 'active')
            ->update([
                'status' => 'revoked',
                'revoked_at' => now(),
                'revoked_by' => $actor?->id,
                'revoked_reason' => 'Rotated by system.',
            ]);

        return $this->generate($client, 'Rotated token '.now()->format('YmdHis'), $actor);
    }

    public function hash(string $plainToken): string
    {
        return hash('sha256', $plainToken);
    }
}
