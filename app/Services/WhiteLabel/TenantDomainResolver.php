<?php

namespace App\Services\WhiteLabel;

use App\Models\School;
use App\Models\SchoolDomainMapping;
use Illuminate\Support\Facades\Cache;

class TenantDomainResolver
{
    /**
     * Resolve the tenant (school) from a hostname.
     */
    public function resolve(string $host): ?School
    {
        // Normalise host to lowercase and strip port if present
        $host = strtolower(trim($host));
        if (strpos($host, ':') !== false) {
            $host = explode(':', $host)[0];
        }

        // Cache keys based on resolved host to prevent DB query overhead
        $cacheKey = 'tenant_domain_resolve:'.$host;

        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($host) {
            // First check direct mapping in domain mappings table
            $mapping = SchoolDomainMapping::query()
                ->where('domain', $host)
                ->where('status', 'active')
                ->first();

            if ($mapping) {
                return School::query()->find($mapping->school_id);
            }

            // Fallback: Check if this is a subdomain of our application (e.g. {slug}.hafizplus.test)
            // Let's identify the main app domain from config
            $appUrl = config('app.url', 'http://localhost');
            $appHost = parse_url($appUrl, PHP_URL_HOST) ?? 'localhost';
            $appHost = strtolower($appHost);

            if ($host !== $appHost && str_ends_with($host, '.'.$appHost)) {
                // Extract the subdomain/slug
                $subdomain = substr($host, 0, -strlen('.'.$appHost));

                if (str_contains($subdomain, '.') || $subdomain === '') {
                    return null;
                }

                // Let's look up a school with this slug or tenant_code
                $school = School::query()
                    ->where(function ($query) use ($subdomain) {
                        $query->where('slug', $subdomain)
                            ->orWhere('tenant_code', $subdomain);
                    })
                    ->where('is_tenant_enabled', true)
                    ->where('tenant_status', 'active')
                    ->first();

                if ($school) {
                    return $school;
                }
            }

            return null;
        });
    }

    /**
     * Clear the cache for a resolved domain.
     */
    public function clearCache(string $domain): void
    {
        Cache::forget('tenant_domain_resolve:'.strtolower(trim($domain)));
    }
}
