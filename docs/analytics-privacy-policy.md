# Analytics Privacy Policy

Kebijakan Privasi Analitik ini menetapkan standar pengolahan data untuk modul Advanced Analytics & Executive Intelligence pada HafizPlus School Platform.

## 1. Tujuan Analytics (Objectives)
Tujuan utama modul analitik adalah menyediakan *decision intelligence* yang membantu manajemen sekolah dan pengelola platform internal (Super Admin/Operations) dalam memantau tren akademis, operasional, kesehatan finansial, serta kinerja teknis platform (SLA & API) tanpa membahayakan data pribadi sensitif milik civitas akademika.

## 2. Data yang Digunakan (Data in Use)
Data yang diolah untuk analitik dikelompokkan menjadi data teragregasi yang bersumber dari:
- **Tren Akademis**: Ringkasan persentase kelulusan target hafalan, tingkat kelulusan level tahsin, dan tingkat kepatuhan mutabaah yaumiyah.
- **Kinerja Operasional**: Angka absensi harian santri/guru, kapasitas kamar/ranjang asrama, log disiplin/kesehatan, dan ketepatan pelaksanaan roll call.
- **Keuangan & Cashless**: Volume transaksi kantin, total tagihan yang diterbitkan, rasio pembayaran lunas, saldo deposit beredar, dan penarikan/top-up.
- **Dukungan Teknis (Support)**: SLA breach rate dari tiket bantuan, jumlah insiden aktif, dan durasi penyelesaian tiket.
- **Telemetri Mobile/API**: Versi aplikasi terpasang, jumlah perangkat aktif terdaftar, dan rate request API.

## 3. Data yang Tidak Boleh Disimpan (Forbidden Stored Data)
Untuk menjamin keamanan data pengguna, platform menerapkan **Privacy Guard** (`AnalyticsPrivacyGuard`). Data di bawah ini **TIDAK BOLEH** disimpan ke dalam snapshot database atau log analitik:
- **Kredensial Keamanan**: Password (baik hash maupun plain text), Personal Identification Number (PIN) dompet cashless, API secret key, dan auth token.
- **Informasi Kontak Pribadi Detail**: Nomor telepon/WhatsApp personal, alamat email personal, NISN, atau NIK siswa/guru (dalam snapshot analitik, data harus diagregasikan per kelas/sekolah).
- **Metadata Sensitif**: Nilai input data sensitif yang di-sanitize secara default sebelum snapshot disimpan.

## 4. Pembatasan Hak Akses Berdasarkan Peran (Role Access Control)
Akses analitik diatur secara ketat oleh `AnalyticsAccessService`:
- **Super Admin & Operations Manager**: Memiliki akses penuh ke Dashboard Eksekutif Global, Kamus Metrik, dan Laporan Eksekutif Lintas Tenant.
- **Customer Success (CS)**: Memiliki akses ke Dashboard Skor Kesehatan Tenant (Tenant Health Score) dan log tiket dukungan.
- **Admin Sekolah & Kepala Sekolah**: Terbatas pada data analitik khusus milik sekolah/tenant mereka sendiri.
- **Guru & Wali Kelas**: Tidak memiliki akses ke dashboard analitik level eksekutif/sekolah secara default.
- **Siswa & Orang Tua**: **DILARANG KERAS** mengakses modul analitik apa pun (Akses akan menghasilkan HTTP 403 Forbidden).

## 5. Isolasi Tenant (Tenant Isolation)
Semua query analitik wajib menyertakan filter scope tenant yang didasarkan pada `TenantContextService::activeSchoolId()`. Dashboard sekolah/tenant tidak diperbolehkan menerima parameter `school_id` mentah dari request user tanpa divalidasi keanggotaannya untuk mencegah kebocoran data antar tenant (*cross-tenant data leakage*).

## 6. Retensi Data (Data Retention)
- **Snapshot Harian**: Disimpan maksimal selama 365 hari (1 tahun). Setelah 1 tahun, data harian dikonsolidasikan menjadi snapshot bulanan atau dihapus secara otomatis.
- **Executive Report Runs & Sections**: Dipertahankan selama 24 bulan sebelum diarsipkan.
- **Analytics Access Logs**: Disimpan selama 90 hari untuk kebutuhan audit kepatuhan, kemudian dibersihkan secara otomatis.

## 7. Batasan Ekspor (Export Limitations)
Ekspor laporan analitik hanya diperbolehkan dalam format PDF standar cetak (untuk print/arsip fisik kepala sekolah) atau Excel terenkripsi. Ekspor tidak boleh menyertakan data individual santri/guru secara massal melainkan dalam bentuk summary chart, tabel agregat, dan matriks performa.

## 8. Larangan Penggunaan Layanan Pihak Ketiga (External Analytics Prohibition)
Untuk mematuhi hukum perlindungan data pribadi siswa (GDPR/UU PDP Indonesia):
- **DILARANG** mengintegrasikan library tracking eksternal (seperti Google Analytics, Mixpanel, Amplitude, atau Hotjar) pada halaman analitik internal maupun portal sekolah.
- Semua visualisasi harus dirender menggunakan vanilla CSS/Blade progress indicators secara server-side tanpa mengirimkan data telemetri santri ke server luar.

## 9. Audit Log Akses (Access Logging)
Setiap permintaan pembacaan dashboard analitik, pembuatan laporan eksekutif, atau modifikasi kamus metrik wajib mencatat aktivitas ke tabel `analytics_access_logs` via `AnalyticsAccessLogger` yang mencatat:
- User ID & IP Address.
- Role pengguna.
- Tipe visualisasi/skope analitik yang dibuka.
- Parameter filter yang digunakan.
- Timestamp akses.

## 10. Penanganan Insiden Kebocoran Data (Incident Handling)
Jika terdeteksi adanya data sensitif (seperti token atau password) masuk ke dalam snapshot database analitik:
1. Operator wajib segera menjalankan command pembersihan snapshot dan mengosongkan metadata bermasalah.
2. Melakukan audit log akses analitik untuk memastikan data tersebut belum diunduh atau diekspos ke pihak luar.
3. Memperbarui aturan sanitasi pada `AnalyticsPrivacyGuard` untuk menyaring pola field baru yang terdeteksi sensitif.
