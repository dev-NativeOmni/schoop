<?php

use App\Http\Controllers\Api\Mobile\V1\Auth\MobileAuthController;
use App\Http\Controllers\Api\Mobile\V1\Bootstrap\MobileBootstrapController;
use App\Http\Controllers\Api\Mobile\V1\Device\MobileDeviceController;
use App\Http\Controllers\Api\Mobile\V1\Merchant\MerchantMobilePosController;
use App\Http\Controllers\Api\Mobile\V1\Notification\MobileNotificationController;
use App\Http\Controllers\Api\Mobile\V1\Parent\ParentMobilePortalController;
use App\Http\Controllers\Api\Mobile\V1\Student\StudentMobilePortalController;
use App\Http\Controllers\Api\Mobile\V1\Teacher\TeacherMobilePortalController;
use App\Http\Controllers\Api\Mobile\V1\Tenant\MobileTenantController;
use App\Http\Controllers\Api\V1\AttendanceApiController;
use App\Http\Controllers\Api\V1\CashlessApiController;
use App\Http\Controllers\Api\V1\ClassRoomApiController;
use App\Http\Controllers\Api\V1\FinanceApiController;
use App\Http\Controllers\Api\V1\StudentApiController;
use App\Http\Controllers\Api\V1\TahfizhApiController;
use App\Http\Controllers\Api\V1\WebhookTestController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->middleware([
        'api.request_log',
        'api.client',
        'api.rate_limit',
    ])
    ->name('api.v1.')
    ->group(function (): void {
        Route::get('/students', [StudentApiController::class, 'index'])
            ->middleware('api.scope:students:read')
            ->name('students.index');

        Route::get('/students/{student}', [StudentApiController::class, 'show'])
            ->middleware('api.scope:students:read')
            ->name('students.show');

        Route::get('/classes', [ClassRoomApiController::class, 'index'])
            ->middleware('api.scope:classes:read')
            ->name('classes.index');

        Route::get('/attendance-records', [AttendanceApiController::class, 'index'])
            ->middleware('api.scope:attendance:read')
            ->name('attendance-records.index');

        Route::post('/attendance-records', [AttendanceApiController::class, 'store'])
            ->middleware('api.scope:attendance:write')
            ->name('attendance-records.store');

        Route::get('/tahfizh/progress', [TahfizhApiController::class, 'progress'])
            ->middleware('api.scope:tahfizh:read')
            ->name('tahfizh.progress');

        Route::get('/finance/bills', [FinanceApiController::class, 'bills'])
            ->middleware('api.scope:finance:read')
            ->name('finance.bills');

        Route::get('/cashless/transactions', [CashlessApiController::class, 'transactions'])
            ->middleware('api.scope:cashless:read')
            ->name('cashless.transactions');

        Route::post('/webhooks/test', [WebhookTestController::class, 'store'])
            ->middleware('api.scope:webhooks:manage')
            ->name('webhooks.test');
    });

