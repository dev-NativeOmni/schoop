<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\DeveloperPortal\ApiAccessService;
use App\Services\DeveloperPortal\ApiResponseFormatter;
use App\Services\DeveloperPortal\WebhookEventService;
use Illuminate\Http\Request;

class WebhookTestController extends Controller
{
    public function __construct(
        private readonly ApiAccessService $access,
        private readonly ApiResponseFormatter $response,
        private readonly WebhookEventService $events,
    ) {}

    public function store(Request $request)
    {
        $schoolId = $this->access->externalSchoolId($request);
        $count = $this->events->queueEvent($schoolId, 'webhook.test', [
            'message' => 'Webhook test from HafizPlus.',
            'requested_at' => now()->toIso8601String(),
        ]);

        return $this->response->ok([
            'queued_deliveries' => $count,
        ], 'Webhook test queued.');
    }
}
