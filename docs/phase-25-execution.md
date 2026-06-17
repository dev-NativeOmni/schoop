# Phase 25 Execution Guide — AI-Assisted Qur’an Learning & Product Differentiation

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
Phase 25 — AI-Assisted Qur’an Learning & Product Differentiation
```

---

# 1. Posisi Phase 25 dalam Roadmap

Phase 25 adalah **growth extension** setelah:

1. Phase 20 — Company/Product Scale & SaaS Operations.
2. Phase 21 — Native Mobile Companion Apps & App Store Distribution.
3. Phase 22 — External API, Partner Integration & Developer Portal.
4. Phase 23 — Advanced Analytics & Executive Intelligence.
5. Phase 24 — LMS Lite & Learning Content Module.

Phase 25 bukan pondasi awal dan bukan modul operasional wajib.

Phase ini dibuat setelah platform memiliki:

1. Data tahfizh.
2. Data tahsin.
3. Data mutabaah.
4. Data attendance.
5. Data finance.
6. Data boarding.
7. Data LMS Lite.
8. Data analytics.
9. Multi-tenant isolation.
10. White-label layer.
11. Mobile/API foundation.

Tujuan Phase 25 adalah membuat HafizPlus punya diferensiasi produk yang kuat:

> Platform tidak hanya mencatat data belajar Qur’an, tetapi membantu guru, santri, dan orang tua memahami langkah belajar berikutnya secara lebih personal, aman, dan tetap dikendalikan manusia.

---

# 2. Keputusan Utama Phase 25

## 2.1 AI adalah Asisten, Bukan Pengganti Guru

AI di Phase 25 tidak boleh menggantikan:

1. Guru tahfizh.
2. Guru tahsin.
3. Kepala sekolah.
4. Musyrif/asrama.
5. Keputusan akademik final.
6. Penilaian kelulusan.
7. Fatwa agama.
8. Koreksi tajwid resmi.

AI hanya boleh membantu:

1. Menyusun rekomendasi latihan.
2. Membuat draft catatan belajar.
3. Mengelompokkan kelemahan umum.
4. Menyarankan materi LMS Lite.
5. Membantu guru menyiapkan feedback.
6. Membuat ringkasan progres untuk parent/student.
7. Memberi saran tindak lanjut yang harus direview manusia.

Prinsip final:

```text
Human-in-the-loop wajib untuk semua output yang berdampak pada penilaian, laporan resmi, atau keputusan akademik.
```

---

## 2.2 AI Tidak Menjadi Penilai Resmi

Phase 25 tidak boleh membuat:

1. Auto-grade tahfizh final.
2. Auto-grade tahsin final.
3. Auto-pass level tahsin.
4. Auto-fail santri.
5. Auto-promote level.
6. Auto-generate rapor resmi tanpa review.
7. Auto-judgement kualitas bacaan.
8. Auto-fatwa atau nasihat agama yang seolah otoritatif.

Semua rekomendasi harus diberi label:

```text
Rekomendasi sistem. Perlu validasi guru.
```

---

## 2.3 Tidak Ada Voice AI / Audio Tajwid di Phase 25

AI voice correction adalah fitur berisiko tinggi.

Phase 25 tidak boleh membuat:

1. Upload audio bacaan Qur’an.
2. Speech-to-text bacaan Qur’an.
3. Voice recognition.
4. Tajwid auto scoring.
5. Makharijul huruf auto scoring.
6. Biometric voice identity.
7. Rekaman suara anak dikirim ke vendor AI.
8. Real-time audio correction.

Alasan:

1. Data suara anak adalah data sensitif.
2. Akurasi tajwid/makhraj butuh validasi ahli.
3. Risiko salah koreksi tinggi.
4. Risiko privasi tinggi.
5. Perlu kajian legal dan persetujuan orang tua.

Voice AI baru boleh menjadi fase terpisah setelah ada:

1. Data protection impact assessment.
2. Consent orang tua.
3. Vendor review.
4. Policy retention audio.
5. Expert tajwid validation.
6. UAT khusus guru tahsin/tahfizh.

---

## 2.4 Tidak Mengirim Data Siswa ke Pihak Ketiga secara Default

Phase 25 harus berjalan dengan prinsip:

```text
No external student data transfer by default.
```

Pada Phase 25, agent tidak boleh langsung mengirim:

1. Nama siswa.
2. NIS/NISN.
3. Nama orang tua.
4. Nomor HP.
5. Email.
6. Alamat.
7. Catatan kesehatan.
8. Catatan disiplin.
9. Data finance.
10. Data boarding sensitif.
11. Audio/foto/video siswa.
12. Token API.

ke layanan AI eksternal.

Jika nanti sekolah ingin memakai provider AI eksternal, wajib dibuat fase/fitur terpisah dengan:

1. Tenant-level opt-in.
2. Data anonymization.
3. Data processing agreement.
4. Consent policy.
5. Audit log.
6. Provider risk review.
7. Admin control.
8. Kill switch.

---

# 3. Tujuan Phase 25

Phase 25 bertujuan membuat modul **AI-Assisted Qur’an Learning & Product Differentiation**.

Fokus Phase 25:

1. Membuat learning profile per santri.
2. Mengumpulkan sinyal belajar dari Tahfizh, Tahsin, Mutabaah, Attendance, LMS Lite, dan Analytics.
3. Membuat rule-based AI/recommendation engine awal.
4. Membuat practice plan Qur’an personal.
5. Menyarankan materi LMS Lite yang relevan.
6. Membantu guru membuat draft feedback.
7. Membuat student learning assistant berbasis rekomendasi aman.
8. Membuat parent guidance digest.
9. Membuat teacher review queue untuk output AI.
10. Membuat safety guard dan audit log.
11. Membuat AI feature flag per tenant.
12. Membuat dokumentasi privacy, safety, dan usage policy.

Phase 25 harus memprioritaskan:

1. Aman.
2. Terjelaskan.
3. Bisa diaudit.
4. Tidak menghakimi siswa.
5. Tidak menggantikan guru.
6. Tidak membocorkan data.
7. Berguna secara praktis untuk pembelajaran Qur’an.

---

# 4. Batasan Phase 25

AI agent tidak boleh membuat fitur berikut pada Phase 25:

1. AI voice correction.
2. Speech recognition bacaan Qur’an.
3. Audio upload.
4. Video analysis.
5. Face recognition.
6. Biometric analysis.
7. Auto-grading resmi.
8. Auto-pass / auto-fail.
9. Auto-generate raport resmi tanpa review.
10. Fatwa generator.
11. Chatbot agama bebas tanpa guardrail.
12. Open-ended AI chat untuk anak tanpa pembatasan.
13. External LLM integration yang mengirim data siswa.
14. Fine-tuning model dengan data siswa.
15. Vector database eksternal.
16. Recommendation marketplace.
17. Full adaptive LMS.
18. AI proctoring.
19. AI plagiarism checker.
20. AI payment/finance decisioning.
21. AI discipline scoring.
22. Ranking siswa otomatis berbasis AI.
23. Predictive labeling seperti “anak malas”, “anak gagal”, atau label negatif lain.
24. Native mobile AI feature baru.
25. Microservices rewrite.
26. Data warehouse eksternal.

Phase 25 cukup membuat:

```text
AI-assisted recommendation layer berbasis data internal, rule-based, explainable, dan teacher-reviewed.
```

---

# 5. Konsep Produk Phase 25

## 5.1 Learning Profile

Learning profile adalah ringkasan kondisi belajar Qur’an per santri.

Sumber data:

1. Tahfizh progress.
2. Tahfizh debt.
3. Tahsin assessment.
4. Mutabaah consistency.
5. Attendance trend.
6. LMS Lite completion.
7. Teacher notes.
8. Analytics student daily metrics.

Contoh profile:

```text
Santri konsisten hadir, progres tahfizh stabil, tetapi tahsin skill mad dan ghunnah masih perlu penguatan. Sistem menyarankan latihan tahsin dasar selama 7 hari dan materi LMS Lite terkait mad thabi'i.
```

Learning profile bukan label permanen.

Learning profile harus:

1. Bisa berubah sesuai data terbaru.
2. Menampilkan alasan rekomendasi.
3. Tidak memakai kata negatif yang merendahkan.
4. Bisa disembunyikan dari parent/student jika belum direview guru.
5. Bisa diregenerate.

---

## 5.2 Learning Signal

Learning signal adalah sinyal data yang dipakai untuk rekomendasi.

Contoh signal:

| Signal | Sumber | Makna |
|---|---|---|
| `tahfizh_debt_increasing` | Tahfizh Debt | Hutang hafalan meningkat |
| `tahfizh_progress_stable` | Hafalan Records | Progres stabil |
| `tahsin_mad_weak` | Tahsin Assessment | Skill mad perlu penguatan |
| `attendance_absence_pattern` | Attendance | Sering absen |
| `mutabaah_low_consistency` | Mutabaah | Aktivitas harian belum konsisten |
| `lms_content_incomplete` | LMS Lite | Materi belum selesai |
| `teacher_note_needs_review` | Teacher notes | Guru memberi catatan perhatian |

Signal harus disimpan sebagai data terstruktur agar bisa diaudit.

---

## 5.3 Practice Plan

Practice plan adalah rencana latihan personal santri.

Contoh practice plan:

1. Hari 1: Murajaah halaman 12 baris 1–7.
2. Hari 2: Latihan mad thabi'i dari materi LMS Lite.
3. Hari 3: Setoran ulang bagian yang sering salah.
4. Hari 4: Review catatan guru.
5. Hari 5: Latihan stabilisasi target harian.

Practice plan tidak boleh menjadi kewajiban resmi kecuali guru mengonfirmasi.

Status plan:

| Status | Makna |
|---|---|
| `draft` | Dibuat sistem, belum direview |
| `teacher_reviewed` | Sudah direview guru |
| `published` | Bisa dilihat parent/student |
| `archived` | Tidak aktif |

---

## 5.4 Teacher Feedback Draft

AI boleh membantu guru membuat draft feedback.

Contoh:

```text
Ananda sudah menunjukkan konsistensi murajaah yang baik. Fokus pekan ini adalah memperkuat bacaan mad dan menjaga target 5 baris per hari. Mohon orang tua mendampingi murajaah ringan 10 menit setelah Maghrib.
```

Draft feedback harus:

1. Bisa diedit guru.
2. Tidak terkirim otomatis.
3. Tidak masuk laporan resmi sebelum disetujui.
4. Mencantumkan sumber data rekomendasi.
5. Tidak mengandung label negatif.

---

## 5.5 Parent Guidance Digest

Parent guidance digest adalah ringkasan aman untuk orang tua.

Konten parent digest:

1. Progres umum anak.
2. Fokus latihan pekan ini.
3. Saran pendampingan di rumah.
4. Materi LMS Lite yang disarankan.
5. Catatan yang sudah disetujui guru.

Parent digest tidak boleh menampilkan:

1. Data anak lain.
2. Ranking sensitif.
3. Catatan internal guru yang belum dipublikasikan.
4. Label negatif.
5. Prediksi masa depan yang pasti.

---

## 5.6 Student Learning Assistant

Student learning assistant adalah halaman rekomendasi belajar untuk santri.

Fitur:

1. Fokus latihan hari ini.
2. Materi yang disarankan.
3. Target ringan.
4. Reminder positif.
5. Riwayat practice plan.

Student assistant tidak boleh berupa chatbot bebas.

Phase 25 hanya membuat assistant berbasis kartu rekomendasi, bukan percakapan open-ended.

---

# 6. Target Output Phase 25

Setelah Phase 25 selesai, aplikasi harus punya:

1. Menu **AI Learning Assistant**.
2. Menu **Learning Profiles**.
3. Menu **Practice Plans**.
4. Menu **Teacher AI Review Queue**.
5. Menu **AI Safety & Audit**.
6. AI feature flag per tenant.
7. Learning signal aggregator.
8. Rule-based recommendation engine.
9. Practice plan generator.
10. Teacher feedback draft tool.
11. Parent guidance digest.
12. Student recommendation page.
13. Audit log semua request/output AI-assisted.
14. Safety guard untuk bahasa/label negatif.
15. Dokumentasi privacy dan safety.
16. Update `docs/project-progress.md`.

---

# 7. Database Design Phase 25

## 7.1 Tabel yang Dibuat

Phase 25 membuat tabel:

1. `ai_feature_flags`
2. `ai_learning_profiles`
3. `ai_learning_signals`
4. `ai_learning_recommendations`
5. `ai_practice_plans`
6. `ai_practice_plan_items`
7. `ai_feedback_templates`
8. `ai_assistance_requests`
9. `ai_assistance_outputs`
10. `ai_teacher_review_queue`
11. `ai_safety_events`
12. `ai_audit_logs`

---

## 7.2 Prinsip Database

1. Semua tabel tenant-owned harus punya `school_id`.
2. Semua data siswa harus punya `student_id` jika terkait individu.
3. Output AI harus bisa dilacak ke input/signal.
4. Output AI tidak boleh overwrite data akademik asli.
5. Output AI tidak boleh menjadi sumber kebenaran tunggal.
6. Semua output yang dipublikasikan harus punya review status.
7. Gunakan JSON hanya untuk metadata fleksibel, bukan untuk data inti yang sering difilter.
8. Jangan menyimpan prompt berisi data pribadi mentah jika tidak perlu.
9. Jangan menyimpan rahasia API pada database biasa.
10. Jangan simpan audio/foto/video di Phase 25.

---

# 8. Role Access Phase 25

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

| Role | Learning Profile | Recommendation | Practice Plan | Teacher Review | Safety Log | Portal |
|---|---|---|---|---|---|---|
| Super Admin | Semua tenant | Semua | Semua | Semua | Semua | Tidak |
| Admin | Sekolah sendiri | Sekolah sendiri | Sekolah sendiri | Bisa review terbatas | Sekolah sendiri | Tidak |
| Kepala Sekolah | Read-only | Read-only | Read-only | Tidak default | Read-only | Tidak |
| Teacher/Guru | Santri scope | Santri scope | Create/review scope | Ya | Tidak default | Tidak |
| Parent | Anak sendiri yang sudah published | Anak sendiri published | Anak sendiri published | Tidak | Tidak | Ya |
| Student | Diri sendiri published | Diri sendiri published | Diri sendiri published | Tidak | Tidak | Ya |

Aturan keras:

1. Parent hanya melihat rekomendasi anak sendiri.
2. Student hanya melihat rekomendasi diri sendiri.
3. Parent/student hanya melihat output yang statusnya `published`.
4. Draft AI tidak boleh terlihat parent/student.
5. Teacher hanya melihat santri dalam scope-nya.
6. Kepala sekolah read-only.
7. Safety log hanya untuk admin/super admin.
8. Semua action review harus tercatat.
9. Tidak ada output AI yang otomatis menjadi laporan resmi.

---

# 9. Validasi Awal Sebelum Eksekusi

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
2. Phase 0 selesai.
3. Phase 1 selesai.
4. Phase 2 selesai.
5. Phase 3 selesai.
6. Phase 4 selesai.
7. Phase 5 selesai.
8. Phase 6 selesai.
9. Phase 7 selesai.
10. Phase 8 selesai.
11. Phase 9 selesai.
12. Phase 10 selesai.
13. Phase 11 selesai.
14. Phase 12 selesai.
15. Phase 13 selesai.
16. Phase 14 selesai.
17. Phase 15 selesai.
18. Phase 16 selesai.
19. Phase 17 selesai.
20. Phase 18 selesai.
21. Phase 19 selesai.
22. Phase 20 selesai.
23. Phase 21 selesai.
24. Phase 22 selesai.
25. Phase 23 selesai.
26. Phase 24 selesai dan UAT aman.
27. Tenant isolation berjalan.
28. LMS Lite content tersedia.
29. Analytics snapshot tersedia.
30. Tidak ada bug P0/P1 terbuka.
31. Working tree bersih atau semua perubahan diketahui.

Jika Phase 24 belum aman, hentikan Phase 25.

---

# 10. Buat Branch Git Phase 25

Jalankan:

```powershell
git checkout -b phase-25-ai-assisted-quran-learning
```

Jika branch sudah ada:

```powershell
git checkout phase-25-ai-assisted-quran-learning
```

---

# 11. Struktur File yang Akan Dibuat

Agent harus membuat atau mengubah file berikut:

```text
app/
├── Console/
│   └── Commands/
│       ├── GenerateAiLearningProfilesCommand.php
│       ├── GenerateAiPracticePlansCommand.php
│       └── PruneAiAuditLogsCommand.php
├── Http/
│   ├── Controllers/
│   │   ├── Ai/
│   │   │   ├── AiLearningDashboardController.php
│   │   │   ├── AiLearningProfileController.php
│   │   │   ├── AiRecommendationController.php
│   │   │   ├── AiPracticePlanController.php
│   │   │   ├── AiTeacherReviewQueueController.php
│   │   │   ├── AiFeedbackDraftController.php
│   │   │   ├── AiFeatureFlagController.php
│   │   │   └── AiSafetyEventController.php
│   │   └── Portal/
│   │       ├── ParentAiLearningPortalController.php
│   │       └── StudentAiLearningPortalController.php
│   └── Requests/
│       └── Ai/
│           ├── StoreAiFeatureFlagRequest.php
│           ├── UpdateAiFeatureFlagRequest.php
│           ├── GeneratePracticePlanRequest.php
│           ├── StoreTeacherFeedbackDraftRequest.php
│           ├── ReviewAiOutputRequest.php
│           └── AiLearningFilterRequest.php
├── Models/
│   ├── AiFeatureFlag.php
│   ├── AiLearningProfile.php
│   ├── AiLearningSignal.php
│   ├── AiLearningRecommendation.php
│   ├── AiPracticePlan.php
│   ├── AiPracticePlanItem.php
│   ├── AiFeedbackTemplate.php
│   ├── AiAssistanceRequest.php
│   ├── AiAssistanceOutput.php
│   ├── AiTeacherReviewQueue.php
│   ├── AiSafetyEvent.php
│   └── AiAuditLog.php
└── Services/
    └── Ai/
        ├── AiAccessService.php
        ├── AiFeatureFlagService.php
        ├── LearningSignalAggregator.php
        ├── QuranLearningProfileService.php
        ├── RuleBasedRecommendationEngine.php
        ├── PracticePlanGenerator.php
        ├── TeacherFeedbackDraftService.php
        ├── ParentGuidanceDigestService.php
        ├── StudentLearningAssistantService.php
        ├── AiTeacherReviewService.php
        ├── AiSafetyGuardService.php
        └── AiAuditLogger.php