Route::prefix('mobile/v1')
    ->middleware(['mobile.response'])
    ->name('api.mobile.v1.')
    ->group(function (): void {
        Route::prefix('auth')
            ->name('auth.')
            ->group(function (): void {
                Route::post('login', [MobileAuthController::class, 'login'])
                    ->middleware('throttle:mobile-login')
                    ->name('login');
                Route::post('forgot-password/request', [MobileAuthController::class, 'requestPasswordReset'])
                    ->middleware('throttle:mobile-password-reset')
                    ->name('forgot-password.request');
                Route::post('forgot-password/reset', [MobileAuthController::class, 'resetPassword'])
                    ->middleware('throttle:mobile-password-reset')
                    ->name('forgot-password.reset');
            });

        Route::middleware(['mobile.auth', 'mobile.version', 'mobile.tenant', 'throttle:mobile-api'])
            ->group(function (): void {
                Route::get('bootstrap', [MobileBootstrapController::class, 'show'])->name('bootstrap');

                Route::prefix('auth')->name('auth.')->group(function (): void {
                    Route::post('logout', [MobileAuthController::class, 'logout'])->name('logout');
                    Route::get('me', [MobileAuthController::class, 'me'])->name('me');
                    Route::post('refresh', [MobileAuthController::class, 'refresh'])->name('refresh');
                    Route::post('change-password', [MobileAuthController::class, 'changePassword'])->name('change-password');
                });

                Route::prefix('tenants')->name('tenants.')->group(function (): void {
                    Route::get('/', [MobileTenantController::class, 'index'])->name('index');
                    Route::post('switch', [MobileTenantController::class, 'switch'])->name('switch');
                    Route::get('current', [MobileTenantController::class, 'current'])->name('current');
                });

                Route::prefix('devices')->name('devices.')->group(function (): void {
                    Route::post('/', [MobileDeviceController::class, 'store'])->name('store');
                    Route::post('push-token', [MobileDeviceController::class, 'pushToken'])->name('push-token');
                    Route::post('revoke', [MobileDeviceController::class, 'revoke'])->name('revoke');
                });

                Route::prefix('parent')->name('parent.')->group(function (): void {
                    Route::get('children', [ParentMobilePortalController::class, 'children'])->name('children');
                    Route::get('children/{student}/summary', [ParentMobilePortalController::class, 'summary'])->name('children.summary');
                    Route::get('children/{student}/tahfizh', [ParentMobilePortalController::class, 'tahfizh'])->middleware('module:tahfizh')->name('children.tahfizh');
                    Route::get('children/{student}/mutabaah', [ParentMobilePortalController::class, 'mutabaah'])->middleware('module:mutabaah')->name('children.mutabaah');
                    Route::get('children/{student}/attendance', [ParentMobilePortalController::class, 'attendance'])->middleware('module:attendance')->name('children.attendance');
                    Route::get('children/{student}/tahsin', [ParentMobilePortalController::class, 'tahsin'])->middleware('module:tahsin')->name('children.tahsin');
                    Route::get('children/{student}/finance', [ParentMobilePortalController::class, 'finance'])->middleware('module:finance')->name('children.finance');
                    Route::get('children/{student}/cashless', [ParentMobilePortalController::class, 'cashless'])->middleware('module:cashless')->name('children.cashless');
                    Route::get('children/{student}/notifications', [ParentMobilePortalController::class, 'notifications'])->middleware('module:notifications')->name('children.notifications');
                });

                Route::prefix('student/me')->name('student.me.')->group(function (): void {
                    Route::get('summary', [StudentMobilePortalController::class, 'summary'])->name('summary');
                    Route::get('tahfizh', [StudentMobilePortalController::class, 'tahfizh'])->middleware('module:tahfizh')->name('tahfizh');
                    Route::get('mutabaah', [StudentMobilePortalController::class, 'mutabaah'])->middleware('module:mutabaah')->name('mutabaah');
                    Route::get('attendance', [StudentMobilePortalController::class, 'attendance'])->middleware('module:attendance')->name('attendance');
                    Route::get('tahsin', [StudentMobilePortalController::class, 'tahsin'])->middleware('module:tahsin')->name('tahsin');
                    Route::get('finance', [StudentMobilePortalController::class, 'finance'])->middleware('module:finance')->name('finance');
                    Route::get('cashless', [StudentMobilePortalController::class, 'cashless'])->middleware('module:cashless')->name('cashless');
                    Route::get('qr-card', [StudentMobilePortalController::class, 'qrCard'])->name('qr-card');
                    Route::get('notifications', [StudentMobilePortalController::class, 'notifications'])->name('notifications');
                });

                Route::prefix('teacher')->name('teacher.')->group(function (): void {
                    Route::get('dashboard', [TeacherMobilePortalController::class, 'dashboard'])->name('dashboard');
                    Route::get('classes', [TeacherMobilePortalController::class, 'classes'])->name('classes');
                    Route::get('students', [TeacherMobilePortalController::class, 'students'])->name('students');
                    Route::get('students/{student}/summary', [TeacherMobilePortalController::class, 'studentSummary'])->name('students.summary');
                    Route::post('tahfizh/records', [TeacherMobilePortalController::class, 'storeTahfizhRecord'])->middleware('module:tahfizh')->name('tahfizh.records.store');
                    Route::get('tahfizh/records', [TeacherMobilePortalController::class, 'tahfizhRecords'])->middleware('module:tahfizh')->name('tahfizh.records.index');
                    Route::post('mutabaah/records', [TeacherMobilePortalController::class, 'storeMutabaahRecords'])->middleware('module:mutabaah')->name('mutabaah.records.store');
                    Route::get('attendance/sessions', [TeacherMobilePortalController::class, 'attendanceSessions'])->middleware('module:attendance')->name('attendance.sessions');
                    Route::post('attendance/scan', [TeacherMobilePortalController::class, 'scanAttendance'])->middleware('module:attendance')->name('attendance.scan');
                    Route::post('attendance/manual-records', [TeacherMobilePortalController::class, 'storeManualAttendance'])->middleware('module:attendance')->name('attendance.manual-records.store');
                    Route::post('tahsin/assessments', [TeacherMobilePortalController::class, 'storeTahsinAssessment'])->middleware('module:tahsin')->name('tahsin.assessments.store');
                    Route::get('notifications', [MobileNotificationController::class, 'teacher'])->name('notifications');
                });

                Route::prefix('merchant')
                    ->name('merchant.')
                    ->middleware(['throttle:mobile-checkout', 'module:cashless'])
                    ->group(function (): void {
                        Route::get('profile', [MerchantMobilePosController::class, 'profile'])->name('profile');
                        Route::get('products', [MerchantMobilePosController::class, 'products'])->name('products');
                        Route::post('pos-sessions/open', [MerchantMobilePosController::class, 'openSession'])->name('pos-sessions.open');
                        Route::post('pos-sessions/{session}/close', [MerchantMobilePosController::class, 'closeSession'])->name('pos-sessions.close');
                        Route::post('sales/preview', [MerchantMobilePosController::class, 'previewSale'])->name('sales.preview');
                        Route::post('sales/checkout', [MerchantMobilePosController::class, 'checkout'])->name('sales.checkout');
                        Route::post('sales/{sale}/void', [MerchantMobilePosController::class, 'voidSale'])->name('sales.void');
                        Route::get('sales', [MerchantMobilePosController::class, 'sales'])->name('sales.index');
                        Route::get('settlements', [MerchantMobilePosController::class, 'settlements'])->name('settlements.index');
                    });
            });
    });
