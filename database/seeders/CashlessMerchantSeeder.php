<?php

namespace Database\Seeders;

use App\Models\CashlessMerchant;
use App\Models\CashlessProduct;
use App\Models\Role;
use App\Models\School;
use Illuminate\Database\Seeder;

class CashlessMerchantSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['finance', 'cashier', 'merchant'] as $roleName) {
            Role::query()->firstOrCreate(
                ['name' => $roleName],
                ['label' => ucfirst($roleName), 'description' => 'Cashless '.$roleName.' role', 'is_active' => true]
            );
        }

        $school = School::query()->where('is_active', true)->orderBy('id')->first();

        if (! $school) {
            return;
        }

        $merchants = [
            ['name' => 'Kantin Utama', 'code' => 'KANTIN', 'type' => 'canteen'],
            ['name' => 'Koperasi Sekolah', 'code' => 'KOPERASI', 'type' => 'cooperative'],
        ];

        foreach ($merchants as $data) {
            $merchant = CashlessMerchant::query()->firstOrCreate(
                ['school_id' => $school->id, 'code' => $data['code']],
                array_merge($data, ['school_id' => $school->id, 'status' => 'active'])
            );

            $products = $merchant->code === 'KANTIN'
                ? [
                    ['name' => 'Air Mineral', 'sku' => 'AM-001', 'price' => 5000],
                    ['name' => 'Nasi Ayam', 'sku' => 'NA-001', 'price' => 15000],
                    ['name' => 'Roti', 'sku' => 'RT-001', 'price' => 7000],
                ]
                : [
                    ['name' => 'Buku Tulis', 'sku' => 'BT-001', 'price' => 6000],
                    ['name' => 'Pulpen', 'sku' => 'PP-001', 'price' => 4000],
                ];

            foreach ($products as $product) {
                CashlessProduct::query()->firstOrCreate(
                    ['school_id' => $school->id, 'cashless_merchant_id' => $merchant->id, 'sku' => $product['sku']],
                    array_merge($product, [
                        'school_id' => $school->id,
                        'cashless_merchant_id' => $merchant->id,
                        'stock' => 100,
                        'track_stock' => true,
                        'status' => 'active',
                    ])
                );
            }
        }
    }
}
