<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterData\ClassRoomController;
use App\Http\Controllers\MasterData\ParentController;
use App\Http\Controllers\MasterData\SchoolController;
use App\Http\Controllers\MasterData\StudentController;
use App\Http\Controllers\MasterData\TeacherController;
use App\Http\Controllers\Tahfizh\HafalanRecordController;
use App\Http\Controllers\Reports\MonthlyTahfizhReportController;
use App\Http\Controllers\Reports\QuarterlyTahfizhReportController;
use App\Http\Controllers\Reports\TahfizhDashboardController;
use App\Http\Controllers\Portal\ParentProgressPortalController;
use App\Http\Controllers\Portal\StudentProgressPortalController;
use App\Http\Controllers\Notifications\AnnouncementController;
use App\Http\Controllers\Notifications\NotificationCenterController;
use App\Http\Controllers\Exports\TahfizhExportController;
use App\Http\Controllers\Admin\SystemStatusController;
use App\Http\Controllers\Tahfizh\TahfizhDebtController;
use App\Http\Controllers\Tahfizh\TahfizhTargetController;
use App\Http\Controllers\Mutabaah\MutabaahActivityController;
use App\Http\Controllers\Mutabaah\MutabaahDailyInputController;
use App\Http\Controllers\Mutabaah\MutabaahReportController;
use App\Http\Controllers\Portal\ParentMutabaahPortalController;
use App\Http\Controllers\Portal\StudentMutabaahPortalController;
use App\Http\Controllers\Attendance\AttendanceQrCardController;
use App\Http\Controllers\Attendance\AttendanceSessionController;
use App\Http\Controllers\Attendance\AttendanceScannerController;
use App\Http\Controllers\Attendance\AttendanceManualRecordController;
use App\Http\Controllers\Attendance\AttendanceReportController;
use App\Http\Controllers\Portal\ParentAttendancePortalController;
use App\Http\Controllers\Portal\StudentAttendancePortalController;
use App\Http\Controllers\Tahsin\TahsinLevelController;
use App\Http\Controllers\Tahsin\TahsinSkillController;
use App\Http\Controllers\Tahsin\TahsinStudentProfileController;
use App\Http\Controllers\Tahsin\TahsinAssessmentController;
use App\Http\Controllers\Tahsin\TahsinReportController;
use App\Http\Controllers\Portal\ParentTahsinPortalController;
use App\Http\Controllers\Portal\StudentTahsinPortalController;
use App\Http\Controllers\Finance\FinanceFeeCategoryController;
use App\Http\Controllers\Finance\FinanceFeeItemController;
use App\Http\Controllers\Finance\StudentBillController;
use App\Http\Controllers\Finance\StudentPaymentController;
use App\Http\Controllers\Finance\FinanceLedgerController;
use App\Http\Controllers\Finance\FinanceReportController;
use App\Http\Controllers\Portal\ParentFinancePortalController;
use App\Http\Controllers\Portal\StudentFinancePortalController;
use App\Http\Controllers\SchoolOs\AcademicYearController;
use App\Http\Controllers\SchoolOs\SchoolOsDashboardController;
use App\Http\Controllers\SchoolOs\SchoolOsSearchController;
use App\Http\Controllers\SchoolOs\SchoolSettingController;
use App\Http\Controllers\SchoolOs\Student360Controller;
use App\Http\Controllers\SchoolOs\SystemModuleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', LogoutController::class)->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'redirect'])
        ->name('dashboard');

    Route::get('/dashboard/super-admin', [DashboardController::class, 'superAdmin'])
        ->middleware('role:super_admin')
        ->name('dashboard.super-admin');
