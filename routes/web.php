<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\WorkScheduleController;
use App\Http\Controllers\Admin\AttendanceReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public attendance page (for kiosk mode)
Route::get('/', [AttendanceController::class, 'index'])->name('attendance.index');
Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.page');

// Admin routes (requires authentication)
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard.index');

    // Employee management
    Route::resource('employees', EmployeeController::class);
    Route::get('employees/{employee}/register-face', [EmployeeController::class, 'registerFace'])
        ->name('employees.register-face');
    Route::post('employees/{employee}/face-descriptor', [EmployeeController::class, 'storeFaceDescriptor'])
        ->name('employees.store-face-descriptor');

    // Work schedules
    Route::resource('work-schedules', WorkScheduleController::class);

    // Departments and Positions
    Route::resource('departments', \App\Http\Controllers\Admin\DepartmentController::class);
    Route::resource('positions', \App\Http\Controllers\Admin\PositionController::class);
    Route::resource('payrolls', \App\Http\Controllers\Admin\PayrollController::class)->except(['edit', 'update']);

    // Attendance reports
    Route::get('reports', [AttendanceReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export', [AttendanceReportController::class, 'export'])->name('reports.export');
});

// Breeze profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
