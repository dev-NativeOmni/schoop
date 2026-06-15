<?php

namespace App\Services\DeveloperPortal;

use App\Models\ApiClient;
use App\Models\ApiDocumentationPage;
use App\Models\ApiRequestLog;
use App\Models\ApiScope;
use App\Models\PartnerIntegration;
use App\Models\WebhookDelivery;
use App\Models\WebhookEndpoint;

class DeveloperPortalService
{
    public function dashboard(): array
    {
        return [
            'api_clients' => ApiClient::query()->count(),
            'active_clients' => ApiClient::query()->where('status', 'active')->count(),
            'api_scopes' => ApiScope::query()->where('is_active', true)->count(),
            'request_logs_today' => ApiRequestLog::query()->whereDate('created_at', now()->toDateString())->count(),
            'partner_integrations' => PartnerIntegration::query()->count(),
            'webhook_endpoints' => WebhookEndpoint::query()->count(),
            'failed_webhooks' => WebhookDelivery::query()->where('status', 'failed')->count(),
            'published_docs' => ApiDocumentationPage::query()->where('status', 'published')->count(),
        ];
    }
}
