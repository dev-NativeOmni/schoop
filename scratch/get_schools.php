<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (App\Models\School::all() as $s) {
    echo $s->id . ' | ' . $s->name . ' | ' . $s->slug . ' | ' . $s->tenant_code . "\n";
}