database/
├── migrations/
│   ├── xxxx_xx_xx_xxxxxx_create_ai_feature_flags_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_ai_learning_profiles_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_ai_learning_signals_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_ai_learning_recommendations_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_ai_practice_plans_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_ai_practice_plan_items_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_ai_feedback_templates_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_ai_assistance_requests_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_ai_assistance_outputs_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_ai_teacher_review_queue_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_ai_safety_events_table.php
│   └── xxxx_xx_xx_xxxxxx_create_ai_audit_logs_table.php
└── seeders/
    ├── AiFeatureFlagSeeder.php
    └── AiFeedbackTemplateSeeder.php

resources/
└── views/
    ├── ai/
    │   ├── dashboard.blade.php
    │   ├── feature-flags/
    │   │   ├── index.blade.php
    │   │   └── edit.blade.php
    │   ├── learning-profiles/
    │   │   ├── index.blade.php
    │   │   └── show.blade.php
    │   ├── recommendations/
    │   │   ├── index.blade.php
    │   │   └── show.blade.php
    │   ├── practice-plans/
    │   │   ├── index.blade.php
    │   │   ├── create.blade.php
    │   │   ├── show.blade.php
    │   │   └── edit.blade.php
    │   ├── feedback-drafts/
    │   │   ├── create.blade.php
    │   │   └── show.blade.php
    │   ├── review-queue/
    │   │   ├── index.blade.php
    │   │   └── show.blade.php
    │   └── safety-events/
    │       ├── index.blade.php
    │       └── show.blade.php
    └── portal/
        ├── parent/
        │   └── ai-learning.blade.php
        └── student/
            └── ai-learning.blade.php

