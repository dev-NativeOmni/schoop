<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\School;

$mappings = [
    1 => ['slug' => 'smaia7', 'tenant_code' => 'smaia7'],
    2 => ['slug' => 'smaia6', 'tenant_code' => 'smaia6'],
    3 => ['slug' => 'smaia5', 'tenant_code' => 'smaia5'],
    4 => ['slug' => 'smaia4', 'tenant_code' => 'smaia4'],
    5 => ['slug' => 'smaia3', 'tenant_code' => 'smaia3'],
    6 => ['slug' => 'hpis', 'tenant_code' => 'hpis'],
];

foreach ($mappings as $id => $data) {
    $school = School::find($id);
    if ($school) {
        $school->update([
            'slug' => $data['slug'],
            'tenant_code' => $data['tenant_code'],
            'is_tenant_enabled' => true,
            'tenant_status' => 'active',
        ]);
        echo "Updated school {$school->name} with slug: {$data['slug']}\n";
    }
}
