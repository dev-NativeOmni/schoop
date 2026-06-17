# Panduan Setup & Jalankan Projek HafizPlus School Platform

Panduan ini berisi langkah-langkah detail untuk memasang, mengonfigurasi, dan menjalankan proyek **HafizPlus School Platform** pada perangkat/komputer baru agar berfungsi utuh dan sama persis.

---

## Prasyarat Lingkungan (Prerequisites)
Pastikan perangkat baru Anda telah terpasang:
1. **PHP >= 8.2** (Rekomendasi PHP 8.2 / 8.3)
2. **Composer** (Package Manager PHP)
3. **Node.js & NPM** (LTS Version)
4. **MySQL / MariaDB** (Untuk basis data lokal)
5. **Git**

---

## Langkah Setup & Instalasi (Step-by-Step)

### 1. Dapatkan Kode Sumber (Clone / Pull)
Jika mengambil dari repositori Git baru, jalankan:
```bash
git clone https://github.com/dev-NativeOmni/hafizplus-school-platform.git
cd hafizplus-school-platform
```
Dan pastikan Anda berada di branch yang diinginkan (misal `phase-25-ai-assisted-quran-learning`):
```bash
git checkout phase-25-ai-assisted-quran-learning
```

### 2. Salin Konfigurasi Lingkungan (`.env`)
Salin berkas `.env.example` untuk membuat file konfigurasi lingkungan lokal Anda:
```bash
cp .env.example .env
```
Buka file `.env` yang baru dibuat di editor teks, lalu sesuaikan pengaturan database Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hafizplus_school_platform
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Instal Dependensi Backend (PHP Composer)
Jalankan perintah berikut untuk mengunduh dan menyinkronkan seluruh pustaka backend Laravel:
```bash
composer install
```

### 4. Instal Dependensi Frontend (Node NPM)
Jalankan perintah ini untuk menginstal pustaka Tailwind CSS, Alpine.js, dan Vite bundler:
```bash
npm install
```

### 5. Generate Application Encryption Key
Laravel memerlukan kunci enkripsi unik untuk mengamankan data sesi dan sandi terdekripsi. Generate key tersebut dengan perintah:
```bash
php artisan key:generate
```

### 6. Migrasi & Seed Database lokal
Pastikan server database MySQL Anda sudah berjalan (misal menggunakan XAMPP, Laragon, atau Docker). Kemudian buat database kosong bernama `hafizplus_school_platform` dan jalankan perintah:
```bash
php artisan migrate --seed
```
*Catatan: Parameter `--seed` akan otomatis mempopulasikan database dengan data awal sekolah, data user master, feature flags, dan template feedback AI.*

### 7. Hubungkan Storage Link (Symlink)
Laravel menyimpan berkas unggahan di direktori privat secara default. Jalankan perintah ini untuk menghubungkannya agar gambar (seperti logo sekolah/brand) bisa diakses di browser secara publik:
```bash
php artisan storage:link
```

### 8. Kompilasi Aset Frontend (CSS & JS)
Kompilasi aset CSS dan JavaScript menggunakan Vite:
- **Untuk Production (Kompilasi Sekali):**
  ```bash
  npm run build
  ```
- **Untuk Development (Real-time Compilation/HMR):**
  ```bash
  npm run dev
  ```

---

## Menjalankan Server Lokal (Local Server Run)

Untuk mengakses web di browser lokal Anda:
1. **Jalankan Laravel Dev Server:**
   ```bash
   php artisan serve
   ```
2. Buka tautan yang muncul (biasanya [http://127.0.0.1:8000](http://127.0.0.1:8000)) pada browser Anda.
3. Gunakan kredensial user hasil seeder untuk login.

---

## Menjalankan Pengujian Integrasi (Testing)
Untuk memastikan seluruh modul dan logika program berjalan aman dan tidak ada kerusakan fungsional, jalankan seluruh rangkaian uji coba (PHPUnit) dengan perintah:
```bash
php artisan test
```
Untuk menguji modul AI (Phase 25) secara spesifik:
```bash
php artisan test --filter Phase25AiAssistedQuranIntegrationTest
```
