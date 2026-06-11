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
use App\Http\Controllers\Tahfizh\TahfizhDebtController;
use App\Http\Controllers\Tahfizh\TahfizhTargetController;
use Illuminate\Support\Facades\Route;

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
            Route::get('hafalan-records/create', [HafalanRecordController::class, 'create'])
                ->name('hafalan-records.create');

            Route::post('hafalan-records', [HafalanRecordController::class, 'store'])
                ->name('hafalan-records.store');

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
});