routes/
└── web.php

docs/
├── phase-25-execution.md
├── phase-25-ai-assisted-quran-learning.md
├── ai-learning-safety-policy.md
├── ai-data-privacy-policy.md
├── ai-human-review-policy.md
├── ai-recommendation-rules.md
└── ai-parent-student-disclaimer.md
```

---

# 12. Buat Model, Migration, Seeder, Controller, Request, Command

Jalankan:

```powershell
php artisan make:model AiFeatureFlag -m
php artisan make:model AiLearningProfile -m
php artisan make:model AiLearningSignal -m
php artisan make:model AiLearningRecommendation -m
php artisan make:model AiPracticePlan -m
php artisan make:model AiPracticePlanItem -m
php artisan make:model AiFeedbackTemplate -m
php artisan make:model AiAssistanceRequest -m
php artisan make:model AiAssistanceOutput -m
php artisan make:model AiTeacherReviewQueue -m
php artisan make:model AiSafetyEvent -m
php artisan make:model AiAuditLog -m

php artisan make:seeder AiFeatureFlagSeeder
php artisan make:seeder AiFeedbackTemplateSeeder

php artisan make:controller Ai/AiLearningDashboardController
php artisan make:controller Ai/AiLearningProfileController
php artisan make:controller Ai/AiRecommendationController
php artisan make:controller Ai/AiPracticePlanController
php artisan make:controller Ai/AiTeacherReviewQueueController
php artisan make:controller Ai/AiFeedbackDraftController
php artisan make:controller Ai/AiFeatureFlagController
php artisan make:controller Ai/AiSafetyEventController
php artisan make:controller Portal/ParentAiLearningPortalController
php artisan make:controller Portal/StudentAiLearningPortalController

