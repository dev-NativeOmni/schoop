<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Role;
use App\Models\School;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

$school = School::query()->where(['code' => 'ALAZHAR7'])->first();
if (!$school) {
    $school = School::first();
}

$roles = Role::all()->keyBy(fn($role) => $role->name);
$plainPassword = 'password';
$defaultPassword = Hash::make($plainPassword);

$usersToSeed = [
    [
        'email' => 'finance@hafizplus.test',
        'role_name' => 'finance',
        'school_id' => $school->id,
        'name' => 'Finance Keuangan',
        'username' => 'finance',
    ],
    [
        'email' => 'cashier@hafizplus.test',
        'role_name' => 'cashier',
        'school_id' => $school->id,
        'name' => 'Kasir Sekolah',
        'username' => 'cashier',
    ],
    [
        'email' => 'merchant@hafizplus.test',
        'role_name' => 'merchant',
        'school_id' => $school->id,
        'name' => 'Kantin Merchant',
        'username' => 'merchant',
    ],
    [
        'email' => 'support@hafizplus.test',
        'role_name' => 'support_staff',
        'school_id' => null,
        'name' => 'Support Staff',
        'username' => 'support',
    ],
    [
        'email' => 'cs@hafizplus.test',
        'role_name' => 'customer_success',
        'school_id' => null,
        'name' => 'Customer Success',
        'username' => 'cs',
    ],
    [
        'email' => 'sales@hafizplus.test',
        'role_name' => 'sales',
        'school_id' => null,
        'name' => 'Sales Representative',
        'username' => 'sales',
    ],
    [
        'email' => 'ops@hafizplus.test',
        'role_name' => 'operations_manager',
        'school_id' => null,
        'name' => 'Operations Manager',
        'username' => 'ops',
    ],
];

foreach ($usersToSeed as $data) {
    if (!isset($roles[$data['role_name']])) {
        echo "Role {$data['role_name']} not found, skipping.\n";
        continue;
    }

    $user = User::query()->updateOrCreate(
        ['email' => $data['email']],
        [
            'role_id' => $roles[$data['role_name']]->id,
            'school_id' => $data['school_id'],
            'name' => $data['name'],
            'username' => $data['username'],
            'phone' => null,
            'password' => $defaultPassword,
            'is_active' => true,
        ]
    );

    echo "Seeded user {$user->name} ({$data['role_name']}) with email: {$user->email}\n";
}
