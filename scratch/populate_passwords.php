<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Crypt;

echo "Populating password_plain for existing users...\n";

$count = 0;
foreach (User::all() as $user) {
    // We assume the default password is 'password' for seeded users
    $user->update([
        'password_plain' => Crypt::encryptString('password'),
    ]);
    $count++;
}

echo "Done! Populated {$count} users.\n";