php artisan make:request Ai/StoreAiFeatureFlagRequest
php artisan make:request Ai/UpdateAiFeatureFlagRequest
php artisan make:request Ai/GeneratePracticePlanRequest
php artisan make:request Ai/StoreTeacherFeedbackDraftRequest
php artisan make:request Ai/ReviewAiOutputRequest
php artisan make:request Ai/AiLearningFilterRequest

php artisan make:command GenerateAiLearningProfilesCommand
php artisan make:command GenerateAiPracticePlansCommand
php artisan make:command PruneAiAuditLogsCommand
```

Buat folder service:

```powershell
mkdir app\Services\Ai
```

Buat file service:

```powershell
New-Item app\Services\Ai\AiAccessService.php
New-Item app\Services\Ai\AiFeatureFlagService.php
New-Item app\Services\Ai\LearningSignalAggregator.php
New-Item app\Services\Ai\QuranLearningProfileService.php
New-Item app\Services\Ai\RuleBasedRecommendationEngine.php
New-Item app\Services\Ai\PracticePlanGenerator.php
New-Item app\Services\Ai\TeacherFeedbackDraftService.php
New-Item app\Services\Ai\ParentGuidanceDigestService.php
New-Item app\Services\Ai\StudentLearningAssistantService.php
New-Item app\Services\Ai\AiTeacherReviewService.php
New-Item app\Services\Ai\AiSafetyGuardService.php
New-Item app\Services\Ai\AiAuditLogger.php
```

Buat folder view:

```powershell
mkdir resources\views\ai
mkdir resources\views\ai\feature-flags
mkdir resources\views\ai\learning-profiles
mkdir resources\views\ai\recommendations
mkdir resources\views\ai\practice-plans
mkdir resources\views\ai\feedback-drafts
mkdir resources\views\ai\review-queue
mkdir resources\views\ai\safety-events
```

Jika folder portal sudah ada, jangan hapus.

---

# 13. Migration `ai_feature_flags`

Isi migration:

```php
Schema::create('ai_feature_flags', function (Blueprint $table): void {
    $table->id();

    $table->foreignId('school_id')
        ->constrained('schools')
        ->cascadeOnDelete();

    $table->string('feature_key');
    $table->string('label');
    $table->boolean('is_enabled')->default(false);
    $table->boolean('requires_teacher_review')->default(true);
    $table->boolean('visible_to_parent')->default(false);
    $table->boolean('visible_to_student')->default(false);
    $table->json('settings')->nullable();
    $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
    $table->timestamps();

    $table->unique(['school_id', 'feature_key']);
    $table->index(['school_id', 'is_enabled']);
});
```

Feature key awal:

1. `learning_profile`
2. `practice_plan`
3. `teacher_feedback_draft`
4. `parent_guidance_digest`
5. `student_learning_assistant`
6. `lms_content_recommendation`

Default semua `is_enabled = false` kecuali admin mengaktifkan.

---

# 14. Migration `ai_learning_profiles`

Isi migration:

```php
Schema::create('ai_learning_profiles', function (Blueprint $table): void {
    $table->id();

    $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
    $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();

    $table->date('profile_date');
    $table->string('profile_status')->default('draft');
    $table->unsignedSmallInteger('confidence_score')->default(0);

    $table->string('tahfizh_trend')->nullable();
    $table->string('tahsin_trend')->nullable();
    $table->string('mutabaah_trend')->nullable();
    $table->string('attendance_trend')->nullable();
    $table->string('lms_engagement_trend')->nullable();

    $table->text('summary')->nullable();
    $table->json('strengths')->nullable();
    $table->json('focus_areas')->nullable();
    $table->json('evidence')->nullable();

    $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
    $table->timestamp('reviewed_at')->nullable();
    $table->foreignId('published_by')->nullable()->constrained('users')->nullOnDelete();
    $table->timestamp('published_at')->nullable();

    $table->timestamps();

    $table->unique(['student_id', 'profile_date']);
    $table->index(['school_id', 'profile_date']);
    $table->index(['school_id', 'profile_status']);
});
```

Allowed `profile_status`:

1. `draft`
2. `teacher_reviewed`
3. `published`
4. `archived`

---

# 15. Migration `ai_learning_signals`

Isi migration:

```php
Schema::create('ai_learning_signals', function (Blueprint $table): void {
    $table->id();

    $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
    $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
    $table->foreignId('ai_learning_profile_id')->nullable()->constrained('ai_learning_profiles')->nullOnDelete();

    $table->date('signal_date');
    $table->string('source_module');
    $table->string('signal_key');
    $table->string('severity')->default('info');
    $table->decimal('score', 8, 2)->nullable();
    $table->text('description')->nullable();
    $table->json('evidence')->nullable();

    $table->timestamps();

    $table->index(['school_id', 'student_id', 'signal_date']);
    $table->index(['source_module', 'signal_key']);
    $table->index(['severity']);
});
```

Allowed `severity`:

1. `info`
2. `positive`
3. `attention`
4. `urgent`

Jangan memakai severity yang menghakimi seperti:

1. `bad_student`
2. `lazy`
3. `failed`
4. `hopeless`

---

# 16. Migration `ai_learning_recommendations`

Isi migration:

```php
Schema::create('ai_learning_recommendations', function (Blueprint $table): void {
    $table->id();

    $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
    $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
    $table->foreignId('ai_learning_profile_id')->nullable()->constrained('ai_learning_profiles')->nullOnDelete();

    $table->string('recommendation_type');
    $table->string('priority')->default('normal');
    $table->string('status')->default('draft');
    $table->string('title');
    $table->text('description')->nullable();
    $table->json('recommended_actions')->nullable();
    $table->json('evidence')->nullable();
    $table->json('related_content')->nullable();

    $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
    $table->timestamp('reviewed_at')->nullable();
    $table->timestamp('published_at')->nullable();

    $table->timestamps();

    $table->index(['school_id', 'student_id']);
    $table->index(['recommendation_type']);
    $table->index(['priority']);
    $table->index(['status']);
});
```

Allowed `recommendation_type`:

1. `tahfizh_practice`
2. `murajaah`
3. `tahsin_focus`
4. `mutabaah_consistency`
5. `attendance_follow_up`
6. `lms_content`
7. `parent_support`
8. `teacher_follow_up`

Allowed `priority`:

1. `low`
2. `normal`
3. `high`
4. `urgent`

Allowed `status`:

1. `draft`
2. `reviewed`
3. `published`
4. `dismissed`
5. `archived`

---

# 17. Migration `ai_practice_plans`

Isi migration:

```php
Schema::create('ai_practice_plans', function (Blueprint $table): void {
    $table->id();

    $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
    $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
    $table->foreignId('ai_learning_recommendation_id')->nullable()->constrained('ai_learning_recommendations')->nullOnDelete();

    $table->string('title');
    $table->text('description')->nullable();
    $table->date('start_date');
    $table->date('end_date');
    $table->string('status')->default('draft');
    $table->string('generated_by_type')->default('system');

    $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
    $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
    $table->timestamp('reviewed_at')->nullable();
    $table->timestamp('published_at')->nullable();

    $table->timestamps();

    $table->index(['school_id', 'student_id']);
    $table->index(['status']);
    $table->index(['start_date', 'end_date']);
});
```

Allowed `status`:

1. `draft`
2. `teacher_reviewed`
3. `published`
4. `completed`
5. `archived`

---

# 18. Migration `ai_practice_plan_items`

Isi migration:

```php
Schema::create('ai_practice_plan_items', function (Blueprint $table): void {
    $table->id();

    $table->foreignId('ai_practice_plan_id')->constrained('ai_practice_plans')->cascadeOnDelete();
    $table->date('practice_date');
    $table->string('item_type');
    $table->string('title');
    $table->text('description')->nullable();
    $table->unsignedSmallInteger('estimated_minutes')->default(10);
    $table->json('linked_content')->nullable();
    $table->string('completion_status')->default('not_started');
    $table->timestamp('completed_at')->nullable();
    $table->timestamps();

    $table->index(['practice_date']);
    $table->index(['item_type']);
    $table->index(['completion_status']);
});
```

Allowed `item_type`:

1. `murajaah`
2. `hafalan_target`
3. `tahsin_practice`
4. `lms_content`
5. `teacher_note_review`
6. `parent_home_support`

Allowed `completion_status`:

1. `not_started`
2. `in_progress`
3. `done`
4. `skipped`

---

# 19. Migration `ai_feedback_templates`

Isi migration:

```php
Schema::create('ai_feedback_templates', function (Blueprint $table): void {
    $table->id();
    $table->foreignId('school_id')->nullable()->constrained('schools')->nullOnDelete();
    $table->string('template_key');
    $table->string('title');
    $table->text('body_template');
    $table->string('tone')->default('supportive');
    $table->boolean('is_active')->default(true);
    $table->timestamps();

    $table->unique(['school_id', 'template_key']);
    $table->index(['template_key']);
    $table->index(['is_active']);
});
```

Template awal:

1. `tahfizh_progress_positive`
2. `tahfizh_need_consistency`
3. `tahsin_focus_area`
4. `murajaah_reminder`
5. `parent_home_support`
6. `lms_content_suggestion`

---

# 20. Migration `ai_assistance_requests`

Isi migration:

```php
Schema::create('ai_assistance_requests', function (Blueprint $table): void {
    $table->id();

    $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
    $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
    $table->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();

    $table->string('request_type');
    $table->string('status')->default('pending');
    $table->json('input_context')->nullable();
    $table->json('sanitized_context')->nullable();
    $table->text('purpose')->nullable();
    $table->timestamp('processed_at')->nullable();
    $table->timestamps();

    $table->index(['school_id', 'request_type']);
    $table->index(['status']);
    $table->index(['student_id']);
});
```

Allowed `request_type`:

1. `generate_learning_profile`
2. `generate_recommendation`
3. `generate_practice_plan`
4. `draft_teacher_feedback`
5. `generate_parent_digest`
6. `generate_student_guidance`

---

# 21. Migration `ai_assistance_outputs`

Isi migration:

```php
Schema::create('ai_assistance_outputs', function (Blueprint $table): void {
    $table->id();

    $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
    $table->foreignId('ai_assistance_request_id')->constrained('ai_assistance_requests')->cascadeOnDelete();
    $table->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();

    $table->string('output_type');
    $table->string('status')->default('draft');
    $table->text('title')->nullable();
    $table->longText('body')->nullable();
    $table->json('structured_output')->nullable();
    $table->json('evidence')->nullable();
    $table->json('safety_checks')->nullable();

    $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
    $table->timestamp('reviewed_at')->nullable();
    $table->timestamps();

    $table->index(['school_id', 'output_type']);
    $table->index(['status']);
});
```

---

# 22. Migration `ai_teacher_review_queue`

Isi migration:

```php
Schema::create('ai_teacher_review_queue', function (Blueprint $table): void {
    $table->id();

    $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
    $table->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
    $table->foreignId('ai_assistance_output_id')->nullable()->constrained('ai_assistance_outputs')->nullOnDelete();

    $table->string('review_type');
    $table->string('status')->default('pending');
    $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
    $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
    $table->text('review_note')->nullable();
    $table->timestamp('reviewed_at')->nullable();
    $table->timestamps();

    $table->index(['school_id', 'status']);
    $table->index(['assigned_to']);
    $table->index(['review_type']);
});
```

Allowed `status`:

1. `pending`
2. `approved`
3. `edited`
4. `rejected`
5. `published`

---

# 23. Migration `ai_safety_events`

Isi migration:

```php
Schema::create('ai_safety_events', function (Blueprint $table): void {
    $table->id();

    $table->foreignId('school_id')->nullable()->constrained('schools')->nullOnDelete();
    $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
    $table->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();

    $table->string('event_type');
    $table->string('severity')->default('info');
    $table->text('description')->nullable();
    $table->json('metadata')->nullable();
    $table->timestamp('resolved_at')->nullable();
    $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
    $table->timestamps();

    $table->index(['school_id', 'event_type']);
    $table->index(['severity']);
    $table->index(['resolved_at']);
});
```

Safety event examples:

1. `negative_label_detected`
2. `unreviewed_output_access_attempt`
3. `external_ai_disabled_attempt`
4. `parent_access_blocked`
5. `student_access_blocked`
6. `sensitive_data_redacted`

---

# 24. Migration `ai_audit_logs`

Isi migration:

```php
Schema::create('ai_audit_logs', function (Blueprint $table): void {
    $table->id();

    $table->foreignId('school_id')->nullable()->constrained('schools')->nullOnDelete();
    $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
    $table->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();

    $table->string('action');
    $table->string('entity_type')->nullable();
    $table->unsignedBigInteger('entity_id')->nullable();
    $table->json('before')->nullable();
    $table->json('after')->nullable();
    $table->ipAddress('ip_address')->nullable();
    $table->string('user_agent')->nullable();
    $table->timestamps();

    $table->index(['school_id', 'action']);
    $table->index(['entity_type', 'entity_id']);
    $table->index(['student_id']);
});
```

---

# 25. Model Fillable dan Relasi

Setiap model harus punya `$fillable` sesuai kolom yang aman.

Contoh `AiLearningProfile`:

```php
protected $fillable = [
    'school_id',
    'student_id',
    'profile_date',
    'profile_status',
    'confidence_score',
    'tahfizh_trend',
    'tahsin_trend',
    'mutabaah_trend',
    'attendance_trend',
    'lms_engagement_trend',
    'summary',
    'strengths',
    'focus_areas',
    'evidence',
    'reviewed_by',
    'reviewed_at',
    'published_by',
    'published_at',
];

