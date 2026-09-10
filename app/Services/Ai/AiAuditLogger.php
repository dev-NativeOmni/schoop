<?php

namespace App\Services\Ai;

use App\Models\AiAuditLog;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Request;

class AiAuditLogger
{
    public function log(
        string $action,
        ?User $user = null,
        ?Student $student = null,
        ?string $entityType = null,
        ?int $entityId = null,
        array $before = [],
        array $after = []
    ): void {
        AiAuditLog::query()
            ->withoutGlobalScopes()
            ->create([
                'school_id' => $user?->school_id ?? $student?->school_id,
                'user_id' => $user?->id,
                'student_id' => $student?->id,
                'action' => $action,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'before' => $before,
                'after' => $after,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
            ]);
    }
}
