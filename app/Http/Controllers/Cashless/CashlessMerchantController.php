<?php

namespace App\Http\Controllers\Cashless;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cashless\StoreCashlessMerchantRequest;
use App\Http\Requests\Cashless\UpdateCashlessMerchantRequest;
use App\Models\CashlessMerchant;
use App\Models\CashlessMerchantUser;
use App\Models\User;
use App\Services\Cashless\CashlessAccessService;
use App\Services\Cashless\CashlessAuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CashlessMerchantController extends Controller
{
    public function __construct(private readonly CashlessAccessService $access, private readonly CashlessAuditService $audit) {}

    public function index(): View
    {
        $schoolId = $this->access->activeSchoolId(auth()->user());
        $merchants = CashlessMerchant::query()->where('school_id', $schoolId)->latest()->paginate(20);

        return view('cashless.merchants.index', compact('merchants'));
    }

    public function create(): View
    {
        $this->access->canManage(auth()->user()) ?: abort(403);
        $schoolId = $this->access->activeSchoolId(auth()->user());
        $users = User::query()->where('school_id', $schoolId)->orWhereHas('schoolMemberships', fn ($q) => $q->where('school_id', $schoolId))->orderBy('name')->get();

        return view('cashless.merchants.create', compact('users'));
    }

    public function store(StoreCashlessMerchantRequest $request): RedirectResponse
    {
        $schoolId = $this->access->activeSchoolId($request->user());
        $data = $request->validated();
        $merchant = CashlessMerchant::query()->create(array_merge($data, ['school_id' => $schoolId]));
        $this->syncCashiers($merchant, $data['cashier_user_ids'] ?? []);
        $this->audit->log($schoolId, $request->user(), 'merchant.created', $merchant);

        return redirect()->route('cashless.merchants.show', $merchant)->with('success', 'Merchant berhasil dibuat.');
    }

    public function show(CashlessMerchant $merchant): View
    {
        $this->access->assertSameSchool(auth()->user(), (int) $merchant->school_id);
        $merchant->load(['users.user', 'products']);

        return view('cashless.merchants.show', compact('merchant'));
    }

    public function edit(CashlessMerchant $merchant): View
    {
        $this->access->assertSameSchool(auth()->user(), (int) $merchant->school_id);
        $users = User::query()->where('school_id', $merchant->school_id)->orderBy('name')->get();
        $assignedUserIds = $merchant->users()->pluck('user_id')->all();

        return view('cashless.merchants.edit', compact('merchant', 'users', 'assignedUserIds'));
    }

    public function update(UpdateCashlessMerchantRequest $request, CashlessMerchant $merchant): RedirectResponse
    {
        $this->access->assertSameSchool($request->user(), (int) $merchant->school_id);
        $data = $request->validated();
        $merchant->update($data);
        $this->syncCashiers($merchant, $data['cashier_user_ids'] ?? []);
        $this->audit->log((int) $merchant->school_id, $request->user(), 'merchant.updated', $merchant);

        return redirect()->route('cashless.merchants.show', $merchant)->with('success', 'Merchant berhasil diperbarui.');
    }

    private function syncCashiers(CashlessMerchant $merchant, array $userIds): void
    {
        CashlessMerchantUser::query()->where('cashless_merchant_id', $merchant->id)->delete();

        foreach (array_unique($userIds) as $userId) {
            CashlessMerchantUser::query()->create([
                'school_id' => $merchant->school_id,
                'cashless_merchant_id' => $merchant->id,
                'user_id' => $userId,
                'role' => 'cashier',
                'can_refund' => false,
                'is_active' => true,
            ]);
        }
    }
}
