<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Admin\EmployeeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Face recognition & Attendance API (public for kiosk mode)
Route::prefix('attendance')->group(function () {
    Route::get('/employees/descriptors', [AttendanceController::class, 'getEmployeeDescriptors']);
    Route::post('/verify', [AttendanceController::class, 'verifyFace']);
    Route::post('/status', [AttendanceController::class, 'getTodayStatus']);
});

// Employee face registration (requires auth for admin)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/employees/{employee}/face-descriptor', [EmployeeController::class, 'storeFaceDescriptor']);
});
