<?php

namespace App\Services\WhiteLabel;

use App\Models\SchoolDomainMapping;
use Illuminate\Support\Str;

class SchoolDomainVerificationService
{
    /**
     * Create a new domain mapping with a verification token.
     */
    public function createMapping(int $schoolId, string $domain, string $type, ?string $notes = null, ?int $userId = null): SchoolDomainMapping
    {
        $domain = $this->normalizeDomain($domain);

        $exists = SchoolDomainMapping::query()
            ->where('domain', $domain)
            ->exists();

        if ($exists) {
            throw new \InvalidArgumentException('Domain sudah terdaftar pada mapping lain.');
        }

        $token = 'hp_verification_'.Str::random(32);

        return SchoolDomainMapping::query()->create([
            'school_id' => $schoolId,
            'domain' => $domain,
            'type' => $type,
            'status' => 'pending',
            'verification_token' => $token,
            'notes' => $notes,
            'created_by' => $userId,
        ]);
    }

    private function normalizeDomain(string $domain): string
    {
        $domain = strtolower(trim($domain));
        $domain = preg_replace('/^https?:\/\//i', '', $domain) ?? $domain;
        $domain = explode('/', $domain)[0];

        return rtrim($domain, '.:/');
    }

    /**
     * Manually verify a domain mapping.
     */
    public function verifyDomain(int $mappingId, ?int $userId = null): SchoolDomainMapping
    {
        $mapping = SchoolDomainMapping::query()->findOrFail($mappingId);

        $mapping->update([
            'status' => 'verified',
            'verified_at' => now(),
            'verified_by' => $userId,
        ]);

        return $mapping;
    }

    /**
     * Activate a verified domain mapping.
     * This will automatically disable any other mapping with status 'active' for the SAME domain.
     */
    public function activateDomain(int $mappingId): SchoolDomainMapping
    {
        $mapping = SchoolDomainMapping::query()->findOrFail($mappingId);

        if ($mapping->status !== 'verified' && $mapping->status !== 'disabled') {
            throw new \Exception('Domain harus diverifikasi terlebih dahulu sebelum diaktifkan.');
        }

        // Deactivate other active mappings for the same domain
        SchoolDomainMapping::query()
            ->where('domain', $mapping->domain)
            ->where('status', 'active')
            ->where('id', '!=', $mapping->id)
            ->update([
                'status' => 'disabled',
                'disabled_at' => now(),
            ]);

        $mapping->update([
            'status' => 'active',
            'activated_at' => now(),
            'disabled_at' => null,
        ]);

        // Clear DNS resolver cache
        app(TenantDomainResolver::class)->clearCache($mapping->domain);

        return $mapping;
    }

    /**
     * Disable an active domain mapping.
     */
    public function disableDomain(int $mappingId): SchoolDomainMapping
    {
        $mapping = SchoolDomainMapping::query()->findOrFail($mappingId);

        $mapping->update([
            'status' => 'disabled',
            'disabled_at' => now(),
        ]);

        // Clear DNS resolver cache
        app(TenantDomainResolver::class)->clearCache($mapping->domain);

        return $mapping;
    }
}
