<?php

namespace Database\Seeders;

use App\Models\ApiDocumentationPage;
use Illuminate\Database\Seeder;

class ApiDocumentationPageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            ['getting-started', 'Getting Started', 'Overview', 'Gunakan Developer Portal untuk membuat API client, memilih scope, membuat token, dan menguji endpoint `/api/v1`.'],
            ['authentication', 'Authentication', 'Security', 'Kirim token melalui header `Authorization: Bearer {token}`. Token hanya ditampilkan sekali saat dibuat atau dirotasi.'],
            ['scopes', 'Scopes', 'Security', 'Setiap endpoint diproteksi scope granular seperti `students:read`, `attendance:write`, dan `webhooks:manage`.'],
            ['students', 'Students API', 'Reference', '`GET /api/v1/students` dan `GET /api/v1/students/{student}` mengembalikan data siswa tenant yang terikat pada API client.'],
            ['classes', 'Classes API', 'Reference', '`GET /api/v1/classes` mengembalikan daftar kelas dan wali kelas tenant.'],
            ['attendance', 'Attendance API', 'Reference', '`GET /api/v1/attendance-records` membaca presensi. `POST /api/v1/attendance-records` membuat catatan presensi baru.'],
            ['tahfizh', 'Tahfizh API', 'Reference', '`GET /api/v1/tahfizh/progress` mengembalikan ringkasan progress hafalan siswa.'],
            ['finance', 'Finance API', 'Reference', '`GET /api/v1/finance/bills` mengembalikan tagihan dan status pembayaran siswa.'],
            ['cashless', 'Cashless API', 'Reference', '`GET /api/v1/cashless/transactions` mengembalikan transaksi kantin atau merchant cashless.'],
            ['webhooks', 'Webhooks', 'Reference', 'Webhook dikirim memakai signature HMAC `X-HafizPlus-Signature` dan request id `X-HafizPlus-Delivery-Id`.'],
            ['errors', 'Errors', 'Reference', 'Semua error API memakai bentuk JSON konsisten berisi `success`, `message`, `errors`, dan `meta`.'],
            ['rate-limits', 'Rate Limits', 'Operations', 'Rate limit dihitung per API client per menit sesuai konfigurasi client. Respons limit memakai status 429.'],
            ['versioning', 'Versioning', 'Operations', 'Endpoint publik memakai prefix versi `/api/v1`. Perubahan breaking wajib dibuat pada versi baru.'],
        ];

        foreach ($pages as $index => [$slug, $title, $category, $content]) {
            ApiDocumentationPage::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $title,
                    'category' => $category,
                    'content' => $content,
                    'visibility' => 'partner',
                    'status' => 'published',
                    'sort_order' => ($index + 1) * 10,
                    'published_at' => now(),
                ],
            );
        }
    }
}
