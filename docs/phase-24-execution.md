# Phase 24 Execution Guide — LMS Lite & Learning Content Module

## 0. Identitas Phase

Dokumen ini adalah instruksi eksekusi untuk AI coding agent di code editor.

Project:

```text
HafizPlus School Platform
```

Produk pertama:

```text
Tahfizh Monitoring App
```

Framework:

```text
Laravel 12
```

Database:

```text
MySQL
```

Project folder lokal:

```text
C:\xampp\htdocs\hafizplus-school-platform
```

Database local:

```text
hafizplus_school_platform
```

Status:

```text
Proyek mandiri, bukan bagian dari HafizPlus 2.0 atau HafizPlus 3.0.
```

Phase saat ini:

```text
Phase 24 — LMS Lite & Learning Content Module
```

---

# 1. Posisi Phase 24 dalam Roadmap

Phase 24 adalah **growth extension**, bukan fase wajib awal.

Roadmap utama sudah selesai sampai:

```text
Phase 20 — Company/Product Scale & SaaS Operations
```

Growth extension yang sudah disiapkan:

```text
Phase 21 — Native Mobile Companion Apps & App Store Distribution
Phase 22 — External API, Partner Integration & Developer Portal
Phase 23 — Advanced Analytics & Executive Intelligence
```

Phase 24 melanjutkan growth extension dengan modul:

```text
LMS Lite & Learning Content Module
```

Tujuan Phase 24 adalah membuat **fitur konten pembelajaran ringan** agar sekolah bisa membuat materi, modul, lesson, lampiran, tugas ringan, quiz ringan, dan progres belajar sederhana.

Phase 24 **bukan full LMS besar**.

---

# 2. Keputusan Strategis Phase 24

## 2.1 Kenapa LMS Lite Baru Masuk Phase 24

LMS adalah modul besar. Karena itu tidak boleh dibuat di awal.

Sebelum LMS Lite, sistem sudah harus punya:

1. Core tahfizh.
2. Parent portal.
3. Notification center.
4. Report dashboard.
5. Mutabaah.
6. Attendance.
7. Tahsin.
8. Finance ledger.
9. SchoolOS Mini.
10. Boarding.
11. Multi-tenant.
12. White-label.
13. Cashless.
14. SaaS operations.
15. Native mobile companion.
16. External API.
17. Analytics dan executive intelligence.

Alasan teknis:

1. LMS membutuhkan struktur school, class, student, teacher, role, tenant, portal, notification, dan report yang sudah stabil.
2. Kalau dibuat terlalu awal, proyek berubah menjadi super app sebelum core produk terbukti dipakai.
3. LMS punya banyak potensi scope creep: video, live class, quiz kompleks, marketplace, certificate, payment, AI tutor, SCORM, proctoring.
4. Phase 24 sengaja dibuat sebagai **LMS Lite**, bukan full LMS.

---

## 2.2 Batas Definisi LMS Lite

LMS Lite di Phase 24 hanya mencakup:

1. Course sederhana.
2. Module / chapter.
3. Lesson berbasis teks, file, link, embed sederhana.
4. Resource attachment.
5. Assignment ringan.
6. Quiz ringan.
7. Student learning progress.
8. Teacher content management.
9. Parent/student read portal.
10. Basic analytics untuk content usage.
11. Integrasi ringan dengan Notification Center.
12. Integrasi ringan dengan Phase 23 analytics snapshot.

LMS Lite tidak mencakup:

1. Full online school.
2. Marketplace kursus.
3. Video hosting platform.
4. Live streaming class.
5. Proctoring exam.
6. SCORM/xAPI compliance.
7. AI tutor otomatis.
8. Payment per course.
9. Course selling.
10. Content creator revenue share.

---

# 3. Pre-Gate Sebelum Phase 24

Sebelum menjalankan Phase 24, agent wajib memastikan Phase 23 sudah aman.

Phase 24 hanya boleh dieksekusi jika:

1. Phase 22 External API berjalan aman.
2. API token tidak disimpan plain text.
3. API scope berjalan.
4. API request log berjalan.
5. Webhook signature dan retry aman.
6. Phase 23 analytics snapshot berjalan.
7. Metric dictionary tidak salah definisi.
8. Tenant health score tidak bocor lintas tenant.
9. Executive dashboard hanya bisa diakses role yang benar.
10. Data analytics tidak mengirim data siswa ke layanan eksternal.
11. `php artisan app:system-health-check` berhasil.
12. `npm run build` berhasil.
13. Tidak ada bug P0/P1 terbuka.

Jika masih ada bug P0/P1, hentikan Phase 24 dan lakukan bug fix sprint dulu.

---

# 4. Klasifikasi Bug Sebelum Phase 24

| Prioritas | Contoh Bug | Keputusan |
|---|---|---|
| P0 | Data tenant bocor, parent bisa lihat anak lain, teacher bisa lihat course tenant lain, API token bocor | Wajib fix sebelum Phase 24 |
| P1 | Analytics salah hitung, report executive salah, notification salah penerima, course progress salah | Wajib fix sebelum Phase 24 |
| P2 | UI kurang rapi, wording kurang jelas, filter kurang nyaman | Boleh dicatat |
| P3 | Enhancement kosmetik | Boleh ditunda |

---

# 5. Tujuan Phase 24

Phase 24 bertujuan membuat **LMS Lite & Learning Content Module**.

Modul ini digunakan untuk:

1. Membuat course pembelajaran sederhana.
2. Mengelola module/chapter dalam course.
3. Mengelola lesson dalam module.
4. Mengunggah atau menautkan resource pembelajaran.
5. Membuat assignment ringan.
6. Membuat quiz ringan.
7. Menghubungkan course ke class room, teacher, dan student.
8. Mencatat progres belajar student.
9. Menampilkan materi ke student portal.
10. Menampilkan ringkasan belajar ke parent portal.
11. Menampilkan dashboard konten untuk teacher/admin/principal.
12. Menyediakan data ringkas ke Phase 23 analytics.
13. Menjaga role-based access.
14. Menjaga ownership-based access.
15. Menjaga tenant isolation.
16. Dokumentasi Phase 24.

---

# 6. Batasan Phase 24

AI agent tidak boleh membuat fitur berikut pada Phase 24:

1. Full LMS enterprise.
2. Marketplace course.
3. Payment per course.
4. Course subscription.
5. Video hosting internal besar.
6. Live streaming.
7. Zoom/Meet integration.
8. Proctoring exam.
9. Anti-cheat exam.
10. SCORM.
11. xAPI.
12. LTI integration.
13. AI tutor.
14. AI grading otomatis.
15. Speech scoring.
16. Tahsin voice correction.
17. Native mobile rewrite.
18. Offline mobile sync kompleks.
19. Forum diskusi kompleks.
20. Chat real-time.
21. Websocket.
22. Gamification besar.
23. Certificate builder kompleks.
24. QR certificate verification.
25. Public course catalog.
26. Content monetization.
27. Creator payout.
28. External analytics tracker yang mengirim data siswa.
29. Data warehouse baru.
30. Microservices rewrite.