protected $casts = [
    'profile_date' => 'date',
    'strengths' => 'array',
    'focus_areas' => 'array',
    'evidence' => 'array',
    'reviewed_at' => 'datetime',
    'published_at' => 'datetime',
];
```

Relasi minimal:

```php
public function school(): BelongsTo
{
    return $this->belongsTo(School::class);
}

public function student(): BelongsTo
{
    return $this->belongsTo(Student::class);
}

public function signals(): HasMany
{
    return $this->hasMany(AiLearningSignal::class);
}

public function recommendations(): HasMany
{
    return $this->hasMany(AiLearningRecommendation::class);
}
```

Tambahkan relasi serupa pada model lain sesuai foreign key.

---

# 26. Service `AiAccessService`

Buat service untuk akses data AI.

Tanggung jawab:

1. Validasi tenant access.
2. Validasi teacher scope.
3. Validasi parent-child ownership.
4. Validasi student self-access.
5. Validasi published-only access untuk parent/student.
6. Validasi admin/super admin.

Method minimal:

```php
class AiAccessService
{
    public function canViewStudentAiData(User $user, Student $student): bool
    {
        // super admin/admin/kepala sekolah/teacher scope/parent own child/student self
    }

    public function canManageAiSettings(User $user, int $schoolId): bool
    {
        // super admin/admin only
    }

