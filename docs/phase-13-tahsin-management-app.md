# Phase 13 — Tahsin Management App

## Status

Phase 13 menambahkan modul Tahsin Management App untuk HafizPlus School Platform.

## Scope

Modul ini mencakup:

1. Level tahsin.
2. Skill tahsin.
3. Profil tahsin santri.
4. Asesmen tahsin manual.
5. Penilaian skill tahsin.
6. Kalkulasi overall score.
7. Grade otomatis.
8. Report tahsin.
9. Parent tahsin portal.
10. Student tahsin portal.
11. Role-based access.
12. Ownership-based access.

## Tabel Baru

1. `tahsin_levels`
2. `tahsin_skills`
3. `tahsin_student_profiles`
4. `tahsin_assessments`
5. `tahsin_assessment_items`

## Model Baru

1. `TahsinLevel`
2. `TahsinSkill`
3. `TahsinStudentProfile`
4. `TahsinAssessment`
5. `TahsinAssessmentItem`

## Controller Baru

1. `TahsinLevelController`
2. `TahsinSkillController`
3. `TahsinStudentProfileController`
4. `TahsinAssessmentController`
5. `TahsinReportController`
6. `ParentTahsinPortalController`
7. `StudentTahsinPortalController`

## Service Baru

1. `TahsinAccessService`
2. `TahsinAssessmentService`
3. `TahsinReportService`

## Seeder Baru

1. `TahsinLevelSeeder`
2. `TahsinSkillSeeder`

## Route Baru

1. `tahsin.reports.dashboard`
2. `tahsin.profiles.index`
3. `tahsin.profiles.show`
4. `tahsin.profiles.edit`
5. `tahsin.profiles.update`
6. `tahsin.assessments.index`
7. `tahsin.assessments.create`
8. `tahsin.assessments.store`
9. `tahsin.assessments.show`
10. `tahsin.levels.index`
11. `tahsin.levels.create`
12. `tahsin.levels.store`
13. `tahsin.levels.show`
14. `tahsin.levels.edit`
15. `tahsin.levels.update`
16. `tahsin.levels.destroy`
17. `tahsin.skills.index`
18. `tahsin.skills.create`
19. `tahsin.skills.store`
20. `tahsin.skills.show`
21. `tahsin.skills.edit`
22. `tahsin.skills.update`
23. `tahsin.skills.destroy`
24. `portal.parent.tahsin`
25. `portal.student.tahsin`

## Role Access

| Role | Akses |
|---|---|
| Super Admin | CRUD master, profil, asesmen, report |
| Admin | CRUD master, profil, asesmen, report |
| Kepala Sekolah | Report dan monitoring read-only |
| Guru | Input asesmen dan report terbatas |
| Parent | Portal tahsin anak sendiri |
| Student | Portal tahsin pribadi |

## Batasan

Phase 13 tidak membuat:

1. AI voice correction.
2. Voice recognition.
3. Audio upload.
4. LMS penuh.
5. Finance.
6. Cashless.
7. White-label.
8. Native mobile app.
9. WhatsApp gateway.
10. Push notification.
11. Multi-tenant kompleks.

## Definition of Done

Phase 13 selesai jika:

1. Migration berhasil.
2. Seeder berhasil.
3. Level tahsin tampil.
4. Skill tahsin tampil.
5. Admin bisa CRUD level dan skill.
6. Admin bisa edit profil tahsin santri.
7. Guru bisa input asesmen.
8. Overall score otomatis dihitung.
9. Grade otomatis muncul.
10. Report tahsin tampil.
11. Parent hanya melihat tahsin anak sendiri.
12. Student hanya melihat tahsin pribadi.
13. Parent/student tidak bisa akses internal dashboard.
14. Parent/student tidak bisa input asesmen.
15. `npm run build` berhasil.
16. `php artisan app:system-health-check` berhasil.
17. Dokumentasi dibuat.
