# Dokumen Peran & Hak Akses UI (Roles & UI Permissions Matrix)

Dokumen ini memetakan seluruh peran (roles) yang ada di platform **HafizPlus**, daftar modul/fitur yang dapat diakses di backend, serta elemen navigasi menu (UI) yang tampil pada Sidebar Kanan mereka masing-masing.

---

## 1. Ringkasan Hak Akses Menu Navigasi (UI Matrix)

| Nama Peran (Role) | Menu Utama yang Tampil di UI / Sidebar | Keterangan Akses Istimewa |
| :--- | :--- | :--- |
| **Super Admin** | Dashboard, Al-Qur'an, SchoolOS, Data Master, Tenancy & Branding, Tahfizh, Mutabaah, Kehadiran, Tahsin, LMS Belajar, Keuangan, Cashless System, SaaS Operations, Developer Portal, Boarding | Akses absolut dan penuh ke seluruh platform (SaaS-wide & Tenant-wide). |
| **Admin Sekolah** | Dashboard, Al-Qur'an, SchoolOS, Data Master, Tenancy & Branding, Tahfizh, Mutabaah, Kehadiran, Tahsin, LMS Belajar, Keuangan, Cashless System, Developer Portal, Boarding | Akses penuh untuk manajemen operasional di level Sekolah (Tenant) aktif. |
| **Kepala Sekolah** | Dashboard, Al-Qur'an, SchoolOS, Tenancy & Branding, Tahfizh, Mutabaah, Kehadiran, Tahsin, LMS Belajar, Keuangan, Cashless System, Boarding | Pemantauan (monitoring) laporan di seluruh modul tenant, serta persetujuan perizinan boarding. |
| **Guru (Pengajar / Guru Tahfidz)** | Dashboard, Al-Qur'an, SchoolOS, Tahfizh, Mutabaah, Kehadiran, Tahsin, LMS Belajar | Mengisi setoran hafalan, mutabaah harian, kehadiran kelas/sesi, dan input nilai tahsin. |
| **Staf Keuangan (Finance)** | Dashboard, Al-Qur'an, Keuangan, Cashless System | Pengelolaan tagihan, pembayaran, kategori biaya, serta mutasi/settlement dompet santri. |
| **Kasir & Merchant** | Dashboard, Al-Qur'an, Cashless System | Membuka sesi POS (Point of Sale), transaksi belanja santri, dan pemantauan transaksi kantin. |
| **Pembina Asrama (Boarding Supervisor)** | Dashboard, Al-Qur'an, Boarding | Absensi roll-call asrama, log kesehatan, log kedisiplinan, dan verifikasi perizinan keluar/masuk asrama. |
| **Wali Santri (Parent)** | Dashboard, Al-Qur'an, Portal Wali | Melihat grafik progres tahfizh anak, mutabaah, kehadiran, tagihan keuangan, transaksi cashless, dan LMS anak. |
| **Santri (Student)** | Dashboard, Al-Qur'an, Portal Santri | Melihat data progres hafalan pribadi, mutabaah mandiri, log kehadiran, tugas LMS, dan saku cashless. |

---

## 2. Rincian Akses & Menu per Peran

### 1. Super Admin
* **Dashboard**: Dasbor Utama dengan statistik platform keseluruhan.
* **SchoolOS**: Dashboard SchoolOS, Tahun Ajaran, Module Registry, School Settings.
* **Data Master**: Kelola Sekolah (tenant baru), Kelola User, Kelas, Guru, Siswa.
* **Tenancy & Branding**: Switcher Sekolah, User Memberships, Tenant Settings, Tenant Modules, White-Label Builder (logo/tema), Tenant Audit Logs.
* **SaaS Operations**: Scale Dashboard, Subscription Plans, School Subscriptions, Tenant Invoices, Implementation Projects, Support Tickets, Incidents, Release Notes, Knowledge Base, CS Notes, Snapshots.
* **Developer Portal**: Developer Dashboard, API Clients, API Scopes, Partner Integrations, Webhooks, Request Logs, API Docs.
* **Tahfizh**: Input Setoran, Setoran Hafalan, Target Hafalan, Hutang Hafalan, Laporan Bulanan & Triwulan.
* **Mutabaah**: Input Mutabaah, Kelola Aktivitas.
* **Kehadiran**: Kartu QR Siswa, Scanner Kehadiran, Input Manual, Sesi Kehadiran.
* **Tahsin**: Profil Tahsin, Penilaian Siswa, Kelola Jenjang & Keterampilan.
* **LMS**: Kursus Belajar, Laporan Progress.
* **Keuangan**: Kategori Biaya, Item Biaya, Tagihan & Pembayaran Siswa.
* **Cashless**: Merchant, Produk, Wallet Santri, Top Up, Refund/Void, Settlement, POS Kasir, Session POS.
* **Boarding**: Asrama, Kamar, Ranjang, Pembina Asrama, Penempatan Santri, Perizinan, Health & Discipline Log, Roll Call.

