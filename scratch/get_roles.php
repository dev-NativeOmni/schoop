<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (App\Models\Role::all() as $r) {
    echo $r->id . ' | ' . $r->name . ' | ' . $r->label . "\n";
}
