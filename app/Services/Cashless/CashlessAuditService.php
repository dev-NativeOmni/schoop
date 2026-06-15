<?php

namespace App\Services\Cashless;

use App\Models\CashlessAuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class CashlessAuditService
{
    public function log(?int $schoolId, ?User $user, string $action, ?Model $auditable = null, array $metadata = []): void
    {
        CashlessAuditLog::query()->create([
            'school_id' => $schoolId,
            'user_id' => $user?->id,
            'action' => $action,
            'auditable_type' => $auditable ? $auditable::class : null,
            'auditable_id' => $auditable?->getKey(),
            'metadata' => $metadata,
            'ip_address' => Request::ip(),
            'user_agent' => substr((string) Request::userAgent(), 0, 1000),
        ]);
    }
}