### 2. Admin Sekolah
* **Dashboard**: Dasbor khusus level sekolah.
* **SchoolOS**: Dashboard SchoolOS, Tahun Ajaran, Module Registry, School Settings.
* **Data Master**: Kelas, Guru, Siswa (Akses terbatas ke sekolah aktif).
* **Tenancy & Branding**: Tenant Dashboard, switcher, memberships, settings, white-label builder.
* **Tahfizh, Mutabaah, Kehadiran, Tahsin, LMS**: Akses penuh untuk manajemen input dan pembuatan konfigurasi modul.
* **Keuangan**: Kelola tarif biaya, tagihan siswa, dan laporan penerimaan.
* **Cashless**: Kelola merchant kantin sekolah, top up saku santri, refund, dan transaksi kasir.
* **Boarding**: Mengelola pembina, ranjang asrama, kamar, log, dan perizinan.

### 3. Kepala Sekolah (Principal)
* **Dashboard & SchoolOS**: Dashboard monitoring.
* **Tenancy**: Dashboard & Switcher Sekolah.
* **Tahfizh & Tahsin**: Dashboard Laporan, Target Hafalan, Hutang Hafalan, Laporan Bulanan & Triwulan (Read-only / approval).
* **Mutabaah & Kehadiran**: Dashboard Laporan Kehadiran & Mutabaah.
* **Keuangan & Cashless**: Dashboard Laporan keuangan & saku.
* **Boarding**: Dashboard Boarding, Penempatan Santri, Perizinan, Health Log, Discipline Log, Roll Call, Laporan Boarding.

### 4. Guru (Teacher)
* **Dashboard & SchoolOS**: Dasbor guru dengan modul kelas aktif.
* **Tahfizh**: Dashboard Laporan, Input Setoran Hafalan, Target Hafalan, Hutang Hafalan, Laporan Bulanan & Triwulan.
* **Mutabaah**: Input Mutabaah Harian Siswa.
* **Kehadiran**: Dashboard Laporan, Kartu QR Siswa, Scanner Kehadiran, Input Manual, Sesi Kehadiran.
* **Tahsin**: Profil Tahsin Siswa, Penilaian Siswa.
* **LMS**: Dashboard LMS, Kursus Belajar, Laporan Progress.

### 5. Staf Keuangan (Finance)
* **Dashboard**: Dasbor finansial.
* **Keuangan**: Dashboard Laporan, Tagihan Siswa, Pembayaran Siswa, Kategori Biaya, Item Biaya.
* **Cashless**: Dashboard Laporan, Merchant, Produk, Wallet Santri, Top Up, Refund/Void, Settlement.

### 6. Kasir & Merchant
* **Dashboard**: Dasbor transaksi kasir/kantin.
* **Cashless**: Dashboard Laporan, POS Kasir, Session POS (untuk transaksi belanja santri).

### 7. Pembina Asrama (Boarding Supervisor)
* **Dashboard**: Dasbor asrama.
* **Boarding**: Dashboard Boarding, Penempatan Santri, Perizinan (verifikasi izin santri pulang/keluar), Health Log, Discipline Log, Roll Call (absensi malam asrama), Laporan Boarding.

### 8. Wali Santri (Parent)
* **Dashboard**: Dasbor wali santri dengan daftar anak.
* **Portal Wali**:
  * Progres Tahfizh anak
  * Mutabaah anak (log ibadah harian anak)
  * Kehadiran anak
  * Tahsin anak (nilai tajwid/bacaan)
  * Keuangan anak (info tagihan SPP & riwayat pembayaran)
  * Cashless anak (riwayat transaksi saku & limit harian belanja anak)
  * Boarding anak (log kamar asrama, izin keluar, log kesehatan & kedisiplinan)
  * LMS anak (tugas & materi pelajaran)

### 9. Santri (Student)
* **Dashboard**: Dasbor pribadi siswa.
* **Portal Santri**:
  * Progres Tahfizh saya
  * Mutabaah saya (input ibadah mandiri)
  * Kehadiran saya
  * Tahsin saya
  * Keuangan saya
  * Cashless saya (sisa saldo saku)
  * Boarding saya (info kamar asrama & pengajuan perizinan)
  * LMS saya (mengerjakan kuis/tugas & membaca materi)
