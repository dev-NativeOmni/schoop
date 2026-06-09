# Architecture Decision Record — HafizPlus School Platform

## ADR-001 — Laravel sebagai Backend Utama

### Keputusan

Menggunakan Laravel sebagai backend utama.

### Alasan

1. Cocok untuk sistem sekolah berbasis CRUD dan laporan.
2. Mendukung auth, middleware, policy, notification, queue, scheduler, dan API.
3. Mudah dikembangkan dari web app menjadi API.
4. Cocok dengan MySQL.
5. Cocok untuk Blade dan PWA.

### Konsekuensi

1. Controller tidak boleh menjadi tempat semua business logic.
2. Business logic penting harus dipindah ke service class.
3. Authorization harus memakai middleware, gate, dan policy.
4. Struktur folder harus dijaga sejak awal.

---

## ADR-002 — Laravel 12 sebagai Versi Awal

### Keputusan

Menggunakan Laravel 12.

### Alasan

1. Project baru harus memakai versi Laravel yang lebih layak untuk jangka panjang.
2. Laravel 11 sempat terkena Composer security blocking pada proses instalasi.
3. Laravel 12 sudah berhasil berjalan di environment developer.
4. Lebih baik mulai dari versi lebih baru daripada memulai produk baru dengan versi yang sudah bermasalah di setup lokal.

### Konsekuensi

1. Dependency harus dipilih yang kompatibel dengan Laravel 12.
2. Jangan memasang package lama tanpa cek kompatibilitas.
3. Jika package belum support Laravel 12, cari alternatif atau tunda.

---

## ADR-003 — MySQL sebagai Database Awal

### Keputusan

Menggunakan MySQL sebagai database utama.

### Alasan

1. Stabil.
2. Mudah di-host sendiri.
3. Cocok untuk data relasional sekolah.
4. Cocok untuk deployment di server sekolah.
5. Cocok dengan XAMPP/local development.

### Konsekuensi

1. Relasi foreign key harus rapi.
2. Index harus diperhatikan.
3. Data hafalan harus divalidasi agar tidak rusak.
4. Backup database wajib disiapkan.

---

## ADR-004 — Blade/PWA Sebelum Native Mobile

### Keputusan

Tidak membuat Android/iOS native di awal.

### Alasan

1. Workflow guru dan parent harus dibuktikan dulu.
2. Native app menambah biaya, waktu, dan kompleksitas.
3. Web responsive/PWA cukup untuk MVP.
4. Revisi dan deployment lebih cepat lewat web.

### Konsekuensi

1. UI mobile browser harus nyaman.
2. Teacher input harus ringan.
3. Parent portal harus responsive.
4. Santri portal harus mudah diakses dari HP.

---

## ADR-005 — Multi-Tenant Ditunda

### Keputusan

Multi-tenant penuh tidak dibuat di awal.

### Alasan

1. Target awal adalah satu sekolah/pilot.
2. Multi-tenant terlalu berisiko jika domain data belum stabil.
3. Isolasi data sekolah harus dirancang matang.

### Konsekuensi

1. Struktur database boleh disiapkan agar mudah ditambah `school_id` nanti.
2. White-label tidak dibuat dulu.
3. Billing tenant tidak dibuat dulu.
4. Custom domain/subdomain tidak dibuat dulu.

---

## ADR-006 — Cashless POS Ditunda

### Keputusan

Cashless kantin / merchant POS tidak dibuat di fase awal.

### Alasan

1. Menyentuh data uang.
2. Risiko salah saldo sangat tinggi.
3. Butuh audit, refund, settlement, dan keamanan transaksi.
4. Server down bisa mengganggu operasional kantin.

### Konsekuensi

1. Finance ledger manual boleh masuk nanti.
2. Wallet tidak dibuat di awal.
3. POS tidak dibuat di awal.
4. Payment gateway tidak dibuat di awal.

---

## ADR-007 — Auth dan Role Dirancang Sesuai Domain Sekolah

### Keputusan

Auth dan role akan dirancang berdasarkan domain sekolah, bukan mengikuti konsep generic team bawaan starter kit.

### Alasan

Produk ini membutuhkan role:

1. Super Admin.
2. Admin Sekolah.
3. Kepala Sekolah.
4. Guru Tahfidz.
5. Orang Tua.
6. Santri.

Konsep ini berbeda dari `team` generic pada banyak starter kit SaaS.

### Konsekuensi

1. Jangan memakai teams support bawaan tanpa keputusan eksplisit.
2. Role harus dibuat sesuai kebutuhan sekolah.
3. Relasi user dengan sekolah, guru, orang tua, dan santri harus dirancang sendiri.
