<?php

// Vercel serverless environment configuration
// Storage folder overrides (Vercel filesystem is read-only, except /tmp)
$_ENV['APP_STORAGE'] = '/tmp/storage';
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';
$_ENV['SESSION_DRIVER'] = 'cookie'; // Gunakan cookie untuk session agar stateless
$_ENV['LOG_CHANNEL'] = 'stderr';    // Log diarahkan ke stderr agar tampil di console Vercel

// Buat direktori yang diperlukan di /tmp jika belum ada
if (!is_dir('/tmp/storage/framework/views')) {
    mkdir('/tmp/storage/framework/views', 0755, true);
}

require __DIR__ . '/../public/index.php';
