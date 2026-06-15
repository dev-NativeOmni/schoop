<?php

namespace App\Http\Middleware;

use App\Services\Mobile\MobileDeviceService;
use App\Services\Mobile\MobileTenantService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMobileTenantContext
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $school = app(MobileTenantService::class)->resolveActiveSchool($request);
        $request->attributes->set('mobile_school', $school);

        $device = $request->attributes->get('mobile_device');

        if ($device) {
            app(MobileDeviceService::class)->markSeen($device, $request, (int) $school->id);
        }

        $token = $request->attributes->get('mobile_access_token');

        if ($token && (int) $token->school_id !== (int) $school->id) {
            $token->forceFill(['school_id' => $school->id])->save();
        }

        return $next($request);
    }
}
