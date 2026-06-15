# Tenant Health Score Policy

Dokumen kebijakan ini mendefinisikan implementasi, metodologi perhitungan, batasan, serta panduan operasional bagi Tim Customer Success (CS) dalam memanfaatkan metrik **Tenant Health Score** pada HafizPlus School Platform.

## 1. Definisi Tenant Health Score
Tenant Health Score adalah metrik komposit berskala 0 - 100 yang mengukur tingkat kesehatan operasional, keterlibatan (*engagement*), dan stabilitas finansial suatu sekolah (tenant) yang berlangganan platform HafizPlus. Skor ini dihitung secara berkala setiap hari pukul 23:55 melalui scheduler yang memicu command `app:recalculate-tenant-health-scores`.

## 2. Komponen Skor (Component Scores)
Skor akhir dibentuk oleh lima komponen utama dengan bobot masing-masing sebagai berikut:

1. **Academic Engagement (Bobot: 25%)**
   - Menilai keaktifan sekolah dalam mengisi data setoran hafalan santri (tahfizh), kelulusan level tahsin, dan aktivitas mutabaah yaumiyah harian.
   - Penalti diberikan jika tidak ada perekaman aktivitas tahfizh/tahsin baru selama lebih dari 7 hari berturut-turut.
   
2. **Operational Activity (Bobot: 25%)**
   - Mengukur tingkat penggunaan absensi harian (QR/manual scan) dan keaktifan modul asrama (roll call asrama, log kesehatan, log disiplin santri).
   - Sekolah dengan tingkat kehadiran santri di atas 90% mendapat skor maksimal untuk sub-komponen absensi.

3. **Financial Health (Bobot: 20%)**
   - Memantau kelancaran transaksi pembayaran tagihan SPP/iuran sekolah, keaktifan penggunaan dompet cashless santri, dan rasio tagihan jatuh tempo yang belum terbayar.
   - Adanya tunggakan tagihan subscription sekolah ke platform juga akan memotong poin finansial ini.

4. **Support Ticket & SLA Compliance (Bobot: 15%)**
   - Mengukur stabilitas platform dan tingkat kepuasan tenant berdasarkan jumlah tiket bantuan yang dibuka serta pelanggaran batas respons/penyelesaian masalah (SLA breach).
   - SLA Breach memotong poin komponen ini secara langsung.

5. **API & Companion App Usage (Bobot: 15%)**
   - Menilai adopsi teknologi companion app oleh wali murid/santri serta keaktifan perangkat seluler yang terdaftar, dan rate error integrasi API partner.

## 3. Formula Perhitungan
Skor dihitung sebagai rata-rata berbobot dari masing-masing komponen:

$$\text{Health Score} = (\text{Academic} \times 0.25) + (\text{Operational} \times 0.25) + (\text{Finance} \times 0.20) + (\text{Support} \times 0.15) + (\text{API} \times 0.15)$$

Setiap komponen dinilai dalam rentang 0 - 100 berdasarkan indikator kinerja kunci (KPI) internal masing-masing sub-modul.

## 4. Threshold Status Kesehatan
Berdasarkan nilai akhir Health Score, tenant dikategorikan ke dalam status berikut:

| Rentang Skor | Status | Deskripsi | Tindakan CS |
|---|---|---|---|
| **85 - 100** | `healthy` | Tenant aktif, operasional berjalan lancar, adopsi fitur tinggi. | Pertahankan kualitas, tawarkan program promo atau fitur advance tambahan. |
| **70 - 84** | `watch` | Mengalami sedikit penurunan adopsi atau keterlambatan input data. | Kirimkan newsletter panduan berkala atau hubungi admin sekolah secara santai. |
| **50 - 69** | `risk` | Adopsi fitur rendah, banyak tagihan tertunggak, atau sering mengajukan komplain. | Lakukan evaluasi penggunaan platform bersama admin/kepala sekolah. |
| **1 - 49** | `critical` | Hampir tidak ada aktivitas operasional, terdapat SLA breach berulang, atau subscription menunggak lama. | Hubungi langsung kepala sekolah/pemilik yayasan untuk investigasi kendala serius. |
| **0** (atau NULL) | `unknown` | Tenant baru yang belum selesai di-onboard atau data operasional belum mencukupi. | Lanjutkan onboarding playbook dan pendampingan setup awal. |

## 5. Keterbatasan Skor (Score Limitations)
- Skor ini dihitung berdasarkan snapshots data agregat harian, sehingga fluktuasi drastis dalam satu hari (seperti hari libur semester) dapat menurunkan skor secara temporer. Hal ini wajar dan tidak menandakan penurunan performa sekolah yang sesungguhnya.
- Skor bersifat kuantitatif berdasarkan entri data di sistem. Sekolah yang tetap melakukan KBM manual tanpa menginput ke platform akan tercatat ber-skor rendah, meskipun secara fisik operasional mereka sehat.

## 6. Larangan Keras Penangguhan Otomatis (Auto Suspension Prohibition)
> [!WARNING]
> **Skor Kesehatan Tenant dilarang keras digunakan untuk melakukan penangguhan (suspension) atau pemblokiran akun tenant secara otomatis oleh sistem.**
> Penangguhan sekolah hanya boleh dilakukan melalui verifikasi manual dan persetujuan tertulis dari manajemen HafizPlus atas pelanggaran ketentuan langganan (TOS), bukan semata-mata karena skor analitik berada pada level `critical` atau `risk`.

## 7. Cara Membaca Rekomendasi (Recommendations Guide)
Sistem secara cerdas menyusun rekomendasi tindakan berdasarkan komponen skor terendah:
- Jika komponen **Academic** rendah: Tampilkan petunjuk tutorial cara membuat target hafalan atau merekam mutabaah secara kolektif.
- Jika komponen **Support** rendah: Prioritaskan penyelesaian tiket terbuka milik sekolah tersebut dan tawarkan sesi training tambahan bagi staf mereka.

## 8. Panduan bagi Customer Success
Tim Customer Success harus memantau dashboard `tenant-health` minimal satu kali dalam seminggu untuk:
1. Menyaring tenant bersetatus `risk` dan `critical`.
2. Menganalisis breakdown komponen untuk menemukan akar masalah (apakah di akademik, finance, atau kendala support).
3. Menggunakan data ini sebagai basis *proactive customer engagement* demi mencegah pembatalan layanan (*churn prevention*).
