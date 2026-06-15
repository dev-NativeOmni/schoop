<?php

namespace App\Services\Analytics;

use App\Models\AnalyticsAccessLog;
use App\Models\User;
use Illuminate\Http\Request;

class AnalyticsAccessLogger
{
    public function __construct(
        private readonly AnalyticsPrivacyGuard $privacyGuard
    ) {}

    public function log(Request $request, string $area, string $action = 'view', ?int $schoolId = null): void
    {
        /** @var User|null $user */
        $user = $request->user();

        $filters = $request->except(['_token', 'password', 'password_confirmation']);
        $sanitizedFilters = $this->privacyGuard->sanitizeFilters($filters);

        AnalyticsAccessLog::query()->create([
            'school_id' => $schoolId ?: app(\App\Services\Tenancy\TenantContextService::class)->activeSchoolId(),
            'user_id' => $user?->id,
            'analytics_area' => $area,
            'action' => $action,
            'route_name' => $request->route()?->getName(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'filters' => $sanitizedFilters,
            'metadata' => [
                'referer' => $request->header('referer'),
            ],
        ]);
    }
}
