<?php

use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\LeaveController;
use App\Http\Controllers\API\AttendanceController;

Route::post('/login', [AuthController::class, 'login'])->name('Login');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/get-attendance-today', [AttendanceController::class, 'getAttendanceToday']);
    Route::get('/get-schedule', [AttendanceController::class, 'getSchedule']);
    Route::post('/store-attendance', [AttendanceController::class, 'store']);
    Route::get('/get-attendance-by-month-year/{month}/{year}', [AttendanceController::class, 'getAttendanceByMonthAndyear']);
    Route::post('/banned', [AttendanceController::class, 'banned']);
    Route::get('/get-image', [AttendanceController::class, 'getImage']);

    Route::get('leaves', [LeaveController::class, 'index']);
    Route::post('leave-request', [LeaveController::class, 'store']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');