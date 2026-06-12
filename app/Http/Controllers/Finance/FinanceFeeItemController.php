<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\StoreFinanceFeeItemRequest;
use App\Http\Requests\Finance\UpdateFinanceFeeItemRequest;
use App\Models\FinanceFeeCategory;
use App\Models\FinanceFeeItem;
use App\Services\Finance\FinanceAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FinanceFeeItemController extends Controller
{
    public function index(Request $request, FinanceAccessService $accessService): View
    {
        abort_unless($accessService->canViewFinanceReport($request->user()), 403);

        $items = FinanceFeeItem::query()
            ->with('category')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(30);

        return view('finance.fee-items.index', compact('items'));
    }

    public function create(Request $request, FinanceAccessService $accessService): View
    {
        abort_unless($accessService->canManageFinance($request->user()), 403);

        $categories = FinanceFeeCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('finance.fee-items.create', compact('categories'));
    }

    public function store(StoreFinanceFeeItemRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_required'] = $request->boolean('is_required', false);
        $data['is_active'] = $request->boolean('is_active', true);

        FinanceFeeItem::query()->create($data);

        return redirect()
            ->route('finance.fee-items.index')
            ->with('success', 'Item biaya berhasil dibuat.');
    }

    public function show(Request $request, FinanceFeeItem $feeItem, FinanceAccessService $accessService): View
    {
        abort_unless($accessService->canViewFinanceReport($request->user()), 403);

        $feeItem->load('category');

        return view('finance.fee-items.show', compact('feeItem'));
    }

    public function edit(Request $request, FinanceFeeItem $feeItem, FinanceAccessService $accessService): View
    {
        abort_unless($accessService->canManageFinance($request->user()), 403);

        $categories = FinanceFeeCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('finance.fee-items.edit', compact('feeItem', 'categories'));
    }

    public function update(UpdateFinanceFeeItemRequest $request, FinanceFeeItem $feeItem): RedirectResponse
    {
        $data = $request->validated();
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_required'] = $request->boolean('is_required', false);
        $data['is_active'] = $request->boolean('is_active', true);

        $feeItem->update($data);

        return redirect()
            ->route('finance.fee-items.index')
            ->with('success', 'Item biaya berhasil diperbarui.');
    }

    public function destroy(Request $request, FinanceFeeItem $feeItem, FinanceAccessService $accessService): RedirectResponse
    {
        abort_unless($accessService->canManageFinance($request->user()), 403);

        $feeItem->delete();

        return redirect()
            ->route('finance.fee-items.index')
            ->with('success', 'Item biaya berhasil dihapus.');
    }
}
