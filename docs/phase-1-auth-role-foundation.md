# Phase 1 — Auth, Role, and Initial Database Foundation

## Status

Phase 1 membangun fondasi teknis awal untuk HafizPlus School Platform.

## Output

1. Auth manual berbasis Blade.
2. Login dengan email atau username.
3. Logout.
4. Role user.
5. Middleware role.
6. Redirect dashboard berdasarkan role.
7. Dashboard placeholder per role.
8. Struktur database awal:
   - roles
   - schools
   - users dengan role dan school
   - class_rooms
   - teacher_profiles
   - parent_profiles
   - students
   - parent_student
9. Seeder role.
10. Seeder sekolah.
11. Seeder akun awal.

## Role

| Role | Name Internal | Fungsi |
|---|---|---|
| Super Admin | super_admin | Kelola sistem penuh |
| Admin Sekolah | admin | Kelola data sekolah |
| Kepala Sekolah | principal | Monitoring |
| Guru Tahfidz | teacher | Input setoran |
| Orang Tua | parent | Lihat progres anak |
| Santri | student | Lihat progres pribadi |

## Akun Awal

Semua password default:

```text
password
```

| Role           | Email                                                               | Username      |
| -------------- | ------------------------------------------------------------------- | ------------- |
| Super Admin    | [superadmin@hafizplus.test](mailto:superadmin@hafizplus.test)       | superadmin    |
| Admin Sekolah  | [admin@hafizplus.test](mailto:admin@hafizplus.test)                 | admin         |
| Kepala Sekolah | [kepalasekolah@hafizplus.test](mailto:kepalasekolah@hafizplus.test) | kepalasekolah |
| Guru Tahfidz   | [guru@hafizplus.test](mailto:guru@hafizplus.test)                   | guru          |
| Orang Tua      | [ortu@hafizplus.test](mailto:ortu@hafizplus.test)                   | ortu          |
| Santri         | [santri@hafizplus.test](mailto:santri@hafizplus.test)               | santri        |

## Validasi

Phase 1 selesai jika:

1. Semua migration berhasil.
2. Seeder berhasil.
3. Login berhasil.
4. Logout berhasil.
5. Setiap role masuk dashboard masing-masing.
6. Middleware role memblokir akses yang salah.
7. `php artisan route:list` menampilkan semua route dashboard.
8. Tidak ada fitur tahfizh detail yang dibuat dulu.

## Catatan

Phase 1 belum membuat input setoran, target hafalan, hutang hafalan, report, atau notifikasi real.

Fitur tersebut masuk fase berikutnya.
