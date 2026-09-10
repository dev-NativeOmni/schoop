<?php

use App\Models\Role;
use Illuminate\Contracts\Console\Kernel;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

foreach (Role::all() as $r) {
    echo $r->id.' | '.$r->name.' | '.$r->label."\n";
}