Route::get('/profile', [ProfileController::class, 'show'])->middleware('auth')->name('profile.show');
    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])
        ->middleware('role:super_admin,admin')
        ->name('dashboard.admin');

    Route::get('/dashboard/kepala-sekolah', [DashboardController::class, 'kepalaSekolah'])
        ->middleware('role:super_admin,principal')
        ->name('dashboard.kepala-sekolah');

    Route::get('/dashboard/guru', [DashboardController::class, 'teacher'])
        ->middleware('role:super_admin,teacher')
        ->name('dashboard.teacher');

    Route::get('/dashboard/orang-tua', [DashboardController::class, 'parent'])
        ->middleware('role:super_admin,parent')
        ->name('dashboard.parent');

    Route::get('/dashboard/santri', [DashboardController::class, 'student'])
        ->middleware('role:super_admin,student')
        ->name('dashboard.student');

    // Master Data Routes
    Route::middleware('role:super_admin,admin')->prefix('master-data')->name('master-data.')->group(function (): void {
        Route::resource('class-rooms', ClassRoomController::class);
        Route::resource('teachers', TeacherController::class)->parameters([
            'teachers' => 'teacher',
        ]);
        Route::resource('parents', ParentController::class)->parameters([
            'parents' => 'parent',
        ]);
        Route::resource('students', StudentController::class);
    });

    Route::middleware('role:super_admin')->prefix('master-data')->name('master-data.')->group(function (): void {
        Route::resource('schools', SchoolController::class);
    });

    // Tahfizh Setoran Routes
    Route::middleware('role:super_admin,admin,teacher')
        ->prefix('tahfizh')
        ->name('tahfizh.')
        ->group(function (): void {
            Route::get('hafalan-records/create', [HafalanRecordController::class, 'create'])
                ->name('hafalan-records.create');

            Route::post('hafalan-records', [HafalanRecordController::class, 'store'])
                ->name('hafalan-records.store');
        });

    Route::middleware('role:super_admin,admin,teacher,principal')
        ->prefix('tahfizh')
        ->name('tahfizh.')
        ->group(function (): void {
            Route::get('hafalan-records', [HafalanRecordController::class, 'index'])
                ->name('hafalan-records.index');

            Route::get('hafalan-records/{hafalan_record}', [HafalanRecordController::class, 'show'])
                ->name('hafalan-records.show');
        });

    Route::middleware('role:super_admin,admin,teacher')
        ->prefix('tahfizh')
        ->name('tahfizh.')
        ->group(function (): void {
            Route::get('hafalan-records/{hafalan_record}/edit', [HafalanRecordController::class, 'edit'])
                ->name('hafalan-records.edit');

            Route::put('hafalan-records/{hafalan_record}', [HafalanRecordController::class, 'update'])
                ->name('hafalan-records.update');

            Route::delete('hafalan-records/{hafalan_record}', [HafalanRecordController::class, 'destroy'])
                ->name('hafalan-records.destroy');
        });

    // Tahfizh Targets and Debts Routes
    Route::middleware('role:super_admin,admin,principal,teacher')
        ->prefix('tahfizh')
        ->name('tahfizh.')
        ->group(function (): void {
            Route::get('targets', [TahfizhTargetController::class, 'index'])
                ->name('targets.index');

            Route::get('targets/{target}', [TahfizhTargetController::class, 'show'])
                ->name('targets.show');

            Route::get('debts', [TahfizhDebtController::class, 'index'])
                ->name('debts.index');

            Route::get('debts/{debt}', [TahfizhDebtController::class, 'show'])
                ->name('debts.show');
        });

    Route::middleware('role:super_admin,admin')
        ->prefix('tahfizh')
        ->name('tahfizh.')
        ->group(function (): void {
            Route::get('targets/create', [TahfizhTargetController::class, 'create'])
                ->name('targets.create');

            Route::post('targets', [TahfizhTargetController::class, 'store'])
                ->name('targets.store');

            Route::get('targets/{target}/edit', [TahfizhTargetController::class, 'edit'])
                ->name('targets.edit');

            Route::put('targets/{target}', [TahfizhTargetController::class, 'update'])
                ->name('targets.update');

            Route::delete('targets/{target}', [TahfizhTargetController::class, 'destroy'])
                ->name('targets.destroy');

            Route::post('debts/calculate', [TahfizhDebtController::class, 'calculate'])
                ->name('debts.calculate');
        });

    // Tahfizh Reports Routes
    Route::middleware('role:super_admin,admin,principal,teacher')
        ->prefix('reports/tahfizh')
        ->name('reports.tahfizh.')
        ->group(function (): void {
            Route::get('dashboard', TahfizhDashboardController::class)
                ->name('dashboard');

            Route::get('monthly', [MonthlyTahfizhReportController::class, 'index'])
                ->name('monthly.index');

            Route::get('monthly/students/{student}', [MonthlyTahfizhReportController::class, 'show'])
                ->name('monthly.show');

            Route::get('quarterly', [QuarterlyTahfizhReportController::class, 'index'])
                ->name('quarterly.index');

            Route::get('quarterly/students/{student}', [QuarterlyTahfizhReportController::class, 'show'])
                ->name('quarterly.show');
        });

    // Parent Portal Routes
    Route::middleware('role:parent')
        ->prefix('portal/parent')
        ->name('portal.parent.')
        ->group(function (): void {
            Route::get('dashboard', [ParentProgressPortalController::class, 'dashboard'])
                ->name('dashboard');

            Route::get('children/{student}', [ParentProgressPortalController::class, 'progress'])
                ->name('children.progress');

            Route::get('children/{student}/records', [ParentProgressPortalController::class, 'records'])
                ->name('children.records');

            Route::get('children/{student}/monthly', [ParentProgressPortalController::class, 'monthly'])
                ->name('children.monthly');
        });

    // Student Portal Routes
    Route::middleware('role:student')
        ->prefix('portal/student')
        ->name('portal.student.')
        ->group(function (): void {
            Route::get('dashboard', [StudentProgressPortalController::class, 'dashboard'])
                ->name('dashboard');

            Route::get('records', [StudentProgressPortalController::class, 'records'])
                ->name('records');

            Route::get('monthly', [StudentProgressPortalController::class, 'monthly'])
                ->name('monthly');
        });

    // Notification Center Routes
    Route::prefix('notifications')
        ->name('notifications.')
        ->group(function (): void {
            Route::get('/', [NotificationCenterController::class, 'index'])
                ->name('index');

            Route::post('mark-all-as-read', [NotificationCenterController::class, 'markAllAsRead'])
                ->name('mark-all-as-read');

            Route::get('{notification}', [NotificationCenterController::class, 'show'])
                ->name('show');

            Route::patch('{notification}/mark-as-read', [NotificationCenterController::class, 'markAsRead'])
                ->name('mark-as-read');

            Route::delete('{notification}', [NotificationCenterController::class, 'destroy'])
                ->name('destroy');
        });

