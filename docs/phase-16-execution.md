Phase 16 Execution Guide — Boarding School Management System
0. Identitas Phase
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
Phase 16 — Boarding School Management System
```
---
1. Keputusan Sebelum Phase 16
1.1 UAT Phase 15 Wajib
Sebelum menjalankan Phase 16, agent wajib memastikan SchoolOS Mini sudah aman.
Jangan menambah Boarding Management jika SchoolOS Mini masih bermasalah.
Phase 16 hanya boleh dieksekusi jika:
SchoolOS dashboard tampil.
Role-based dashboard berjalan.
Module registry tampil.
Module health cards tampil.
Academic year aktif tampil.
School term aktif tampil.
School settings bisa disimpan.
Student 360 tampil.
Internal search bisa menemukan santri.
Parent hanya melihat anak sendiri.
Student hanya melihat data pribadi.
Guru hanya melihat santri sesuai scope.
Kepala sekolah read-only.
Admin bisa mengelola settings dan module registry.
`npm run build` berhasil.
`php artisan app:system-health-check` berhasil.
Backup database berhasil.
Tidak ada bug P0/P1 terbuka.
Jika masih ada bug P0/P1, hentikan Phase 16 dan buat bug fix sprint dulu.
---
1.2 Klasifikasi Bug Sebelum Phase 16
Prioritas	Contoh Bug	Keputusan
P0	Login gagal, data bocor, parent bisa lihat anak lain, student bisa lihat data santri lain, Student 360 bocor	Wajib fix sebelum Phase 16
P1	Dashboard SchoolOS error, module registry rusak, search salah scope, health check gagal	Wajib fix sebelum Phase 16
P2	UI kurang rapi, teks kurang jelas, spacing kurang bagus	Boleh dicatat
P3	Enhancement kosmetik	Boleh ditunda
---
2. Tujuan Phase 16
Phase 16 bertujuan membuat modul Boarding School Management System.
Modul ini digunakan untuk mengelola kebutuhan asrama/pesantren/boarding school:
Data asrama.
Data kamar.
Data ranjang/tempat tidur.
Penempatan santri ke asrama/kamar/ranjang.
Data pembina/wali asrama.
Perizinan keluar/pulang.
Catatan kesehatan santri.
Catatan kedisiplinan santri.
Roll call / pengecekan malam.
Dashboard boarding untuk admin, kepala sekolah, dan pembina.
Portal read-only untuk orang tua.
Portal read-only untuk santri.
Role-based access.
Ownership-based access.
Dokumentasi Phase 16.
---
3. Batasan Phase 16
AI agent tidak boleh membuat fitur berikut pada Phase 16:
Multi-tenant architecture.
White-label school app.
Domain/subdomain per sekolah.
Billing SaaS.
Cashless kantin.
Merchant POS.
Payment gateway.
Wallet.
QRIS otomatis.
Virtual account.
Mobile Android native.
Mobile iOS native.
Face recognition.
RFID.
NFC.
Fingerprint.
IoT door lock.
CCTV integration.
WhatsApp gateway.
Push notification.
Firebase.
Websocket.
LMS penuh.
Medical record kompleks.
Payroll pembina.
Marketplace konten.
AI behavior scoring.
Phase 16 hanya membuat:
```text
Boarding School Management System berbasis Laravel Blade + MySQL.
```
---
4. Konsep Boarding Management
4.1 Dormitory / Asrama
Asrama adalah bangunan atau unit tempat tinggal santri.
Contoh:
Asrama Putra.
Asrama Putri.
Asrama Tahfizh.
Asrama Kelas 7.
Asrama SMA.
Data minimal:
Nama asrama.
Jenis/gender.
Kapasitas.
Status aktif.
Catatan.
---
4.2 Room / Kamar
Room adalah kamar di dalam asrama.
Data minimal:
Asrama.
Nama kamar.
Lantai.
Kapasitas.
Status aktif.
---
4.3 Bed / Ranjang
Bed adalah tempat tidur individual di dalam kamar.
Status bed:
Status	Makna
`available`	Kosong
`occupied`	Terisi
`maintenance`	Tidak bisa dipakai
`inactive`	Tidak aktif
---
4.4 Student Assignment
Student assignment adalah data penempatan santri ke asrama, kamar, dan ranjang.
Aturan:
Satu santri hanya boleh punya satu assignment aktif.
Satu bed hanya boleh ditempati satu santri aktif.
Assignment lama tidak dihapus.
Jika pindah kamar, tutup assignment lama lalu buat assignment baru.
Riwayat penempatan harus tetap tersimpan.
Status assignment:
Status	Makna
`active`	Sedang ditempati
`moved`	Sudah pindah
`ended`	Sudah selesai
`cancelled`	Dibatalkan
---
4.5 Boarding Supervisor
Boarding supervisor adalah pembina/wali asrama.
Pada Phase 16, agent boleh menambahkan role baru:
```text
boarding_supervisor
```
Jika struktur role belum siap untuk role baru, gunakan role `teacher` atau `admin` sementara, tetapi tetap buat tabel profile pembina agar scope boarding bisa dikontrol.
Scope pembina:
Bisa ditugaskan ke asrama.
Bisa ditugaskan ke kamar tertentu.
Bisa mencatat roll call.
Bisa mencatat izin.
Bisa mencatat kesehatan.
Bisa mencatat kedisiplinan.
Tidak boleh mengakses finance.
Tidak boleh mengakses settings sistem.
Tidak boleh melihat data santri di luar scope asrama/kamar jika scope diterapkan.
---
4.6 Leave Request / Perizinan
Leave request adalah izin keluar/pulang santri.
Jenis izin:
Type	Makna
`short_leave`	Izin keluar sebentar
`overnight_leave`	Izin bermalam
`home_visit`	Pulang ke rumah
`medical_leave`	Izin karena kesehatan
`emergency_leave`	Izin darurat
Status izin:
Status	Makna
`draft`	Draft
`submitted`	Diajukan
`approved`	Disetujui
`rejected`	Ditolak
`returned`	Sudah kembali
`cancelled`	Dibatalkan
Aturan:
Parent boleh melihat izin anak sendiri.
Student boleh melihat izin diri sendiri.
Parent/student tidak boleh approve izin.
Approval hanya untuk admin, kepala sekolah jika diizinkan, atau pembina sesuai policy.
Return harus dicatat saat santri kembali.
---
4.7 Health Log
Health log adalah catatan kesehatan sederhana.
Contoh:
Demam.
Batuk.
Pusing.
Cedera ringan.
Periksa UKS.
Minum obat.
Dirujuk ke klinik.
Severity:
Severity	Makna
`low`	Ringan
`medium`	Sedang
`high`	Butuh perhatian
`critical`	Darurat
Phase 16 tidak membuat rekam medis kompleks.
---
4.8 Discipline Log
Discipline log adalah catatan kedisiplinan santri.
Contoh:
Terlambat roll call.
Tidak merapikan kamar.
Melanggar aturan asrama.
Tidak mengikuti jadwal.
Membantu kegiatan asrama.
Prestasi kedisiplinan.
Type:
Type	Makna
`violation`	Pelanggaran
`warning`	Peringatan
`achievement`	Prestasi
`note`	Catatan umum
Point boleh positif atau negatif.
---
4.9 Roll Call
Roll call adalah pengecekan keberadaan santri di asrama.
Jenis sesi:
Type	Makna
`morning`	Pagi
`afternoon`	Sore
`night`	Malam
`custom`	Custom
Status record:
Status	Makna
`present`	Hadir
`late`	Terlambat
`permission`	Izin
`sick`	Sakit
`absent`	Tidak ada
Phase 16 menggunakan input manual. Jangan membuat face recognition, RFID, NFC, atau fingerprint.
---
5. Target Output Phase 16
Setelah Phase 16 selesai, aplikasi harus punya:
Menu Boarding.
Menu Asrama.
Menu Kamar.
Menu Ranjang.
Menu Penempatan Santri.
Menu Pembina Asrama.
Menu Perizinan Boarding.
Menu Catatan Kesehatan.
Menu Catatan Kedisiplinan.
Menu Roll Call Boarding.
Menu Laporan Boarding.
Portal boarding untuk orang tua.
Portal boarding untuk santri.
Tabel:
`boarding_dormitories`
`boarding_rooms`
`boarding_beds`
`boarding_supervisor_profiles`
`boarding_student_assignments`
`boarding_leave_requests`
`boarding_health_logs`
`boarding_discipline_logs`
`boarding_roll_call_sessions`
`boarding_roll_call_records`
Model:
`BoardingDormitory`
`BoardingRoom`
`BoardingBed`
`BoardingSupervisorProfile`
`BoardingStudentAssignment`
`BoardingLeaveRequest`
`BoardingHealthLog`
`BoardingDisciplineLog`
`BoardingRollCallSession`
`BoardingRollCallRecord`
Controller:
`BoardingDashboardController`
`BoardingDormitoryController`
`BoardingRoomController`
`BoardingBedController`
`BoardingSupervisorController`
`BoardingStudentAssignmentController`
`BoardingLeaveRequestController`
`BoardingHealthLogController`
`BoardingDisciplineLogController`
`BoardingRollCallController`
`BoardingReportController`
`ParentBoardingPortalController`
`StudentBoardingPortalController`
Request:
`StoreBoardingDormitoryRequest`
`UpdateBoardingDormitoryRequest`
`StoreBoardingRoomRequest`
`UpdateBoardingRoomRequest`
`StoreBoardingBedRequest`
`UpdateBoardingBedRequest`
`StoreBoardingSupervisorRequest`
`UpdateBoardingSupervisorRequest`
`StoreBoardingStudentAssignmentRequest`
`StoreBoardingLeaveRequestRequest`
`UpdateBoardingLeaveRequestStatusRequest`
`StoreBoardingHealthLogRequest`
`StoreBoardingDisciplineLogRequest`
`StoreBoardingRollCallSessionRequest`
`StoreBoardingRollCallRecordRequest`
`BoardingReportFilterRequest`
Service:
`BoardingAccessService`
`BoardingOccupancyService`
`BoardingAssignmentService`
`BoardingLeaveRequestService`
`BoardingRollCallService`
`BoardingReportService`
Seeder:
`BoardingDormitorySeeder`
View:
dashboard boarding
asrama index/create/edit/show
kamar index/create/edit/show
ranjang index/create/edit/show
pembina index/create/edit/show
assignment index/create/show
leave request index/create/show
health log index/create/show
discipline log index/create/show
roll call session index/create/show
report dashboard
parent boarding portal
student boarding portal
Dokumentasi Phase 16.
Update `docs/project-progress.md`.
---
6. Role Access Phase 16
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
boarding_supervisor
parent
student
```
Role	Master Boarding	Assignment	Izin	Health Log	Discipline Log	Roll Call	Report	Portal
Super Admin	CRUD	CRUD	Approve/Reject	CRUD	CRUD	CRUD	Semua	Tidak
Admin	CRUD	CRUD	Approve/Reject	CRUD	CRUD	CRUD	Semua	Tidak
Kepala Sekolah	Read-only	Read-only	Read-only/Approve opsional	Read-only	Read-only	Read-only	Semua	Tidak
Boarding Supervisor	Read-only scope	Read-only scope	Create/Update scope	Create scope	Create scope	Create scope	Scope	Tidak
Teacher/Guru	Tidak default	Tidak default	Tidak default	Tidak default	Tidak default	Tidak default	Tidak default	Tidak
Parent	Tidak	Tidak	Anak sendiri read-only	Anak sendiri read-only	Anak sendiri read-only	Anak sendiri read-only	Anak sendiri	Ya
Student	Tidak	Tidak	Diri sendiri read-only	Diri sendiri read-only	Diri sendiri read-only	Diri sendiri read-only	Diri sendiri	Ya
Aturan keras:
Parent hanya boleh melihat data boarding anak sendiri.
Student hanya boleh melihat data boarding pribadi.
Parent/student tidak boleh mengakses dashboard internal boarding.
Parent/student tidak boleh approve izin.
Parent/student tidak boleh membuat health log dan discipline log pada Phase 16.
Boarding supervisor hanya melihat santri sesuai scope asrama/kamar.
Teacher/guru tidak otomatis mendapat akses boarding.
Kepala sekolah read-only kecuali policy sekolah mengizinkan approval.
Admin boleh mengelola master boarding.
Jangan membuat akses finance, payment, cashless, white-label, atau multi-tenant di Phase 16.
---
7. Validasi Awal Sebelum Eksekusi
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
Laravel 12 berjalan.
Phase 0 selesai.
Phase 1 selesai.
Phase 2 selesai.
Phase 3 selesai.
Phase 4 selesai.
Phase 5 selesai.
Phase 6 selesai.
Phase 7 selesai.
Phase 8 selesai.
Phase 9 selesai.
Phase 10 selesai.
Phase 11 selesai.
Phase 12 selesai.
Phase 13 selesai.
Phase 14 selesai.
Phase 15 selesai dan UAT aman.
Tabel berikut sudah ada:
`users`
`roles`
`schools`
`class_rooms`
`students`
`parent_profiles`
`parent_student`
`teacher_profiles`
`system_modules`
`academic_years`
`school_terms`
`school_settings`
Working tree bersih atau semua perubahan diketahui.
Jika Phase 15 belum aman, hentikan Phase 16.
---
8. Buat Branch Git Phase 16
Jalankan:
```powershell
git checkout -b phase-16-boarding-school-management
```
Jika branch sudah ada:
```powershell
git checkout phase-16-boarding-school-management
```
---
9. Struktur File yang Akan Dibuat
Agent harus membuat atau mengubah file berikut:
```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── Boarding/
│   │   │   ├── BoardingDashboardController.php
│   │   │   ├── BoardingDormitoryController.php
│   │   │   ├── BoardingRoomController.php
│   │   │   ├── BoardingBedController.php
│   │   │   ├── BoardingSupervisorController.php
│   │   │   ├── BoardingStudentAssignmentController.php
│   │   │   ├── BoardingLeaveRequestController.php
│   │   │   ├── BoardingHealthLogController.php
│   │   │   ├── BoardingDisciplineLogController.php
│   │   │   ├── BoardingRollCallController.php
│   │   │   └── BoardingReportController.php
│   │   └── Portal/
│   │       ├── ParentBoardingPortalController.php
│   │       └── StudentBoardingPortalController.php
│   └── Requests/
│       └── Boarding/
│           ├── StoreBoardingDormitoryRequest.php
│           ├── UpdateBoardingDormitoryRequest.php
│           ├── StoreBoardingRoomRequest.php
│           ├── UpdateBoardingRoomRequest.php
│           ├── StoreBoardingBedRequest.php
│           ├── UpdateBoardingBedRequest.php
│           ├── StoreBoardingSupervisorRequest.php
│           ├── UpdateBoardingSupervisorRequest.php
│           ├── StoreBoardingStudentAssignmentRequest.php
│           ├── StoreBoardingLeaveRequestRequest.php
│           ├── UpdateBoardingLeaveRequestStatusRequest.php
│           ├── StoreBoardingHealthLogRequest.php
│           ├── StoreBoardingDisciplineLogRequest.php
│           ├── StoreBoardingRollCallSessionRequest.php
│           ├── StoreBoardingRollCallRecordRequest.php
│           └── BoardingReportFilterRequest.php
├── Models/
│   ├── BoardingDormitory.php
│   ├── BoardingRoom.php
│   ├── BoardingBed.php
│   ├── BoardingSupervisorProfile.php
│   ├── BoardingStudentAssignment.php
│   ├── BoardingLeaveRequest.php
│   ├── BoardingHealthLog.php
│   ├── BoardingDisciplineLog.php
│   ├── BoardingRollCallSession.php
│   └── BoardingRollCallRecord.php
└── Services/
    └── Boarding/
        ├── BoardingAccessService.php
        ├── BoardingOccupancyService.php
        ├── BoardingAssignmentService.php
        ├── BoardingLeaveRequestService.php
        ├── BoardingRollCallService.php
        └── BoardingReportService.php

database/
├── migrations/
│   ├── xxxx_xx_xx_xxxxxx_create_boarding_dormitories_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_boarding_rooms_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_boarding_beds_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_boarding_supervisor_profiles_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_boarding_student_assignments_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_boarding_leave_requests_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_boarding_health_logs_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_boarding_discipline_logs_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_boarding_roll_call_sessions_table.php
│   └── xxxx_xx_xx_xxxxxx_create_boarding_roll_call_records_table.php
└── seeders/
    └── BoardingDormitorySeeder.php

resources/
└── views/
    ├── boarding/
    │   ├── dashboard.blade.php
    │   ├── dormitories/
    │   │   ├── index.blade.php
    │   │   ├── create.blade.php
    │   │   ├── edit.blade.php
    │   │   └── show.blade.php
    │   ├── rooms/
    │   │   ├── index.blade.php
    │   │   ├── create.blade.php
    │   │   ├── edit.blade.php
    │   │   └── show.blade.php
    │   ├── beds/
    │   │   ├── index.blade.php
    │   │   ├── create.blade.php
    │   │   ├── edit.blade.php
    │   │   └── show.blade.php
    │   ├── supervisors/
    │   │   ├── index.blade.php
    │   │   ├── create.blade.php
    │   │   ├── edit.blade.php
    │   │   └── show.blade.php
    │   ├── assignments/
    │   │   ├── index.blade.php
    │   │   ├── create.blade.php
    │   │   └── show.blade.php
    │   ├── leave-requests/
    │   │   ├── index.blade.php
    │   │   ├── create.blade.php
    │   │   └── show.blade.php
    │   ├── health-logs/
    │   │   ├── index.blade.php
    │   │   ├── create.blade.php
    │   │   └── show.blade.php
    │   ├── discipline-logs/
    │   │   ├── index.blade.php
    │   │   ├── create.blade.php
    │   │   └── show.blade.php
    │   ├── roll-calls/
    │   │   ├── index.blade.php
    │   │   ├── create.blade.php
    │   │   └── show.blade.php
    │   └── reports/
    │       └── dashboard.blade.php
    └── portal/
        ├── parent/
        │   └── boarding.blade.php
        └── student/
            └── boarding.blade.php

routes/
└── web.php

docs/
├── phase-16-execution.md
└── phase-16-boarding-school-management.md
```
---
10. Buat Model, Migration, Seeder, Controller, Request
Jalankan:
```powershell
php artisan make:model BoardingDormitory -m
php artisan make:model BoardingRoom -m
php artisan make:model BoardingBed -m
php artisan make:model BoardingSupervisorProfile -m
php artisan make:model BoardingStudentAssignment -m
php artisan make:model BoardingLeaveRequest -m
php artisan make:model BoardingHealthLog -m
php artisan make:model BoardingDisciplineLog -m
php artisan make:model BoardingRollCallSession -m
php artisan make:model BoardingRollCallRecord -m

php artisan make:seeder BoardingDormitorySeeder

php artisan make:controller Boarding/BoardingDashboardController
php artisan make:controller Boarding/BoardingDormitoryController --resource
php artisan make:controller Boarding/BoardingRoomController --resource
php artisan make:controller Boarding/BoardingBedController --resource
php artisan make:controller Boarding/BoardingSupervisorController --resource
php artisan make:controller Boarding/BoardingStudentAssignmentController
php artisan make:controller Boarding/BoardingLeaveRequestController
php artisan make:controller Boarding/BoardingHealthLogController
php artisan make:controller Boarding/BoardingDisciplineLogController
php artisan make:controller Boarding/BoardingRollCallController
php artisan make:controller Boarding/BoardingReportController
php artisan make:controller Portal/ParentBoardingPortalController
php artisan make:controller Portal/StudentBoardingPortalController

php artisan make:request Boarding/StoreBoardingDormitoryRequest
php artisan make:request Boarding/UpdateBoardingDormitoryRequest
php artisan make:request Boarding/StoreBoardingRoomRequest
php artisan make:request Boarding/UpdateBoardingRoomRequest
php artisan make:request Boarding/StoreBoardingBedRequest
php artisan make:request Boarding/UpdateBoardingBedRequest
php artisan make:request Boarding/StoreBoardingSupervisorRequest
php artisan make:request Boarding/UpdateBoardingSupervisorRequest
php artisan make:request Boarding/StoreBoardingStudentAssignmentRequest
php artisan make:request Boarding/StoreBoardingLeaveRequestRequest
php artisan make:request Boarding/UpdateBoardingLeaveRequestStatusRequest
php artisan make:request Boarding/StoreBoardingHealthLogRequest
php artisan make:request Boarding/StoreBoardingDisciplineLogRequest
php artisan make:request Boarding/StoreBoardingRollCallSessionRequest
php artisan make:request Boarding/StoreBoardingRollCallRecordRequest
php artisan make:request Boarding/BoardingReportFilterRequest
```
Buat folder service:
```powershell
mkdir app\Services\Boarding
```
Buat file service:
```powershell
New-Item app\Services\Boarding\BoardingAccessService.php
New-Item app\Services\Boarding\BoardingOccupancyService.php
New-Item app\Services\Boarding\BoardingAssignmentService.php
New-Item app\Services\Boarding\BoardingLeaveRequestService.php
New-Item app\Services\Boarding\BoardingRollCallService.php
New-Item app\Services\Boarding\BoardingReportService.php
```
Buat folder view:
```powershell
mkdir resources\views\boarding
mkdir resources\views\boarding\dormitories
mkdir resources\views\boarding\rooms
mkdir resources\views\boarding\beds
mkdir resources\views\boarding\supervisors
mkdir resources\views\boarding\assignments
mkdir resources\views\boarding\leave-requests
mkdir resources\views\boarding\health-logs
mkdir resources\views\boarding\discipline-logs
mkdir resources\views\boarding\roll-calls
mkdir resources\views\boarding\reports
```
Jika folder `resources\views\portal\parent` dan `resources\views\portal\student` sudah ada, jangan hapus.
---
11. Migration Ringkas
Agent wajib mengisi migration dengan field minimal berikut.
11.1 `boarding_dormitories`
```php
$table->id();
$table->foreignId('school_id')->nullable()->constrained('schools')->nullOnDelete();
$table->string('name');
$table->string('gender')->nullable(); // male, female, mixed
$table->unsignedSmallInteger('capacity')->default(0);
$table->boolean('is_active')->default(true);
$table->text('description')->nullable();
$table->timestamps();
$table->softDeletes();

$table->index(['school_id', 'is_active']);
$table->index('gender');
```
11.2 `boarding_rooms`
```php
$table->id();
$table->foreignId('boarding_dormitory_id')->constrained('boarding_dormitories')->cascadeOnDelete();
$table->string('name');
$table->string('floor')->nullable();
$table->unsignedSmallInteger('capacity')->default(0);
$table->boolean('is_active')->default(true);
$table->text('description')->nullable();
$table->timestamps();
$table->softDeletes();

$table->index(['boarding_dormitory_id', 'is_active']);
```
11.3 `boarding_beds`
```php
$table->id();
$table->foreignId('boarding_room_id')->constrained('boarding_rooms')->cascadeOnDelete();
$table->string('code');
$table->string('status')->default('available');
$table->text('description')->nullable();
$table->timestamps();
$table->softDeletes();

$table->unique(['boarding_room_id', 'code']);
$table->index(['boarding_room_id', 'status']);
```
11.4 `boarding_supervisor_profiles`
```php
$table->id();
$table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
$table->foreignId('school_id')->nullable()->constrained('schools')->nullOnDelete();
$table->foreignId('boarding_dormitory_id')->nullable()->constrained('boarding_dormitories')->nullOnDelete();
$table->foreignId('boarding_room_id')->nullable()->constrained('boarding_rooms')->nullOnDelete();
$table->string('phone')->nullable();
$table->string('status')->default('active');
$table->text('notes')->nullable();
$table->timestamps();
$table->softDeletes();

$table->unique('user_id');
$table->index(['school_id', 'status']);
$table->index(['boarding_dormitory_id', 'boarding_room_id']);
```
11.5 `boarding_student_assignments`
```php
$table->id();
$table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
$table->foreignId('boarding_dormitory_id')->constrained('boarding_dormitories')->cascadeOnDelete();
$table->foreignId('boarding_room_id')->constrained('boarding_rooms')->cascadeOnDelete();
$table->foreignId('boarding_bed_id')->nullable()->constrained('boarding_beds')->nullOnDelete();
$table->date('start_date');
$table->date('end_date')->nullable();
$table->string('status')->default('active');
$table->text('notes')->nullable();
$table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
$table->timestamps();
$table->softDeletes();

$table->index(['student_id', 'status']);
$table->index(['boarding_dormitory_id', 'status']);
$table->index(['boarding_room_id', 'status']);
$table->index(['boarding_bed_id', 'status']);
```
11.6 `boarding_leave_requests`
```php
$table->id();
$table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
$table->foreignId('requested_by_user_id')->nullable()->constrained('users')->nullOnDelete();
$table->foreignId('approved_by_user_id')->nullable()->constrained('users')->nullOnDelete();
$table->string('type')->default('short_leave');
$table->string('status')->default('submitted');
$table->string('destination')->nullable();
$table->text('reason')->nullable();
$table->dateTime('leave_start_at');
$table->dateTime('leave_end_at')->nullable();
$table->dateTime('returned_at')->nullable();
$table->text('approval_note')->nullable();
$table->timestamps();
$table->softDeletes();

$table->index(['student_id', 'status']);
$table->index(['type', 'status']);
$table->index(['leave_start_at', 'leave_end_at']);
```
11.7 `boarding_health_logs`
```php
$table->id();
$table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
$table->foreignId('recorded_by_user_id')->nullable()->constrained('users')->nullOnDelete();
$table->string('severity')->default('low');
$table->string('condition_title');
$table->text('description')->nullable();
$table->text('action_taken')->nullable();
$table->dateTime('logged_at');
$table->timestamps();
$table->softDeletes();

$table->index(['student_id', 'logged_at']);
$table->index('severity');
```
11.8 `boarding_discipline_logs`
```php
$table->id();
$table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
$table->foreignId('recorded_by_user_id')->nullable()->constrained('users')->nullOnDelete();
$table->string('type')->default('note');
$table->string('category')->nullable();
$table->text('description');
$table->integer('points')->default(0);
$table->text('action_taken')->nullable();
$table->dateTime('logged_at');
$table->timestamps();
$table->softDeletes();

$table->index(['student_id', 'logged_at']);
$table->index(['type', 'category']);
```
11.9 `boarding_roll_call_sessions`
```php
$table->id();
$table->foreignId('boarding_dormitory_id')->nullable()->constrained('boarding_dormitories')->nullOnDelete();
$table->foreignId('boarding_room_id')->nullable()->constrained('boarding_rooms')->nullOnDelete();
$table->date('session_date');
$table->string('session_type')->default('night');
$table->dateTime('started_at')->nullable();
$table->dateTime('closed_at')->nullable();
$table->string('status')->default('open');
$table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
$table->timestamps();
$table->softDeletes();

$table->index(['session_date', 'session_type']);
$table->index(['boarding_dormitory_id', 'status']);
$table->index(['boarding_room_id', 'status']);
```
11.10 `boarding_roll_call_records`
```php
$table->id();
$table->foreignId('boarding_roll_call_session_id')->constrained('boarding_roll_call_sessions')->cascadeOnDelete();
$table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
$table->foreignId('recorded_by_user_id')->nullable()->constrained('users')->nullOnDelete();
$table->string('status')->default('present');
$table->text('note')->nullable();
$table->dateTime('recorded_at')->nullable();
$table->timestamps();

$table->unique(['boarding_roll_call_session_id', 'student_id'], 'boarding_roll_call_unique_student');
$table->index(['student_id', 'status']);
```
---
12. Model Relationship Minimal
Agent wajib menambahkan relationship berikut.
12.1 `BoardingDormitory`
```php
public function rooms()
{
    return $this->hasMany(BoardingRoom::class);
}

public function assignments()
{
    return $this->hasMany(BoardingStudentAssignment::class);
}
```
12.2 `BoardingRoom`
```php
public function dormitory()
{
    return $this->belongsTo(BoardingDormitory::class, 'boarding_dormitory_id');
}

public function beds()
{
    return $this->hasMany(BoardingBed::class);
}

public function assignments()
{
    return $this->hasMany(BoardingStudentAssignment::class);
}
```
12.3 `BoardingBed`
```php
public function room()
{
    return $this->belongsTo(BoardingRoom::class, 'boarding_room_id');
}
```
12.4 `BoardingStudentAssignment`
```php
public function student()
{
    return $this->belongsTo(Student::class);
}

public function dormitory()
{
    return $this->belongsTo(BoardingDormitory::class, 'boarding_dormitory_id');
}

public function room()
{
    return $this->belongsTo(BoardingRoom::class, 'boarding_room_id');
}

public function bed()
{
    return $this->belongsTo(BoardingBed::class, 'boarding_bed_id');
}
```
12.5 Update `Student`
Tambahkan jika belum ada:
```php
public function boardingAssignments()
{
    return $this->hasMany(BoardingStudentAssignment::class);
}

public function activeBoardingAssignment()
{
    return $this->hasOne(BoardingStudentAssignment::class)->where('status', 'active');
}

public function boardingLeaveRequests()
{
    return $this->hasMany(BoardingLeaveRequest::class);
}

public function boardingHealthLogs()
{
    return $this->hasMany(BoardingHealthLog::class);
}

public function boardingDisciplineLogs()
{
    return $this->hasMany(BoardingDisciplineLog::class);
}
```
---
13. Service Rules
13.1 `BoardingAssignmentService`
Service ini wajib menjaga aturan:
Santri tidak boleh punya dua assignment aktif.
Bed tidak boleh ditempati dua assignment aktif.
Saat assignment aktif dibuat, bed berubah menjadi `occupied`.
Saat assignment diakhiri, bed kembali menjadi `available`.
Pindah kamar harus menutup assignment lama dan membuat assignment baru.
Jangan delete assignment aktif; gunakan status.
13.2 `BoardingOccupancyService`
Service ini wajib menghitung:
Total dormitory capacity.
Occupied dormitory beds.
Available beds.
Occupancy percentage.
Room-level occupancy.
Dormitory-level occupancy.
13.3 `BoardingAccessService`
Service ini wajib menjaga:
Admin/super admin bisa melihat semua.
Kepala sekolah read-only semua.
Boarding supervisor hanya scope asrama/kamar.
Parent hanya anak sendiri.
Student hanya diri sendiri.
User tanpa akses mendapat 403.
13.4 `BoardingLeaveRequestService`
Service ini wajib menjaga:
Status flow `submitted → approved/rejected`.
Status `approved → returned`.
Izin rejected tidak bisa returned.
Izin cancelled tidak bisa approved.
Approval menyimpan user approval dan catatan.
Return menyimpan waktu kembali.
13.5 `BoardingRollCallService`
Service ini wajib menjaga:
Satu session tidak boleh punya record ganda untuk student sama.
Session closed tidak boleh diubah kecuali admin.
Status record hanya boleh memakai status yang ditentukan.
Roll call bisa dibuat per asrama atau per kamar.
Record hanya mengambil santri dengan assignment aktif.
---
14. Route Phase 16
Tambahkan ke `routes/web.php` di dalam middleware `auth`.
Nama route yang wajib dibuat:
```text
boarding.dashboard

boarding.dormitories.index
boarding.dormitories.create
boarding.dormitories.store
boarding.dormitories.show
boarding.dormitories.edit
boarding.dormitories.update
boarding.dormitories.destroy

boarding.rooms.index
boarding.rooms.create
boarding.rooms.store
boarding.rooms.show
boarding.rooms.edit
boarding.rooms.update
boarding.rooms.destroy

boarding.beds.index
boarding.beds.create
boarding.beds.store
boarding.beds.show
boarding.beds.edit
boarding.beds.update
boarding.beds.destroy

boarding.supervisors.index
boarding.supervisors.create
boarding.supervisors.store
boarding.supervisors.show
boarding.supervisors.edit
boarding.supervisors.update
boarding.supervisors.destroy

boarding.assignments.index
boarding.assignments.create
boarding.assignments.store
boarding.assignments.show
boarding.assignments.end
boarding.assignments.move

boarding.leave-requests.index
boarding.leave-requests.create
boarding.leave-requests.store
boarding.leave-requests.show
boarding.leave-requests.approve
boarding.leave-requests.reject
boarding.leave-requests.mark-returned
boarding.leave-requests.cancel

boarding.health-logs.index
boarding.health-logs.create
boarding.health-logs.store
boarding.health-logs.show

boarding.discipline-logs.index
boarding.discipline-logs.create
boarding.discipline-logs.store
boarding.discipline-logs.show

boarding.roll-calls.index
boarding.roll-calls.create
boarding.roll-calls.store
boarding.roll-calls.show
boarding.roll-calls.records.store
boarding.roll-calls.close

boarding.reports.dashboard

portal.parent.boarding
portal.student.boarding
```
---
15. Update Navigasi
Update layout utama:
```text
resources/views/layouts/app.blade.php
```
Tambahkan menu:
```text
Boarding
```
Submenu internal:
Dashboard Boarding.
Asrama.
Kamar.
Ranjang.
Penempatan Santri.
Pembina Asrama.
Perizinan.
Health Log.
Discipline Log.
Roll Call.
Laporan Boarding.
Menu parent/student:
Boarding Anak.
Boarding Saya.
Jangan tampilkan menu internal boarding ke parent/student.
---
16. Update Module Registry
Jika tabel `system_modules` sudah ada dari Phase 15, update seeder atau buat record baru:
```text
module_key: boarding
name: Boarding
description: Boarding school management, dormitory, room assignment, leave request, health log, discipline log, and roll call.
route_name: boarding.dashboard
sort_order: 80
is_enabled: true
is_core: false
```
Jalankan seeder setelah update.
---
17. Update `docs/project-progress.md`
Buka:
```text
docs/project-progress.md
```
Update menjadi:
```md
# Project Progress — HafizPlus School Platform

| Phase | Nama | Status |
|---:|---|---|
| 0 | Product Foundation | Done |
| 1 | Auth, Role, and Initial Database Foundation | Done |
| 2 | Master Data Foundation | Done |
| 3 | Tahfizh Core Database Foundation | Done |
| 4 | Tahfizh Input Foundation | Done |
| 5 | Target and Debt Calculation | Done |
| 6 | Dashboard and Reports | Done |
| 7 | Parent and Student Progress Portal | Done |
| 8 | Notification Center | Done |
| 9 | Export PDF and Excel | Done |
| 10 | Production Hardening | Done |
| 11 | Mutabaah Yaumiyah Tracker | Done |
| 12 | QR Attendance System | Done |
| 13 | Tahsin Management App | Done |
| 14 | Student Finance Ledger | Done |
| 15 | SchoolOS Mini | Done |
| 16 | Boarding School Management System | Done |
| 17 | Multi-Tenant Foundation | Pending |
| 18 | White-Label School App Builder | Pending |
| 19 | Cashless Kantin / Merchant POS | Pending |
```
---
18. Validasi Akhir
Jalankan:
```powershell
php artisan migrate
php artisan db:seed --class=BoardingDormitorySeeder
php artisan route:list
php artisan app:system-health-check
npm run build
```
Jalankan server:
```powershell
php artisan serve
```
Buka URL berikut:
```text
http://127.0.0.1:8000/boarding
http://127.0.0.1:8000/boarding/dormitories
http://127.0.0.1:8000/boarding/rooms
http://127.0.0.1:8000/boarding/beds
http://127.0.0.1:8000/boarding/assignments
http://127.0.0.1:8000/boarding/leave-requests
http://127.0.0.1:8000/boarding/health-logs
http://127.0.0.1:8000/boarding/discipline-logs
http://127.0.0.1:8000/boarding/roll-calls
http://127.0.0.1:8000/boarding/reports
http://127.0.0.1:8000/portal/parent/boarding
http://127.0.0.1:8000/portal/student/boarding
```
---
19. Test Manual Wajib
19.1 Test Master Boarding
Admin buat asrama.
Admin buat kamar.
Admin buat bed.
Admin buat pembina asrama.
Admin cek occupancy awal.
Target:
Asrama tersimpan.
Kamar tersimpan.
Bed status `available`.
Pembina punya scope.
Dashboard occupancy tidak error.
---
19.2 Test Assignment
Admin assign santri ke bed kosong.
Cek bed berubah menjadi `occupied`.
Coba assign santri yang sama ke bed lain.
Sistem harus menolak assignment aktif ganda.
Coba assign santri lain ke bed yang sama.
Sistem harus menolak bed aktif ganda.
Pindahkan santri ke kamar lain.
Assignment lama harus status `moved`.
Assignment baru harus status `active`.
---
19.3 Test Leave Request
Admin/pembina buat izin santri.
Approve izin.
Mark returned.
Coba returned pada izin rejected.
Sistem harus menolak.
---
19.4 Test Health Log
Pembina input health log.
Kepala sekolah lihat read-only.
Parent lihat log anak sendiri.
Student lihat log sendiri.
Parent coba akses health log anak lain.
Sistem harus 403.
---
19.5 Test Discipline Log
Pembina input discipline log.
Admin lihat semua.
Kepala sekolah lihat read-only.
Parent hanya lihat anak sendiri.
Student hanya lihat diri sendiri.
---
19.6 Test Roll Call
Buat roll call session malam.
Sistem tampilkan santri assignment aktif.
Input status present/late/permission/sick/absent.
Coba input record ganda untuk santri sama.
Sistem harus menolak.
Close session.
Coba edit setelah closed.
Sistem harus menolak kecuali admin.
---
20. Definition of Done
Phase 16 selesai jika:
Migration berhasil.
Seeder berhasil.
Menu Boarding tampil.
Admin bisa membuat asrama.
Admin bisa membuat kamar.
Admin bisa membuat bed.
Admin bisa menempatkan santri ke bed.
Bed occupancy benar.
Santri tidak bisa punya dua assignment aktif.
Bed tidak bisa dipakai dua santri aktif.
Pembina bisa melihat scope boarding.
Leave request berjalan.
Health log berjalan.
Discipline log berjalan.
Roll call berjalan.
Boarding dashboard tampil.
Boarding report tampil.
Parent hanya melihat data boarding anak sendiri.
Student hanya melihat data boarding pribadi.
Parent/student tidak bisa akses dashboard internal boarding.
Kepala sekolah read-only.
Module registry update dengan module `boarding`.
`npm run build` berhasil.
`php artisan app:system-health-check` berhasil.
Dokumentasi dibuat.
`docs/project-progress.md` terupdate.
---
21. Commit Phase 16
Jalankan:
```powershell
git status
git add .
git commit -m "feat: add boarding school management system"
```
Jika remote tersedia:
```powershell
git push origin phase-16-boarding-school-management
```
---
22. Output Akhir yang Harus Dilaporkan Agent
Setelah selesai, agent harus melaporkan:
```text
Phase 16 selesai.

Project:
- HafizPlus School Platform
- Laravel 12
- MySQL

Fitur dibuat:
- Boarding Dashboard
- Dormitory Management
- Room Management
- Bed Management
- Boarding Supervisor Profile
- Student Room/Bed Assignment
- Boarding Leave Request
- Boarding Health Log
- Boarding Discipline Log
- Boarding Roll Call
- Boarding Report
- Parent Boarding Portal
- Student Boarding Portal
- Role-based access
- Ownership-based access
- Boarding module registry

Tabel dibuat:
- boarding_dormitories
- boarding_rooms
- boarding_beds
- boarding_supervisor_profiles
- boarding_student_assignments
- boarding_leave_requests
- boarding_health_logs
- boarding_discipline_logs
- boarding_roll_call_sessions
- boarding_roll_call_records

Route dibuat:
- boarding.dashboard
- boarding.dormitories.*
- boarding.rooms.*
- boarding.beds.*
- boarding.supervisors.*
- boarding.assignments.*
- boarding.leave-requests.*
- boarding.health-logs.*
- boarding.discipline-logs.*
- boarding.roll-calls.*
- boarding.reports.dashboard
- portal.parent.boarding
- portal.student.boarding

Belum dibuat:
- Multi-tenant foundation
- White-label school app
- Domain/subdomain per sekolah
- Billing SaaS
- Native mobile app
- Cashless kantin
- Merchant POS
- Wallet
- Payment gateway
- Face recognition
- RFID/NFC
- Push notification
- WhatsApp gateway

Status:
- Siap lanjut Phase 17 hanya setelah UAT Phase 16 aman.
```
---
23. Larangan Setelah Phase 16
Agent harus berhenti setelah Phase 16 selesai.
Jangan lanjut membuat:
Multi-Tenant Foundation.
White-Label School App Builder.
Cashless Kantin.
Merchant POS.
Wallet.
Payment Gateway.
Mobile App.
Domain/subdomain per sekolah.
Billing SaaS.
WhatsApp Gateway.
Push Notification.
Semua itu masuk phase berikutnya.
---
24. Keputusan Akhir
Phase 16 hanya valid jika Boarding School Management stabil, aman, dan tidak merusak:
Tahfizh.
Parent Portal.
Student Portal.
Notification Center.
Export.
Mutabaah.
Attendance.
Tahsin.
Finance.
SchoolOS Mini.
Prioritas setelah Phase 16:
UAT Boarding.
Fix bug Boarding.
Cek assignment asrama/kamar/bed.
Cek occupancy.
Cek leave request.
Cek health log.
Cek discipline log.
Cek roll call.
Cek akses parent/student.
Backup database.
Baru pertimbangkan Phase 17 — Multi-Tenant Foundation.
Jangan masuk multi-tenant sebelum Boarding aman. Multi-tenant akan memperbesar risiko data bocor antar sekolah. Kalau single-school data ownership saja belum solid, multi-tenant akan menjadi sumber masalah besar.