<?php

namespace App\Services\Tenancy;

use App\Models\TenantAuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class TenantAuditLogger
{
    public function log(string $action, ?Model $model = null, array $oldValues = [], array $newValues = []): void
    {
        /** @var Request $request */
        $request = request();

        TenantAuditLog::query()->create([
            'school_id' => app(TenantContextService::class)->activeSchoolId(),
            'user_id' => auth()->id(),
            'action' => $action,
            'auditable_type' => $model ? $model::class : null,
            'auditable_id' => $model?->getKey(),
            'old_values' => $oldValues ?: null,
            'new_values' => $newValues ?: null,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 1000),
        ]);
    }
}
