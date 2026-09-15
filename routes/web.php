<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Employee\AttendanceController as EmployeeAttendanceController;

/*
|--------------------------------------------------------------------------
| Web Routes - Employee Attendance Management System
|--------------------------------------------------------------------------
*/

// Home page redirect to login
Route::get('/', function () {
    return redirect('/login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// =========================================================================
// Admin Routes (Only accessible by logged-in users with 'admin' role)
// =========================================================================
Route::middleware(['auth', 'role:admin'])->group(function () {

    // 1. Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // 2. Employee List
    Route::get('/admin/employees', [EmployeeController::class, 'index'])->name('admin.employees.index');

    // 3. Add New Employee Form
    Route::get('/admin/employees/create', [EmployeeController::class, 'create'])->name('admin.employees.create');

    // 4. Save New Employee Form Data
    Route::post('/admin/employees', [EmployeeController::class, 'store'])->name('admin.employees.store');

    // 5. Edit Employee Form
    Route::get('/admin/employees/{id}/edit', [EmployeeController::class, 'edit'])->name('admin.employees.edit');

    // 6. Update Employee Form Data
    Route::put('/admin/employees/{id}', [EmployeeController::class, 'update'])->name('admin.employees.update');

    // 7. Toggle Employee Active/Inactive Status
    Route::post('/admin/employees/{id}/toggle-status', [EmployeeController::class, 'toggleStatus'])->name('admin.employees.toggleStatus');

    // 8. Delete Employee
    Route::delete('/admin/employees/{id}', [EmployeeController::class, 'destroy'])->name('admin.employees.destroy');

    // 9. All Attendance Logs (with filter by date, status, employee)
    Route::get('/admin/attendances', [AdminAttendanceController::class, 'index'])->name('admin.attendances.index');

    // 10. Manual Attendance Edit Form
    Route::get('/admin/attendances/{id}/edit', [AdminAttendanceController::class, 'edit'])->name('admin.attendances.edit');

    // 11. Save Manual Attendance Update
    Route::put('/admin/attendances/{id}', [AdminAttendanceController::class, 'update'])->name('admin.attendances.update');

    // 12. Daily Attendance Report (Select a date -> View all employees)
    Route::get('/admin/reports/daily', [ReportController::class, 'daily'])->name('admin.reports.daily');
    Route::get('/admin/reports/daily/export', [ReportController::class, 'exportDaily'])->name('admin.reports.daily.export');

    // 13. Monthly Attendance Report (Select month & year -> Employee-wise summary)
    Route::get('/admin/reports/monthly', [ReportController::class, 'monthly'])->name('admin.reports.monthly');
    Route::get('/admin/reports/monthly/export', [ReportController::class, 'exportMonthly'])->name('admin.reports.monthly.export');

});


// =========================================================================
// Employee Routes (Only accessible by logged-in users with 'employee' role)
// =========================================================================
Route::middleware(['auth', 'role:employee'])->group(function () {

    // 1. Employee Dashboard & Punch Screen
    Route::get('/employee/dashboard', [EmployeeAttendanceController::class, 'index'])->name('employee.dashboard');

    // 2. Clock In Action
    Route::post('/employee/clock-in', [EmployeeAttendanceController::class, 'clockIn'])->name('employee.attendance.clockIn');

    // 3. Clock Out Action
    Route::post('/employee/clock-out', [EmployeeAttendanceController::class, 'clockOut'])->name('employee.attendance.clockOut');

    // 4. Export Own Attendance CSV
    Route::get('/employee/attendance/export', [EmployeeAttendanceController::class, 'export'])->name('employee.attendance.export');

     Route::get('employee/test', [AuthController::class, 'test'])->name('test');

});