    public function canReviewAiOutput(User $user, Student $student): bool
    {
        // teacher scope/admin
    }

    public function canViewPublishedAiOutput(User $user, Student $student): bool
    {
        // parent/student ownership only, published output only
    }
}
```

Aturan:

1. Jangan duplikasi access logic di controller.
2. Semua controller AI wajib memakai service ini.
3. Semua unauthorized access harus menghasilkan `403`.
4. Safety event harus dibuat untuk access attempt sensitif.

---

# 27. Service `LearningSignalAggregator`

Tanggung jawab:

1. Ambil data tahfizh terbaru.
2. Ambil data tahsin terbaru.
3. Ambil data mutabaah terbaru.
4. Ambil attendance trend.
5. Ambil LMS Lite completion.
6. Ambil analytics student metrics jika tersedia.
7. Buat signal terstruktur.

Contoh signal generation:

```php
public function generateForStudent(Student $student, CarbonInterface $date): array
{
    return [
        $this->buildTahfizhSignal($student, $date),
        $this->buildTahsinSignal($student, $date),
        $this->buildMutabaahSignal($student, $date),
        $this->buildAttendanceSignal($student, $date),
        $this->buildLmsSignal($student, $date),
    ];
}
```

Signal harus explainable.

Setiap signal wajib punya:

1. `source_module`
2. `signal_key`
3. `severity`
4. `score`
5. `description`
6. `evidence`

---

# 28. Service `RuleBasedRecommendationEngine`

Pada Phase 25, gunakan rule-based engine dulu.

Jangan langsung membuat LLM integration.

Contoh rule:

| Kondisi | Rekomendasi |
|---|---|
| Hutang hafalan naik 3 hari berturut-turut | Buat plan murajaah ringan 7 hari |
| Tahsin skill `mad` weak | Rekomendasikan materi LMS Lite tentang mad |
| Attendance sering absent | Buat teacher follow-up recommendation |
| Mutabaah rendah | Buat parent support digest |
| LMS materi terkait belum selesai | Rekomendasikan konten tersebut |

Method minimal:

```php
class RuleBasedRecommendationEngine
{
    public function generate(Student $student, Collection $signals): array
    {
        $recommendations = [];

        // Rule 1: Tahfizh debt increasing
        // Rule 2: Tahsin weak skill
        // Rule 3: LMS content suggestion
        // Rule 4: Parent home support

        return $recommendations;
    }
}
```

Setiap recommendation wajib punya:

1. `recommendation_type`
2. `priority`
3. `title`
4. `description`
5. `recommended_actions`
6. `evidence`
7. `related_content`

---

# 29. Service `QuranLearningProfileService`

Tanggung jawab:

1. Membuat learning profile.
2. Menghubungkan profile dengan signals.
3. Membuat summary aman.
4. Menentukan strengths dan focus areas.
5. Menyimpan confidence score.

Confidence score bukan nilai siswa.

Confidence score berarti:

```text
Seberapa cukup data untuk membuat rekomendasi.
```

Contoh:

| Data tersedia | Confidence |
|---|---:|
| Data tahfizh saja | 30 |
| Tahfizh + tahsin | 50 |
| Tahfizh + tahsin + mutabaah + attendance | 75 |
| Semua + LMS + analytics | 90 |

Jangan menampilkan confidence sebagai ranking siswa.

---

# 30. Service `PracticePlanGenerator`

Tanggung jawab:

1. Membuat practice plan 3–7 hari.
2. Menggunakan rekomendasi yang sudah ada.
3. Menghubungkan materi LMS Lite.
4. Menghindari jadwal terlalu berat.
5. Membuat status awal `draft`.
6. Mengirim ke teacher review queue.

Aturan:

1. Maksimal 3 item latihan per hari.
2. Estimasi default 10–20 menit per item.
3. Jangan memberi beban berlebihan.
4. Jangan membuat target yang bertentangan dengan target guru.
5. Jangan mengubah target resmi tahfizh.
6. Jangan membuat tagihan/finance/attendance action.

---

# 31. Service `TeacherFeedbackDraftService`

Tanggung jawab:

1. Membuat draft feedback dari template.
2. Menggunakan data learning profile dan recommendation.
3. Menjaga tone positif.
4. Menghindari label negatif.
5. Menyimpan output sebagai draft.
6. Memasukkan ke review queue.

Prohibited wording:

1. malas
2. gagal
3. buruk
4. tidak mampu
5. tertinggal parah
6. tidak punya harapan
7. lemah secara permanen

Preferred wording:

1. perlu pendampingan
2. perlu penguatan
3. perlu konsistensi
4. fokus pekan ini
5. sudah mulai berkembang
6. dapat dibantu dengan latihan ringan

---

# 32. Service `AiSafetyGuardService`

Tanggung jawab:

1. Filter kata negatif.
2. Cegah output tanpa disclaimer.
3. Cek status review.
4. Redact data sensitif dari output.
5. Catat safety event.
6. Blokir parent/student melihat draft.

Method minimal:

```php
class AiSafetyGuardService
{
    public function validateOutput(string $text): array
    {
        // returns ['safe' => bool, 'issues' => []]
    }

    public function sanitizeForParent(string $text): string
    {
        // remove internal-only phrases and add disclaimer
    }

