<?php

namespace App\Http\Controllers\SaasOps;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaasOps\StoreSaasTenantInvoiceRequest;
use App\Http\Requests\SaasOps\StoreSaasTenantPaymentRequest;
use App\Models\SaasSchoolSubscription;
use App\Models\SaasTenantInvoice;
use App\Services\SaasOps\SaasOperationsAccessService;
use App\Services\SaasOps\TenantBillingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SaasTenantInvoiceController extends Controller
{
    public function __construct(private readonly SaasOperationsAccessService $access, private readonly TenantBillingService $billing) {}

    public function index(): View
    {
        abort_unless($this->access->canManageTenantInvoices(auth()->user()), 403);

        return view('saas-ops.tenant-invoices.index', ['invoices' => SaasTenantInvoice::query()->with('school')->latest()->paginate(20)]);
    }

    public function create(): View
    {
        abort_unless($this->access->canManageTenantInvoices(auth()->user()), 403);

        return view('saas-ops.tenant-invoices.create', ['subscriptions' => SaasSchoolSubscription::query()->with(['school', 'plan'])->latest()->get()]);
    }

    public function store(StoreSaasTenantInvoiceRequest $request): RedirectResponse
    {
        $subscription = SaasSchoolSubscription::query()->findOrFail($request->saas_school_subscription_id);
        $invoice = $this->billing->createDraftInvoice($subscription, $request->items, $request->due_date, $request->user(), $request->note);

        return redirect()->route('saas-ops.tenant-invoices.show', $invoice)->with('success', 'Draft invoice dibuat.');
    }

    public function show(SaasTenantInvoice $invoice): View
    {
        $this->access->assertCanAccessSchool(auth()->user(), (int) $invoice->school_id);
        $invoice->load(['school', 'subscription.plan', 'items', 'payments']);

        return view('saas-ops.tenant-invoices.show', compact('invoice'));
    }

    public function issue(SaasTenantInvoice $invoice): RedirectResponse
    {
        $this->billing->issueInvoice($invoice, auth()->user());

        return back()->with('success', 'Invoice di-issue.');
    }

    public function storePayment(StoreSaasTenantPaymentRequest $request, SaasTenantInvoice $invoice): RedirectResponse
    {
        $this->billing->postManualPayment($invoice, (int) $request->amount, $request->user(), $request->validated());

        return back()->with('success', 'Pembayaran manual dicatat.');
    }

    public function void(Request $request, SaasTenantInvoice $invoice): RedirectResponse
    {
        $request->validate(['reason' => ['required', 'string', 'min:5', 'max:1000']]);
        $this->billing->voidInvoice($invoice, $request->user(), $request->reason);

        return back()->with('success', 'Invoice divoid.');
    }
}
