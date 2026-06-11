# Phase 7 — Parent and Student Progress Portal

## Status

Phase 7 membangun portal progres tahfizh untuk orang tua dan santri.

Portal ini bersifat read-only.

## Output

1. Portal Orang Tua.
2. Portal Santri.
3. Dashboard progres anak untuk orang tua.
4. Dashboard progres pribadi untuk santri.
5. Riwayat setoran untuk orang tua.
6. Riwayat setoran untuk santri.
7. Laporan bulanan anak.
8. Laporan bulanan santri.
9. Ownership-based access.
10. Role-based access.

## Controller Baru

```text
App\Http\Controllers\Portal\ParentProgressPortalController
App\Http\Controllers\Portal\StudentProgressPortalController
```

## Request Baru

```text
App\Http\Requests\Portal\ParentProgressFilterRequest
App\Http\Requests\Portal\StudentProgressFilterRequest
```

## Service Baru

```text
App\Services\Portal\ParentStudentAccessService
App\Services\Portal\StudentProgressSnapshotService
```

## Route Baru

```text
portal.parent.dashboard
portal.parent.children.progress
portal.parent.children.records
portal.parent.children.monthly

portal.student.dashboard
portal.student.records
portal.student.monthly
```

## Role Access

| Role           | Parent Portal    | Student Portal   |
| -------------- | ---------------- | ---------------- |
| Super Admin    | Tidak            | Tidak            |
| Admin Sekolah  | Tidak            | Tidak            |
| Kepala Sekolah | Tidak            | Tidak            |
| Guru Tahfidz   | Tidak            | Tidak            |
| Orang Tua      | Ya, anak sendiri | Tidak            |
| Santri         | Tidak            | Ya, data sendiri |

## Aturan Keamanan

Orang tua hanya boleh melihat anak sendiri berdasarkan relasi:

```text
parent_profiles → parent_student → students
```

Santri hanya boleh melihat data sendiri berdasarkan relasi:

```text
students.user_id = auth()->id()
```

Jika akses tidak sah, sistem harus mengembalikan:

```text
403 Forbidden
```

## Data yang Ditampilkan Orang Tua

1. Daftar anak.
2. Target aktif.
3. Capaian bulan ini.
4. Hutang hafalan.
5. Riwayat setoran.
6. Laporan bulanan.
7. Catatan guru.

## Data yang Ditampilkan Santri

1. Target aktif.
2. Capaian bulan ini.
3. Hutang hafalan.
4. Riwayat setoran pribadi.
5. Laporan bulanan pribadi.
6. Catatan guru.

## Belum Dibuat

Phase 7 belum membuat:

1. Notification center.
2. Push notification.
3. WhatsApp gateway.
4. Chat orang tua dan guru.
5. Export PDF.
6. Export Excel.
7. API mobile.
8. Native Android.
9. Native iOS.
10. Payment.
11. Attendance.
12. Mutabaah.
13. Tahsin.

## Definition of Done

Phase 7 selesai jika:

1. Parent bisa login dan melihat daftar anak.
2. Parent bisa melihat progres anak sendiri.
3. Parent tidak bisa melihat anak lain.
4. Parent bisa melihat riwayat setoran anak.
5. Parent bisa melihat laporan bulanan anak.
6. Student bisa login dan melihat progres pribadi.
7. Student bisa melihat riwayat setoran pribadi.
8. Student bisa melihat laporan bulanan pribadi.
9. Parent tidak bisa akses dashboard internal.
10. Student tidak bisa akses dashboard internal.
11. Parent dan student tidak bisa input setoran.
12. Semua akses ilegal menghasilkan 403.
13. Build frontend berhasil.
14. Laporan Phase 7 didokumentasikan.
