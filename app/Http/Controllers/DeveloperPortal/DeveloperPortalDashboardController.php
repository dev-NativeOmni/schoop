<?php

namespace App\Http\Controllers\DeveloperPortal;

use App\Http\Controllers\Controller;
use App\Services\DeveloperPortal\ApiAccessService;
use App\Services\DeveloperPortal\DeveloperPortalService;
use Illuminate\Http\Request;

class DeveloperPortalDashboardController extends Controller
{
    public function __construct(
        private readonly ApiAccessService $access,
        private readonly DeveloperPortalService $portal,
    ) {}

    public function __invoke(Request $request)
    {
        $this->access->assertDeveloperPortalAccess($request->user());

        return view('developer-portal.dashboard', [
            'stats' => $this->portal->dashboard(),
        ]);
    }
}
