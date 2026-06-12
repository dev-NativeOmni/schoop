<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\StoreFinanceFeeCategoryRequest;
use App\Http\Requests\Finance\UpdateFinanceFeeCategoryRequest;
use App\Models\FinanceFeeCategory;
use App\Services\Finance\FinanceAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FinanceFeeCategoryController extends Controller
{
    public function index(Request $request, FinanceAccessService $accessService): View
    {
        abort_unless($accessService->canViewFinanceReport($request->user()), 403);

        $categories = FinanceFeeCategory::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20);

        return view('finance.fee-categories.index', compact('categories'));
    }

    public function create(Request $request, FinanceAccessService $accessService): View
    {
        abort_unless($accessService->canManageFinance($request->user()), 403);

        return view('finance.fee-categories.create');
    }

    public function store(StoreFinanceFeeCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active', true);

        FinanceFeeCategory::query()->create($data);

        return redirect()
            ->route('finance.fee-categories.index')
            ->with('success', 'Kategori biaya berhasil dibuat.');
    }

    public function show(Request $request, FinanceFeeCategory $feeCategory, FinanceAccessService $accessService): View
    {
        abort_unless($accessService->canViewFinanceReport($request->user()), 403);

        $feeCategory->load('feeItems');

        return view('finance.fee-categories.show', compact('feeCategory'));
    }

    public function edit(Request $request, FinanceFeeCategory $feeCategory, FinanceAccessService $accessService): View
    {
        abort_unless($accessService->canManageFinance($request->user()), 403);

        return view('finance.fee-categories.edit', compact('feeCategory'));
    }

    public function update(UpdateFinanceFeeCategoryRequest $request, FinanceFeeCategory $feeCategory): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active', true);

        $feeCategory->update($data);

        return redirect()
            ->route('finance.fee-categories.index')
            ->with('success', 'Kategori biaya berhasil diperbarui.');
    }

    public function destroy(Request $request, FinanceFeeCategory $feeCategory, FinanceAccessService $accessService): RedirectResponse
    {
        abort_unless($accessService->canManageFinance($request->user()), 403);

        $feeCategory->delete();

        return redirect()
            ->route('finance.fee-categories.index')
            ->with('success', 'Kategori biaya berhasil dihapus.');
    }
}
