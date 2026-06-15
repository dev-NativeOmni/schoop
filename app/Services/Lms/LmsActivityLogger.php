<?php

namespace App\Services\Lms;

use App\Models\LmsActivityLog;
use App\Services\Tenancy\TenantContextService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class LmsActivityLogger
{
    public function __construct(
        private readonly TenantContextService $tenantContext,
    ) {
        //
    }

    public function log(string $activityType, string $description, ?Model $subject = null, ?array $properties = null): void
    {
        $user = Auth::user();
        $schoolId = $this->tenantContext->activeSchoolId() ?? ($user ? $user->school_id : null);

        LmsActivityLog::create([
            'school_id' => $schoolId,
            'user_id' => $user ? $user->id : null,
            'activity_type' => $activityType,
            'description' => $description,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject ? $subject->getKey() : null,
            'properties' => $properties,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
