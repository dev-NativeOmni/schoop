<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterData\ClassRoomController;
use App\Http\Controllers\MasterData\ParentController;
use App\Http\Controllers\MasterData\SchoolController;
use App\Http\Controllers\MasterData\StudentController;
use App\Http\Controllers\MasterData\TeacherController;
use App\Http\Controllers\MasterData\UserController;
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
use App\Http\Controllers\QuranMushafController;
use App\Http\Controllers\QuranPdfController;
use App\Http\Controllers\Boarding\BoardingDashboardController;
use App\Http\Controllers\Boarding\BoardingDormitoryController;
use App\Http\Controllers\Boarding\BoardingRoomController;
use App\Http\Controllers\Boarding\BoardingBedController;
use App\Http\Controllers\Boarding\BoardingSupervisorController;
use App\Http\Controllers\Boarding\BoardingStudentAssignmentController;
use App\Http\Controllers\Boarding\BoardingLeaveRequestController;
use App\Http\Controllers\Boarding\BoardingHealthLogController;
use App\Http\Controllers\Boarding\BoardingDisciplineLogController;
use App\Http\Controllers\Boarding\BoardingRollCallController;
use App\Http\Controllers\Boarding\BoardingReportController;
use App\Http\Controllers\Portal\ParentBoardingPortalController;
use App\Http\Controllers\Portal\StudentBoardingPortalController;
use App\Http\Controllers\Tenancy\TenantAuditLogController;
use App\Http\Controllers\Tenancy\TenantDashboardController;
use App\Http\Controllers\Tenancy\TenantMembershipController;
use App\Http\Controllers\Tenancy\TenantModuleController;
use App\Http\Controllers\Tenancy\TenantSettingController;
use App\Http\Controllers\Tenancy\TenantSwitcherController;
use App\Http\Controllers\Cashless\CashlessMerchantController;
use App\Http\Controllers\Cashless\CashlessProductController;
use App\Http\Controllers\Cashless\CashlessWalletController;
use App\Http\Controllers\Cashless\CashlessTopUpController;
use App\Http\Controllers\Cashless\CashlessPosSessionController;
use App\Http\Controllers\Cashless\CashlessPosController;
use App\Http\Controllers\Cashless\CashlessRefundController;
use App\Http\Controllers\Cashless\CashlessSettlementController;
use App\Http\Controllers\Cashless\CashlessReportController;
use App\Http\Controllers\Portal\ParentCashlessPortalController;
use App\Http\Controllers\Portal\StudentCashlessPortalController;
use App\Http\Controllers\SaasOps\CustomerSuccessNoteController;
use App\Http\Controllers\SaasOps\ImplementationProjectController;
use App\Http\Controllers\SaasOps\IncidentReportController;
use App\Http\Controllers\SaasOps\KnowledgeBaseArticleController;
use App\Http\Controllers\SaasOps\OnboardingChecklistController;
use App\Http\Controllers\SaasOps\ProductScaleDashboardController;
use App\Http\Controllers\SaasOps\ProductUsageSnapshotController;
use App\Http\Controllers\SaasOps\ReleaseNoteController;
use App\Http\Controllers\SaasOps\SaasSchoolSubscriptionController;
use App\Http\Controllers\SaasOps\SaasSubscriptionPlanController;
use App\Http\Controllers\SaasOps\SaasTenantInvoiceController;
use App\Http\Controllers\SaasOps\SlaPolicyController;
use App\Http\Controllers\SaasOps\SupportTicketController;
use App\Http\Controllers\DeveloperPortal\ApiClientController;
use App\Http\Controllers\DeveloperPortal\ApiDocumentationPageController;
use App\Http\Controllers\DeveloperPortal\ApiRequestLogController;
use App\Http\Controllers\DeveloperPortal\ApiScopeController;
use App\Http\Controllers\DeveloperPortal\DeveloperPortalDashboardController;
use App\Http\Controllers\DeveloperPortal\PartnerIntegrationController;
use App\Http\Controllers\DeveloperPortal\WebhookDeliveryController;
use App\Http\Controllers\DeveloperPortal\WebhookEndpointController;
use App\Http\Controllers\Analytics\ExecutiveAnalyticsDashboardController;
use App\Http\Controllers\Analytics\SchoolAnalyticsDashboardController;
use App\Http\Controllers\Analytics\TenantHealthAnalyticsController;
use App\Http\Controllers\Analytics\AcademicAnalyticsController;
use App\Http\Controllers\Analytics\OperationalAnalyticsController;
use App\Http\Controllers\Analytics\FinanceAnalyticsController;
use App\Http\Controllers\Analytics\SupportAnalyticsController;
use App\Http\Controllers\Analytics\MobileApiAnalyticsController;
use App\Http\Controllers\Analytics\ExecutiveReportController;
use App\Http\Controllers\Analytics\MetricDictionaryController;
use App\Http\Controllers\Ai\AiLearningDashboardController;
use App\Http\Controllers\Ai\AiLearningProfileController;
use App\Http\Controllers\Ai\AiRecommendationController;
use App\Http\Controllers\Ai\AiPracticePlanController;
use App\Http\Controllers\Ai\AiTeacherReviewQueueController;
use App\Http\Controllers\Ai\AiFeedbackDraftController;
use App\Http\Controllers\Ai\AiFeatureFlagController;
use App\Http\Controllers\Ai\AiSafetyEventController;
use App\Http\Controllers\Portal\ParentAiLearningPortalController;
use App\Http\Controllers\Portal\StudentAiLearningPortalController;
use App\Http\Controllers\Billing\SubscriptionPlanController;
use App\Http\Controllers\Billing\PlanModuleController;
use App\Http\Controllers\Billing\SchoolSubscriptionController;
use App\Http\Controllers\Billing\SchoolModuleOverrideController;
use App\Http\Controllers\Billing\ModuleLockedController;



