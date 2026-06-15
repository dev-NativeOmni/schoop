<?php

namespace App\Http\Controllers\DeveloperPortal;

use App\Http\Controllers\Controller;
use App\Models\WebhookDelivery;
use App\Services\DeveloperPortal\ApiAccessService;
use App\Services\DeveloperPortal\WebhookDeliveryService;
use Illuminate\Http\Request;

class WebhookDeliveryController extends Controller
{
    public function __construct(
        private readonly ApiAccessService $access,
        private readonly WebhookDeliveryService $deliveries,
    ) {}

    public function index(Request $request)
    {
        $this->access->assertDeveloperPortalAccess($request->user());

        return view('developer-portal.webhook-deliveries.index', [
            'deliveries' => WebhookDelivery::query()->with('endpoint.client')->latest()->paginate(30),
        ]);
    }

    public function show(Request $request, WebhookDelivery $delivery)
    {
        $this->access->assertDeveloperPortalAccess($request->user());

        return view('developer-portal.webhook-deliveries.show', [
            'delivery' => $delivery->load('endpoint.client'),
        ]);
    }

    public function retry(Request $request, WebhookDelivery $delivery)
    {
        $this->access->assertCanManageClients($request->user());
        $delivery->update(['status' => 'retrying']);
        $this->deliveries->deliver($delivery);

        return redirect()->route('developer-portal.webhook-deliveries.show', $delivery)->with('success', 'Webhook delivery diproses ulang.');
    }
}
