<?php

namespace App\Http\Controllers\DeveloperPortal;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeveloperPortal\ApiRequestLogFilterRequest;
use App\Models\ApiRequestLog;
use App\Services\DeveloperPortal\ApiAccessService;
use Illuminate\Http\Request;

class ApiRequestLogController extends Controller
{
    public function __construct(private readonly ApiAccessService $access) {}

    public function index(ApiRequestLogFilterRequest $request)
    {
        $this->access->assertDeveloperPortalAccess($request->user());

        $logs = ApiRequestLog::query()
            ->with(['client', 'school'])
            ->when($request->filled('api_client_id'), fn ($query) => $query->where('api_client_id', $request->integer('api_client_id')))
            ->when($request->filled('status'), fn ($query) => $query->where('response_status', $request->integer('status')))
            ->when($request->filled('date_from'), fn ($query) => $query->whereDate('created_at', '>=', $request->date('date_from')))
            ->when($request->filled('date_until'), fn ($query) => $query->whereDate('created_at', '<=', $request->date('date_until')))
            ->latest('created_at')
            ->paginate(50)
            ->withQueryString();

        return view('developer-portal.request-logs.index', [
            'logs' => $logs,
        ]);
    }

    public function show(Request $request, ApiRequestLog $log)
    {
        $this->access->assertDeveloperPortalAccess($request->user());

        return view('developer-portal.request-logs.show', [
            'log' => $log->load(['client', 'school']),
        ]);
    }
}
