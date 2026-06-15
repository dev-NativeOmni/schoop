<?php

namespace App\Http\Middleware;

use App\Models\MobileAppVersion;
use App\Support\MobileApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMobileAppVersion
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $platform = $request->header('X-Mobile-Platform') ?: $request->input('platform');
        $currentVersion = $request->header('X-Mobile-Version') ?: $request->input('app_version');

        if (! $platform || ! $currentVersion) {
            return $next($request);
        }

        $version = MobileAppVersion::query()
            ->where('platform', $platform)
            ->where('is_active', true)
            ->latest('released_at')
            ->latest('id')
            ->first();

        if ($version && $version->minimum_supported_version && version_compare($currentVersion, $version->minimum_supported_version, '<')) {
            return MobileApiResponse::error(
                'Update aplikasi dibutuhkan.',
                [],
                426,
                [
                    'platform' => $platform,
                    'current_version' => $currentVersion,
                    'minimum_supported_version' => $version->minimum_supported_version,
                    'latest_version' => $version->version,
                    'force_update' => true,
                ]
            );
        }

        return $next($request);
    }
}
