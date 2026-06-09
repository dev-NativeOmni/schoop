# Phase 4 — Tahfizh Input Foundation

## Status

Phase 4 membangun fondasi input setoran tahfizh untuk HafizPlus School Platform.

## Output

1. Halaman daftar setoran tahfizh.
2. Halaman tambah setoran tahfizh.
3. Halaman detail setoran tahfizh.
4. Halaman edit setoran tahfizh.
5. Soft delete setoran tahfizh.
6. Validasi halaman dan baris mushaf.
7. Kalkulasi otomatis total baris.
8. Sequential guard awal.
9. Role-based access untuk setoran tahfizh.

## Route

Route yang dibuat:

1. `tahfizh.hafalan-records.index`
2. `tahfizh.hafalan-records.create`
3. `tahfizh.hafalan-records.store`
4. `tahfizh.hafalan-records.show`
5. `tahfizh.hafalan-records.edit`
6. `tahfizh.hafalan-records.update`
7. `tahfizh.hafalan-records.destroy`

## Role Access

| Role | Index | Show | Create | Store | Edit | Update | Delete |
|---|---|---|---|---|---|---|---|
| Super Admin | Ya | Ya | Ya | Ya | Ya | Ya | Ya |
| Admin Sekolah | Ya | Ya | Ya | Ya | Ya | Ya | Ya |
| Kepala Sekolah | Ya | Ya | Tidak | Tidak | Tidak | Tidak | Tidak |
| Guru Tahfidz | Ya | Ya | Ya | Ya | Miliknya | Miliknya | Miliknya |
| Orang Tua | Tidak | Tidak | Tidak | Tidak | Tidak | Tidak | Tidak |
| Santri | Tidak | Tidak | Tidak | Tidak | Tidak | Tidak | Tidak |

## Aturan Mushaf

Sistem memakai aturan:

```text
1 halaman = 15 baris
Total halaman = 604 halaman
```

Validasi:

1. Halaman minimal 1.
2. Halaman maksimal 604.
3. Baris minimal 1.
4. Baris maksimal 15.
5. Halaman akhir tidak boleh lebih kecil dari halaman awal.
6. Pada halaman yang sama, baris akhir tidak boleh lebih kecil dari baris awal.
7. Total baris dihitung otomatis.

## Sequential Guard

Aturan sequential guard awal:

1. Jika santri belum punya setoran, titik awal bebas.
2. Jika santri sudah punya setoran, setoran berikutnya harus mulai dari posisi setelah setoran terakhir.
3. Jika setoran terakhir halaman 1 baris 5, maka setoran berikutnya harus mulai halaman 1 baris 6.
4. Jika setoran terakhir halaman 1 baris 15, maka setoran berikutnya harus mulai halaman 2 baris 1.
5. Setoran lompat ditolak.

## Status Setoran Phase 4

Status yang aktif pada Phase 4:

1. `lunas`
2. `kurang`
3. `lebih`

Status berikut belum diaktifkan di UI Phase 4:

1. `tidak_hadir`
2. `izin`
3. `sakit`

Alasannya: struktur `hafalan_records` Phase 3 masih mewajibkan halaman dan baris. Absensi tahfizh perlu desain khusus agar tidak memaksa input halaman/baris palsu.

## Belum Dibuat

Phase 4 belum membuat:

1. Hutang hafalan otomatis.
2. Target achievement otomatis.
3. Report bulanan.
4. Report triwulan.
5. Dashboard statistik.
6. Parent progress detail.
7. Student progress detail.
8. Notification real.
9. Export PDF.
10. Export Excel.
11. API mobile.

## Definition of Done

Phase 4 selesai jika:

1. Route setoran tahfizh tersedia.
2. Menu Setoran Tahfizh muncul untuk Super Admin, Admin, Guru, dan Kepala Sekolah.
3. Super Admin bisa CRUD setoran.
4. Admin bisa CRUD setoran.
5. Guru bisa tambah setoran.
6. Guru hanya bisa edit/hapus setoran miliknya.
7. Kepala Sekolah hanya bisa melihat setoran.
8. Orang Tua tidak bisa akses setoran admin/guru.
9. Santri tidak bisa akses setoran admin/guru.
10. Total baris dihitung otomatis.
11. Input halaman/baris invalid ditolak.
12. Setoran lompat ditolak.
13. Dokumentasi Phase 4 dibuat.
14. Build frontend berhasil.