    public function sanitizeForStudent(string $text): string
    {
        // age-appropriate wording
    }
}
```

Disclaimer wajib:

```text
Rekomendasi ini dibuat oleh sistem untuk membantu proses belajar dan telah/harus direview oleh guru. Keputusan pembelajaran tetap mengikuti arahan guru.
```

---

# 33. Service `AiAuditLogger`

Semua action penting harus tercatat:

1. Generate learning profile.
2. Generate recommendation.
3. Generate practice plan.
4. Create feedback draft.
5. Review output.
6. Publish output.
7. Dismiss output.
8. Parent/student view published output.
9. Unauthorized access attempt.
10. Feature flag update.

Method minimal:

```php
class AiAuditLogger
{
    public function log(
        string $action,
        ?User $user = null,
        ?Student $student = null,
        ?string $entityType = null,
        ?int $entityId = null,
        array $before = [],
        array $after = []
    ): void {
        // insert ai_audit_logs
    }
}
```

---

# 34. Controller Requirements

## 34.1 `AiLearningDashboardController`

Fitur:

1. Ringkasan jumlah profile draft.
2. Ringkasan recommendation pending.
3. Ringkasan practice plan draft.
4. Review queue count.
5. Safety event count.
6. Filter school/tenant.

Akses:

1. Super admin.
2. Admin.
3. Kepala sekolah read-only.
4. Teacher limited scope.

---

## 34.2 `AiLearningProfileController`

Fitur:

1. Index profile.
2. Show profile.
3. Generate/regenerate profile untuk admin/teacher authorized.
4. Review profile.
5. Publish profile.
6. Archive profile.

Parent/student tidak boleh akses controller internal ini.

---

## 34.3 `AiPracticePlanController`

Fitur:

1. Index plan.
2. Create plan manual assisted.
3. Generate plan dari recommendation.
4. Show plan.
5. Edit draft plan.
6. Submit for review.
7. Publish plan.
8. Archive plan.

---

## 34.4 `AiTeacherReviewQueueController`

Fitur:

1. List pending output.
2. Show output.
3. Approve.
4. Edit then approve.
5. Reject.
6. Publish.

Semua action harus audit logged.

---

## 34.5 `ParentAiLearningPortalController`

Fitur parent:

1. Melihat child published learning guidance.
2. Melihat published practice plan.
3. Melihat parent guidance digest.
4. Melihat recommended LMS content yang sudah published.

Tidak boleh:

1. Lihat draft.
2. Lihat output anak lain.
3. Generate output.
4. Review output.
5. Edit output.

---

## 34.6 `StudentAiLearningPortalController`

Fitur student:

1. Melihat practice plan published.
2. Melihat rekomendasi belajar sendiri.
3. Menandai practice item sebagai done/skipped.
4. Melihat materi LMS Lite yang disarankan.

Tidak boleh:

1. Lihat data siswa lain.
2. Lihat draft.
3. Generate output.
4. Mengubah learning profile.
5. Mengubah recommendation.

---

# 35. Route Design

Tambahkan route di `routes/web.php`.

Contoh:

```php
Route::middleware(['auth'])->group(function (): void {
    Route::prefix('ai-learning')->name('ai-learning.')->group(function (): void {
        Route::get('/', [AiLearningDashboardController::class, 'index'])->name('dashboard');

        Route::resource('feature-flags', AiFeatureFlagController::class)->only(['index', 'edit', 'update']);
        Route::resource('learning-profiles', AiLearningProfileController::class)->only(['index', 'show']);
        Route::post('learning-profiles/{student}/generate', [AiLearningProfileController::class, 'generate'])->name('learning-profiles.generate');
        Route::post('learning-profiles/{profile}/review', [AiLearningProfileController::class, 'review'])->name('learning-profiles.review');
        Route::post('learning-profiles/{profile}/publish', [AiLearningProfileController::class, 'publish'])->name('learning-profiles.publish');

        Route::resource('recommendations', AiRecommendationController::class)->only(['index', 'show']);
        Route::resource('practice-plans', AiPracticePlanController::class);
        Route::post('practice-plans/{practicePlan}/publish', [AiPracticePlanController::class, 'publish'])->name('practice-plans.publish');

        Route::get('feedback-drafts/create', [AiFeedbackDraftController::class, 'create'])->name('feedback-drafts.create');
        Route::post('feedback-drafts', [AiFeedbackDraftController::class, 'store'])->name('feedback-drafts.store');

        Route::get('review-queue', [AiTeacherReviewQueueController::class, 'index'])->name('review-queue.index');
        Route::get('review-queue/{reviewItem}', [AiTeacherReviewQueueController::class, 'show'])->name('review-queue.show');
        Route::post('review-queue/{reviewItem}/approve', [AiTeacherReviewQueueController::class, 'approve'])->name('review-queue.approve');
        Route::post('review-queue/{reviewItem}/reject', [AiTeacherReviewQueueController::class, 'reject'])->name('review-queue.reject');

        Route::get('safety-events', [AiSafetyEventController::class, 'index'])->name('safety-events.index');
        Route::get('safety-events/{safetyEvent}', [AiSafetyEventController::class, 'show'])->name('safety-events.show');
    });

    Route::get('portal/parent/ai-learning', [ParentAiLearningPortalController::class, 'index'])->name('portal.parent.ai-learning');
    Route::get('portal/student/ai-learning', [StudentAiLearningPortalController::class, 'index'])->name('portal.student.ai-learning');
});
```

Sesuaikan nama middleware role yang sudah ada di project.

---

# 36. Command Requirements

## 36.1 `GenerateAiLearningProfilesCommand`

Signature:

```php
protected $signature = 'app:ai-generate-learning-profiles {--school_id=} {--student_id=} {--date=}';
```

Tugas:

1. Ambil student sesuai filter.
2. Generate signals.
3. Generate learning profile.
4. Generate recommendations.
5. Simpan audit log.

Command tidak boleh publish otomatis.

---

## 36.2 `GenerateAiPracticePlansCommand`

Signature:

```php
protected $signature = 'app:ai-generate-practice-plans {--school_id=} {--student_id=} {--days=7}';
```

Tugas:

1. Ambil recommendations draft/reviewed.
2. Generate practice plan draft.
3. Masukkan ke teacher review queue.
4. Simpan audit log.

Command tidak boleh publish otomatis.

---

## 36.3 `PruneAiAuditLogsCommand`

Signature:

```php
protected $signature = 'app:ai-prune-audit-logs {--days=730}';
```

Tugas:

1. Hapus/arsip audit log lama sesuai policy.
2. Jangan hapus safety event unresolved.
3. Jangan hapus data review queue aktif.
4. Tulis summary hasil.

Catatan:

Retention policy final harus ikut kebijakan sekolah/perusahaan.

---

# 37. Seeder Requirements

## 37.1 `AiFeatureFlagSeeder`

Seed default feature flags untuk sekolah aktif.

Semua feature default:

```text
is_enabled = false
requires_teacher_review = true
visible_to_parent = false
visible_to_student = false
```

Admin harus mengaktifkan manual.

---

## 37.2 `AiFeedbackTemplateSeeder`

Seed template positif:

1. Tahfizh progress positive.
2. Tahfizh consistency support.
3. Tahsin focus area.
4. Murajaah reminder.
5. Parent home support.
6. LMS content suggestion.

Template tidak boleh mengandung wording negatif.

---

# 38. View Requirements

## 38.1 Dashboard AI

Tampilkan card:

1. Learning profiles generated.
2. Recommendations pending review.
3. Practice plans draft.
4. Published practice plans.
5. Safety events unresolved.
6. Feature flags status.

Tampilkan warning:

```text
AI Assistant hanya membantu rekomendasi. Semua output yang berdampak pada pembelajaran wajib direview guru.
```

---

## 38.2 Learning Profile Show

Tampilkan:

1. Student identity minimal.
2. Profile date.
3. Confidence score dengan penjelasan.
4. Tahfizh trend.
5. Tahsin trend.
6. Mutabaah trend.
7. Attendance trend.
8. LMS engagement.
9. Strengths.
10. Focus areas.
11. Evidence.
12. Review status.
13. Buttons review/publish sesuai role.

Jangan tampilkan:

1. Data finance.
2. Health/discipline sensitive notes.
3. Data siswa lain.

---

## 38.3 Practice Plan Show

Tampilkan:

1. Judul plan.
2. Periode.
3. Status.
4. Item per hari.
5. Linked LMS content.
6. Evidence.
7. Review/publish action.
8. Disclaimer.

---

## 38.4 Parent Portal AI Learning

Tampilkan hanya published:

1. Focus this week.
2. Practice plan approved.
3. Parent home support.
4. Suggested LMS content.
5. Teacher-approved note.

Wajib tampilkan disclaimer.

---

## 38.5 Student Portal AI Learning

Tampilkan hanya published:

1. Latihan hari ini.
2. Checklist practice item.
3. Suggested LMS content.
4. Positive encouragement.
5. Teacher-approved note.

Gunakan bahasa suportif dan ringkas.

---

# 39. Safety Rules

AI output harus menolak atau memperbaiki konten yang:

1. Menghakimi siswa.
2. Membuat prediksi masa depan absolut.
3. Memberi label negatif permanen.
4. Menyuruh tindakan hukuman.
5. Mengandung nasihat medis.
6. Mengandung fatwa agama.
7. Mengandung data siswa lain.
8. Mengandung data internal sekolah yang belum dipublikasi.
9. Mengandung data finance sensitif.
10. Mengandung catatan disiplin sensitif.

Contoh kalimat yang harus dihindari:

```text
Anak ini malas dan tertinggal parah.
```

Ganti dengan:

```text
Ananda perlu pendampingan tambahan dan latihan ringan yang lebih konsisten pada pekan ini.
```

---

# 40. Privacy Rules

Phase 25 wajib mengikuti aturan privacy:

1. Parent hanya melihat anak sendiri.
2. Student hanya melihat data sendiri.
3. Draft tidak boleh terlihat parent/student.
4. Output harus minim data pribadi.
5. Jangan tampilkan ranking lintas siswa.
6. Jangan tampilkan catatan guru internal tanpa review.
7. Jangan kirim data ke provider eksternal.
8. Jangan simpan prompt mentah berisi data sensitif.
9. Jangan simpan audio/foto/video.
10. Semua publish harus tercatat.

---

# 41. Testing Checklist

## 41.1 Command Test

Jalankan:

```powershell
php artisan migrate
php artisan db:seed --class=AiFeatureFlagSeeder
php artisan db:seed --class=AiFeedbackTemplateSeeder
php artisan app:ai-generate-learning-profiles --school_id=1
php artisan app:ai-generate-practice-plans --school_id=1 --days=7
php artisan app:system-health-check
npm run build
```

Target:

1. Migration berhasil.
2. Seeder berhasil.
3. Learning profile draft terbentuk.
4. Recommendation draft terbentuk.
5. Practice plan draft terbentuk.
6. Review queue terbentuk.
7. Tidak ada output published otomatis.

---

## 41.2 Role Test

Test minimal:

| Role | Test |
|---|---|
| Super Admin | Bisa lihat semua AI dashboard |
| Admin | Bisa manage feature flag sekolah sendiri |
| Kepala Sekolah | Bisa read-only profile/recommendation |
| Teacher | Bisa review output santri scope |
| Parent | Hanya lihat output published anak sendiri |
| Student | Hanya lihat output published diri sendiri |

---

## 41.3 Security Test

Wajib test:

1. Parent buka data anak lain → 403.
2. Student buka data siswa lain → 403.
3. Parent buka draft recommendation → 403.
4. Student buka review queue → 403.
5. Teacher buka santri luar scope → 403.
6. Admin tenant A buka tenant B → 403.
7. Feature disabled → menu tidak muncul.
8. Output negatif → safety event tercatat.
9. Publish tanpa review → ditolak.
10. External AI disabled by default.

---

## 41.4 Data Accuracy Test

Wajib cek:

1. Tahfizh debt signal sesuai data hutang.
2. Tahsin weak skill sesuai assessment.
3. Attendance signal sesuai record.
4. LMS recommendation sesuai materi yang tersedia.
5. Practice plan tidak melebihi 7 hari default.
6. Practice plan item masuk tanggal yang benar.
7. Recommendation punya evidence.
8. Learning profile punya confidence score.
9. Parent digest hanya berisi output approved.
10. Student assistant hanya berisi output approved.

---

# 42. Dokumentasi Wajib

Buat file:

```text
docs/phase-25-ai-assisted-quran-learning.md
docs/ai-learning-safety-policy.md
docs/ai-data-privacy-policy.md
docs/ai-human-review-policy.md
docs/ai-recommendation-rules.md
docs/ai-parent-student-disclaimer.md
```

Isi minimal:

## `ai-learning-safety-policy.md`

1. AI as assistant, not decision maker.
2. No auto grading.
3. No negative labeling.
4. Human review requirement.
5. Safety event process.
6. Parent/student output restrictions.

## `ai-data-privacy-policy.md`

1. No external data transfer by default.
2. No audio/video in Phase 25.
3. Data minimization.
4. Tenant isolation.
5. Parent/student ownership.
6. Retention policy.

## `ai-human-review-policy.md`

1. Draft.
2. Review.
3. Approve/edit/reject.
4. Publish.
5. Audit log.
6. Responsibility boundaries.

## `ai-recommendation-rules.md`

1. List of rules.
2. Evidence required.
3. Priority mapping.
4. Recommendation examples.
5. Prohibited output.

## `ai-parent-student-disclaimer.md`

1. Disclaimer text.
2. Parent-facing wording.
3. Student-facing wording.
4. Teacher review wording.

---

# 43. Update `docs/project-progress.md`

Tambahkan:

```md
## Phase 25 — AI-Assisted Qur’an Learning & Product Differentiation

