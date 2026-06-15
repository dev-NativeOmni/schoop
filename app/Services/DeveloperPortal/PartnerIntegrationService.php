<?php

namespace App\Services\DeveloperPortal;

use App\Models\PartnerIntegration;
use App\Models\User;

class PartnerIntegrationService
{
    public function approve(PartnerIntegration $integration, User $actor): void
    {
        $integration->update([
            'status' => 'active',
            'approved_by' => $actor->id,
            'approved_at' => now(),
        ]);
    }
}
