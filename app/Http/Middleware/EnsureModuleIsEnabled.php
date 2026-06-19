<?php

namespace App\Http\Middleware;

use App\Services\Billing\CurrentSchoolResolver;
use App\Services\Billing\ModuleAccessService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureModuleIsEnabled
{
    public function __construct(
        private readonly CurrentSchoolResolver $currentSchoolResolver,
        private readonly ModuleAccessService $moduleAccessService,
    ) {}

    public function handle(Request $request, Closure $next, string $moduleKey): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        if (method_exists($user, 'hasRole') && $user->hasRole(['super_admin'])) {
            return $next($request);
        }

        $school = $this->currentSchoolResolver->resolve($user);

        if (! $school) {
            abort(403, 'School context tidak ditemukan.');
        }

        if ($this->moduleAccessService->isEnabledForSchool($school, $moduleKey)) {
            return $next($request);
        }

        if (method_exists($user, 'hasRole') && $user->hasRole(['admin', 'admin_sekolah'])) {
            return redirect()->route('billing.locked.module', ['moduleKey' => $moduleKey]);
        }

        abort(403, 'Module tidak aktif untuk sekolah ini.');
    }
}
