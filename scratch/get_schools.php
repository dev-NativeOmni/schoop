<?php

use App\Models\School;
use Illuminate\Contracts\Console\Kernel;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

foreach (School::all() as $s) {
    echo $s->id.' | '.$s->name.' | '.$s->slug.' | '.$s->tenant_code."\n";
}
