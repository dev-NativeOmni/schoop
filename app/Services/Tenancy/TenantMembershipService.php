<?php

namespace App\Services\Tenancy;

use App\Models\UserSchoolMembership;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TenantMembershipService
{
    protected TenantAuditLogger $logger;

    public function __construct(TenantAuditLogger $logger)
    {
        $this->logger = $logger;
    }

    public function createMembership(array $data): UserSchoolMembership
    {
        return DB::transaction(function () use ($data) {
            if (!empty($data['is_default'])) {
                $this->clearDefaultMemberships($data['user_id']);
            }

            $membership = UserSchoolMembership::create([
                'user_id' => $data['user_id'],
                'school_id' => $data['school_id'],
                'role_id' => $data['role_id'] ?? null,
                'membership_status' => $data['membership_status'] ?? 'active',
                'is_default' => $data['is_default'] ?? false,
                'joined_at' => $data['joined_at'] ?? now(),
                'created_by' => auth()->id(),
            ]);

            $this->logger->log('create_membership', $membership, [], $membership->toArray());

            return $membership;
        });
    }

    public function updateMembership(UserSchoolMembership $membership, array $data): UserSchoolMembership
    {
        return DB::transaction(function () use ($membership, $data) {
            $oldValues = $membership->toArray();

            if (!empty($data['is_default']) && $data['is_default']) {
                $this->clearDefaultMemberships($membership->user_id);
            }

            $membership->update([
                'role_id' => $data['role_id'] ?? $membership->role_id,
                'membership_status' => $data['membership_status'] ?? $membership->membership_status,
                'is_default' => $data['is_default'] ?? $membership->is_default,
                'joined_at' => $data['joined_at'] ?? $membership->joined_at,
            ]);

            $this->logger->log('update_membership', $membership, $oldValues, $membership->toArray());

            return $membership;
        });
    }

    public function deleteMembership(UserSchoolMembership $membership): void
    {
        DB::transaction(function () use ($membership) {
            $oldValues = $membership->toArray();
            $membership->delete();
            $this->logger->log('delete_membership', $membership, $oldValues, []);
        });
    }

    protected function clearDefaultMemberships(int $userId): void
    {
        UserSchoolMembership::query()
            ->where('user_id', $userId)
            ->where('is_default', true)
            ->update(['is_default' => false]);
    }
}
