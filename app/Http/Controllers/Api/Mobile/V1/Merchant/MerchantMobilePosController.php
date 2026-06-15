<?php

namespace App\Http\Controllers\Api\Mobile\V1\Merchant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Mobile\V1\MobileMerchantCheckoutRequest;
use App\Models\CashlessPosSession;
use App\Models\CashlessSale;
use App\Services\Mobile\MobileMerchantWorkflowService;
use App\Support\MobileApiResponse;
use Illuminate\Http\Request;

class MerchantMobilePosController extends Controller
{
    public function __construct(
        private readonly MobileMerchantWorkflowService $workflow,
    ) {}

    public function profile(Request $request)
    {
        return MobileApiResponse::ok($this->workflow->profile($request->user()));
    }

    public function products(Request $request)
    {
        return MobileApiResponse::ok([
            'products' => $this->workflow->products(
                $request->user(),
                $request->filled('merchant_id') ? $request->integer('merchant_id') : null
            ),
        ]);
    }

    public function openSession(Request $request)
    {
        $payload = $request->validate([
            'cashless_merchant_id' => ['required', 'integer', 'exists:cashless_merchants,id'],
            'shift_name' => ['nullable', 'string', 'max:100'],
            'opening_note' => ['nullable', 'string', 'max:2000'],
        ]);

        return MobileApiResponse::ok([
            'session' => $this->workflow->openSession($request->user(), $payload),
        ], 'Session POS dibuka.', status: 201);
    }

    public function closeSession(Request $request, CashlessPosSession $session)
    {
        $payload = $request->validate([
            'closing_note' => ['nullable', 'string', 'max:2000'],
        ]);

        return MobileApiResponse::ok([
            'session' => $this->workflow->closeSession($request->user(), $session, $payload['closing_note'] ?? null),
        ], 'Session POS ditutup.');
    }

    public function previewSale(MobileMerchantCheckoutRequest $request)
    {
        return MobileApiResponse::ok(
            $this->workflow->previewSale($request->user(), $request->validated())
        );
    }

    public function checkout(MobileMerchantCheckoutRequest $request)
    {
        return MobileApiResponse::ok([
            'sale' => $this->workflow->checkout($request->user(), $request->validated()),
        ], 'Checkout berhasil.', status: 201);
    }

    public function voidSale(Request $request, CashlessSale $sale)
    {
        $payload = $request->validate([
            'reason' => ['required', 'string', 'max:2000'],
        ]);

        return MobileApiResponse::ok([
            'refund' => $this->workflow->voidSale($request->user(), $sale, $payload['reason']),
        ], 'Transaksi berhasil di-void.');
    }

    public function sales(Request $request)
    {
        return MobileApiResponse::ok([
            'sales' => $this->workflow->sales($request->user()),
        ]);
    }

    public function settlements(Request $request)
    {
        return MobileApiResponse::ok([
            'settlements' => $this->workflow->settlements($request->user()),
        ]);
    }
}