Status: Completed

Output:
- AI feature flag foundation
- Learning profile
- Learning signal aggregator
- Rule-based recommendation engine
- Practice plan generator
- Teacher feedback draft
- Teacher review queue
- Parent guidance digest
- Student learning assistant
- AI safety events
- AI audit logs
- AI privacy and safety docs

Boundary:
- No voice AI
- No auto grading
- No external student data transfer by default
- No AI replacing teacher
- No public parent/student draft access
```

---

# 44. Quality Gate

Sebelum commit, jalankan:

```powershell
php artisan optimize:clear
php artisan migrate:status
php artisan route:list
php artisan app:system-health-check
npm run build
```

Jika project punya test suite:

```powershell
php artisan test
```

Jika belum ada test suite, minimal lakukan manual test role dan security checklist.

---

# 45. Definition of Done Phase 25

Phase 25 dianggap selesai jika:

1. Semua migration AI berhasil.
2. Semua model dan relasi tersedia.
3. AI feature flags tersedia per school/tenant.
4. Learning signals bisa dibuat dari data internal.
5. Learning profile draft bisa dibuat.
6. Recommendation draft bisa dibuat.
7. Practice plan draft bisa dibuat.
8. Teacher review queue berjalan.
9. Teacher bisa approve/edit/reject output.
10. Parent hanya melihat output anak sendiri yang sudah published.
11. Student hanya melihat output dirinya yang sudah published.
12. Safety guard memblokir wording negatif.
13. Safety event tercatat.
14. Audit log tercatat.
15. Tidak ada auto publish tanpa review.
16. Tidak ada external AI data transfer by default.
17. Tidak ada voice/audio AI.
18. Tidak ada auto grading resmi.
19. Dokumentasi safety/privacy/review selesai.
20. `npm run build` berhasil.
21. `php artisan app:system-health-check` berhasil.
22. Tidak ada bug P0/P1 terbuka.

---

# 46. Commit

Setelah semua selesai:

```powershell
git status
git add .
git commit -m "feat: add ai assisted quran learning foundation"
```

---

# 47. Setelah Phase 25

Setelah Phase 25, jangan langsung membuat AI voice atau chatbot bebas.

Urutan evaluasi berikutnya:

1. Pilot internal dengan guru tahfizh/tahsin.
2. Review kualitas rekomendasi.
3. Review wording parent/student.
4. Audit tenant isolation.
5. Audit privacy.
6. Audit safety events.
7. Minta feedback 1–3 sekolah.
8. Validasi apakah rekomendasi benar-benar membantu guru.

Jika hasil pilot bagus, kandidat fase lanjutan:

```text
Phase 26 — AI Governance, Consent Management & External AI Provider Readiness
```

Phase 26 baru membahas kesiapan jika suatu hari ingin memakai provider AI eksternal atau fitur AI yang lebih sensitif.

Jangan membuat AI voice correction sebelum Phase 26 dan policy/legal/privacy siap.
