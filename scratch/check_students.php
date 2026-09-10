<?php

use App\Models\CashlessMerchant;
use App\Models\ParentProfile;
use App\Models\School;
use App\Models\Student;
use Illuminate\Contracts\Console\Kernel;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

echo "=== SCHOOLS ===\n";
foreach (School::all() as $s) {
    echo "ID: {$s->id} | Name: {$s->name} | Code: {$s->code}\n";
}

echo "\n=== MERCHANTS ===\n";
foreach (CashlessMerchant::all() as $m) {
    echo "ID: {$m->id} | Name: {$m->name} | Code: {$m->code}\n";
}

echo "\n=== PARENTS ===\n";
foreach (ParentProfile::with('user', 'students')->get() as $p) {
    echo "ID: {$p->id} | Name: {$p->full_name} | User: {$p->user->name} | Email: {$p->user->email}\n";
    foreach ($p->students as $std) {
        echo "  - Child Student ID: {$std->id} | Name: {$std->full_name}\n";
    }
}

echo "\n=== STUDENTS & WALLETS ===\n";
foreach (Student::with('user', 'cashlessWallet')->get() as $std) {
    $w = $std->cashlessWallet;
    $walletStr = $w ? "Wallet ID: {$w->id} | Balance: {$w->balance} | Limit: ".($w->daily_limit ?? 'None').' | Has PIN: '.($w->pin ? 'Yes' : 'No') : 'No Wallet';
    echo "ID: {$std->id} | Name: {$std->full_name} | User: ".($std->user?->name ?? 'None')." | {$walletStr}\n";
}
