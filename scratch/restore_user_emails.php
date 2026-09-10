<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Contracts\Console\Kernel;

$emailMapping = [
    'admin@hafizplus.test' => 'admin@smaia7',
    'kepalasekolah@hafizplus.test' => 'kepalasekolah@smaia7',
    'guru@hafizplus.test' => 'guru@smaia7',
    'guru2@hafizplus.test' => 'guru2@smaia7',
    'ortu@hafizplus.test' => 'ortu@smaia7',
    'ortu2@hafizplus.test' => 'ortu2@smaia7',
    'santri@hafizplus.test' => 'santri@smaia7',
    'santri2@hafizplus.test' => 'santri2@smaia7',
    'santri3@hafizplus.test' => 'santri3@smaia7',
    'pembina@hafizplus.test' => 'pembina@smaia7',
];

foreach ($emailMapping as $old => $new) {
    $user = User::query()->where(['email' => $old])->first();
    if ($user) {
        $user->update(['email' => $new]);
        echo "Updated email: {$old} -> {$new}\n";
    } else {
        // Try searching by username if email is already changed or different
        $username = explode('@', $old)[0];
        $userByUsername = User::query()->where(['username' => $username])->first();
        if ($userByUsername) {
            $userByUsername->update(['email' => $new]);
            echo "Updated email by username ({$username}): -> {$new}\n";
        }
    }
}
