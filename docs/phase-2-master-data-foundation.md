# Phase 2 — Master Data Foundation

## Status

Phase 2 membangun master data dasar untuk HafizPlus School Platform.

## Output

1. CRUD Sekolah.
2. CRUD Kelas.
3. CRUD Guru Tahfidz.
4. CRUD Orang Tua.
5. CRUD Santri.
6. Relasi Orang Tua dan Santri.
7. Navigasi master data.
8. Validasi form menggunakan Form Request.
9. Restriksi akses menggunakan middleware role.

## Role Access

| Modul | Super Admin | Admin Sekolah | Kepala Sekolah | Guru | Orang Tua | Santri |
|---|---|---|---|---|---|---|
| Sekolah | CRUD | Tidak | Tidak | Tidak | Tidak | Tidak |
| Kelas | CRUD | CRUD | Tidak | Tidak | Tidak | Tidak |
| Guru | CRUD | CRUD | Tidak | Tidak | Tidak | Tidak |
| Orang Tua | CRUD | CRUD | Tidak | Tidak | Tidak | Tidak |
| Santri | CRUD | CRUD | Tidak | Tidak | Tidak | Tidak |

## Belum Dibuat

1. Input setoran hafalan.
2. Target hafalan.
3. Hutang hafalan.
4. Validasi urutan hafalan.
5. Report bulanan.
6. Report triwulan.
7. Dashboard statistik.
8. Parent progress detail.
9. Student progress detail.
10. Notifikasi real.

## Definition of Done

Phase 2 selesai jika:

1. Semua halaman master data bisa dibuka.
2. Data sekolah bisa dibuat, dilihat, diedit, dan dihapus oleh Super Admin.
3. Data kelas bisa dibuat, dilihat, diedit, dan dihapus oleh Admin/Super Admin.
4. Data guru bisa dibuat, dilihat, diedit, dan dihapus oleh Admin/Super Admin.
5. Data orang tua bisa dibuat, dilihat, diedit, dan dihapus oleh Admin/Super Admin.
6. Data santri bisa dibuat, dilihat, diedit, dan dihapus oleh Admin/Super Admin.
7. Relasi orang tua dan santri bisa disimpan.
8. Middleware role bekerja.
9. User tanpa akses mendapat 403.
10. Build frontend berhasil.
