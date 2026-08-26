<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Vercel serverless environment configuration
putenv('APP_DEBUG=true');
$_ENV['APP_DEBUG'] = 'true';
$_SERVER['APP_DEBUG'] = 'true';

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

// Buat direktori yang diperlukan di /tmp jika belum ada
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
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

if (file_exists(__DIR__ . '/../bootstrap/providers.php') && !file_exists('/tmp/storage/bootstrap/providers.php')) {
    copy(__DIR__ . '/../bootstrap/providers.php', '/tmp/storage/bootstrap/providers.php');
}
if (file_exists(__DIR__ . '/../bootstrap/cache/packages.php') && !file_exists('/tmp/storage/bootstrap/cache/packages.php')) {
    copy(__DIR__ . '/../bootstrap/cache/packages.php', '/tmp/storage/bootstrap/cache/packages.php');
}
if (file_exists(__DIR__ . '/../bootstrap/cache/services.php') && !file_exists('/tmp/storage/bootstrap/cache/services.php')) {
    copy(__DIR__ . '/../bootstrap/cache/services.php', '/tmp/storage/bootstrap/cache/services.php');
}

try {
    define('LARAVEL_START', microtime(true));

    require __DIR__.'/../vendor/autoload.php';

    /** @var Application $app */
    $app = require_once __DIR__.'/../bootstrap/app.php';

    $request = Request::capture();
    $app->handleRequest($request);
} catch (\Throwable $e) {
    header('Content-Type: text/plain', true, 500);
    echo "SERVER CRASH EXCEPTION: " . $e->getMessage() . "\nFile: " . $e->getFile() . ":" . $e->getLine() . "\n\nTrace:\n" . $e->getTraceAsString();
}
