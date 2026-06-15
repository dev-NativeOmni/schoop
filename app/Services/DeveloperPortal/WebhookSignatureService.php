<?php

namespace App\Services\DeveloperPortal;

class WebhookSignatureService
{
    public function hashSecret(?string $plainSecret): ?string
    {
        return $plainSecret ? hash('sha256', $plainSecret) : null;
    }

    public function sign(array $payload, string $secretHash): string
    {
        return hash_hmac('sha256', json_encode($payload, JSON_UNESCAPED_SLASHES), $secretHash);
    }
}