Phase 24 hanya membuat:

```text
LMS Lite berbasis Laravel Blade + MySQL + tenant-aware access.
```

---

# 7. Konsep Domain LMS Lite

## 7.1 Course

Course adalah kumpulan materi pembelajaran.

Contoh course:

1. Tahsin Dasar.
2. Adab Harian Santri.
3. Bahasa Arab Dasar.
4. Fiqih Ibadah Kelas 7.
5. Materi Hafalan Hadits Pendek.
6. Panduan Mutabaah Ramadhan.
7. Materi Orientasi Santri Baru.

Course bisa bersifat:

| Type | Keterangan |
|---|---|
| `general` | Course umum sekolah |
| `quran` | Course Qur'an / tahfizh / tahsin |
| `islamic_studies` | Fiqih, hadits, akidah, adab |
| `arabic` | Bahasa Arab |
| `school_subject` | Mata pelajaran sekolah |
| `boarding` | Materi asrama / kedisiplinan |
| `orientation` | Orientasi siswa baru |

---

## 7.2 Course Module

Course module adalah bab atau section dalam course.

Contoh:

```text
Course: Tahsin Dasar
Module 1: Makharijul Huruf
Module 2: Sifat Huruf
Module 3: Mad Dasar
Module 4: Ghunnah
```

---

## 7.3 Lesson

Lesson adalah materi pertemuan atau unit belajar.

Tipe lesson Phase 24:

| Type | Keterangan |
|---|---|
| `text` | Materi teks |
| `file` | Materi file PDF/doc/image |
| `link` | Link eksternal |
| `embed` | Embed sederhana, misalnya video dari platform eksternal |
| `assignment` | Tugas ringan |
| `quiz` | Quiz ringan |

Catatan:

1. Untuk video, Phase 24 hanya menyimpan link atau embed.
2. Jangan membuat video hosting sendiri.
3. Jangan mengunduh video eksternal.
4. Jangan menyimpan file besar tanpa batas.

---

## 7.4 Enrollment

Enrollment adalah relasi peserta dengan course.

Enrollment dapat berasal dari:

1. Class room.
2. Student individual.
3. School-wide assignment.
4. Manual admin.
5. Teacher assignment.

Phase 24 cukup mendukung:

1. Assign course ke class room.
2. Assign course ke individual student.
3. Assign teacher sebagai instructor.

---

## 7.5 Learning Progress

Learning progress mencatat status lesson per student.

Status:

| Status | Makna |
|---|---|
| `not_started` | Belum mulai |
| `in_progress` | Sedang dipelajari |
| `completed` | Selesai |
| `needs_review` | Perlu ditinjau guru |

Course progress dihitung dari lesson progress:

```text
course_progress_percent = completed_lessons / total_required_lessons * 100
```

---

## 7.6 Assignment Lite

Assignment Lite adalah tugas sederhana.

Tipe submission:

| Type | Keterangan |
|---|---|
| `text` | Jawaban teks |
| `file` | Upload file sederhana |
| `link` | Link tugas |

Status submission:

| Status | Makna |
|---|---|
| `draft` | Belum dikumpulkan |
| `submitted` | Sudah dikumpulkan |
| `reviewed` | Sudah dinilai/ditinjau |
| `returned` | Dikembalikan untuk revisi |

---

## 7.7 Quiz Lite

Quiz Lite adalah quiz sederhana.

Phase 24 hanya mendukung:

1. Multiple choice.
2. True/false.
3. Short answer manual review.

Phase 24 tidak membuat:

1. Question bank kompleks.
2. Randomized exam engine.
3. Timed exam strict.
4. Anti-cheat.
5. Proctoring.
6. AI grading.

---

# 8. Target Output Phase 24

Setelah Phase 24 selesai, aplikasi harus punya:

1. Menu **LMS Lite**.
2. Menu **Courses**.
3. Menu **Course Modules**.
4. Menu **Lessons**.
5. Menu **Learning Resources**.
6. Menu **Assignments**.
7. Menu **Quiz Lite**.
8. Menu **Course Enrollment**.
9. Menu **Learning Progress**.
10. Teacher content dashboard.
11. Admin content dashboard.
12. Principal read-only dashboard.
13. Student learning portal.
14. Parent learning summary portal.
15. Analytics snapshot integration.
16. Notification integration.
17. Dokumentasi Phase 24.
18. Update `docs/project-progress.md`.

---

# 9. Database Tables

Phase 24 membuat tabel:

1. `lms_courses`
2. `lms_course_modules`
3. `lms_lessons`
4. `lms_lesson_resources`
5. `lms_course_instructors`
6. `lms_course_enrollments`
7. `lms_lesson_progress`
8. `lms_assignments`
9. `lms_assignment_submissions`
10. `lms_quizzes`
11. `lms_quiz_questions`
12. `lms_quiz_attempts`
13. `lms_quiz_answers`
14. `lms_activity_logs`

---

# 10. Models

Phase 24 membuat model:

1. `LmsCourse`
2. `LmsCourseModule`
3. `LmsLesson`
4. `LmsLessonResource`
5. `LmsCourseInstructor`
6. `LmsCourseEnrollment`
7. `LmsLessonProgress`
8. `LmsAssignment`
9. `LmsAssignmentSubmission`
10. `LmsQuiz`
11. `LmsQuizQuestion`
12. `LmsQuizAttempt`
13. `LmsQuizAnswer`
14. `LmsActivityLog`

---

# 11. Controllers

Buat controller di namespace:

```text
App\Http\Controllers\Lms
```

Controller internal:

1. `LmsDashboardController`
2. `LmsCourseController`
3. `LmsCourseModuleController`
4. `LmsLessonController`
5. `LmsLessonResourceController`
6. `LmsEnrollmentController`
7. `LmsAssignmentController`
8. `LmsAssignmentSubmissionController`
9. `LmsQuizController`
10. `LmsQuizAttemptController`
11. `LmsProgressReportController`

Controller portal:

```text
App\Http\Controllers\Portal
```

1. `StudentLmsPortalController`
2. `ParentLmsPortalController`

---

# 12. Requests

Buat request di namespace:

```text
App\Http\Requests\Lms
```

Request:

1. `StoreLmsCourseRequest`
2. `UpdateLmsCourseRequest`
3. `StoreLmsCourseModuleRequest`
4. `UpdateLmsCourseModuleRequest`
5. `StoreLmsLessonRequest`
6. `UpdateLmsLessonRequest`
7. `StoreLmsLessonResourceRequest`
8. `StoreLmsEnrollmentRequest`
9. `StoreLmsAssignmentRequest`
10. `UpdateLmsAssignmentRequest`
11. `SubmitLmsAssignmentRequest`
12. `StoreLmsQuizRequest`
13. `UpdateLmsQuizRequest`
14. `StoreLmsQuizQuestionRequest`
15. `SubmitLmsQuizAttemptRequest`
16. `LmsProgressReportFilterRequest`

---

# 13. Services

Buat folder:

```text
app/Services/Lms
```

Service:

1. `LmsAccessService`
2. `LmsCourseService`
3. `LmsModuleService`
4. `LmsLessonService`
5. `LmsResourceService`
6. `LmsEnrollmentService`
7. `LmsProgressService`
8. `LmsAssignmentService`
9. `LmsQuizService`
10. `LmsReportService`
11. `LmsNotificationService`
12. `LmsAnalyticsSnapshotService`
13. `LmsActivityLogger`

---

# 14. Commands

Buat command:

1. `app:lms-recalculate-progress`
2. `app:lms-generate-analytics-snapshot`
3. `app:lms-prune-activity-logs`

Command behavior:

## 14.1 `app:lms-recalculate-progress`

Tujuan:

1. Menghitung ulang progress lesson dan course.
2. Memperbaiki progress yang tidak sinkron.
3. Bisa dijalankan setelah migration atau bug fix.

## 14.2 `app:lms-generate-analytics-snapshot`

Tujuan:

1. Membuat snapshot ringkas untuk Phase 23 analytics.
2. Menghitung active learners.
3. Menghitung completion rate.
4. Menghitung overdue assignment.
5. Menghitung course engagement.

## 14.3 `app:lms-prune-activity-logs`

Tujuan:

1. Membersihkan activity log lama.
2. Default retention 180 hari.
3. Tidak menghapus progress utama.
4. Tidak menghapus submission atau quiz attempt.

---

# 15. Seeders

Buat seeder:

1. `LmsCourseTypeSeeder`
2. `LmsSampleCourseSeeder`

Seeder minimal:

```text
Course: Tahsin Dasar
Module: Pengenalan Makharijul Huruf
Lesson: Apa itu Makharijul Huruf
Lesson: Latihan Huruf Tenggorokan
Assignment: Rekaman Latihan Bacaan — manual review
Quiz: Quiz Ringan Makharijul Huruf
```

Catatan:

1. Seeder sample boleh dibuat hanya untuk local/development.
2. Jangan membuat data sample yang otomatis aktif di production tanpa kontrol.

---

# 16. Role Access Phase 24

Sebelum coding, agent wajib cek role di database:

```powershell
php artisan tinker
```

Lalu:

```php
App\Models\Role::query()->pluck('name')->all();
```

Role default yang diasumsikan:

```text
super_admin
admin
admin_sekolah
kepala_sekolah
teacher
guru
guru_tahfidz
parent
student
```

| Role | Course CRUD | Lesson CRUD | Assignment | Quiz | Enrollment | Progress Report | Portal |
|---|---|---|---|---|---|---|---|
| Super Admin | Ya | Ya | Ya | Ya | Ya | Semua | Tidak |
| Admin Sekolah | Ya | Ya | Ya | Ya | Ya | Tenant sendiri | Tidak |
| Kepala Sekolah | Read-only | Read-only | Read-only | Read-only | Read-only | Semua tenant sendiri | Tidak |
| Teacher/Guru | Course scope | Lesson scope | Ya, course scope | Ya, course scope | Santri/class scope | Course scope | Tidak |
| Parent | Tidak | Tidak | Tidak | Tidak | Tidak | Anak sendiri | Ya |
| Student | Tidak | Tidak | Submit | Attempt | Tidak | Data sendiri | Ya |

Aturan keras:

1. Parent hanya boleh melihat learning progress anak sendiri.
2. Student hanya boleh melihat course dan lesson yang dienroll untuk dirinya.
3. Teacher hanya boleh mengelola course yang dia menjadi instructor.
4. Teacher tidak boleh melihat course tenant lain.
5. Admin sekolah tidak boleh melihat course sekolah lain.
6. Super admin boleh audit semua tenant.
7. Kepala sekolah read-only.
8. Parent tidak boleh submit tugas untuk anak.
9. Student tidak boleh mengubah nilai/review.
10. Quiz score harus dihitung server-side.
11. Assignment review hanya oleh teacher/admin.

---

# 17. Tenant Isolation Rules

Semua tabel utama LMS wajib memiliki `school_id`, kecuali tabel yang memang hanya pivot dan tetap bisa diturunkan dari parent table.

Minimal tabel yang wajib punya `school_id`:

1. `lms_courses`
2. `lms_course_modules`
3. `lms_lessons`
4. `lms_lesson_resources`
5. `lms_course_enrollments`
6. `lms_lesson_progress`
7. `lms_assignments`
8. `lms_assignment_submissions`
9. `lms_quizzes`
10. `lms_quiz_attempts`
11. `lms_activity_logs`

Rules:

1. Semua query LMS harus tenant-aware.
2. Jangan resolve tenant dari request bebas.
3. Tenant harus berasal dari `TenantContextService` / active school context.
4. Jangan percaya `school_id` dari hidden input jika user bukan super admin.
5. Form request harus validasi ownership.
6. Policy/service harus cek tenant sebelum action.

---

# 18. Migration Specification

## 18.1 `lms_courses`

Kolom:

1. `id`
2. `school_id` nullable/constrained schools
3. `created_by` nullable/constrained users
4. `updated_by` nullable/constrained users
5. `title`
6. `slug`
7. `course_code` unique per school
8. `type`
9. `description` text nullable
10. `cover_image_path` nullable
11. `level` nullable
12. `visibility` enum: `draft`, `published`, `archived`
13. `enrollment_mode` enum: `manual`, `class_room`, `school_wide`
14. `start_date` nullable
15. `end_date` nullable
16. `is_required` boolean default false
17. `sort_order` integer default 0
18. timestamps
19. soft deletes

Indexes:

1. `school_id`
2. `course_code`
3. `type`
4. `visibility`
5. `start_date`
6. `end_date`

Unique:

```text
school_id + course_code
school_id + slug
```

---

## 18.2 `lms_course_modules`

Kolom:

1. `id`
2. `school_id`
3. `course_id`
4. `title`
5. `description` nullable
6. `sort_order` integer default 0
7. `is_required` boolean default true
8. timestamps
9. soft deletes

Unique:

```text
course_id + sort_order
```

---

## 18.3 `lms_lessons`

Kolom:

1. `id`
2. `school_id`
3. `course_id`
4. `module_id`
5. `created_by`
6. `title`
7. `slug`
8. `lesson_type` enum: `text`, `file`, `link`, `embed`, `assignment`, `quiz`
9. `content` longText nullable
10. `external_url` nullable
11. `embed_code` text nullable
12. `estimated_minutes` unsigned integer default 0
13. `is_required` boolean default true
14. `visibility` enum: `draft`, `published`, `archived`
15. `available_from` nullable datetime
16. `available_until` nullable datetime
17. `sort_order` integer default 0
18. timestamps
19. soft deletes

Indexes:

1. `school_id`
2. `course_id`
3. `module_id`
4. `lesson_type`
5. `visibility`

---

## 18.4 `lms_lesson_resources`

Kolom:

1. `id`
2. `school_id`
3. `lesson_id`
4. `uploaded_by`
5. `title`
6. `resource_type` enum: `file`, `link`
7. `file_path` nullable
8. `external_url` nullable
9. `mime_type` nullable
10. `file_size` unsignedBigInteger nullable
11. `sort_order` integer default 0
12. timestamps
13. soft deletes

Rules:

1. File size harus dibatasi.
2. MIME type harus divalidasi.
3. Jangan allow file executable.
4. Jangan menyimpan path absolut Windows di database.

---

## 18.5 `lms_course_instructors`

Kolom:

1. `id`
2. `school_id`
3. `course_id`
4. `teacher_user_id`
5. `role` enum: `owner`, `assistant`, `viewer`
6. `assigned_by`
7. `assigned_at`
8. timestamps

Unique:

```text
course_id + teacher_user_id
```

---

## 18.6 `lms_course_enrollments`

Kolom:

1. `id`
2. `school_id`
3. `course_id`
4. `student_id`
5. `class_room_id` nullable
6. `enrolled_by` nullable/constrained users
7. `source` enum: `manual`, `class_room`, `school_wide`, `system`
8. `status` enum: `active`, `completed`, `dropped`, `suspended`
9. `started_at` nullable
10. `completed_at` nullable
11. `progress_percent` decimal 5,2 default 0
12. timestamps
13. soft deletes

Unique:

```text
course_id + student_id
```

---

## 18.7 `lms_lesson_progress`

Kolom:

1. `id`
2. `school_id`
3. `course_id`
4. `lesson_id`
5. `student_id`
6. `status` enum: `not_started`, `in_progress`, `completed`, `needs_review`
7. `started_at` nullable
8. `completed_at` nullable
9. `last_accessed_at` nullable
10. `time_spent_seconds` unsigned integer default 0
11. `marked_by` nullable/constrained users
12. timestamps

Unique:

```text
lesson_id + student_id
```

---

## 18.8 `lms_assignments`

Kolom:

1. `id`
2. `school_id`
3. `course_id`
4. `lesson_id` nullable
5. `created_by`
6. `title`
7. `description` longText nullable
8. `submission_type` enum: `text`, `file`, `link`
9. `due_at` nullable datetime
10. `max_score` unsigned integer default 100
11. `allow_late_submission` boolean default false
12. `status` enum: `draft`, `published`, `archived`
13. timestamps
14. soft deletes

---

## 18.9 `lms_assignment_submissions`

Kolom:

1. `id`
2. `school_id`
3. `assignment_id`
4. `course_id`
5. `student_id`
6. `submitted_by` nullable/constrained users
7. `answer_text` longText nullable
8. `file_path` nullable
9. `external_url` nullable
10. `status` enum: `draft`, `submitted`, `reviewed`, `returned`
11. `score` decimal 5,2 nullable
12. `feedback` text nullable
13. `reviewed_by` nullable/constrained users
14. `reviewed_at` nullable
15. `submitted_at` nullable
16. timestamps
17. soft deletes

Unique:

```text
assignment_id + student_id
```

---

## 18.10 `lms_quizzes`

Kolom:

1. `id`
2. `school_id`
3. `course_id`
4. `lesson_id` nullable
5. `created_by`
6. `title`
7. `description` nullable
8. `max_attempts` unsigned tiny integer default 1
9. `passing_score` decimal 5,2 default 70
10. `status` enum: `draft`, `published`, `archived`
11. timestamps
12. soft deletes

---

## 18.11 `lms_quiz_questions`

Kolom:

1. `id`
2. `school_id`
3. `quiz_id`
4. `question_text` longText
5. `question_type` enum: `multiple_choice`, `true_false`, `short_answer`
6. `options` json nullable
7. `correct_answer` json nullable
8. `score_weight` decimal 5,2 default 1
9. `sort_order` integer default 0
10. timestamps
11. soft deletes

Security note:

1. Correct answer tidak boleh dikirim ke frontend student saat quiz berjalan.
2. Correct answer hanya boleh dipakai server-side.

---

## 18.12 `lms_quiz_attempts`

Kolom:

1. `id`
2. `school_id`
3. `quiz_id`
4. `course_id`
5. `student_id`
6. `attempt_number` unsigned tiny integer
7. `status` enum: `in_progress`, `submitted`, `graded`
8. `score` decimal 5,2 nullable
9. `started_at` nullable
10. `submitted_at` nullable
11. `graded_at` nullable
12. timestamps
13. soft deletes

Unique:

```text
quiz_id + student_id + attempt_number
```

---

## 18.13 `lms_quiz_answers`

Kolom:

1. `id`
2. `school_id`
3. `quiz_attempt_id`
4. `quiz_question_id`
5. `answer` json nullable
6. `is_correct` boolean nullable
7. `score_awarded` decimal 5,2 nullable
8. `teacher_feedback` text nullable
9. timestamps

---

## 18.14 `lms_activity_logs`

Kolom:

1. `id`
2. `school_id`
3. `course_id` nullable
4. `lesson_id` nullable
5. `student_id` nullable
6. `user_id` nullable
7. `event_type`
8. `event_summary`
9. `metadata` json nullable
10. `ip_address` nullable
11. `user_agent` nullable
12. `created_at`

No update/delete needed for normal UI.

---

# 19. Model Relationships

## 19.1 `LmsCourse`

Relationships:

1. belongsTo `School`
2. belongsTo `User` as creator
3. hasMany `LmsCourseModule`
4. hasMany `LmsLesson`
5. hasMany `LmsCourseInstructor`
6. hasMany `LmsCourseEnrollment`
7. hasMany `LmsAssignment`
8. hasMany `LmsQuiz`

## 19.2 `LmsCourseModule`

Relationships:

1. belongsTo `School`
2. belongsTo `LmsCourse`
3. hasMany `LmsLesson`

## 19.3 `LmsLesson`

Relationships:

1. belongsTo `School`
2. belongsTo `LmsCourse`
3. belongsTo `LmsCourseModule`
4. hasMany `LmsLessonResource`
5. hasMany `LmsLessonProgress`
6. hasOne `LmsAssignment`
7. hasOne `LmsQuiz`

## 19.4 `LmsCourseEnrollment`

Relationships:

1. belongsTo `School`
2. belongsTo `LmsCourse`
3. belongsTo `Student`
4. belongsTo `ClassRoom`

## 19.5 `LmsAssignmentSubmission`

Relationships:

1. belongsTo `School`
2. belongsTo `LmsAssignment`
3. belongsTo `LmsCourse`
4. belongsTo `Student`
5. belongsTo `User` as reviewer

## 19.6 `LmsQuizAttempt`

Relationships:

1. belongsTo `School`
2. belongsTo `LmsQuiz`
3. belongsTo `LmsCourse`
4. belongsTo `Student`
5. hasMany `LmsQuizAnswer`

---

# 20. Access Service Rules

Buat:

```text
app/Services/Lms/LmsAccessService.php
```

Rules:

1. `canManageCourse(User $user, LmsCourse $course): bool`
2. `canViewCourse(User $user, LmsCourse $course): bool`
3. `canEnrollStudent(User $user, LmsCourse $course, Student $student): bool`
4. `canViewStudentProgress(User $user, Student $student): bool`
5. `canSubmitAssignment(User $user, LmsAssignment $assignment): bool`
6. `canReviewSubmission(User $user, LmsAssignmentSubmission $submission): bool`
7. `canAttemptQuiz(User $user, LmsQuiz $quiz): bool`
8. `assertSameTenant(Model $a, Model $b): void`

Hard rules:

1. Parent access melalui `parent_student`.
2. Student access melalui `students.user_id`.
3. Teacher course access melalui `lms_course_instructors`.
4. Admin access via tenant.
5. Super admin access all.
6. Principal read-only tenant-wide.

---

# 21. Progress Service Rules

Buat:

```text
app/Services/Lms/LmsProgressService.php
```

Behavior:

1. Create progress record when student opens lesson.
2. Mark lesson `in_progress` on first access.
3. Mark lesson `completed` when student clicks complete.
4. Assignment lesson becomes completed only after submission reviewed or submitted, depending setting.
5. Quiz lesson becomes completed only after attempt submitted and passing rules satisfied.
6. Recalculate course progress after each progress update.
7. Store `last_accessed_at`.
8. Log activity to `lms_activity_logs`.

Progress formula:

```text
required_completed_lessons / total_required_lessons * 100
```

If course has zero required lesson:

```text
progress_percent = 0
```

---

# 22. Assignment Service Rules

Buat:

```text
app/Services/Lms/LmsAssignmentService.php
```

Behavior:

1. Student can create draft submission.
2. Student can submit once.
3. Student can update draft before submission.
4. Student cannot edit after submitted unless teacher returns.
5. Teacher/admin can review submission.
6. Review can set score and feedback.
7. Returned submission can be resubmitted.
8. Late submission controlled by `allow_late_submission`.
9. Parent cannot submit.
10. All file uploads validated.

---

# 23. Quiz Service Rules

Buat:

```text
app/Services/Lms/LmsQuizService.php
```

Behavior:

1. Student can start quiz if enrolled.
2. Attempt number increments server-side.
3. Attempt cannot exceed `max_attempts`.
4. Correct answers never sent to frontend.
5. Multiple choice and true/false can be auto-scored server-side.
6. Short answer defaults to manual review.
7. Score stored in attempt.
8. Progress updates after submitted/graded.
9. Parent cannot attempt.
10. Teacher/admin can view attempts in their course/tenant.

---

# 24. Notification Integration

Phase 24 may use existing Notification Center.

Events:

| Event | Recipient | Trigger |
|---|---|---|
| Course published | enrolled students / parent optional | Course becomes published |
| New lesson available | enrolled students | Lesson published |
| Assignment due soon | students | Manual/scheduled later |
| Assignment reviewed | student | Teacher reviews |
| Quiz graded | student | Quiz graded |
| Course completed | student/parent optional | Progress 100% |

Rules:

1. Use database notification only.
2. No WhatsApp.
3. No Firebase push.
4. No email automation by default.
5. No queue production assumption unless project already uses queue safely.

---

# 25. Analytics Integration

Phase 24 should feed Phase 23 analytics using internal snapshot tables/services.

Metrics:

1. Active learners today.
2. Active learners 7 days.
3. Published courses.
4. Draft courses.
5. Lesson completion rate.
6. Course completion rate.
7. Assignment submission rate.
8. Assignment overdue count.
9. Quiz average score.
10. Low engagement course list.

Rules:

1. Do not send data to external analytics platform.
2. Do not expose individual student analytics to unauthorized users.
3. Aggregate data for executive dashboard.
4. Parent/student see only own/child data.
5. Tenant analytics must be isolated.

---

# 26. UI Structure

```text
LMS Lite
├── Dashboard
├── Courses
│   ├── Course Detail
│   ├── Modules
│   ├── Lessons
│   ├── Instructors
│   └── Enrollments
├── Assignments
├── Quiz Lite
├── Progress Reports
└── Settings / Content Policy
```

Student portal:

```text
My Learning
├── Active Courses
├── Course Detail
├── Lesson Viewer
├── Assignment Submission
├── Quiz Attempt
└── Progress Summary
```

Parent portal:

```text
Learning Summary
├── Child Course List
├── Child Progress
├── Assignment Status
└── Quiz Summary
```

---

# 27. Views

Create views:

```text
resources/views/lms/
├── dashboard.blade.php
├── courses/
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── edit.blade.php
│   ├── show.blade.php
│   └── enrollments.blade.php
├── modules/
│   ├── create.blade.php
│   ├── edit.blade.php
│   └── partial-list.blade.php
├── lessons/
│   ├── create.blade.php
│   ├── edit.blade.php
│   ├── show.blade.php
│   └── viewer.blade.php
├── assignments/
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── edit.blade.php
│   ├── show.blade.php
│   └── submissions.blade.php
├── quizzes/
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── edit.blade.php
│   ├── show.blade.php
│   └── attempts.blade.php
└── reports/
    └── progress.blade.php
```

Portal views:

```text
resources/views/portal/student/lms/
├── dashboard.blade.php
├── course.blade.php
├── lesson.blade.php
├── assignment.blade.php
├── quiz.blade.php
└── progress.blade.php

resources/views/portal/parent/lms/
├── dashboard.blade.php
├── child-courses.blade.php
└── child-progress.blade.php
```

---

# 28. Routes

Add routes in `routes/web.php`.

Internal LMS routes:

```php
Route::middleware(['auth', 'tenant'])->prefix('lms')->name('lms.')->group(function () {
    Route::get('/', [LmsDashboardController::class, 'index'])->name('dashboard');

    Route::resource('courses', LmsCourseController::class);
    Route::resource('courses.modules', LmsCourseModuleController::class)->shallow();
    Route::resource('courses.lessons', LmsLessonController::class)->shallow();

    Route::get('courses/{course}/enrollments', [LmsEnrollmentController::class, 'index'])->name('courses.enrollments.index');
    Route::post('courses/{course}/enrollments', [LmsEnrollmentController::class, 'store'])->name('courses.enrollments.store');
    Route::delete('courses/{course}/enrollments/{enrollment}', [LmsEnrollmentController::class, 'destroy'])->name('courses.enrollments.destroy');

    Route::resource('assignments', LmsAssignmentController::class);
    Route::get('assignments/{assignment}/submissions', [LmsAssignmentSubmissionController::class, 'index'])->name('assignments.submissions.index');
    Route::post('submissions/{submission}/review', [LmsAssignmentSubmissionController::class, 'review'])->name('submissions.review');

    Route::resource('quizzes', LmsQuizController::class);
    Route::get('quizzes/{quiz}/attempts', [LmsQuizAttemptController::class, 'index'])->name('quizzes.attempts.index');

    Route::get('reports/progress', [LmsProgressReportController::class, 'index'])->name('reports.progress');
});
```

Student portal routes:

```php
Route::middleware(['auth', 'role:student'])->prefix('portal/student/lms')->name('portal.student.lms.')->group(function () {
    Route::get('/', [StudentLmsPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('courses/{course}', [StudentLmsPortalController::class, 'course'])->name('course');
    Route::get('lessons/{lesson}', [StudentLmsPortalController::class, 'lesson'])->name('lesson');
    Route::post('lessons/{lesson}/complete', [StudentLmsPortalController::class, 'completeLesson'])->name('lessons.complete');
    Route::get('assignments/{assignment}', [StudentLmsPortalController::class, 'assignment'])->name('assignment');
    Route::post('assignments/{assignment}/submit', [StudentLmsPortalController::class, 'submitAssignment'])->name('assignments.submit');
    Route::get('quizzes/{quiz}', [StudentLmsPortalController::class, 'quiz'])->name('quiz');
    Route::post('quizzes/{quiz}/submit', [StudentLmsPortalController::class, 'submitQuiz'])->name('quizzes.submit');
});
```

Parent portal routes:

```php
Route::middleware(['auth', 'role:parent'])->prefix('portal/parent/lms')->name('portal.parent.lms.')->group(function () {
    Route::get('/', [ParentLmsPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('children/{student}', [ParentLmsPortalController::class, 'childCourses'])->name('children.courses');
    Route::get('children/{student}/courses/{course}', [ParentLmsPortalController::class, 'childCourseProgress'])->name('children.courses.progress');
});
```

Adjust middleware names to actual project conventions.

---

# 29. File Upload Rules

Allowed file types for lesson resources and assignment submissions:

1. PDF
2. DOC/DOCX
3. XLS/XLSX only if needed
4. PPT/PPTX only if needed
5. PNG/JPG/JPEG/WebP
6. MP3 small if needed later, but disabled by default

Default max size:

```text
10 MB per file
```

Do not allow:

1. `.php`
2. `.exe`
3. `.bat`
4. `.cmd`
5. `.js` upload as executable resource
6. `.sh`
7. `.zip` by default
8. Unknown binary

Storage path:

```text
storage/app/private/lms/{school_id}/...
```

Do not store:

1. Absolute Windows path.
2. Public unsecured path for private assignment files.
3. Student submissions in public folder.

---

# 30. Content Policy

Create docs file:

```text
docs/lms-content-policy.md
```

Policy contents:

1. Content must be school-approved.
2. Teacher content can be draft until published.
3. Admin can archive inappropriate content.
4. Sensitive student data must not be placed in public lesson content.
5. External links must be reviewed.
6. Embed content must not bypass school privacy policy.
7. Copyright responsibility belongs to school/admin.
8. No public marketplace in Phase 24.

---

# 31. Documentation Output

Create docs:

```text
docs/phase-24-execution.md
docs/phase-24-lms-lite-learning-content.md
docs/lms-content-policy.md
docs/lms-role-access-policy.md
docs/lms-file-upload-policy.md
docs/lms-progress-calculation-policy.md
docs/lms-quiz-lite-policy.md
docs/lms-assignment-lite-policy.md
```

Update:

```text
docs/project-progress.md
```

Add:

```md
## Phase 24 — LMS Lite & Learning Content Module

Status: Done / Pending based on execution.

Output:
- LMS Lite dashboard.
- Course/module/lesson management.
- Learning resources.
- Course enrollment.
- Student learning portal.
- Parent learning summary portal.
- Assignment Lite.
- Quiz Lite.
- Progress report.
- LMS analytics snapshot.
- LMS policy docs.

Not included:
- Full LMS enterprise.
- Marketplace course.
- Payment per course.
- Video hosting.
- Live streaming.
- SCORM/xAPI.
- AI tutor.
- Proctoring.
```

---

# 32. Validasi Awal Sebelum Eksekusi

Jalankan:

```powershell
cd C:\xampp\htdocs\hafizplus-school-platform

php artisan --version
php -v
composer -V
npm -v
php artisan migrate:status
php artisan route:list
php artisan app:system-health-check
npm run build
git status
```

Target:

1. Laravel 12 berjalan.
2. Phase 0–23 selesai atau minimal dokumen roadmap tersedia.
3. Multi-tenant foundation aktif.
4. White-label tidak rusak.
5. Cashless ledger aman.
6. SaaS ops aktif.
7. Native mobile API tidak rusak.
8. External API dan Developer Portal tidak rusak.
9. Analytics dashboard tidak rusak.
10. Tidak ada P0/P1 terbuka.
11. Working tree bersih atau semua perubahan diketahui.

Jika Phase 23 belum aman, hentikan Phase 24.

---

# 33. Buat Branch Git Phase 24

Jalankan:

```powershell
git checkout -b phase-24-lms-lite-learning-content
```

Jika branch sudah ada:

```powershell
git checkout phase-24-lms-lite-learning-content
```

---

# 34. Artisan Commands untuk Membuat File

Jalankan:

```powershell
php artisan make:model LmsCourse -m
php artisan make:model LmsCourseModule -m
php artisan make:model LmsLesson -m
php artisan make:model LmsLessonResource -m
php artisan make:model LmsCourseInstructor -m
php artisan make:model LmsCourseEnrollment -m
php artisan make:model LmsLessonProgress -m
php artisan make:model LmsAssignment -m
php artisan make:model LmsAssignmentSubmission -m
php artisan make:model LmsQuiz -m
php artisan make:model LmsQuizQuestion -m
php artisan make:model LmsQuizAttempt -m
php artisan make:model LmsQuizAnswer -m
php artisan make:model LmsActivityLog -m

php artisan make:controller Lms/LmsDashboardController
php artisan make:controller Lms/LmsCourseController --resource
php artisan make:controller Lms/LmsCourseModuleController --resource
php artisan make:controller Lms/LmsLessonController --resource
php artisan make:controller Lms/LmsLessonResourceController
php artisan make:controller Lms/LmsEnrollmentController
php artisan make:controller Lms/LmsAssignmentController --resource
php artisan make:controller Lms/LmsAssignmentSubmissionController
php artisan make:controller Lms/LmsQuizController --resource
php artisan make:controller Lms/LmsQuizAttemptController
php artisan make:controller Lms/LmsProgressReportController
php artisan make:controller Portal/StudentLmsPortalController
php artisan make:controller Portal/ParentLmsPortalController

php artisan make:request Lms/StoreLmsCourseRequest
php artisan make:request Lms/UpdateLmsCourseRequest
php artisan make:request Lms/StoreLmsCourseModuleRequest
php artisan make:request Lms/UpdateLmsCourseModuleRequest
php artisan make:request Lms/StoreLmsLessonRequest
php artisan make:request Lms/UpdateLmsLessonRequest
php artisan make:request Lms/StoreLmsLessonResourceRequest
php artisan make:request Lms/StoreLmsEnrollmentRequest
php artisan make:request Lms/StoreLmsAssignmentRequest
php artisan make:request Lms/UpdateLmsAssignmentRequest
php artisan make:request Lms/SubmitLmsAssignmentRequest
php artisan make:request Lms/StoreLmsQuizRequest
php artisan make:request Lms/UpdateLmsQuizRequest
php artisan make:request Lms/StoreLmsQuizQuestionRequest
php artisan make:request Lms/SubmitLmsQuizAttemptRequest
php artisan make:request Lms/LmsProgressReportFilterRequest

php artisan make:command LmsRecalculateProgressCommand
php artisan make:command LmsGenerateAnalyticsSnapshotCommand
php artisan make:command LmsPruneActivityLogsCommand

php artisan make:seeder LmsCourseTypeSeeder
php artisan make:seeder LmsSampleCourseSeeder
```

Buat folder service:

```powershell
mkdir app\Services\Lms
```

Buat file service:

```powershell
New-Item app\Services\Lms\LmsAccessService.php
New-Item app\Services\Lms\LmsCourseService.php
New-Item app\Services\Lms\LmsModuleService.php
New-Item app\Services\Lms\LmsLessonService.php
New-Item app\Services\Lms\LmsResourceService.php
New-Item app\Services\Lms\LmsEnrollmentService.php
New-Item app\Services\Lms\LmsProgressService.php
New-Item app\Services\Lms\LmsAssignmentService.php
New-Item app\Services\Lms\LmsQuizService.php
New-Item app\Services\Lms\LmsReportService.php
New-Item app\Services\Lms\LmsNotificationService.php
New-Item app\Services\Lms\LmsAnalyticsSnapshotService.php
New-Item app\Services\Lms\LmsActivityLogger.php
```

Buat folder view:

```powershell
mkdir resources\views\lms
mkdir resources\views\lms\courses
mkdir resources\views\lms\modules
mkdir resources\views\lms\lessons
mkdir resources\views\lms\assignments
mkdir resources\views\lms\quizzes
mkdir resources\views\lms\reports
mkdir resources\views\portal\student\lms
mkdir resources\views\portal\parent\lms
```

---

# 35. Implementation Order

Ikuti urutan implementasi ini:

1. Buat migrations.
2. Buat models dan relationships.
3. Buat services access/progress dasar.
4. Buat course CRUD.
5. Buat module CRUD.
6. Buat lesson CRUD.
7. Buat resource upload/link.
8. Buat instructor assignment.
9. Buat enrollment.
10. Buat student portal lesson viewer.
11. Buat progress tracking.
12. Buat assignment lite.
13. Buat quiz lite.
14. Buat parent portal summary.
15. Buat LMS dashboard/report.
16. Buat notification integration.
17. Buat analytics snapshot integration.
18. Buat commands.
19. Buat seeders.
20. Buat docs.
21. Jalankan validation.
22. Commit.

Jangan lompat ke quiz/assignment sebelum course/module/lesson/enrollment/progress stabil.

---

# 36. Testing Checklist

## 36.1 Course Management

Test:

1. Admin membuat course.
2. Admin publish course.
3. Admin archive course.
4. Teacher hanya melihat course yang dia menjadi instructor.
5. Principal hanya read-only.
6. Draft course tidak muncul di student portal.

## 36.2 Lesson Management

Test:

1. Teacher membuat lesson di course scope.
2. Teacher tidak bisa membuat lesson di course lain.
3. Draft lesson tidak muncul di student.
4. Published lesson muncul sesuai enrollment.
5. Resource file valid bisa diakses oleh enrolled student.
6. Resource file tidak bisa diakses tenant lain.

## 36.3 Enrollment

Test:

1. Admin enroll satu class room.
2. Admin enroll satu student.
3. Duplicate enrollment tidak terjadi.
4. Student hanya melihat course enrolled.
5. Parent hanya melihat course anak sendiri.

## 36.4 Progress

Test:

1. Student membuka lesson → status `in_progress`.
2. Student klik complete → status `completed`.
3. Course progress berubah.
4. Recalculate command menghasilkan progress sama.
5. Parent melihat progress anak.

## 36.5 Assignment

Test:

1. Teacher membuat assignment.
2. Student submit text.
3. Student submit file valid.
4. Student tidak bisa submit assignment course yang tidak dienroll.
5. Teacher review submission.
6. Parent read-only melihat status.
7. Returned submission bisa resubmit.

## 36.6 Quiz

Test:

1. Teacher membuat quiz.
2. Teacher membuat question multiple choice.
3. Student attempt quiz.
4. Correct answer tidak tampil di frontend.
5. Server menghitung score.
6. Max attempt dipatuhi.
7. Parent melihat summary, bukan answer key.

## 36.7 Tenant Isolation

Test:

1. Tenant A course tidak terlihat Tenant B.
2. Student Tenant A tidak bisa access lesson Tenant B.
3. Teacher Tenant A tidak bisa manage course Tenant B.
4. Resource file Tenant A tidak bisa dibuka user Tenant B.
5. Analytics snapshot tenant tidak bercampur.

## 36.8 Role Access

Test:

1. Parent tidak bisa membuka internal LMS dashboard.
2. Student tidak bisa membuka internal LMS dashboard.
3. Parent tidak bisa submit assignment.
4. Student tidak bisa review assignment.
5. Principal tidak bisa edit course.
6. Teacher tidak bisa enroll student lintas scope.

