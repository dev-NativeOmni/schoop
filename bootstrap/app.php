<?php

use App\Http\Middleware\CheckRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(prepend: [
            \App\Http\Middleware\ResolveTenantFromDomain::class,
        ]);
        $middleware->alias([
            'role' => CheckRole::class,
            'tenant.resolve' => \App\Http\Middleware\ResolveTenantContext::class,
            'tenant.access' => \App\Http\Middleware\EnsureTenantAccess::class,
            'mobile.auth' => \App\Http\Middleware\AuthenticateMobileAccessToken::class,
            'mobile.tenant' => \App\Http\Middleware\EnsureMobileTenantContext::class,
            'mobile.version' => \App\Http\Middleware\EnsureMobileAppVersion::class,
            'mobile.response' => \App\Http\Middleware\MobileApiResponseMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
