# Phase 24: LMS Lite & Learning Content Module

## Overview
Modul **LMS Lite & Learning Content** menyediakan fitur manajemen kurikulum pembelajaran digital di platform HafizPlus. Staf pengajar dapat menyusun materi dalam bentuk bab/modul dan topik/lesson. Topik mendukung tipe teks, dokumen file privat, link eksternal, dan video embed. Selain itu, guru dapat menambahkan kuis pilihan ganda dan tugas pengumpulan file.

## Fitur Utama
1. **Multi-Tenant Isolation:** Semua kelas pembelajaran terikat dengan `school_id` dari `TenantContextService` dan diisolasi penuh per sekolah.
2. **Syllabus Builder:** Penyusunan bab dan materi pembelajaran secara dinamis dengan penentuan sort order dan materi wajib (required).
3. **Private File Attachments:** Unggahan materi pembelajaran disimpan secara privat di `storage/app/private/lms/{school_id}` untuk mencegah kebocoran data.
4. **Server-Side Quiz System:** Pengerjaan kuis pilihan ganda, isian singkat, dan benar-salah dinilai secara dinamis secara server-side. Kunci jawaban tidak pernah dikirimkan ke peramban siswa.
5. **Homework Assignments:** Tugas pengumpulan file dengan batasan format dan ukuran file maksimal 10MB.
6. **Student & Parent Portals:** Portal mandiri bagi siswa untuk belajar dan wali murid untuk memantau progres kurikulum serta nilai anak.
