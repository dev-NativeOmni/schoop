<?php

namespace App\Http\Controllers\Cashless;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cashless\StoreCashlessProductRequest;
use App\Http\Requests\Cashless\UpdateCashlessProductRequest;
use App\Models\CashlessMerchant;
use App\Models\CashlessProduct;
use App\Services\Cashless\CashlessAccessService;
use App\Services\Cashless\CashlessAuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CashlessProductController extends Controller
{
    public function __construct(private readonly CashlessAccessService $access, private readonly CashlessAuditService $audit) {}

    public function index(): View
    {
        $schoolId = $this->access->activeSchoolId(auth()->user());
        $products = CashlessProduct::query()->with('merchant')->where('school_id', $schoolId)->latest()->paginate(30);

        return view('cashless.products.index', compact('products'));
    }

    public function create(): View
    {
        $schoolId = $this->access->activeSchoolId(auth()->user());
        $merchants = CashlessMerchant::query()->where('school_id', $schoolId)->where('status', 'active')->orderBy('name')->get();

        return view('cashless.products.create', compact('merchants'));
    }

    public function store(StoreCashlessProductRequest $request): RedirectResponse
    {
        $schoolId = $this->access->activeSchoolId($request->user());
        $data = $request->validated();
        $merchant = CashlessMerchant::query()->where('school_id', $schoolId)->findOrFail($data['cashless_merchant_id']);
        $product = CashlessProduct::query()->create(array_merge($data, [
            'school_id' => $schoolId,
            'cashless_merchant_id' => $merchant->id,
            'track_stock' => $request->boolean('track_stock'),
        ]));
        $this->audit->log($schoolId, $request->user(), 'product.created', $product);

        return redirect()->route('cashless.products.show', $product)->with('success', 'Produk berhasil dibuat.');
    }

    public function show(CashlessProduct $product): View
    {
        $this->access->assertSameSchool(auth()->user(), (int) $product->school_id);
        $product->load('merchant');

        return view('cashless.products.show', compact('product'));
    }

    public function edit(CashlessProduct $product): View
    {
        $this->access->assertSameSchool(auth()->user(), (int) $product->school_id);
        $merchants = CashlessMerchant::query()->where('school_id', $product->school_id)->orderBy('name')->get();

        return view('cashless.products.edit', compact('product', 'merchants'));
    }

    public function update(UpdateCashlessProductRequest $request, CashlessProduct $product): RedirectResponse
    {
        $this->access->assertSameSchool($request->user(), (int) $product->school_id);
        $data = $request->validated();
        $product->update(array_merge($data, ['track_stock' => $request->boolean('track_stock')]));
        $this->audit->log((int) $product->school_id, $request->user(), 'product.updated', $product);

        return redirect()->route('cashless.products.show', $product)->with('success', 'Produk berhasil diperbarui.');
    }
}
