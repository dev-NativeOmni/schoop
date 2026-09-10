<?php

namespace App\Http\Controllers\Tenancy;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenancy\TenantAuditLogFilterRequest;
use App\Models\TenantAuditLog;
use App\Services\Tenancy\TenantContextService;
use Illuminate\View\View;

class TenantAuditLogController extends Controller
{
    protected TenantContextService $contextService;

    public function __construct(TenantContextService $contextService)
    {
        $this->contextService = $contextService;
    }

    public function index(TenantAuditLogFilterRequest $request): View
    {
        $schoolId = $this->contextService->activeSchoolId();

        $query = TenantAuditLog::query()
            ->with(['user', 'school'])
            ->where('school_id', $schoolId);

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        if ($request->filled('action')) {
            $query->where('action', 'like', '%'.$request->input('action').'%');
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->input('end_date'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                    ->orWhere('auditable_type', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        $logs = $query->latest()->paginate(20)->withQueryString();

        return view('tenancy.audit-logs.index', compact('logs'));
    }
}
