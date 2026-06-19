<?php

namespace App\Http\Middleware;

use App\Services\Billing\CurrentSchoolResolver;
use App\Services\Billing\SubscriptionStatusService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscriptionIsActive
{
    public function __construct(
        private readonly CurrentSchoolResolver $currentSchoolResolver,
        private readonly SubscriptionStatusService $subscriptionStatusService,
    ) {}

    public function handle(Request $request, Closure $next): Response
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

        if (! $this->subscriptionStatusService->isActive($school)) {
            if (method_exists($user, 'hasRole') && $user->hasRole(['admin', 'admin_sekolah'])) {
                return redirect()->route('billing.locked.module', ['moduleKey' => 'subscription']);
            }

            abort(403, 'Subscription sekolah tidak aktif.');
        }

        return $next($request);
    }
}
