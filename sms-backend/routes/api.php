<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;


Route::post('/auth/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
	Route::get('/users/me', [UserController::class, 'me']);

	// Attendance endpoints
	Route::post('/attendance', [\App\Http\Controllers\AttendanceController::class, 'store']);
	Route::get('/sections/{id}/attendance', [\App\Http\Controllers\AttendanceController::class, 'index']);

	// Admin reporting
	Route::get('/admin/attendance-report', [\App\Http\Controllers\AttendanceController::class, 'attendanceReport']);
	Route::get('/admin/attendance-section-averages', [\App\Http\Controllers\AttendanceController::class, 'attendanceSectionAverages']);
	Route::get('/admin/attendance-trends', [\App\Http\Controllers\AttendanceController::class, 'attendanceTrends']);

	// Student & Parent views
	Route::get('/me/attendance', [\App\Http\Controllers\AttendanceController::class, 'myAttendance']);
	Route::get('/parent/children/attendance', [\App\Http\Controllers\AttendanceController::class, 'parentChildrenAttendance']);

	// SIS endpoints (Admin + Teacher)
	Route::get('/classes', [\App\Http\Controllers\ClassesController::class, 'index'])->middleware('role:Admin,Teacher');
	Route::get('/sections', [\App\Http\Controllers\SectionsController::class, 'index'])->middleware('role:Admin,Teacher');
	Route::get('/students', [\App\Http\Controllers\StudentsController::class, 'index'])->middleware('role:Admin,Teacher');
});
