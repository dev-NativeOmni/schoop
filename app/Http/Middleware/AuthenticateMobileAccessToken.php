<?php

namespace App\Http\Middleware;

use App\Services\Mobile\MobileTokenService;
use App\Support\MobileApiResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateMobileAccessToken
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = app(MobileTokenService::class)->resolveFromRequest($request);

        if (! $token || ! $token->user || ! $token->user->is_active) {
            return MobileApiResponse::error('Unauthorized', [], 401);
        }

        $request->attributes->set('mobile_access_token', $token);
        $request->attributes->set('mobile_device', $token->mobileDevice);
        $request->setUserResolver(fn () => $token->user);
        Auth::setUser($token->user);

        return $next($request);
    }
}
