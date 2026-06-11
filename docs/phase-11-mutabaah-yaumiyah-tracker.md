# Phase 11 — Mutabaah Yaumiyah Tracker

## Status

Phase 11 menambahkan modul Mutabaah Yaumiyah Tracker untuk HafizPlus School Platform.

## Scope

Modul ini mencakup:

1. Template aktivitas mutabaah.
2. Kategori mutabaah.
3. Input mutabaah harian.
4. Rekap mutabaah.
5. Dashboard mutabaah.
6. Portal mutabaah orang tua.
7. Portal mutabaah santri.

## Tabel Baru

1. `mutabaah_categories`
2. `mutabaah_activities`
3. `mutabaah_records`

## Model Baru

1. `MutabaahCategory`
2. `MutabaahActivity`
3. `MutabaahRecord`

## Controller Baru

1. `MutabaahActivityController`
2. `MutabaahDailyInputController`
3. `MutabaahReportController`
4. `ParentMutabaahPortalController`
5. `StudentMutabaahPortalController`

## Service Baru

1. `MutabaahAccessService`
2. `MutabaahRecordService`
3. `MutabaahReportService`

## Route Baru

1. `mutabaah.activities.index`
2. `mutabaah.activities.create`
3. `mutabaah.activities.store`
4. `mutabaah.activities.show`
5. `mutabaah.activities.edit`
6. `mutabaah.activities.update`
7. `mutabaah.activities.destroy`
8. `mutabaah.daily.index`
9. `mutabaah.daily.store`
10. `mutabaah.reports.dashboard`
11. `mutabaah.reports.student`
12. `portal.parent.mutabaah`
13. `portal.student.mutabaah`

## Role Access

| Role | Akses |
|---|---|
| Super Admin | CRUD template, input, report |
| Admin | CRUD template, input, report |
| Kepala Sekolah | Report read-only |
| Guru | Input dan report terbatas |
| Parent | Portal mutabaah anak sendiri |
| Student | Portal mutabaah pribadi |

## Batasan

Phase 11 tidak membuat:

1. Attendance.
2. Tahsin.
3. Finance.
4. Cashless.
5. White-label.
6. Mobile app.
7. WhatsApp gateway.
8. Push notification.
9. Multi-tenant kompleks.

## Definition of Done

Phase 11 selesai jika:

1. Migration berhasil.
2. Seeder berhasil.
3. Template mutabaah tampil.
4. Admin bisa CRUD template.
5. Guru bisa input mutabaah harian.
6. Record tidak duplicate untuk santri + aktivitas + tanggal.
7. Dashboard mutabaah tampil.
8. Parent hanya bisa melihat anak sendiri.
9. Student hanya bisa melihat data sendiri.
10. Internal route tidak bisa diakses parent/student.
11. `npm run build` berhasil.
12. `php artisan app:system-health-check` berhasil.
13. Dokumentasi dibuat.
