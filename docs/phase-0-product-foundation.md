# Phase 0 — Product Foundation HafizPlus School Platform

## 1. Identitas Produk

**Nama Produk:** HafizPlus School Platform

**Produk Pertama:** Tahfizh Monitoring App

**Status:** Proyek mandiri, bukan bagian dari HafizPlus 2.0 atau HafizPlus 3.0.

## 2. Arah Produk

HafizPlus School Platform adalah platform digital sekolah Islam yang dimulai dari monitoring tahfizh, lalu berkembang secara bertahap menjadi parent portal, notifikasi, dashboard manajemen, mutabaah, presensi, tahsin, finance ledger, SchoolOS Mini, multi-tenant, white-label, mobile app, dan cashless POS.

Produk ini tidak dimulai sebagai super app.

Produk pertama yang harus dibuat stabil adalah:

> Tahfizh Monitoring App yang dipakai harian oleh guru, dipantau oleh admin/kepala sekolah, dan bisa dilihat progresnya oleh orang tua serta santri.

## 3. Masalah Utama

Banyak sekolah Islam masih mengelola data tahfizh secara manual menggunakan buku, spreadsheet, atau laporan terpisah.

Masalah yang diselesaikan:

1. Guru sulit melihat riwayat setoran santri secara cepat.
2. Admin sekolah sulit membuat rekap kelas, bulanan, dan triwulan.
3. Kepala sekolah sulit memonitor aktivitas guru dan perkembangan santri secara objektif.
4. Orang tua tidak mendapat informasi progres anak secara rutin.
5. Santri tidak tahu posisi progres dan hutang hafalannya.
6. Target hafalan dan hutang hafalan masih dihitung manual.
7. Data rawan tercecer, duplikat, salah input, atau tidak konsisten.

## 4. Value Proposition

HafizPlus membantu sekolah Islam mengelola tahfizh secara digital, terstruktur, dan mudah dipantau oleh guru, admin, kepala sekolah, orang tua, dan santri.

## 5. Target Market

Target utama:

1. Sekolah Islam.
2. Sekolah tahfizh.
3. Pesantren modern.
4. Boarding school Islam.
5. SDIT, SMPIT, SMAIT.
6. Lembaga tahfizh nonformal.

Target awal:

1. Satu sekolah pilot.
2. Sekolah Islam yang punya program tahfizh aktif.
3. Pesantren atau boarding school yang membutuhkan monitoring hafalan.

## 6. Positioning Produk

HafizPlus bukan hanya aplikasi catatan hafalan.

HafizPlus diposisikan sebagai:

> Platform monitoring tahfizh dan aktivitas Qur’an untuk sekolah Islam, dengan dashboard guru, dashboard manajemen, dan portal orang tua.

## 7. Role Utama

| Role | Fungsi Utama |
|---|---|
| Super Admin | Kelola sistem penuh |
| Admin Sekolah | Kelola data sekolah, kelas, santri, laporan |
| Kepala Sekolah | Monitoring aktivitas guru, santri, dan setorannya |
| Guru Tahfidz | Input setoran dan pantau target |
| Orang Tua | Lihat progres anak |
| Santri | Lihat progres pribadi |

## 8. MVP Awal

Fitur wajib MVP:

1. Login role-based.
2. Master data santri.
3. Master data guru.
4. Master data kelas.
5. Master data program / strata.
6. Target hafalan.
7. Input setoran hafalan.
8. Validasi urutan hafalan.
9. Hitung hutang hafalan.
10. Dashboard guru.
11. Dashboard admin sekolah.
12. Dashboard kepala sekolah.
13. Report bulanan.
14. Report triwulan.
15. Parent portal awal.
16. Student portal awal.
17. Notifikasi dasar.
18. Audit log dasar.

## 9. Bukan MVP

Fitur berikut tidak dikerjakan di awal:

1. Full LMS.
2. Native Android.
3. Native iOS.
4. Payment gateway.
5. Cashless kantin.
6. White-label app builder.
7. Multi-tenant kompleks.
8. AI voice correction.
9. QR attendance.
10. Finance ledger.
11. Boarding management.
12. Marketplace konten.
13. Video learning platform.

## 10. Urutan Modul

Urutan pengembangan yang dikunci:

1. Tahfizh Monitoring App.
2. Teacher Daily Input.
3. Parent Portal.
4. Student Portal.
5. Notification Center.
6. Kepala Sekolah Dashboard.
7. Academic / Management Dashboard.
8. Mutabaah Yaumiyah.
9. QR Attendance.
10. Tahsin Management.
11. Student Finance Ledger.
12. Boarding School Management.
13. SchoolOS Mini.
14. Multi-Tenant Platform.
15. White-Label School App.
16. Mobile Android/iOS.
17. Cashless Kantin / Merchant POS.

## 11. Definition of Done Phase 0

Phase 0 selesai jika:

1. Project Laravel 12 berjalan.
2. Database local siap.
3. Environment `.env` benar.
4. Migration default berhasil.
5. Folder dokumentasi tersedia.
6. Identitas produk tertulis.
7. Scope MVP tertulis.
8. Role matrix tertulis.
9. ADR awal tertulis.
10. Data protection plan tertulis.
11. Git commit awal dibuat.
12. Tidak ada fitur Phase 1 yang dibuat sebelum Phase 0 selesai.

## 12. Keputusan Final

Produk pertama bukan super app.

Produk pertama adalah:

> Tahfizh Monitoring App yang stabil, cepat, dan dipakai harian untuk monitoring hafalan.

Jika workflow ini belum kuat, jangan tambah modul besar.
