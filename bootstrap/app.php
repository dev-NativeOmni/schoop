<?php

use App\Http\Middleware\ApplyApiRateLimit;
use App\Http\Middleware\AuthenticateApiClient;
use App\Http\Middleware\AuthenticateMobileAccessToken;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\EnsureApiScope;
use App\Http\Middleware\EnsureMobileAppVersion;
use App\Http\Middleware\EnsureMobileTenantContext;
use App\Http\Middleware\EnsureModuleIsEnabled;
use App\Http\Middleware\EnsureSubscriptionIsActive;
use App\Http\Middleware\EnsureTenantAccess;
use App\Http\Middleware\LogExternalApiRequest;
use App\Http\Middleware\MobileApiResponseMiddleware;
use App\Http\Middleware\ResolveTenantContext;
use App\Http\Middleware\ResolveTenantFromDomain;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\ApplicationBuilder;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = new Application(dirname(__DIR__));

if ($storagePath = env('LARAVEL_STORAGE_PATH', env('APP_STORAGE'))) {
    $app->useStoragePath($storagePath);
}

if ($bootstrapPath = env('LARAVEL_BOOTSTRAP_PATH')) {
    $app->useBootstrapPath($bootstrapPath);
}

return (new ApplicationBuilder($app))
    ->withKernels()
    ->withEvents()
    ->withCommands()
    ->withProviders()
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->web(append: [
            ResolveTenantFromDomain::class,
        ]);
        $middleware->alias([
            'role' => CheckRole::class,
            'tenant.resolve' => ResolveTenantContext::class,
            'tenant.access' => EnsureTenantAccess::class,
            'subscription.active' => EnsureSubscriptionIsActive::class,
            'module' => EnsureModuleIsEnabled::class,
            'mobile.auth' => AuthenticateMobileAccessToken::class,
            'mobile.tenant' => EnsureMobileTenantContext::class,
            'mobile.version' => EnsureMobileAppVersion::class,
            'mobile.response' => MobileApiResponseMiddleware::class,
            'api.client' => AuthenticateApiClient::class,
            'api.scope' => EnsureApiScope::class,
            'api.request_log' => LogExternalApiRequest::class,
            'api.rate_limit' => ApplyApiRateLimit::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
