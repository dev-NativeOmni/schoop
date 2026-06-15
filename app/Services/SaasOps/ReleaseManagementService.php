<?php

namespace App\Services\SaasOps;

use App\Models\ReleaseNote;
use App\Models\User;

class ReleaseManagementService
{
    public function publish(ReleaseNote $releaseNote, User $actor): ReleaseNote
    {
        $releaseNote->update(['status' => 'published', 'published_at' => now(), 'created_by' => $releaseNote->created_by ?: $actor->id]);

        return $releaseNote;
    }
}