// Announcement Routes
    Route::middleware('role:super_admin,admin')
        ->prefix('notifications/announcements')
        ->name('notifications.announcements.')
        ->group(function (): void {
            Route::get('create', [AnnouncementController::class, 'create'])
                ->name('create');

            Route::post('/', [AnnouncementController::class, 'store'])
                ->name('store');
        });

    // Export Routes
    Route::middleware('role:super_admin,admin,principal,teacher')
        ->prefix('exports/tahfizh')
        ->name('exports.tahfizh.')
        ->group(function (): void {
            Route::get('/', [TahfizhExportController::class, 'index'])
                ->name('index');

            Route::get('monthly/excel', [TahfizhExportController::class, 'monthlyExcel'])
                ->name('monthly.excel');

            Route::get('monthly/pdf', [TahfizhExportController::class, 'monthlyPdf'])
                ->name('monthly.pdf');

            Route::get('quarterly/excel', [TahfizhExportController::class, 'quarterlyExcel'])
                ->name('quarterly.excel');

            Route::get('quarterly/pdf', [TahfizhExportController::class, 'quarterlyPdf'])
                ->name('quarterly.pdf');

            Route::get('dashboard/excel', [TahfizhExportController::class, 'dashboardExcel'])
                ->name('dashboard.excel');

            Route::get('dashboard/pdf', [TahfizhExportController::class, 'dashboardPdf'])
                ->name('dashboard.pdf');
        });

    // System Status Routes
    Route::middleware('role:super_admin,admin')
        ->prefix('admin/system')
        ->name('admin.system.')
        ->group(function (): void {
            Route::get('status', SystemStatusController::class)
                ->name('status');
        });

    // Mutabaah Routes
    Route::middleware('role:super_admin,admin,teacher,principal')
        ->prefix('mutabaah')
        ->name('mutabaah.')
        ->group(function (): void {
            Route::get('reports/dashboard', [MutabaahReportController::class, 'dashboard'])
                ->name('reports.dashboard');

            Route::get('reports/student/{student}', [MutabaahReportController::class, 'studentReport'])
                ->name('reports.student');

            Route::get('daily', [MutabaahDailyInputController::class, 'index'])
                ->name('daily.index');

            Route::post('daily', [MutabaahDailyInputController::class, 'store'])
                ->name('daily.store');
        });

    Route::middleware('role:super_admin,admin')
        ->prefix('mutabaah')
        ->name('mutabaah.')
        ->group(function (): void {
            Route::resource('activities', MutabaahActivityController::class);
        });

    // Parent Mutabaah Portal Route
    Route::get('/portal/parent/mutabaah/{student}', [ParentMutabaahPortalController::class, 'show'])
        ->middleware('role:parent')
        ->name('portal.parent.mutabaah');

    // Student Mutabaah Portal Route
    Route::get('/portal/student/mutabaah', [StudentMutabaahPortalController::class, 'index'])
        ->middleware('role:student')
        ->name('portal.student.mutabaah');

    // Attendance Routes
    Route::prefix('attendance')
        ->name('attendance.')
        ->middleware(['role:super_admin,admin,admin_sekolah,kepala_sekolah,principal,teacher,guru,guru_tahfidz'])
        ->group(function (): void {
            Route::get('/qr-cards', [AttendanceQrCardController::class, 'index'])
                ->name('qr-cards.index');

            Route::get('/qr-cards/print', [AttendanceQrCardController::class, 'print'])
                ->name('qr-cards.print');

            Route::post('/qr-cards/{student}/rotate', [AttendanceQrCardController::class, 'rotate'])
                ->name('qr-cards.rotate');

            Route::get('/scanner', [AttendanceScannerController::class, 'index'])
                ->name('scanner.index');

            Route::post('/scanner/scan', [AttendanceScannerController::class, 'scan'])
                ->name('scanner.scan');

            Route::get('/manual/create', [AttendanceManualRecordController::class, 'create'])
                ->name('manual.create');

            Route::post('/manual', [AttendanceManualRecordController::class, 'store'])
                ->name('manual.store');

            Route::get('/reports/dashboard', [AttendanceReportController::class, 'dashboard'])
                ->name('reports.dashboard');

            Route::resource('sessions', AttendanceSessionController::class);
        });

    // Parent Attendance Portal Route
    Route::get('/portal/parent/attendance', [ParentAttendancePortalController::class, 'index'])
        ->middleware(['role:parent'])
        ->name('portal.parent.attendance');

    // Student Attendance Portal Route
    Route::get('/portal/student/attendance', [StudentAttendancePortalController::class, 'index'])
        ->middleware(['role:student'])
        ->name('portal.student.attendance');

    // Tahsin Management Routes
    Route::prefix('tahsin')
        ->name('tahsin.')
        ->middleware(['role:super_admin,admin,admin_sekolah,kepala_sekolah,principal,teacher,guru,guru_tahfidz'])
        ->group(function (): void {
            Route::get('/reports/dashboard', [TahsinReportController::class, 'dashboard'])
                ->name('reports.dashboard');

            Route::get('/profiles', [TahsinStudentProfileController::class, 'index'])
                ->name('profiles.index');

            Route::get('/profiles/{student}', [TahsinStudentProfileController::class, 'show'])
                ->name('profiles.show');

            Route::get('/profiles/{student}/edit', [TahsinStudentProfileController::class, 'edit'])
                ->name('profiles.edit');

            Route::put('/profiles/{student}', [TahsinStudentProfileController::class, 'update'])
                ->name('profiles.update');

            Route::get('/assessments', [TahsinAssessmentController::class, 'index'])
                ->name('assessments.index');

            Route::get('/assessments/create', [TahsinAssessmentController::class, 'create'])
                ->name('assessments.create');

            Route::post('/assessments', [TahsinAssessmentController::class, 'store'])
                ->name('assessments.store');

            Route::get('/assessments/{assessment}', [TahsinAssessmentController::class, 'show'])
                ->name('assessments.show');

            Route::resource('levels', TahsinLevelController::class);
            Route::resource('skills', TahsinSkillController::class);
        });

    // Parent Tahsin Portal Route
    Route::get('/portal/parent/tahsin', [ParentTahsinPortalController::class, 'index'])
        ->middleware(['role:parent'])
        ->name('portal.parent.tahsin');

    // Student Tahsin Portal Route
    Route::get('/portal/student/tahsin', [StudentTahsinPortalController::class, 'index'])
        ->middleware(['role:student'])
        ->name('portal.student.tahsin');

    // Student Finance Ledger Routes
    Route::prefix('finance')
        ->name('finance.')
        ->middleware(['role:super_admin,admin,principal'])
        ->group(function (): void {
            Route::get('/reports/dashboard', [FinanceReportController::class, 'dashboard'])
                ->name('reports.dashboard');

            Route::get('/bills', [StudentBillController::class, 'index'])
                ->name('bills.index');

            Route::get('/bills/create', [StudentBillController::class, 'create'])
                ->name('bills.create');

            Route::post('/bills', [StudentBillController::class, 'store'])
                ->name('bills.store');

            Route::get('/bills/{bill}', [StudentBillController::class, 'show'])
                ->name('bills.show');

            Route::patch('/bills/{bill}/void', [StudentBillController::class, 'void'])
                ->name('bills.void');

            Route::get('/payments', [StudentPaymentController::class, 'index'])
                ->name('payments.index');

            Route::get('/payments/create', [StudentPaymentController::class, 'create'])
                ->name('payments.create');

            Route::post('/payments', [StudentPaymentController::class, 'store'])
                ->name('payments.store');

            Route::get('/payments/{payment}', [StudentPaymentController::class, 'show'])
                ->name('payments.show');

            Route::patch('/payments/{payment}/void', [StudentPaymentController::class, 'void'])
                ->name('payments.void');

            Route::get('/ledgers/students/{student}', [FinanceLedgerController::class, 'student'])
                ->name('ledgers.student');

            Route::resource('fee-categories', FinanceFeeCategoryController::class);
            Route::resource('fee-items', FinanceFeeItemController::class);
        });

    Route::get('/portal/parent/finance', [ParentFinancePortalController::class, 'index'])
        ->middleware(['role:parent'])
        ->name('portal.parent.finance');

    Route::get('/portal/student/finance', [StudentFinancePortalController::class, 'index'])
        ->middleware(['role:student'])
        ->name('portal.student.finance');

    Route::prefix('schoolos')
        ->name('schoolos.')
        ->group(function (): void {
            Route::get('/', [SchoolOsDashboardController::class, 'index'])
                ->name('dashboard');

            Route::get('/search', SchoolOsSearchController::class)
                ->name('search');

            Route::get('/students/{student}/360', [Student360Controller::class, 'show'])
                ->name('students.show');

            Route::resource('academic-years', AcademicYearController::class)
                ->except(['show', 'destroy']);

            Route::get('/settings', [SchoolSettingController::class, 'index'])
                ->name('settings.index');

            Route::patch('/settings', [SchoolSettingController::class, 'update'])
                ->name('settings.update');

            Route::get('/modules', [SystemModuleController::class, 'index'])
                ->name('modules.index');

            Route::patch('/modules/{systemModule}', [SystemModuleController::class, 'update'])
                ->name('modules.update');
        });
});
