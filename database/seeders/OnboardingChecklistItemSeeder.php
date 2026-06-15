<?php

namespace Database\Seeders;

use App\Models\OnboardingChecklistItem;
use Illuminate\Database\Seeder;

class OnboardingChecklistItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'Data sekolah diterima',
            'Data admin sekolah diterima',
            'Data kelas diterima',
            'Data guru diterima',
            'Data santri diterima',
            'Data orang tua diterima',
            'Tenant dibuat',
            'Module aktif dikonfigurasi',
            'Branding dasar dikonfigurasi',
            'Admin training selesai',
            'Guru training selesai',
            'Parent portal diuji',
            'Tahfizh workflow diuji',
            'Report diuji',
            'Backup diuji',
            'Go-live approval diterima',
            'Handover ke support selesai',
        ];

        foreach ($items as $index => $title) {
            OnboardingChecklistItem::query()->updateOrCreate(
                ['title' => $title],
                ['category' => 'implementation', 'is_required' => true, 'sort_order' => $index + 1, 'is_active' => true]
            );
        }
    }
}
