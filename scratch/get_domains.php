<?php

use App\Models\SchoolDomainMapping;
use Illuminate\Contracts\Console\Kernel;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

foreach (SchoolDomainMapping::all() as $m) {
    echo $m->id.' | '.$m->school_id.' | '.$m->domain.' | '.$m->status."\n";
}
