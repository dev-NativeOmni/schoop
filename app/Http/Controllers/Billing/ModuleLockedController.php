<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Services\Billing\CurrentSchoolResolver;
use App\Services\Billing\ModuleAccessService;
use App\Services\Billing\SubscriptionStatusService;
use App\Models\SystemModule;
use Illuminate\View\View;

class ModuleLockedController extends Controller
{
    public function __construct(
        private readonly CurrentSchoolResolver $schoolResolver,
        private readonly ModuleAccessService $moduleAccessService,
        private readonly SubscriptionStatusService $subscriptionStatusService,
    ) {}

    public function show(string $moduleKey): View
    {
        $user = auth()->user();
        $school = $this->schoolResolver->resolve($user);

        $module = SystemModule::query()->where('module_key', $moduleKey)->first();
        $moduleName = $module ? $module->name : ucfirst($moduleKey);

        $activeSubscription = null;
        $reason = 'Akses ke modul ini dibatasi.';
        
        if ($school) {
            $activeSubscription = $this->subscriptionStatusService->activeSubscriptionFor($school);
            $reason = $this->moduleAccessService->reasonForDeniedAccess($school, $moduleKey);
        }

        return view('billing.locked.module', compact('moduleKey', 'moduleName', 'school', 'activeSubscription', 'reason'));
    }
}
