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
});
