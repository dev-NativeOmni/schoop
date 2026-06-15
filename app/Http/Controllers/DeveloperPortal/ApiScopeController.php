<?php

namespace App\Http\Controllers\DeveloperPortal;

use App\Http\Controllers\Controller;
use App\Models\ApiScope;
use App\Services\DeveloperPortal\ApiAccessService;
use Illuminate\Http\Request;

class ApiScopeController extends Controller
{
    public function __construct(private readonly ApiAccessService $access) {}

    public function index(Request $request)
    {
        $this->access->assertDeveloperPortalAccess($request->user());

        return view('developer-portal.api-scopes.index', [
            'scopes' => ApiScope::query()->orderBy('sort_order')->orderBy('code')->paginate(50),
        ]);
    }

    public function show(Request $request, ApiScope $apiScope)
    {
        $this->access->assertDeveloperPortalAccess($request->user());

        return view('developer-portal.api-scopes.show', [
            'scope' => $apiScope->loadCount('clients'),
        ]);
    }
}
