<?php

// Vercel serverless environment configuration
// Storage folder overrides (Vercel filesystem is read-only, except /tmp)
$_ENV['APP_STORAGE'] = '/tmp/storage';
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';
$_ENV['SESSION_DRIVER'] = 'cookie'; // Gunakan cookie untuk session agar stateless
$_ENV['LOG_CHANNEL'] = 'stderr';    // Log diarahkan ke stderr agar tampil di console Vercel

// Map Supabase Integration variables to Laravel database config
if (isset($_ENV['POSTGRES_HOST'])) {
    $_ENV['DB_CONNECTION'] = 'pgsql';
    $_ENV['DB_HOST'] = $_ENV['POSTGRES_HOST'];
    $_ENV['DB_PORT'] = $_ENV['POSTGRES_PORT'] ?? '5432';
    $_ENV['DB_DATABASE'] = $_ENV['POSTGRES_DATABASE'];
    $_ENV['DB_USERNAME'] = $_ENV['POSTGRES_USER'];
    $_ENV['DB_PASSWORD'] = $_ENV['POSTGRES_PASSWORD'];
}

// Buat direktori yang diperlukan di /tmp jika belum ada
if (!is_dir('/tmp/storage/framework/views')) {
    mkdir('/tmp/storage/framework/views', 0755, true);
}

require __DIR__ . '/../public/index.php';
