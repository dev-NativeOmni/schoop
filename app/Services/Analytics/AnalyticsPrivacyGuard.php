<?php

namespace App\Services\Analytics;

use App\Models\User;
use App\Services\Tenancy\TenantAccessService;

class AnalyticsPrivacyGuard
{
    private array $sensitiveKeys = [
        'password',
        'password_confirmation',
        'token',
        'api_token',
        'access_token',
        'refresh_token',
        'secret',
        'api_key',
        'private_key',
        'pin',
        'wallet_pin',
        'remember_token',
        'session_id',
        'cookie',
        'authorization',
        'nisn',
        'phone',
        'email',
        'address',
        'full_payload',
    ];

    public function sanitizeFilters(array $filters): array
    {
        return $this->removeSensitiveKeys($filters);
    }

    public function sanitizeMetadata(array $metadata): array
    {
        return $this->removeSensitiveKeys($metadata);
    }

    public function removeSensitiveKeys(array $payload): array
    {
        $sanitized = [];
        foreach ($payload as $key => $value) {
            if (in_array(strtolower($key), $this->sensitiveKeys, true)) {
                continue;
            }

            if (is_array($value)) {
                $sanitized[$key] = $this->removeSensitiveKeys($value);
            } else {
                $sanitized[$key] = $value;
            }
        }

        return $sanitized;
    }

    public function assertSchoolScope(?int $requestedSchoolId, User $user): void
    {
        if ($user->hasRole(['super_admin', 'operations_manager', 'customer_success', 'support_staff', 'sales'])) {
            return;
        }

        abort_unless($requestedSchoolId && app(TenantAccessService::class)->userCanAccessSchool($user, $requestedSchoolId), 403, 'Akses analitik tenant ditolak.');
    }

    public function canExposeMetricToSchool(string $metricKey): bool
    {
        // Metric definitions that schools are allowed to see
        // Typically, we hide super admin system-wide active counts, etc.
        $blockedForSchool = [
            'tenant.active_count',
        ];

        return ! in_array($metricKey, $blockedForSchool, true);
    }
}
