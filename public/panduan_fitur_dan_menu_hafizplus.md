# Panduan Lengkap Fitur per Menu: HafizPlus School Platform

Dokumen ini memuat seluruh daftar modul dan submenu yang ada pada sidebar aplikasi **HafizPlus**, lengkap dengan fungsi spesifik masing-masing menu serta relasi dan integrasi datanya dengan modul lain.

---

## 1. Menu: Dashboard
* **Fitur**: **Dashboard Utama** (`/dashboard`)
  * **Fungsi**: Titik masuk utama (*smart redirector*) yang secara otomatis mengarahkan pengguna ke ringkasan kerja sesuai dengan peran akun (*Super Admin, Admin Sekolah, Kepala Sekolah, Guru, Orang Tua, Santri, Kasir, Bagian Keuangan, atau Tim SaaS-Ops*).
  * **Relasi dengan Fitur Lain**: Mengagregasi metrik cepat dari **Data Master**, **Tahfizh**, **Keuangan**, dan **Presensi**.

---

## 2. Menu: Al-Qur'an Mushaf
* **Fitur**: **Al-Qur'an Mushaf Digital & PDF** (`/mushaf`)
  * **Fungsi**: Menyediakan mushaf Al-Qur'an 30 juz interaktif lengkap dengan navigasi surah/ayat, terjemahan Kemenag RI, audio murottal per ayat, dan penampil (*viewer*) PDF Mushaf standar Rasm Utsmani Madinah 15 baris.
  * **Relasi dengan Fitur Lain**:
    * **Setoran Tahfizh**: Menjadi rujukan pembacaan ayat dan batas halaman saat guru menyimak setoran.
    * **Target Tahfizh**: Menentukan batas awal (*surah/ayat/halaman*) dan batas akhir target hafalan.
    * **AI Learning**: Menghubungkan rekomendasi latihan muraja'ah langsung ke teks ayat terkait.

---

## 3. Menu: SchoolOS
* **Fitur**: **Dashboard SchoolOS** (`/schoolos`)
  * **Fungsi**: Pusat kendali kesehatan operasional sekolah, ringkasan integrasi modul aktif, dan pencarian cepat data santri/guru secara global.
  * **Relasi dengan Fitur Lain**: Menghubungkan seluruh modul inti ke dalam satu arsitektur terintegrasi.
* **Fitur**: **Tahun Ajaran** (`/schoolos/academic-years`)
  * **Fungsi**: Mengatur kalender tahun ajaran (contoh: 2026/2027), status semester ganjil/genap, dan tanggal mulai/selesai periode belajar.
  * **Relasi dengan Fitur Lain**: Menjadi filter periode waktu untuk **Tagihan Keuangan**, **Penetapan Target Tahfizh**, **Asesmen Tahsin**, dan **Raport**.
* **Fitur**: **Module Registry** (`/schoolos/modules`)
  * **Fungsi**: Katalog status aktivasi seluruh modul sistem (inti dan opsional) pada instalasi platform.
  * **Relasi dengan Fitur Lain**: Terkoneksi dengan **Paket Langganan (Billing)** dan **Tenant Modules**.
* **Fitur**: **School Settings** (`/schoolos/settings`)
  * **Fungsi**: Konfigurasi parameter umum sekolah seperti nama resmi, NPSN, nomor kontak, alamat, dan zona waktu.
  * **Relasi dengan Fitur Lain**: Digunakan pada kop cetak dokumen di **Laporan Tahfizh**, **Kwitansi Keuangan**, dan **Kartu QR Santri**.

---

## 4. Menu: Data Master
* **Fitur**: **Sekolah** (`/master-data/schools`) *[Khusus Super Admin]*
  * **Fungsi**: Manajemen multi-sekolah/cabang pesantren yang terdaftar pada ekosistem platform.
  * **Relasi dengan Fitur Lain**: Entitas induk (*tenant*) yang menaungi seluruh data kelas, guru, santri, dan transaksi.
* **Fitur**: **Kelola User** (`/master-data/users`) *[Khusus Super Admin]*
  * **Fungsi**: Manajemen seluruh akun login sistem, pembuatan kredensial, penugasan role, dan reset kata sandi.
  * **Relasi dengan Fitur Lain**: Menjadi akun autentikasi untuk mengakses **Portal Guru, Wali, Santri, dan Admin**.
