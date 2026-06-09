# Phase 0 Execution Guide — HafizPlus School Platform

## 0. Identitas Eksekusi

Dokumen ini adalah instruksi eksekusi untuk AI coding agent di code editor.

Agent harus menjalankan pekerjaan Phase 0 untuk proyek baru:

**Nama Produk:** HafizPlus School Platform
**Produk Pertama:** Tahfizh Monitoring App
**Nama Folder Project:** `hafizplus-school-platform`
**Nama Database:** `hafizplus_school_platform`
**Framework:** Laravel 12
**Database:** MySQL
**Status Proyek:** Proyek mandiri, bukan bagian dari HafizPlus 2.0 atau HafizPlus 3.0.

---

## 1. Aturan Utama untuk AI Agent

AI agent wajib mematuhi aturan berikut.

### 1.1 Jangan Anggap Ini Lanjutan HafizPlus Lama

Proyek ini bukan:

* HafizPlus 2.0
* HafizPlus 3.0
* HafizApp lama
* Clone dari repo lama
* Refactor dari proyek sebelumnya

Proyek ini adalah produk baru:

> HafizPlus School Platform

HafizPlus 2.0 hanya boleh diperlakukan sebagai referensi pengalaman, bukan sebagai basis kode.

---

### 1.2 Jangan Membuat Fitur Phase 1 Dulu

Pada Phase 0, agent **tidak boleh** membuat fitur domain utama seperti:

* CRUD Santri
* CRUD Guru
* CRUD Kelas
* Input Setoran
* Target Hafalan
* Hutang Hafalan
* Report Bulanan
* Dashboard Guru
* Dashboard Admin
* Dashboard Kepala Sekolah
* Parent Portal
* Student Portal
* Notification Center
* API Tahfizh
* Multi-tenant logic

Phase 0 hanya untuk:

1. Validasi project Laravel berjalan.
2. Konfigurasi environment.
3. Dokumentasi pondasi produk.
4. Struktur awal folder dokumentasi.
5. Keputusan arsitektur.
6. Role permission planning.
7. Data protection planning.
8. Git commit awal.

---

### 1.3 Jangan Memasang Stack yang Belum Dibutuhkan

Agent tidak boleh memasang:

* React
* Vue
* Inertia
* Native Android
* Native iOS
* Flutter
* Payment gateway
* WhatsApp gateway
* Websocket
* Redis queue production setup
* Full LMS module
* Cashless POS
* White-label builder
* Multi-tenant package
* AI voice correction package

Stack awal harus tetap sederhana:

* Laravel 12
* Blade
* MySQL
* Vite
* Tailwind bawaan jika sudah tersedia
* Auth nanti di Phase berikutnya, bukan dipaksakan di Phase 0 jika belum diminta

---

## 2. Tujuan Phase 0

Phase 0 bertujuan membuat fondasi produk agar pengembangan tidak asal coding.

Output utama Phase 0:

1. Project Laravel 12 berjalan.
2. Database local siap.
3. `.env` benar.
4. App key sudah dibuat.
5. Migration default berhasil.
6. Git repository siap.
7. Folder `docs` tersedia.
8. Dokumen produk dan arsitektur awal tersedia.
9. Role matrix terdokumentasi.
10. Scope MVP terdokumentasi.
11. Batasan fitur terdokumentasi.
12. Checklist Phase 0 selesai.

---

## 3. Kondisi Awal yang Sudah Diketahui

Environment developer:

```text
OS: Windows
Local server path: C:\xampp\htdocs
PHP: 8.2.12
Composer: 2.9.2
Laravel project: Laravel 12 sudah berhasil jalan
Database target: MySQL via XAMPP
Project folder: C:\xampp\htdocs\hafizplus-school-platform
```

Catatan penting:

Sebelumnya Laravel installer terbaru gagal karena starter kit terbaru membutuhkan PHP 8.3+. Karena itu proyek dibuat menggunakan Laravel 12 dengan Composer, bukan `laravel new` starter kit terbaru.

---

## 4. Struktur Project yang Diharapkan

Pastikan project berada di:

```text
C:\xampp\htdocs\hafizplus-school-platform
```

Struktur minimal Laravel harus seperti ini:

```text
hafizplus-school-platform/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── tests/
├── .env
├── artisan
├── composer.json
├── package.json
└── vite.config.js
```

Jika struktur ini belum ada, hentikan eksekusi dan laporkan.

---

## 5. Validasi Project Laravel

Jalankan command berikut dari root project:

```powershell
cd C:\xampp\htdocs\hafizplus-school-platform
php artisan --version
```

Target:

```text
Laravel Framework 12.x.x
```

Lalu cek:

```powershell
php -v
composer -V
node -v
npm -v
```

Jika `node` atau `npm` belum tersedia, catat sebagai blocker untuk build frontend. Jangan menghapus project.

---

## 6. Konfigurasi `.env`

Buka file:

```text
.env
```

Pastikan konfigurasi utama seperti ini:

```env
APP_NAME="HafizPlus School Platform"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

APP_LOCALE=id
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=id_ID

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hafizplus_school_platform
DB_USERNAME=root
DB_PASSWORD=
```

Jika MySQL XAMPP memakai password, sesuaikan `DB_PASSWORD`.

Jangan commit file `.env`.

---

## 7. Generate App Key

Jalankan:

```powershell
php artisan key:generate
```

After success, make sure `APP_KEY` in `.env` is filled.

---

## 8. Database Local

Database yang harus digunakan:

```text
hafizplus_school_platform
```

Jika database belum dibuat, buat melalui phpMyAdmin or MySQL client:

```sql
CREATE DATABASE hafizplus_school_platform
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

Jangan memakai database lama:

```text
hafizplus_2
hafizplus
hafizapp
hafizplus_3
```

Database harus bersih untuk proyek baru.

---

## 9. Migration Default Laravel

Jalankan:

```powershell
php artisan migrate
```

Target:

Migration default Laravel berhasil.

Jika gagal karena database belum ada, buat database terlebih dahulu.

Jika gagal karena koneksi MySQL, cek:

1. Apache/XAMPP tidak wajib untuk artisan.
2. MySQL XAMPP harus aktif.
3. DB_DATABASE benar.
4. DB_USERNAME benar.
5. DB_PASSWORD sesuai.

---

## 10. Frontend Dependency

Jalankan:

```powershell
npm install
npm run build
```

Jika `npm install` gagal karena Node belum ada, catat sebagai blocker.

Jangan mengganti stack frontend tanpa keputusan eksplisit.

---

## 11. Jalankan Development Server

Jalankan:

```powershell
php artisan serve
```

Buka:

```text
http://127.0.0.1:8000
```

Target:

Halaman Laravel tampil tanpa error.

Jika ingin menjalankan Vite development:

```powershell
npm run dev
```

Gunakan terminal terpisah.

---

## 12. Buat Folder Dokumentasi

Pastikan folder berikut ada:

```text
docs/
```

Jika belum ada, buat:

```powershell
mkdir docs
```

---

## 13. File Dokumentasi yang Wajib Ada

Agent harus membuat file berikut:

```text
docs/project-identity.md
docs/phase-0-product-foundation.md
docs/role-permission-matrix.md
docs/architecture-decision-record.md
docs/data-protection-plan.md
docs/mvp-scope.md
docs/phase-0-checklist.md
docs/phase-0-execution.md
```

Catatan:

File `docs/phase-0-execution.md` adalah file ini.
