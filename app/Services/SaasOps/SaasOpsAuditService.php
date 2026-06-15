<?php

namespace App\Services\SaasOps;

use App\Models\TenantAuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class SaasOpsAuditService
{
    public function log(?int $schoolId, ?User $user, string $action, ?Model $model = null, array $values = []): void
    {
        TenantAuditLog::query()->create([
            'school_id' => $schoolId,
            'user_id' => $user?->id,
            'action' => $action,
            'auditable_type' => $model ? $model::class : null,
            'auditable_id' => $model?->getKey(),
            'old_values' => null,
            'new_values' => $values ?: null,
            'ip_address' => request()?->ip(),
            'user_agent' => substr((string) request()?->userAgent(), 0, 1000),
        ]);
    }
}