* **Fitur**: **Kelas / Halaqah** (`/master-data/class-rooms`)
  * **Fungsi**: Mengelola rombongan belajar kelas akademik maupun kelompok halaqah Al-Qur'an.
  * **Relasi dengan Fitur Lain**: Menjadi pengelompokan data santri pada **Tahfizh**, **Presensi**, **LMS**, dan **Asrama**.
* **Fitur**: **Guru / Asatidz** (`/master-data/teachers`)
  * **Fungsi**: Mengelola biodata ustadz/ustadzah, NIP, spesialisasi pengajaran, dan penugasan wali kelas/pembina halaqah.
  * **Relasi dengan Fitur Lain**: Memberikan otorisasi penginputan pada **Setoran Tahfizh**, **Tahsin**, **Presensi**, dan **Instruktur LMS**.
* **Fitur**: **Siswa / Santri** (`/master-data/students`)
  * **Fungsi**: Mengelola biodata santri (NISN, NIS, tanggal lahir, wali murid, status aktif/alumni).
  * **Relasi dengan Fitur Lain**: Entitas sentral yang terhubung ke **Student 360° Profile**, **Tahfizh**, **Presensi**, **Keuangan**, **Dompet Cashless**, dan **Kamar Asrama**.

---

## 5. Menu: Branding & Tenancy
* **Fitur**: **Tenant Dashboard** (`/tenancy`)
  * **Fungsi**: Ringkasan status tenancy, lisensi domain, dan identitas tenant yang sedang aktif.
  * **Relasi dengan Fitur Lain**: Terhubung dengan **SchoolOS** dan **Paket Langganan**.
* **Fitur**: **Ganti Sekolah** (`/tenancy/switch`)
  * **Fungsi**: Fitur *Tenant Switcher* untuk berpindah konteks antar-cabang sekolah tanpa perlu login ulang.
  * **Relasi dengan Fitur Lain**: Memperbarui variabel sesi `active_school_id` untuk seluruh modul lainnya.
* **Fitur**: **User Memberships** (`/tenancy/memberships`)
  * **Fungsi**: Mengatur keanggotaan pengguna lintas sekolah dan menentukan sekolah default.
  * **Relasi dengan Fitur Lain**: Terhubung ke **Master User** dan hak akses multi-sekolah.
* **Fitur**: **Tenant Settings** (`/tenancy/settings`)
  * **Fungsi**: Pengaturan preferensi teknis spesifik tenant (format nomor tagihan, kebijakan PIN dompet, kuota setoran).
  * **Relasi dengan Fitur Lain**: Menjadi variabel konfigurasi bagi **Finance** dan **Cashless**.
* **Fitur**: **Tenant Modules** (`/tenancy/modules`)
  * **Fungsi**: Memantau modul apa saja yang aktif pada sekolah yang bersangkutan berdasarkan paket langganan.
  * **Relasi dengan Fitur Lain**: Terhubung langsung ke **ModuleAccessService** dan **Billing**.
* **Fitur**: **White-Label Builder** (`/white-label`)
  * **Fungsi**: Pengaturan visual sekolah (unggah Logo Sekolah, Favicon, Latar Login kustom, palet warna primer/aksen, dan radius tombol/kartu).
  * **Relasi dengan Fitur Lain**: Mengubah tampilan antarmuka (**Navbar, Halaman Login, PDF Raport, Kartu Santri**) dan file manifest PWA mobile secara instan.
* **Fitur**: **Tenant Audit Logs** (`/tenancy/audit-logs`)
  * **Fungsi**: Rekam jejak keamanan (*audit trail*) atas setiap aktivitas perubahan data penting oleh staf sekolah.
  * **Relasi dengan Fitur Lain**: Merekam aksi modifikasi pada **Keuangan**, **Penghapusan Data Santri**, dan **Perubahan Nilai**.

---

## 6. Menu: Billing & Paket
* **Fitur**: **Paket Langganan** (`/billing/plans`)
  * **Fungsi**: Mengelola skema paket SaaS (*Starter, Pro, Enterprise*), harga bulanan/tahunan, dan limit kapasitas.
  * **Relasi dengan Fitur Lain**: Menentukan modul yang otomatis terbuka untuk sekolah di **Module Registry**.