---

# 37. Bug Priority Phase 24

| Priority | Bug |
|---|---|
| P0 | Cross-tenant course/resource/progress leak |
| P0 | Parent melihat data anak lain |
| P0 | Student melihat course/lesson yang bukan enrollment-nya |
| P0 | Correct answer quiz bocor ke frontend |
| P0 | File private bisa diakses publik |
| P1 | Progress salah hitung |
| P1 | Assignment submission hilang/salah student |
| P1 | Teacher bisa edit course bukan scope-nya |
| P1 | Analytics snapshot salah tenant |
| P2 | UI lesson viewer kurang nyaman |
| P2 | Report LMS lambat |
| P3 | Empty state wording kurang rapi |

---

# 38. Definition of Done Phase 24

Phase 24 dianggap selesai jika:

1. Migration LMS berhasil.
2. Semua model LMS dibuat.
3. Relationship utama benar.
4. Course CRUD berjalan.
5. Module CRUD berjalan.
6. Lesson CRUD berjalan.
7. Resource upload/link berjalan.
8. File validation berjalan.
9. Course instructor assignment berjalan.
10. Enrollment student/class berjalan.
11. Student portal course list berjalan.
12. Student lesson viewer berjalan.
13. Lesson progress berjalan.
14. Course progress percent dihitung.
15. Parent learning summary berjalan.
16. Assignment Lite berjalan.
17. Submission text/file/link berjalan.
18. Assignment review berjalan.
19. Quiz Lite berjalan.
20. Quiz attempt berjalan.
21. Quiz answer key tidak bocor.
22. Quiz scoring server-side berjalan.
23. Progress report internal berjalan.
24. LMS dashboard berjalan.
25. Notification integration minimal berjalan.
26. Analytics snapshot command berjalan.
27. Recalculate progress command berjalan.
28. Prune activity log command berjalan.
29. Tenant isolation diuji.
30. Parent ownership diuji.
31. Student ownership diuji.
32. Teacher instructor scope diuji.
33. Principal read-only diuji.
34. File storage private diuji.
35. `php artisan migrate` berhasil.
36. `php artisan db:seed --class=LmsSampleCourseSeeder` berhasil di local.
37. `php artisan app:lms-recalculate-progress` berhasil.
38. `php artisan app:lms-generate-analytics-snapshot` berhasil.
39. `php artisan route:list` tidak error.
40. `php artisan app:system-health-check` berhasil.
41. `npm run build` berhasil.
42. Dokumentasi Phase 24 dibuat.
43. `docs/project-progress.md` diupdate.
44. Tidak ada bug P0/P1 terbuka.
45. Commit dibuat.

---

# 39. Validation Commands

Jalankan:

```powershell
cd C:\xampp\htdocs\hafizplus-school-platform

php artisan optimize:clear
php artisan migrate
php artisan db:seed --class=LmsCourseTypeSeeder
php artisan db:seed --class=LmsSampleCourseSeeder
php artisan app:lms-recalculate-progress
php artisan app:lms-generate-analytics-snapshot
php artisan route:list
php artisan app:system-health-check
npm run build
git status
```

Target:

1. Tidak ada migration error.
2. Tidak ada route error.
3. Seeder berjalan.
4. Command LMS berjalan.
5. Health check OK.
6. Frontend build OK.
7. Tidak ada P0/P1.

---

# 40. Manual UAT Scenario

Gunakan akun:

| Role | Jumlah |
|---|---:|
| Super Admin | 1 |
| Admin Sekolah | 1 |
| Kepala Sekolah | 1 |
| Teacher | 1–2 |
| Student | 2–5 |
| Parent | 1–3 |

Scenario:

1. Admin login.
2. Admin membuat course `Tahsin Dasar`.
3. Admin assign teacher sebagai instructor.
4. Teacher login.
5. Teacher membuat module dan lesson.
6. Teacher publish lesson.
7. Admin enroll satu class room.
8. Student login.
9. Student membuka course.
10. Student membuka lesson.
11. Student complete lesson.
12. Teacher membuat assignment.
13. Student submit assignment.
14. Teacher review assignment.
15. Teacher membuat quiz.
16. Student attempt quiz.
17. Parent login.
18. Parent melihat progress anak.
19. Kepala sekolah login.
20. Kepala sekolah melihat LMS dashboard read-only.
21. Admin melihat analytics snapshot.
22. Pastikan tenant isolation aman.

---

# 41. Commit

Setelah semua validasi berhasil:

```powershell
git add .
git commit -m "feat: add lms lite learning content module"
```

---

# 42. Final Report Template untuk Agent

Gunakan format laporan berikut setelah selesai:

```md
# Phase 24 Completion Report — LMS Lite & Learning Content Module

## Completed

- LMS Lite dashboard created.
- Course CRUD created.
- Course module CRUD created.
- Lesson CRUD created.
- Learning resource upload/link created.
- Instructor assignment created.
- Course enrollment created.
- Student learning portal created.
- Parent learning summary portal created.
- Lesson progress tracking created.
- Assignment Lite created.
- Quiz Lite created.
- LMS progress report created.
- Notification integration created.
- Analytics snapshot integration created.
- LMS commands created.
- LMS documentation created.

## Not Included

- Full LMS enterprise.
- Course marketplace.
- Payment per course.
- Video hosting platform.
- Live streaming.
- SCORM/xAPI.
- AI tutor.
- Proctoring.
- External analytics tracker.

## Validation

- php artisan migrate: OK
- LMS seeders: OK
- php artisan app:lms-recalculate-progress: OK
- php artisan app:lms-generate-analytics-snapshot: OK
- php artisan route:list: OK
- php artisan app:system-health-check: OK
- npm run build: OK

## Security

- Tenant isolation checked.
- Parent ownership checked.
- Student ownership checked.
- Teacher instructor scope checked.
- Quiz answer key not exposed.
- Private resources not public.

## Status

Phase 24 complete and ready for UAT.
```

---

# 43. Setelah Phase 24

Jangan langsung menambah Phase 25 sebelum UAT LMS Lite.

Urutan setelah Phase 24:

1. UAT LMS Lite.
2. Test tenant isolation.
3. Test parent/student ownership.
4. Test teacher course scope.
5. Test file access security.
6. Test quiz answer leakage.
7. Test assignment submission.
8. Test analytics snapshot.
9. Fix P0/P1.
10. Baru pertimbangkan Phase 25.

Rekomendasi Phase 25:

```text
Phase 25 — AI-Assisted Qur’an Learning & Product Differentiation
```

Namun Phase 25 harus sangat dibatasi. Jangan membuat AI grading produksi penuh sebelum data, consent, privacy, dan model evaluation benar-benar siap.

