# Phase 15 - SchoolOS Mini

## Status

Phase 15 menambahkan SchoolOS Mini sebagai layer integrasi HafizPlus School Platform.

## Scope

Modul ini mencakup:

1. SchoolOS Dashboard.
2. Role-based home dashboard.
3. Module registry.
4. School settings.
5. Academic year.
6. School term.
7. Student 360 Profile.
8. Internal search sederhana.
9. Module health cards.
10. Quick access ke modul aktif.

## Tabel Baru

1. `academic_years`
2. `school_terms`
3. `school_settings`
4. `system_modules`

## Model Baru

1. `AcademicYear`
2. `SchoolTerm`
3. `SchoolSetting`
4. `SystemModule`

## Controller Baru

1. `SchoolOsDashboardController`
2. `Student360Controller`
3. `AcademicYearController`
4. `SchoolSettingController`
5. `SystemModuleController`
6. `SchoolOsSearchController`

## Service Baru

1. `SchoolOsAccessService`
2. `SchoolContextService`
3. `ModuleRegistryService`
4. `SchoolOsDashboardService`
5. `Student360SnapshotService`
6. `SchoolOsSearchService`

## Seeder Baru

1. `SystemModuleSeeder`

## Route Baru

1. `schoolos.dashboard`
2. `schoolos.search`
3. `schoolos.students.show`
4. `schoolos.academic-years.index`
5. `schoolos.academic-years.create`
6. `schoolos.academic-years.store`
7. `schoolos.academic-years.edit`
8. `schoolos.academic-years.update`
9. `schoolos.settings.index`
10. `schoolos.settings.update`
11. `schoolos.modules.index`
12. `schoolos.modules.update`

## Role Access

| Role | Akses |
|---|---|
| Super Admin | Semua |
| Admin | Dashboard, settings, module registry, Student 360 |
| Kepala Sekolah | Dashboard read-only, Student 360 |
| Guru | Dashboard guru, Student 360 santri scope |
| Parent | Dashboard terbatas, Student 360 anak sendiri |
| Student | Dashboard terbatas, Student 360 diri sendiri |

## Batasan

Phase 15 tidak membuat:

1. Multi-tenant kompleks.
2. White-label.
3. Cashless kantin.
4. Merchant POS.
5. Wallet.
6. Payment gateway.
7. Native mobile.
8. LMS penuh.
9. WhatsApp gateway.
10. Push notification.

## Definition of Done

Phase 15 selesai jika:

1. Migration berhasil.
2. Seeder berhasil.
3. SchoolOS dashboard tampil.
4. Module registry tampil.
5. Module cards tampil.
6. Academic year bisa dibuat.
7. Academic year aktif tampil di dashboard.
8. School settings bisa disimpan.
9. Student 360 tampil.
10. Internal search bisa menemukan santri.
11. Parent hanya melihat anak sendiri.
12. Student hanya melihat data pribadi.
13. Guru hanya melihat santri scope.
14. Kepala sekolah read-only.
15. Admin bisa mengelola settings dan module registry.
16. `npm run build` berhasil.
17. `php artisan app:system-health-check` berhasil.
18. Dokumentasi dibuat.