* **Fitur**: **Langganan Sekolah** (`/billing/school-subscriptions`)
  * **Fungsi**: Mengelola status masa aktif sewa platform per sekolah (*active, trialing, expired, suspended*).
  * **Relasi dengan Fitur Lain**: Menjadi saklar utama pembatasan akses ke seluruh fitur operasional sekolah.
* **Fitur**: **Override Modul** (`/billing/module-overrides`)
  * **Fungsi**: Memberikan pengecualian khusus untuk membuka/mengunci modul tertentu pada sekolah tertentu di luar paket standarnya.
  * **Relasi dengan Fitur Lain**: Menimpa (*override*) aturan paket standar pada **ModuleAccessService**.

---

## 7. Menu: Tahfizh
* **Fitur**: **Dashboard Laporan Tahfizh** (`/reports/tahfizh/dashboard`)
  * **Fungsi**: Menampilkan grafik performa hafalan sekolah, tren setoran harian, santri teraktif, dan persentase pencapaian target.
  * **Relasi dengan Fitur Lain**: Menerima feed data dari seluruh **Setoran Hafalan** dan **Target Tahfizh**.
* **Fitur**: **Setoran Hafalan** (`/tahfizh/hafalan-records`)
  * **Fungsi**: Riwayat buku setoran lengkap (*ziyadah & muraja'ah*) santri beserta riwayat nilai kelancaran, fashahah, dan tajwid.
  * **Relasi dengan Fitur Lain**: Terhubung ke **Portal Santri** dan **Portal Wali** secara live.
* **Fitur**: **Input Setoran** (`/tahfizh/hafalan-records/create`)
  * **Fungsi**: Antarmuka cepat bagi asatidz untuk mencatat hafalan baru santri per ayat/halaman hanya dalam beberapa detik.
  * **Relasi dengan Fitur Lain**:
    * Mengurangi saldo **Hutang Tahfizh** secara otomatis.
    * Mengisi checklist pada **Mutabaah Yaumiyah**.
* **Fitur**: **Target Hafalan** (`/tahfizh/targets`)
  * **Fungsi**: Menetapkan target hafalan wajib santri (contoh: 1 halaman/hari, 1 juz/bulan).
  * **Relasi dengan Fitur Lain**: Menjadi tolok ukur perhitungan **Hutang Hafalan**.
* **Fitur**: **Hutang Hafalan** (`/tahfizh/debts`)
  * **Fungsi**: Memantau santri yang belum memenuhi kuota target hafalan periodik dan akumulasi tanggungan setoran.
  * **Relasi dengan Fitur Lain**: Mengirimkan sinyal peringatan ke **Portal Wali Santri** dan bahan evaluasi di **AI Practice Plan**.
* **Fitur**: **Laporan Bulanan & Triwulan** (`/reports/tahfizh/monthly` & `/reports/tahfizh/quarterly`)
  * **Fungsi**: Merekap data hafalan dalam format siap cetak PDF / Ekspor Excel sebagai Raport Tahfizh resmi.
  * **Relasi dengan Fitur Lain**: Menggunakan branding logo dan kop dari **White-Label**.

---

## 8. Menu: Mutabaah
* **Fitur**: **Dashboard Laporan Mutabaah** (`/mutabaah/reports/dashboard`)
  * **Fungsi**: Visualisasi grafik ketaatan ibadah santri (sholat 5 waktu, tilawah, dzikir, dhuha, tahajjud, puasa sunnah).
  * **Relasi dengan Fitur Lain**: Merekap data dari **Input Mutabaah** dan **Student 360° Profile**.
* **Fitur**: **Input Mutabaah** (`/mutabaah/daily`)
  * **Fungsi**: Checklist harian amalan yaumiyah santri (dapat diisi oleh santri, guru, atau musyrif asrama).
  * **Relasi dengan Fitur Lain**: Terintegrasi langsung dengan **Portal Wali Santri** untuk pemantauan karakter dari rumah.
* **Fitur**: **Kelola Aktivitas** (`/mutabaah/activities`)
  * **Fungsi**: Menambah, menyunting, atau menonaktifkan poin-poin ibadah dan pembiasaan adab yang dinilai.
  * **Relasi dengan Fitur Lain**: Memperbarui form input di **Input Mutabaah**.

---

## 9. Menu: Kehadiran (Attendance)
* **Fitur**: **Dashboard Laporan Kehadiran** (`/attendance/reports/dashboard`)
  * **Fungsi**: Analitik persentase presensi santri (Hadir, Sakit, Izin, Alpa) per sesi dan per kelas.
  * **Relasi dengan Fitur Lain**: Terhubung ke **Student 360° Profile** dan evaluasi kedisiplinan.
* **Fitur**: **Kartu QR Siswa** (`/attendance/qr-cards`)
  * **Fungsi**: Cetak massal kartu santri ber-QR code dengan token keamanan kriptografis dinamis.
  * **Relasi dengan Fitur Lain**: Mengambil data santri dari **Master Data** dan branding dari **White-Label**.
* **Fitur**: **Scanner Kehadiran** (`/attendance/scanner`)
  * **Fungsi**: Pemindai kamera langsung (laptop webcam / HP) untuk absensi presensi dalam hitungan milidetik.
  * **Relasi dengan Fitur Lain**: Menulis catatan presensi ke **Sesi Kehadiran** dan memicu notifikasi kehadiran.
* **Fitur**: **Input Manual** (`/attendance/manual/create`)
  * **Fungsi**: Penginputan atau koreksi presensi santri secara manual jika berhalangan scan QR.
  * **Relasi dengan Fitur Lain**: Terhubung ke perizinan pada modul **Boarding (Asrama)**.
* **Fitur**: **Sesi Kehadiran** (`/attendance/sessions`)
  * **Fungsi**: Pengaturan jadwal dan jenis sesi presensi (KBM Pagi, Halaqah Shubuh, Sholat Ashar, Apel Malam).
  * **Relasi dengan Fitur Lain**: Menjadi wadah pengelompokan data log scanner.

---

## 10. Menu: Tahsin
* **Fitur**: **Dashboard Laporan Tahsin** (`/tahsin/reports/dashboard`)
  * **Fungsi**: Grafik sebaran santri berdasarkan jilid/tingkat kemahiran membaca Al-Qur'an.
  * **Relasi dengan Fitur Lain**: Terkoneksi ke **Student 360° Profile**.
* **Fitur**: **Profil Tahsin Siswa** (`/tahsin/profiles`)
  * **Fungsi**: Riwayat individual santri mencakup jilid saat ini, tanggal kenaikan tingkat, dan catatan ustadz penguji.
  * **Relasi dengan Fitur Lain**: Menjadi rujukan kesiapan santri sebelum diizinkan masuk ke **Target Tahfizh**.
* **Fitur**: **Penilaian Siswa (Asesmen)** (`/tahsin/assessments`)
  * **Fungsi**: Formulir ujian kelulusan jilid/tingkat tahsin dengan rubrik penilaian makhraj, tajwid, dan kelancaran.
  * **Relasi dengan Fitur Lain**: Terbit di **Portal Santri** dan **Portal Orang Tua**.
* **Fitur**: **Kelola Jenjang & Keterampilan** (`/tahsin/levels` & `/tahsin/skills`)
  * **Fungsi**: Mengatur tingkatan tahsin (Jilid 1 s.d. Al-Qur'an Besar) serta indikator kompetensi tajwidnya.
  * **Relasi dengan Fitur Lain**: Menjadi opsi rubrik pada **Penilaian Siswa**.

---

## 11. Menu: LMS Belajar
* **Fitur**: **Dashboard LMS** (`/lms`)
  * **Fungsi**: Ringkasan kelas online aktif, jumlah materi terbit, dan tugas yang membutuhkan penilaian guru.
  * **Relasi dengan Fitur Lain**: Terhubung dengan **Data Master Guru & Siswa**.
* **Fitur**: **Kursus Belajar** (`/lms/courses`)
  * **Fungsi**: Manajemen kelas mata pelajaran/diniyah, silabus materi video/dokumen, penugasan santri, dan kuis online.
  * **Relasi dengan Fitur Lain**:
    * Santri mengakses dan mengumpulkan tugas di **Portal Santri**.
    * Nilai tugas terpantau di **Portal Wali Santri**.
* **Fitur**: **Laporan Progress LMS** (`/lms/reports`)
  * **Fungsi**: Rekapitulasi nilai tugas, skor kuis, dan persentase penyelesaian materi belajar santri.
  * **Relasi dengan Fitur Lain**: Mengisi data rekam akademik pada **Student 360° Profile**.

---

## 12. Menu: Keuangan
* **Fitur**: **Dashboard Laporan Keuangan** (`/finance/reports/dashboard`)
  * **Fungsi**: Visualisasi arus kas masuk SPP, total piutang/tunggakan santri, dan rekapitulasi penerimaan per pos biaya.
  * **Relasi dengan Fitur Lain**: Mengagregasi data dari seluruh **Tagihan Siswa** dan **Pembayaran Siswa**.
* **Fitur**: **Tagihan Siswa** (`/finance/bills`)
  * **Fungsi**: Penerbitan invoice biaya berkala (SPP Bulanan, Uang Gedung, Uang Makan, Seragam) per santri atau rombel.
  * **Relasi dengan Fitur Lain**: Muncul transparan pada menu **Keuangan Anak di Portal Wali Santri**.
* **Fitur**: **Pembayaran Siswa** (`/finance/payments`)
  * **Fungsi**: Pencatatan transaksi pelunasan tagihan, verifikasi bukti transfer, dan cetak kwitansi resmi.
  * **Relasi dengan Fitur Lain**: Mengubah status tagihan menjadi lunas dan mencatat kas masuk ke **Buku Kas / General Ledger**.
* **Fitur**: **Kategori & Item Biaya** (`/finance/fee-categories` & `/finance/fee-items`)
  * **Fungsi**: Master jenis dan tarif biaya yang berlaku di sekolah.
  * **Relasi dengan Fitur Lain**: Menjadi acuan pembuatan invoice di **Tagihan Siswa**.

---

## 13. Menu: Cashless System (Kantin & Smart Card)
* **Fitur**: **Dashboard Laporan Cashless** (`/cashless/reports/dashboard`)
  * **Fungsi**: Ringkasan perputaran uang non-tunai di kantin, volume transaksi, dan omzet per merchant.
  * **Relasi dengan Fitur Lain**: Terhubung dengan transaksi **POS Kasir** dan **Wallet Santri**.
* **Fitur**: **Merchant & Produk Merchant** (`/cashless/merchants` & `/cashless/products`)
  * **Fungsi**: Master stan kantin/koperasi, daftar makanan/minuman/kitab, harga, dan manajemen inventaris.
  * **Relasi dengan Fitur Lain**: Menjadi katalog menu pada antarmuka **POS Kasir**.
* **Fitur**: **Wallet Santri** (`/cashless/wallets`)
  * **Fungsi**: Pengelolaan saldo dompet digital santri, status kartu, dan pengaturan limit belanja harian.
  * **Relasi dengan Fitur Lain**: Orang tua dapat memantau saldo dan riwayat jajan santri di **Portal Wali Santri**.
* **Fitur**: **Top Up Wallet** (`/cashless/top-ups/create`)
  * **Fungsi**: Penambahan saldo dompet santri oleh kasir atau via setor tunai.
  * **Relasi dengan Fitur Lain**: Dapat diintegrasikan ke invoice penagihan pada **Modul Finance**.
* **Fitur**: **Refund / Void** (`/cashless/refunds/create`)
  * **Fungsi**: Pembatalan transaksi salah input atau pengembalian saldo santri.
  * **Relasi dengan Fitur Lain**: Mengembalikan saldo ke **Wallet Santri** dan mencatat jurnal koreksi.
* **Fitur**: **Settlement** (`/cashless/settlements`)
  * **Fungsi**: Rekonsiliasi dan pencairan dana bagi hasil dari rekening bersama sekolah ke pengelola kantin.
  * **Relasi dengan Fitur Lain**: Terhubung ke pencatatan kas keluar di **Modul Finance**.
* **Fitur**: **POS Kasir & Session POS** (`/cashless/pos/cashier` & `/cashless/pos-sessions`)
  * **Fungsi**: Antarmuka kasir layar sentuh (*touchscreen-friendly*) untuk transaksi cepat dengan scan kartu dan verifikasi PIN.
  * **Relasi dengan Fitur Lain**: Memotong saldo **Wallet Santri** seketika.

---

## 14. Menu: Boarding (Manajemen Asrama)
* **Fitur**: **Dashboard Boarding** (`/boarding`)
  * **Fungsi**: Ringkasan okupansi ranjang asrama, daftar santri yang sedang izin di luar, santri sakit, dan apel kamar malam.
  * **Relasi dengan Fitur Lain**: Mengagregasi data dari seluruh sub-fitur asrama.
* **Fitur**: **Asrama, Kamar, & Ranjang** (`/boarding/dormitories`, `/boarding/rooms`, `/boarding/beds`)
  * **Fungsi**: Master gedung asrama (putra/putri), denah kamar, dan kapasitas tempat tidur.
  * **Relasi dengan Fitur Lain**: Menjadi lokasi penempatan santri di **Penempatan Santri**.
* **Fitur**: **Penempatan Santri** (`/boarding/assignments`)
  * **Fungsi**: Alokasi santri ke nomor kamar/ranjang tertentu dan penugasan musyrif pembina kamar.
  * **Relasi dengan Fitur Lain**: Mengambil data santri dari **Master Data Siswa**.
* **Fitur**: **Perizinan Santri** (`/boarding/leave-requests`)
  * **Fungsi**: Alur pengajuan izin keluar/pulang santri, persetujuan musyrif/kepala asrama, dan tiket digital pos satpam (*Gate Pass*).
  * **Relasi dengan Fitur Lain**:
    * Diajukan dan dipantau oleh wali santri di **Portal Wali**.
    * Memvalidasi absensi santri pada **Modul Presensi Kehadiran**.

---

## 15. Menu: SaaS Ops (Khusus Pengelola Platform)
* **Fitur**: **Scale Dashboard** (`/saas-ops`)
  * **Fungsi**: Monitoring utilisasi serverless, volume traffic data, dan kesehatan multi-tenant sekolah.
  * **Relasi dengan Fitur Lain**: Memantau beban kerja database dan integrasi API seluruh sekolah.
* **Fitur**: **Plans, School Subs, & Invoices** (`/saas-ops/subscription-plans`, dll.)
  * **Fungsi**: Operasional komersial paket langganan dan penagihan B2B antar penyedia platform dengan pihak sekolah.
  * **Relasi dengan Fitur Lain**: Mengontrol status buka/tutup lisensi sekolah di **Billing**.
* **Fitur**: **Tickets, Incidents, & Releases** (`/saas-ops/support-tickets`, dll.)
  * **Fungsi**: Helpdesk penerimaan komplain admin sekolah, manajemen respon insiden bug/down, dan publikasi versi baru (*Release Notes*).
  * **Relasi dengan Fitur Lain**: Menjaga SLA layanan untuk seluruh admin tenant.

---

## 16. Menu: Developer Portal
* **Fitur**: **Dev Dashboard** (`/developer-portal/dashboard`)
  * **Fungsi**: Ringkasan performa integrasi API, total request harian, dan metrik throughput webhook.
  * **Relasi dengan Fitur Lain**: Memonitor koneksi sistem pihak ketiga ke database platform.
* **Fitur**: **API Clients** (`/developer-portal/api-clients`)
  * **Fungsi**: Pembuatan dan manajemen Client ID serta Secret Token API dengan pembatasan hak akses (*Bearer Scopes*).
  * **Relasi dengan Fitur Lain**: Jalur aman integrasi data **Tahfizh, Kehadiran, dan Keuangan** ke aplikasi mobile luar, bot WhatsApp, atau mesin fisik.
* **Fitur**: **API Docs** (`/developer-portal/docs`)
  * **Fungsi**: Dokumentasi teknis OpenAPI interaktif (endpoint REST, parameter request, dan contoh response JSON).
  * **Relasi dengan Fitur Lain**: Panduan bagi developer mitra integrasi sekolah.

---

## 17. Menu: Portal Wali (Khusus Akun Orang Tua)
* **Fitur**: **Progres Tahfizh** (`/portal/parent/dashboard`)
  * **Fungsi**: Melihat capaian juz hafalan anak, grafik setoran, dan status kelancaran harian secara realtime.
  * **Relasi dengan Fitur Lain**: Terhubung langsung ke modul **Tahfizh (Hafalan Records)**.
* **Fitur**: **Mutabaah Anak** (`/portal/parent/mutabaah/{id}`)
  * **Fungsi**: Melihat checklist ibadah harian anak di asrama/sekolah.
  * **Relasi dengan Fitur Lain**: Terhubung ke modul **Mutabaah**.
* **Fitur**: **Kehadiran Anak** (`/portal/parent/attendance`)
  * **Fungsi**: Memantau absensi harian KBM dan sholat berjamaah anak.
  * **Relasi dengan Fitur Lain**: Terhubung ke modul **Attendance**.
* **Fitur**: **Tahsin Anak** (`/portal/parent/tahsin`)
  * **Fungsi**: Melihat perkembangan jilid dan sertifikasi tahsin anak.
  * **Relasi dengan Fitur Lain**: Terhubung ke modul **Tahsin**.
* **Fitur**: **Keuangan Anak** (`/portal/parent/finance`)
  * **Fungsi**: Mengecek rincian tagihan SPP, mengunggah bukti bayar, dan mengunduh kwitansi lunas.
  * **Relasi dengan Fitur Lain**: Terhubung ke modul **Finance**.
* **Fitur**: **Cashless Anak** (`/portal/parent/cashless`)
  * **Fungsi**: Memantau sisa saldo dompet santri dan membatasi jajan harian anak di kantin.
  * **Relasi dengan Fitur Lain**: Terhubung ke modul **Cashless System**.
* **Fitur**: **Boarding Anak** (`/portal/parent/boarding`)
  * **Fungsi**: Mengajukan izin penjemputan/pulang santri dan melihat catatan kesehatan anak dari poskestren.
  * **Relasi dengan Fitur Lain**: Terhubung ke modul **Boarding**.
* **Fitur**: **LMS Anak** (`/portal/parent/lms`)
  * **Fungsi**: Memantau keaktifan belajar daring dan nilai tugas anak.
  * **Relasi dengan Fitur Lain**: Terhubung ke modul **LMS Belajar**.

---

## 18. Menu: Portal Santri (Khusus Akun Santri)
* **Fitur**: **Progres Tahfizh** (`/portal/student/dashboard`)
  * **Fungsi**: Dashboard personal santri untuk melihat target hafalan aktif, sisa hutang setoran, dan rekap ziyadah/muraja'ah.
  * **Relasi dengan Fitur Lain**: Terhubung ke modul **Tahfizh**.
* **Fitur**: **Mutabaah Saya** (`/portal/student/mutabaah`)
  * **Fungsi**: Lembar pengisian amalan ibadah mandiri santri.
  * **Relasi dengan Fitur Lain**: Terhubung ke modul **Mutabaah**.
* **Fitur**: **Kehadiran Saya** (`/portal/student/attendance`)
  * **Fungsi**: Menampilkan riwayat scan kehadiran dan kartu QR virtual santri.
  * **Relasi dengan Fitur Lain**: Terhubung ke modul **Attendance**.
* **Fitur**: **Tahsin Saya** (`/portal/student/tahsin`)
  * **Fungsi**: Rincian target kenaikan jilid tahsin dan catatan tajwid dari pengajar.
  * **Relasi dengan Fitur Lain**: Terhubung ke modul **Tahsin**.
* **Fitur**: **Keuangan Saya** (`/portal/student/finance`)
  * **Fungsi**: Informasi tagihan biaya sekolah santri.
  * **Relasi dengan Fitur Lain**: Terhubung ke modul **Finance**.
* **Fitur**: **Cashless Saya** (`/portal/student/cashless`)
  * **Fungsi**: Informasi saldo dompet jajan kantin dan riwayat struk belanja santri.
  * **Relasi dengan Fitur Lain**: Terhubung ke modul **Cashless System**.
* **Fitur**: **Boarding Saya** (`/portal/student/boarding`)
  * **Fungsi**: Informasi kamar/ranjang, jadwal piket, riwayat izin keluar, dan tiket barcode gerbang.
  * **Relasi dengan Fitur Lain**: Terhubung ke modul **Boarding**.
* **Fitur**: **LMS Saya** (`/portal/student/lms`)
  * **Fungsi**: Ruang belajar daring untuk mengunduh materi, mengerjakan tugas, dan mengikuti kuis online.
  * **Relasi dengan Fitur Lain**: Terhubung ke modul **LMS Belajar**.
