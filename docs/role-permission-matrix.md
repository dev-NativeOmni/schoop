# Role Permission Matrix — HafizPlus School Platform

## 1. Role Utama

| Role | Fungsi Utama |
|---|---|
| Super Admin | Kelola sistem penuh |
| Admin Sekolah | Kelola data sekolah, kelas, santri, laporan |
| Kepala Sekolah | Monitoring aktivitas guru, santri, dan setorannya |
| Guru Tahfidz | Input setoran dan pantau target |
| Orang Tua | Lihat progres anak |
| Santri | Lihat progres pribadi |

---

## 2. Super Admin

### Boleh

1. Kelola seluruh sistem.
2. Kelola semua user.
3. Kelola semua role.
4. Kelola master data.
5. Melihat seluruh laporan.
6. Menghapus data penting.
7. Melihat audit log.
8. Melakukan backup database.
9. Mengatur konfigurasi sistem.

### Tidak Boleh Dilakukan Sembarangan

1. Menghapus data produksi tanpa backup.
2. Mengubah role user tanpa alasan jelas.
3. Menghapus audit log.
4. Menonaktifkan proteksi data tanpa dokumentasi.

---

## 3. Admin Sekolah

### Boleh

1. Kelola data sekolah.
2. Kelola kelas.
3. Kelola santri.
4. Kelola guru.
5. Kelola target hafalan.
6. Melihat laporan semua kelas.
7. Export laporan.
8. Kirim notifikasi.
9. Melihat dashboard admin.

### Tidak Boleh

1. Mengubah konfigurasi sistem sensitif.
2. Menghapus super admin.
3. Menghapus audit log.
4. Melihat data sekolah lain saat sistem sudah multi-tenant.
5. Mengakses konfigurasi server.

---

## 4. Kepala Sekolah

### Boleh

1. Melihat dashboard manajemen.
2. Melihat aktivitas guru.
3. Melihat progres santri.
4. Melihat jumlah setoran.
5. Melihat santri yang belum target.
6. Melihat performa kelas.
7. Melihat laporan bulanan.
8. Melihat laporan triwulan.
9. Export laporan manajemen jika diizinkan.

### Tidak Boleh

1. Input setoran harian kecuali juga diberi role guru.
2. Menghapus data hafalan.
3. Mengubah data teknis sistem.
4. Mengubah role user.
5. Mengakses konfigurasi super admin.

### Catatan

Role Kepala Sekolah lebih cocok sebagai role monitoring dan evaluasi, bukan role input utama.

---

## 5. Guru Tahfidz

### Boleh

1. Melihat santri yang diajar.
2. Input setoran hafalan.
3. Melihat target santri.
4. Melihat hutang hafalan santri.
5. Melihat riwayat setoran santri.
6. Melihat santri yang belum target.
7. Edit input dalam batas waktu tertentu.

### Tidak Boleh

1. Melihat semua data sekolah jika tidak ditugaskan.
2. Menghapus data permanen.
3. Mengubah role user.
4. Mengakses data finance.
5. Mengubah data master sekolah.

---

## 6. Orang Tua

### Boleh

1. Melihat data anak sendiri.
2. Melihat progres hafalan anak.
3. Melihat riwayat setoran anak.
4. Melihat target hafalan anak.
5. Melihat hutang hafalan anak.
6. Melihat notifikasi untuk dirinya.
7. Melihat laporan bulanan anak.

### Tidak Boleh

1. Melihat data anak lain.
2. Mengubah data setoran.
3. Menghapus data.
4. Mengakses dashboard guru.
5. Mengakses dashboard admin.
6. Mengakses dashboard kepala sekolah.

---

## 7. Santri

### Boleh

1. Melihat progres pribadi.
2. Melihat target pribadi.
3. Melihat hutang hafalan pribadi.
4. Melihat riwayat setoran pribadi.
5. Melihat catatan guru untuk dirinya.

### Tidak Boleh

1. Mengubah data setoran.
2. Melihat data santri lain.
3. Mengakses dashboard guru.
4. Mengakses dashboard admin.
5. Mengakses dashboard kepala sekolah.

---

## 8. Matrix Ringkas

| Fitur | Super Admin | Admin Sekolah | Kepala Sekolah | Guru Tahfidz | Orang Tua | Santri |
|---|---|---|---|---|---|---|
| Kelola user | Ya | Terbatas | Tidak | Tidak | Tidak | Tidak |
| Kelola role | Ya | Tidak | Tidak | Tidak | Tidak | Tidak |
| Kelola kelas | Ya | Ya | Lihat | Tidak | Tidak | Tidak |
| Kelola santri | Ya | Ya | Lihat | Lihat terbatas | Lihat anak sendiri | Lihat diri sendiri |
| Input setoran | Ya | Opsional | Tidak | Ya | Tidak | Tidak |
| Edit setoran | Ya | Ya | Tidak | Terbatas | Tidak | Tidak |
| Hapus setoran | Ya | Terbatas | Tidak | Tidak | Tidak | Tidak |
| Lihat report semua kelas | Ya | Ya | Ya | Tidak | Tidak | Tidak |
| Lihat report kelas ajar | Ya | Ya | Ya | Ya | Tidak | Tidak |
| Lihat progress anak | Ya | Ya | Ya | Ya | Ya, anak sendiri | Tidak |
| Lihat progress pribadi | Ya | Ya | Ya | Ya | Tidak | Ya |
| Export laporan | Ya | Ya | Terbatas | Terbatas | Tidak | Tidak |
| Kirim notifikasi | Ya | Ya | Tidak | Terbatas | Tidak | Tidak |
| Backup database | Ya | Tidak | Tidak | Tidak | Tidak | Tidak |
| Audit log | Ya | Lihat terbatas | Tidak | Tidak | Tidak | Tidak |
