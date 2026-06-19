<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (App\Models\SchoolDomainMapping::all() as $m) {
    echo $m->id . ' | ' . $m->school_id . ' | ' . $m->domain . ' | ' . $m->status . "\n";
}
