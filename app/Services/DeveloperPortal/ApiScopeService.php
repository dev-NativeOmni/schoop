<?php

namespace App\Services\DeveloperPortal;

use App\Models\ApiClient;
use App\Models\ApiScope;
use App\Models\User;

class ApiScopeService
{
    public function syncScopes(ApiClient $client, array $scopeIds, ?User $actor = null): void
    {
        $sync = [];

        foreach ($scopeIds as $scopeId) {
            $sync[$scopeId] = [
                'granted_by' => $actor?->id,
                'granted_at' => now(),
            ];
        }

        $client->scopes()->sync($sync);
    }

    public function hasScope(ApiClient $client, string $scope): bool
    {
        return $client->scopes()
            ->where('code', $scope)
            ->where('is_active', true)
            ->exists();
    }

    public function defaultScopes()
    {
        return ApiScope::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('code')
            ->get();
    }
}
