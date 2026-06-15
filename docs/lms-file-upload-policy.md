# Kebijakan Unggah Berkas LMS (LMS File Upload Policy)

## Ketentuan Ukuran Berkas
- Maksimal ukuran berkas yang diperbolehkan adalah **10 MB** (10.240 KB).
- Unggahan yang melebihi batas ini akan ditolak otomatis di tingkat validasi HTTP Form Request.

## Ekstensi Berkas yang Diizinkan
- **Dokumen:** `.pdf`, `.doc`, `.docx`, `.xls`, `.xlsx`, `.ppt`, `.pptx`, `.txt`
- **Gambar:** `.jpg`, `.jpeg`, `.png`, `.gif`

## Ekstensi Berkas yang Dilarang (Blocked Extensions)
Untuk mencegah serangan eksekusi kode (RCE), format berkas berikut akan diblokir total:
- Berkas Eksekutif/Script: `.exe`, `.bat`, `.cmd`, `.sh`, `.php`, `.js`
- Berkas Kompresi Berat: `.zip` (opsional diblokir jika rawan penyisipan trojan)

## Lokasi Penyimpanan Aman
Seluruh berkas materi dan pengumpulan tugas disimpan di direktori privat server:
- Materi: `storage/app/private/lms/{school_id}/*`
- Tugas: `storage/app/private/lms_submissions/{school_id}/*`
Akses ke file-file ini dikontrol melalui route download khusus yang mengecek otentikasi sesi aktif pengguna.