Route::get('/', [App\Http\Controllers\Public\TenantPublicLandingController::class, 'index'])->name('tenant.public.landing');
Route::get('/manifest.json', [App\Http\Controllers\Public\TenantPwaManifestController::class, 'show'])->name('tenant.pwa.manifest');

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
    Route::post('/dashboard/super-admin/update-logo', [DashboardController::class, 'updateLogo'])
        ->middleware('role:super_admin')
        ->name('super-admin.update-logo');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
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
        Route::resource('users', UserController::class);
    });

    // Quran Interactive Routes
    Route::get('/quran-pdf', [QuranPdfController::class, 'index'])
        ->name('quran.pdf');

    Route::post('/quran-pdf/config', [QuranPdfController::class, 'updateConfig'])
        ->middleware('role:super_admin,admin')
        ->name('quran.pdf.config');

    Route::get('/mushaf', [QuranMushafController::class, 'index'])
        ->name('quran.mushaf');

    Route::middleware(['subscription.active'])->group(function (): void {
        // Tahfizh Setoran Routes
    Route::middleware('role:super_admin,admin,teacher', 'module:tahfizh')
        ->prefix('tahfizh')
        ->name('tahfizh.')
        ->group(function (): void {
            Route::get('hafalan-records/create', [HafalanRecordController::class, 'create'])
                ->name('hafalan-records.create');

            Route::post('hafalan-records', [HafalanRecordController::class, 'store'])
                ->name('hafalan-records.store');
        });

    Route::middleware('role:super_admin,admin,teacher,principal', 'module:tahfizh')
        ->prefix('tahfizh')
        ->name('tahfizh.')
        ->group(function (): void {
            Route::get('hafalan-records', [HafalanRecordController::class, 'index'])
                ->name('hafalan-records.index');

            Route::get('hafalan-records/{hafalan_record}', [HafalanRecordController::class, 'show'])
                ->name('hafalan-records.show');
        });

    Route::middleware('role:super_admin,admin,teacher', 'module:tahfizh')
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
    Route::middleware('role:super_admin,admin', 'module:tahfizh')
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

    Route::middleware('role:super_admin,admin,principal,teacher', 'module:tahfizh')
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

    // Tahfizh Reports Routes
    Route::middleware('role:super_admin,admin,principal,teacher', 'module:tahfizh')
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
    Route::middleware('role:parent', 'module:tahfizh')
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
    Route::middleware('role:student', 'module:tahfizh')
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
        ->middleware('module:notifications')
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
    Route::middleware('role:super_admin,admin', 'module:notifications')
        ->prefix('notifications/announcements')
        ->name('notifications.announcements.')
        ->group(function (): void {
            Route::get('create', [AnnouncementController::class, 'create'])
                ->name('create');

            Route::post('/', [AnnouncementController::class, 'store'])
                ->name('store');
        });

    // Export Routes
    Route::middleware('role:super_admin,admin,principal,teacher', 'module:exports')
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
    Route::middleware('role:super_admin,admin,teacher,principal', 'module:mutabaah')
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

    Route::middleware('role:super_admin,admin', 'module:mutabaah')
        ->prefix('mutabaah')
        ->name('mutabaah.')
        ->group(function (): void {
            Route::resource('activities', MutabaahActivityController::class);
        });

    // Parent Mutabaah Portal Route
    Route::get('/portal/parent/mutabaah/{student}', [ParentMutabaahPortalController::class, 'show'])
        ->middleware('role:parent', 'module:mutabaah')
        ->name('portal.parent.mutabaah');

    // Student Mutabaah Portal Route
    Route::get('/portal/student/mutabaah', [StudentMutabaahPortalController::class, 'index'])
        ->middleware('role:student', 'module:mutabaah')
        ->name('portal.student.mutabaah');

    // Attendance Routes
    Route::prefix('attendance')
        ->name('attendance.')
        ->middleware(['role:super_admin,admin,admin_sekolah,kepala_sekolah,principal,teacher,guru,guru_tahfidz', 'module:attendance'])
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
        ->middleware(['role:parent', 'module:attendance'])
        ->name('portal.parent.attendance');

    // Student Attendance Portal Route
    Route::get('/portal/student/attendance', [StudentAttendancePortalController::class, 'index'])
        ->middleware(['role:student', 'module:attendance'])
        ->name('portal.student.attendance');

    // Tahsin Management Routes
    Route::prefix('tahsin')
        ->name('tahsin.')
        ->middleware(['role:super_admin,admin,admin_sekolah,kepala_sekolah,principal,teacher,guru,guru_tahfidz', 'module:tahsin'])
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
        ->middleware(['role:parent', 'module:tahsin'])
        ->name('portal.parent.tahsin');

    // Student Tahsin Portal Route
    Route::get('/portal/student/tahsin', [StudentTahsinPortalController::class, 'index'])
        ->middleware(['role:student', 'module:tahsin'])
        ->name('portal.student.tahsin');

    // Student Finance Ledger Routes
    Route::prefix('finance')
        ->name('finance.')
        ->middleware(['role:super_admin,admin,principal,finance', 'module:finance'])
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
        ->middleware(['role:parent', 'module:finance'])
        ->name('portal.parent.finance');

    Route::get('/portal/parent/cashless', [ParentCashlessPortalController::class, 'index'])
        ->middleware(['role:parent', 'module:cashless'])
        ->name('portal.parent.cashless');

    Route::put('/portal/parent/cashless/{wallet}', [ParentCashlessPortalController::class, 'update'])
        ->middleware(['role:parent', 'module:cashless'])
        ->name('portal.parent.cashless.update');

    Route::get('/portal/student/finance', [StudentFinancePortalController::class, 'index'])
        ->middleware(['role:student', 'module:finance'])
        ->name('portal.student.finance');

    Route::get('/portal/student/cashless', StudentCashlessPortalController::class)
        ->middleware(['role:student', 'module:cashless'])
        ->name('portal.student.cashless');

    Route::prefix('schoolos')
        ->name('schoolos.')
        ->middleware('module:schoolos')
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


    // Boarding School Management System Routes
    Route::middleware(['role:super_admin,admin,principal,boarding_supervisor', 'module:boarding'])->prefix('boarding')->name('boarding.')->group(function (): void {
        Route::get('/', BoardingDashboardController::class)->name('dashboard');

        // Master Data CRUD
        Route::resource('dormitories', BoardingDormitoryController::class);
        Route::resource('rooms', BoardingRoomController::class);
        Route::resource('beds', BoardingBedController::class);
        Route::resource('supervisors', BoardingSupervisorController::class)->parameters([
            'supervisors' => 'supervisor',
        ]);

        // Student Assignments
        Route::get('assignments', [BoardingStudentAssignmentController::class, 'index'])->name('assignments.index');
        Route::get('assignments/create', [BoardingStudentAssignmentController::class, 'create'])->name('assignments.create');
        Route::post('assignments', [BoardingStudentAssignmentController::class, 'store'])->name('assignments.store');
        Route::get('assignments/{assignment}', [BoardingStudentAssignmentController::class, 'show'])->name('assignments.show');
        Route::put('assignments/{assignment}/end', [BoardingStudentAssignmentController::class, 'end'])->name('assignments.end');
        Route::post('assignments/{assignment}/move', [BoardingStudentAssignmentController::class, 'move'])->name('assignments.move');

        // Leave Requests
        Route::get('leave-requests', [BoardingLeaveRequestController::class, 'index'])->name('leave-requests.index');
        Route::get('leave-requests/create', [BoardingLeaveRequestController::class, 'create'])->name('leave-requests.create');
        Route::post('leave-requests', [BoardingLeaveRequestController::class, 'store'])->name('leave-requests.store');
        Route::get('leave-requests/{leaveRequest}', [BoardingLeaveRequestController::class, 'show'])->name('leave-requests.show');
        Route::post('leave-requests/{leaveRequest}/approve', [BoardingLeaveRequestController::class, 'approve'])->name('leave-requests.approve');
        Route::post('leave-requests/{leaveRequest}/reject', [BoardingLeaveRequestController::class, 'reject'])->name('leave-requests.reject');
        Route::post('leave-requests/{leaveRequest}/mark-returned', [BoardingLeaveRequestController::class, 'markReturned'])->name('leave-requests.mark-returned');
        Route::post('leave-requests/{leaveRequest}/cancel', [BoardingLeaveRequestController::class, 'cancel'])->name('leave-requests.cancel');

        // Health Logs
        Route::get('health-logs', [BoardingHealthLogController::class, 'index'])->name('health-logs.index');
        Route::get('health-logs/create', [BoardingHealthLogController::class, 'create'])->name('health-logs.create');
        Route::post('health-logs', [BoardingHealthLogController::class, 'store'])->name('health-logs.store');
        Route::get('health-logs/{healthLog}', [BoardingHealthLogController::class, 'show'])->name('health-logs.show');

        // Discipline Logs
        Route::get('discipline-logs', [BoardingDisciplineLogController::class, 'index'])->name('discipline-logs.index');
        Route::get('discipline-logs/create', [BoardingDisciplineLogController::class, 'create'])->name('discipline-logs.create');
        Route::post('discipline-logs', [BoardingDisciplineLogController::class, 'store'])->name('discipline-logs.store');
        Route::get('discipline-logs/{disciplineLog}', [BoardingDisciplineLogController::class, 'show'])->name('discipline-logs.show');

        // Roll Calls
        Route::get('roll-calls', [BoardingRollCallController::class, 'index'])->name('roll-calls.index');
        Route::get('roll-calls/create', [BoardingRollCallController::class, 'create'])->name('roll-calls.create');
        Route::post('roll-calls', [BoardingRollCallController::class, 'store'])->name('roll-calls.store');
        Route::get('roll-calls/{rollCall}', [BoardingRollCallController::class, 'show'])->name('roll-calls.show');
        Route::post('roll-calls/{rollCall}/records', [BoardingRollCallController::class, 'storeRecords'])->name('roll-calls.records.store');
        Route::post('roll-calls/{rollCall}/close', [BoardingRollCallController::class, 'close'])->name('roll-calls.close');

        // Reports
        Route::get('reports', BoardingReportController::class)->name('reports.dashboard');
    });

    // Parent Boarding Portal Route
    Route::get('/portal/parent/boarding', ParentBoardingPortalController::class)
        ->middleware(['role:parent', 'module:boarding'])
        ->name('portal.parent.boarding');

    // Student Boarding Portal Route
    Route::get('/portal/student/boarding', StudentBoardingPortalController::class)
        ->middleware(['role:student', 'module:boarding'])
        ->name('portal.student.boarding');

    // Phase 24 — LMS Lite & Learning Content Routes
    Route::middleware(['auth', 'role:super_admin,admin,principal,teacher', 'module:lms'])->prefix('lms')->name('lms.')->group(function (): void {
        Route::get('/', [\App\Http\Controllers\Lms\LmsDashboardController::class, 'index'])->name('dashboard');
        Route::resource('courses', \App\Http\Controllers\Lms\LmsCourseController::class);
        Route::resource('modules', \App\Http\Controllers\Lms\LmsCourseModuleController::class);
        Route::post('modules/reorder', [\App\Http\Controllers\Lms\LmsCourseModuleController::class, 'reorder'])->name('modules.reorder');
        Route::resource('lessons', \App\Http\Controllers\Lms\LmsLessonController::class);
        Route::post('lessons/reorder', [\App\Http\Controllers\Lms\LmsLessonController::class, 'reorder'])->name('lessons.reorder');
        Route::resource('resources', \App\Http\Controllers\Lms\LmsLessonResourceController::class);
        Route::resource('enrollments', \App\Http\Controllers\Lms\LmsEnrollmentController::class);
        Route::resource('assignments', \App\Http\Controllers\Lms\LmsAssignmentController::class);
        Route::post('submissions/{submission}/grade', [\App\Http\Controllers\Lms\LmsAssignmentSubmissionController::class, 'grade'])->name('submissions.grade');
        Route::resource('quizzes', \App\Http\Controllers\Lms\LmsQuizController::class);
        Route::resource('quizzes.questions', \App\Http\Controllers\Lms\LmsQuizQuestionController::class);
        Route::get('attempts/{attempt}', [\App\Http\Controllers\Lms\LmsQuizAttemptController::class, 'show'])->name('attempts.show');
        Route::get('reports', [\App\Http\Controllers\Lms\LmsProgressReportController::class, 'index'])->name('reports.index');
    });

    // LMS Secure Private File Access Route (authenticated users only)
    Route::get('/lms/private-file/{type}/{id}', [\App\Http\Controllers\Lms\LmsLessonResourceController::class, 'downloadPrivateFile'])
        ->middleware(['auth', 'module:lms'])
        ->name('lms.private-file.download');

    // Portal Student LMS Routes
    Route::middleware(['auth', 'role:student', 'module:lms'])->prefix('portal/student/lms')->name('portal.student.lms.')->group(function (): void {
        Route::get('/', [\App\Http\Controllers\Portal\StudentLmsPortalController::class, 'index'])->name('index');
        Route::get('/course/{course}', [\App\Http\Controllers\Portal\StudentLmsPortalController::class, 'showCourse'])->name('course.show');
        Route::get('/lesson/{lesson}', [\App\Http\Controllers\Portal\StudentLmsPortalController::class, 'showLesson'])->name('lesson.show');
        Route::post('/lesson/{lesson}/complete', [\App\Http\Controllers\Portal\StudentLmsPortalController::class, 'completeLesson'])->name('lesson.complete');
        Route::post('/assignment/{assignment}/submit', [\App\Http\Controllers\Portal\StudentLmsPortalController::class, 'submitAssignment'])->name('assignment.submit');
        Route::post('/quiz/{quiz}/start', [\App\Http\Controllers\Portal\StudentLmsPortalController::class, 'startQuiz'])->name('quiz.start');
        Route::get('/quiz/attempt/{attempt}', [\App\Http\Controllers\Portal\StudentLmsPortalController::class, 'showQuizAttempt'])->name('quiz.attempt.show');
        Route::post('/quiz/attempt/{attempt}/submit', [\App\Http\Controllers\Portal\StudentLmsPortalController::class, 'submitQuizAttempt'])->name('quiz.attempt.submit');
    });

    // Portal Parent LMS Routes
    Route::middleware(['auth', 'role:parent', 'module:lms'])->prefix('portal/parent/lms')->name('portal.parent.lms.')->group(function (): void {
        Route::get('/', [\App\Http\Controllers\Portal\ParentLmsPortalController::class, 'index'])->name('index');
        Route::get('/student/{student}/course/{course}', [\App\Http\Controllers\Portal\ParentLmsPortalController::class, 'showChildProgress'])->name('child.course.show');
    });

    });

    // Phase 20 — Company/Product Scale & SaaS Operations
    Route::middleware(['role:super_admin,operations_manager,support_staff,customer_success,sales,admin,admin_sekolah,principal,kepala_sekolah,teacher,merchant,boarding_supervisor,finance,cashier'])
        ->prefix('saas-ops')
        ->name('saas-ops.')
        ->group(function (): void {
            // Internal operations roles only
            Route::middleware(['role:super_admin,operations_manager,support_staff,customer_success,sales'])->group(function (): void {
                Route::get('/dashboard', [ProductScaleDashboardController::class, 'index'])->name('dashboard');
                Route::get('usage-snapshots', [ProductUsageSnapshotController::class, 'index'])->name('usage-snapshots.index');
            });

            Route::middleware(['role:super_admin,operations_manager'])->group(function (): void {
                Route::resource('subscription-plans', SaasSubscriptionPlanController::class);

                Route::get('school-subscriptions/create', [SaasSchoolSubscriptionController::class, 'create'])->name('school-subscriptions.create');
                Route::post('school-subscriptions', [SaasSchoolSubscriptionController::class, 'store'])->name('school-subscriptions.store');
                Route::get('school-subscriptions', [SaasSchoolSubscriptionController::class, 'index'])->name('school-subscriptions.index');
                Route::get('school-subscriptions/{subscription}', [SaasSchoolSubscriptionController::class, 'show'])->name('school-subscriptions.show');
                Route::post('school-subscriptions/{subscription}/activate', [SaasSchoolSubscriptionController::class, 'activate'])->name('school-subscriptions.activate');
                Route::post('school-subscriptions/{subscription}/suspend', [SaasSchoolSubscriptionController::class, 'suspend'])->name('school-subscriptions.suspend');
                Route::post('school-subscriptions/{subscription}/cancel', [SaasSchoolSubscriptionController::class, 'cancel'])->name('school-subscriptions.cancel');

                Route::get('tenant-invoices', [SaasTenantInvoiceController::class, 'index'])->name('tenant-invoices.index');
                Route::get('tenant-invoices/create', [SaasTenantInvoiceController::class, 'create'])->name('tenant-invoices.create');
                Route::post('tenant-invoices', [SaasTenantInvoiceController::class, 'store'])->name('tenant-invoices.store');
                Route::get('tenant-invoices/{invoice}', [SaasTenantInvoiceController::class, 'show'])->name('tenant-invoices.show');
                Route::post('tenant-invoices/{invoice}/issue', [SaasTenantInvoiceController::class, 'issue'])->name('tenant-invoices.issue');
                Route::post('tenant-invoices/{invoice}/payments', [SaasTenantInvoiceController::class, 'storePayment'])->name('tenant-invoices.payments.store');
                Route::post('tenant-invoices/{invoice}/void', [SaasTenantInvoiceController::class, 'void'])->name('tenant-invoices.void');

                Route::resource('implementation-projects', ImplementationProjectController::class);
                Route::get('implementation-projects/{project}/onboarding', [OnboardingChecklistController::class, 'show'])->name('onboarding.show');
                Route::post('implementation-projects/{project}/onboarding/{record}/complete', [OnboardingChecklistController::class, 'complete'])->name('onboarding.complete');

                Route::resource('sla-policies', SlaPolicyController::class);
            });

            Route::middleware(['role:super_admin,operations_manager,support_staff'])->group(function (): void {
                Route::resource('incident-reports', IncidentReportController::class);
                Route::resource('release-notes', ReleaseNoteController::class);
            });

            Route::middleware(['role:super_admin,operations_manager,support_staff,customer_success'])->group(function (): void {
                Route::resource('knowledge-base', KnowledgeBaseArticleController::class);
                Route::resource('customer-success-notes', CustomerSuccessNoteController::class)->only(['index', 'create', 'store', 'show']);
            });

            // Support tickets accessible to both school users and SaaS operations staff
            Route::resource('support-tickets', SupportTicketController::class)->only(['index', 'create', 'store', 'show']);
            Route::post('support-tickets/{ticket}/messages', [SupportTicketController::class, 'storeMessage'])->name('support-tickets.messages.store');
            Route::post('support-tickets/{ticket}/assign', [SupportTicketController::class, 'assign'])->name('support-tickets.assign');
            Route::post('support-tickets/{ticket}/resolve', [SupportTicketController::class, 'resolve'])->name('support-tickets.resolve');
            Route::post('support-tickets/{ticket}/close', [SupportTicketController::class, 'close'])->name('support-tickets.close');
        });

    // Phase 22 — External API, Partner Integration & Developer Portal
    Route::middleware(['role:super_admin,operations_manager,support_staff,customer_success,admin,admin_sekolah'])
        ->prefix('developer-portal')
        ->name('developer-portal.')
        ->group(function (): void {
            Route::get('/dashboard', DeveloperPortalDashboardController::class)->name('dashboard');

            Route::post('api-clients/{apiClient}/tokens', [ApiClientController::class, 'generateToken'])->name('api-clients.tokens.generate');
            Route::post('api-clients/{apiClient}/tokens/rotate', [ApiClientController::class, 'rotateToken'])->name('api-clients.tokens.rotate');
            Route::delete('api-clients/{apiClient}/tokens/{token}', [ApiClientController::class, 'revokeToken'])->name('api-clients.tokens.revoke');
            Route::resource('api-clients', ApiClientController::class)->except(['destroy']);
            Route::delete('api-clients/{apiClient}/revoke', [ApiClientController::class, 'destroy'])->name('api-clients.revoke');

            Route::get('api-scopes', [ApiScopeController::class, 'index'])->name('api-scopes.index');
            Route::get('api-scopes/{apiScope}', [ApiScopeController::class, 'show'])->name('api-scopes.show');

            Route::post('partner-integrations/{partnerIntegration}/approve', [PartnerIntegrationController::class, 'approve'])->name('partner-integrations.approve');
            Route::resource('partner-integrations', PartnerIntegrationController::class);

            Route::resource('webhooks', WebhookEndpointController::class)->parameters(['webhooks' => 'webhook']);

            Route::get('webhook-deliveries', [WebhookDeliveryController::class, 'index'])->name('webhook-deliveries.index');
            Route::get('webhook-deliveries/{delivery}', [WebhookDeliveryController::class, 'show'])->name('webhook-deliveries.show');
            Route::post('webhook-deliveries/{delivery}/retry', [WebhookDeliveryController::class, 'retry'])->name('webhook-deliveries.retry');

            Route::get('request-logs', [ApiRequestLogController::class, 'index'])->name('request-logs.index');
            Route::get('request-logs/{log}', [ApiRequestLogController::class, 'show'])->name('request-logs.show');

            Route::resource('docs', ApiDocumentationPageController::class)->parameters(['docs' => 'doc']);
        });

    // Phase 23 — Advanced Analytics & Executive Intelligence
    Route::middleware(['auth'])
        ->prefix('analytics')
        ->name('analytics.')
        ->group(function (): void {
            Route::get('/executive', [ExecutiveAnalyticsDashboardController::class, 'index'])
                ->name('executive.dashboard');

            Route::get('/school', [SchoolAnalyticsDashboardController::class, 'index'])
                ->name('school.dashboard');

            Route::get('/tenant-health', [TenantHealthAnalyticsController::class, 'index'])
                ->name('tenant-health.index');
            Route::get('/tenant-health/{school}', [TenantHealthAnalyticsController::class, 'show'])
                ->name('tenant-health.show');

            Route::get('/academic', [AcademicAnalyticsController::class, 'index'])
                ->name('academic.dashboard');

            Route::get('/operational', [OperationalAnalyticsController::class, 'index'])
                ->name('operational.dashboard');

            Route::get('/finance', [FinanceAnalyticsController::class, 'index'])
                ->name('finance.dashboard');

            Route::get('/support', [SupportAnalyticsController::class, 'index'])
                ->name('support.dashboard');

            Route::get('/mobile-api', [MobileApiAnalyticsController::class, 'index'])
                ->name('mobile-api.dashboard');

            Route::get('/executive-reports', [ExecutiveReportController::class, 'index'])
                ->name('executive-reports.index');
            Route::get('/executive-reports/create', [ExecutiveReportController::class, 'create'])
                ->name('executive-reports.create');
            Route::post('/executive-reports', [ExecutiveReportController::class, 'store'])
                ->name('executive-reports.store');
            Route::get('/executive-reports/{report}', [ExecutiveReportController::class, 'show'])
                ->name('executive-reports.show');
            Route::get('/executive-reports/{report}/print', [ExecutiveReportController::class, 'print'])
                ->name('executive-reports.print');

            Route::resource('metric-dictionary', MetricDictionaryController::class);
        });

    // Tenancy Management Routes
    Route::middleware(['tenant.resolve'])->group(function (): void {
        Route::get('/tenancy', [TenantDashboardController::class, 'index'])->name('tenancy.dashboard');

        Route::get('/tenancy/switch', [TenantSwitcherController::class, 'index'])->name('tenancy.switcher');
        Route::post('/tenancy/switch', [TenantSwitcherController::class, 'switch'])->name('tenancy.switch');

        Route::middleware(['tenant.access', 'role:super_admin,admin,admin_sekolah'])->group(function (): void {
            Route::resource('/tenancy/memberships', TenantMembershipController::class)
                ->names('tenancy.memberships');

            Route::get('/tenancy/settings', [TenantSettingController::class, 'index'])->name('tenancy.settings.index');
            Route::put('/tenancy/settings', [TenantSettingController::class, 'update'])->name('tenancy.settings.update');

            Route::get('/tenancy/modules', [TenantModuleController::class, 'index'])->name('tenancy.modules.index');
            Route::put('/tenancy/modules/{tenantModule}', [TenantModuleController::class, 'update'])->name('tenancy.modules.update');

            Route::get('/tenancy/audit-logs', [TenantAuditLogController::class, 'index'])->name('tenancy.audit-logs.index');

            // Phase 19 — Cashless Kantin / Merchant POS
            Route::prefix('cashless')->name('cashless.')->middleware(['role:super_admin,admin,finance,cashier,merchant,principal', 'subscription.active', 'module:cashless'])->group(function (): void {
                Route::middleware(['role:super_admin,admin,finance,principal'])->group(function (): void {
                    Route::get('/reports/dashboard', [CashlessReportController::class, 'dashboard'])->name('reports.dashboard');
                    Route::get('/reports/wallet-transactions', [CashlessReportController::class, 'walletTransactions'])->name('reports.wallet-transactions');
                    Route::get('/reports/merchant-sales', [CashlessReportController::class, 'merchantSales'])->name('reports.merchant-sales');
                });

                Route::middleware(['role:super_admin,admin,finance'])->group(function (): void {
                    Route::resource('merchants', CashlessMerchantController::class)->except(['destroy']);
                    Route::resource('products', CashlessProductController::class)->except(['destroy']);

                    Route::get('/wallets', [CashlessWalletController::class, 'index'])->name('wallets.index');
                    Route::get('/wallets/{wallet}', [CashlessWalletController::class, 'show'])->name('wallets.show');
                    Route::patch('/wallets/{wallet}/freeze', [CashlessWalletController::class, 'freeze'])->name('wallets.freeze');
                    Route::patch('/wallets/{wallet}/unfreeze', [CashlessWalletController::class, 'unfreeze'])->name('wallets.unfreeze');

                    Route::get('/top-ups/create', [CashlessTopUpController::class, 'create'])->name('top-ups.create');
                    Route::post('/top-ups', [CashlessTopUpController::class, 'store'])->name('top-ups.store');
                    Route::patch('/top-ups/{transaction}/void', [CashlessTopUpController::class, 'void'])->name('top-ups.void');

                    Route::get('/refunds/create', [CashlessRefundController::class, 'create'])->name('refunds.create');
                    Route::post('/refunds', [CashlessRefundController::class, 'store'])->name('refunds.store');

                    Route::get('/settlements', [CashlessSettlementController::class, 'index'])->name('settlements.index');
                    Route::post('/settlements', [CashlessSettlementController::class, 'store'])->name('settlements.store');
                    Route::get('/settlements/{settlement}', [CashlessSettlementController::class, 'show'])->name('settlements.show');
                    Route::patch('/settlements/{settlement}/approve', [CashlessSettlementController::class, 'approve'])->name('settlements.approve');
                    Route::patch('/settlements/{settlement}/void', [CashlessSettlementController::class, 'void'])->name('settlements.void');
                });

                Route::middleware(['role:super_admin,admin,cashier,merchant'])->group(function (): void {
                    Route::get('/pos-sessions', [CashlessPosSessionController::class, 'index'])->name('pos-sessions.index');
                    Route::get('/pos-sessions/open', [CashlessPosSessionController::class, 'open'])->name('pos-sessions.open');
                    Route::post('/pos-sessions', [CashlessPosSessionController::class, 'store'])->name('pos-sessions.store');
                    Route::get('/pos-sessions/{posSession}', [CashlessPosSessionController::class, 'show'])->name('pos-sessions.show');
                    Route::patch('/pos-sessions/{posSession}/close', [CashlessPosSessionController::class, 'close'])->name('pos-sessions.close');

                    Route::get('/pos/cashier', [CashlessPosController::class, 'cashier'])->name('pos.cashier');
                    Route::post('/pos/sales', [CashlessPosController::class, 'store'])->name('pos.sales.store');
                    Route::get('/pos/receipt/{sale}', [CashlessPosController::class, 'receipt'])->name('pos.receipt');
                });
            });

            // White-Label School App Builder Routes
            Route::prefix('white-label')->name('white-label.')->middleware(['role:super_admin,admin,admin_sekolah', 'subscription.active', 'module:white_label'])->group(function (): void {
                Route::get('/', [App\Http\Controllers\WhiteLabel\WhiteLabelDashboardController::class, 'index'])->name('dashboard');
                
                // Brand Profile
                Route::get('/brand', [App\Http\Controllers\WhiteLabel\SchoolBrandProfileController::class, 'show'])->name('brand.show');
                Route::get('/brand/edit', [App\Http\Controllers\WhiteLabel\SchoolBrandProfileController::class, 'edit'])->name('brand.edit');
                Route::put('/brand', [App\Http\Controllers\WhiteLabel\SchoolBrandProfileController::class, 'update'])->name('brand.update');
                
                // Theme Builder
                Route::get('/theme/edit', [App\Http\Controllers\WhiteLabel\SchoolThemeSettingController::class, 'edit'])->name('themes.edit');
                Route::put('/theme', [App\Http\Controllers\WhiteLabel\SchoolThemeSettingController::class, 'update'])->name('themes.update');
                Route::get('/theme/preview', [App\Http\Controllers\WhiteLabel\SchoolThemeSettingController::class, 'preview'])->name('themes.preview');
                
                // Domain Mapping
                Route::post('/domains/{domain}/verify', [App\Http\Controllers\WhiteLabel\SchoolDomainMappingController::class, 'verify'])->name('domains.verify');
                Route::post('/domains/{domain}/activate', [App\Http\Controllers\WhiteLabel\SchoolDomainMappingController::class, 'activate'])->name('domains.activate');
                Route::post('/domains/{domain}/disable', [App\Http\Controllers\WhiteLabel\SchoolDomainMappingController::class, 'disable'])->name('domains.disable');
                Route::resource('/domains', App\Http\Controllers\WhiteLabel\SchoolDomainMappingController::class)->names('domains');
                
                // PWA Settings
                Route::get('/pwa', [App\Http\Controllers\WhiteLabel\SchoolPwaSettingController::class, 'show'])->name('pwa.show');
                Route::get('/pwa/edit', [App\Http\Controllers\WhiteLabel\SchoolPwaSettingController::class, 'edit'])->name('pwa.edit');
                Route::put('/pwa', [App\Http\Controllers\WhiteLabel\SchoolPwaSettingController::class, 'update'])->name('pwa.update');
                
                // Preview, Publish & Rollback
                Route::get('/preview', [App\Http\Controllers\WhiteLabel\WhiteLabelPreviewController::class, 'show'])->name('preview.show');
                Route::post('/publish', [App\Http\Controllers\WhiteLabel\WhiteLabelPreviewController::class, 'publish'])->name('publish');
                Route::post('/rollback/{id}', [App\Http\Controllers\WhiteLabel\WhiteLabelPreviewController::class, 'rollback'])->name('rollback');
            });
        });
    });

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
        Route::post('review-queue/{reviewItem}/publish', [AiTeacherReviewQueueController::class, 'publish'])->name('review-queue.publish');

        Route::get('safety-events', [AiSafetyEventController::class, 'index'])->name('safety-events.index');
        Route::get('safety-events/{safetyEvent}', [AiSafetyEventController::class, 'show'])->name('safety-events.show');
    });

    Route::get('portal/parent/ai-learning', [ParentAiLearningPortalController::class, 'index'])->name('portal.parent.ai-learning');
    Route::get('portal/student/ai-learning', [StudentAiLearningPortalController::class, 'index'])->name('portal.student.ai-learning');
    Route::post('portal/student/ai-learning/items/{itemId}/status', [StudentAiLearningPortalController::class, 'updateStatus'])->name('portal.student.ai-learning.update-status');

    // Subscription & Module Entitlement Routes
    Route::middleware(['role:super_admin,admin,admin_sekolah'])
        ->prefix('billing')
        ->name('billing.')
        ->group(function (): void {
            Route::resource('plans', SubscriptionPlanController::class);

            Route::get('plans/{plan}/modules', [PlanModuleController::class, 'edit'])
                ->name('plans.modules.edit');

            Route::put('plans/{plan}/modules', [PlanModuleController::class, 'update'])
                ->name('plans.modules.update');

            Route::resource('school-subscriptions', SchoolSubscriptionController::class);

            Route::resource('module-overrides', SchoolModuleOverrideController::class)
                ->except(['show']);

            Route::get('locked/module/{moduleKey}', [ModuleLockedController::class, 'show'])
                ->name('locked.module');
        });
});
