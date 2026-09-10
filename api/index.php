<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Vercel serverless environment configuration
putenv('LARAVEL_STORAGE_PATH=/tmp/storage');
$_ENV['LARAVEL_STORAGE_PATH'] = '/tmp/storage';
$_SERVER['LARAVEL_STORAGE_PATH'] = '/tmp/storage';

putenv('LARAVEL_BOOTSTRAP_PATH=/tmp/storage/bootstrap');
$_ENV['LARAVEL_BOOTSTRAP_PATH'] = '/tmp/storage/bootstrap';
$_SERVER['LARAVEL_BOOTSTRAP_PATH'] = '/tmp/storage/bootstrap';

putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';
$_SERVER['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';

putenv('LOG_CHANNEL=stderr');
$_ENV['LOG_CHANNEL'] = 'stderr';
$_SERVER['LOG_CHANNEL'] = 'stderr';

putenv('SESSION_DRIVER=cookie');
$_ENV['SESSION_DRIVER'] = 'cookie';
$_SERVER['SESSION_DRIVER'] = 'cookie';

putenv('CACHE_STORE=array');
$_ENV['CACHE_STORE'] = 'array';
$_SERVER['CACHE_STORE'] = 'array';

putenv('FORCE_HTTPS=true');
$_ENV['FORCE_HTTPS'] = 'true';
$_SERVER['FORCE_HTTPS'] = 'true';

// Create writable temporary directories on Vercel cold-start
if (! is_dir('/tmp/storage/framework/views')) {
    $storageDirs = [
        '/tmp/storage/app/public',
        '/tmp/storage/app/private',
        '/tmp/storage/framework/views',
        '/tmp/storage/framework/sessions',
        '/tmp/storage/framework/cache',
        '/tmp/storage/framework/cache/data',
        '/tmp/storage/logs',
        '/tmp/storage/bootstrap',
        '/tmp/storage/bootstrap/cache',
    ];

    foreach ($storageDirs as $dir) {
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }

    if (file_exists(__DIR__.'/../bootstrap/providers.php') && ! file_exists('/tmp/storage/bootstrap/providers.php')) {
        copy(__DIR__.'/../bootstrap/providers.php', '/tmp/storage/bootstrap/providers.php');
    }
}

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

try {
    /** @var Application $app */
    $app = require_once __DIR__.'/../bootstrap/app.php';

    $request = Request::capture();
    $app->handleRequest($request);
} catch (Throwable $e) {
    error_log('[Vercel Fatal] '.$e->getMessage().' in '.$e->getFile().':'.$e->getLine());
    http_response_code(500);
    echo '<!DOCTYPE html><html><head><title>500 Internal Error</title><meta name="viewport" content="width=device-width, initial-scale=1"></head><body style="font-family:sans-serif;padding:2rem;background:#060b14;color:#f8fafc;">';
    echo '<h1 style="color:#f43f5e;">System Initialization Notice</h1>';
    echo '<p style="color:#cbd5e1;">'.htmlspecialchars($e->getMessage()).'</p>';
    echo '<p style="font-size:12px;color:#94a3b8;">'.htmlspecialchars($e->getFile()).':'.$e->getLine().'</p>';
    echo '</body></html>';
}
