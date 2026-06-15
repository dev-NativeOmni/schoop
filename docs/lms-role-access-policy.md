# Kebijakan Akses Peran LMS (LMS Role & Access Policy)

## Matriks Hak Akses Modul LMS

| Peran (Role) | CRUD Kelas | CRUD Materi | Penilaian Tugas | Monitoring Progres | Mengikuti Kelas |
| :--- | :---: | :---: | :---: | :---: | :---: |
| **Super Admin** | Ya (Global) | Ya | Ya | Ya | - |
| **Admin Sekolah** | Ya (Sekolah) | Ya | Ya | Ya | - |
| **Kepala Sekolah (Principal)** | Tidak | Tidak | Tidak | Ya | - |
| **Guru (Teacher)** | Ya (Ditugaskan) | Ya (Ditugaskan) | Ya | Ya | - |
| **Santri (Student)** | Tidak | Tidak | Tidak | Ya (Pribadi) | Ya |
| **Wali Murid (Parent)** | Tidak | Tidak | Tidak | Ya (Anak) | Tidak |

## Batasan Keamanan
- Guru dan Admin hanya diizinkan memanipulasi data LMS dalam batasan `school_id` mereka masing-masing.
- Siswa hanya dapat mengakses materi jika status pendaftarannya (enrollment) aktif.
