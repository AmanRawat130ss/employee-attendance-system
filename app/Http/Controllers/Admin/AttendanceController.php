<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // 1. Display all attendance records with optional date & status filters
    public function index(Request $request)
    {
        $query = Attendance::with('user');

        // Optional filter by specific date
        if ($request->filled('date')) {
            $query->where('date', $request->date);
        }

        // Optional filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Optional filter by specific employee
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $attendances = $query->orderBy('date', 'desc')
            ->orderBy('login_time', 'desc')
            ->get();

        // Get list of all employees for the filter dropdown
        $employees = User::where('role', 'employee')->get();

        return view('admin.attendances.index', [
            'attendances' => $attendances,
            'employees'   => $employees,
            'selectedDate' => $request->date ?? '',
            'selectedStatus' => $request->status ?? '',
            'selectedUserId' => $request->user_id ?? '',
        ]);
    }

    // 2. Show form to manually edit an attendance record
    public function edit($id)
    {
        $attendance = Attendance::with('user')->findOrFail($id);

        return view('admin.attendances.edit', [
            'attendance' => $attendance,
        ]);
    }

    // 3. Save manual updates made by the administrator
    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        // Validate form input
        $request->validate([
            'login_time'  => 'nullable',
            'logout_time' => 'nullable',
            'status'      => 'required|in:Present,Absent,Half Day,Leave,Holiday',
            'notes'       => 'nullable|string|max:255',
        ]);

        $loginTime  = $request->login_time;
        $logoutTime = $request->logout_time;
        $status     = $request->status;

        // Auto calculate hours if both times are provided
        $totalHours = 0;
        if ($loginTime && $logoutTime) {
            $totalHours = Attendance::calculateHours($loginTime, $logoutTime);
        }

        // Update the record
        $attendance->update([
            'login_time'  => $loginTime,
            'logout_time' => $logoutTime,
            'total_hours' => $totalHours,
            'status'      => $status,
            'notes'       => $request->notes ?? 'Manually updated by Administrator',
        ]);

        return redirect()->route('admin.attendances.index')
            ->with('success', "Attendance record for {$attendance->user->name} updated successfully!");
    }
}
