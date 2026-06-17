# AI Learning Safety Policy - HafizPlus

Kebijakan keselamatan penggunaan modul AI-assisted belajar Qur'an di HafizPlus.

## 1. Prinsip Kolaborasi AI dan Guru
Asisten AI didesain hanya membantu menyusun draf rencana latihan dan memberikan draft catatan umpan balik. Keputusan kelulusan akademik, penilaian hafalan resmi, dan penilaian makharijul huruf tetap berada di tangan guru.

## 2. Pelabelan Negatif Dilarang (Wording Restrictions)
Penggunaan kata-kata yang mendiskreditkan anak secara permanen seperti "malas", "gagal", "buruk", atau "tidak punya harapan" dilarang.
Sistem secara otomatis menyaring kata-kata tersebut dan menggantinya dengan kebahasaan yang positif/suportif:
- `malas` -> `perlu konsistensi`
- `gagal` -> `perlu pendampingan`
- `buruk` -> `perlu penguatan`

Setiap kali kata larangan terdeteksi, sistem secara otomatis mencatat `AiSafetyEvent` bertipe `negative_label_detected` dengan tingkat keseriusan `attention` untuk dievaluasi oleh administrator sekolah.

## 3. Pembatasan Hak Akses Draft
- Wali murid dan siswa dilarang keras mengakses draf yang belum melalui review guru.
- Rencana latihan dan rekomendasi hanya muncul di portal parent/student setelah statusnya diubah menjadi `published` oleh guru.
