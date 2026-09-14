<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // Show Admin Dashboard with live stats
    public function index()
    {
        $today = Carbon::today()->toDateString();

        // 1. Total Employees count (excluding admins)
        $totalEmployees = User::where('role', 'employee')->where('status', 'active')->count();

        // 2. Count Present Today
        $presentToday = Attendance::where('date', $today)
            ->where('status', 'Present')
            ->count();

        // 3. Count Half Day Today
        $halfDayToday = Attendance::where('date', $today)
            ->where('status', 'Half Day')
            ->count();

        // 4. Count Employees on Leave Today
        $onLeaveToday = Attendance::where('date', $today)
            ->where('status', 'Leave')
            ->count();

        // 5. Calculate Absent Today
        // (Active employees who did not login, did not take half day, and are not on leave)
        $markedCount = $presentToday + $halfDayToday + $onLeaveToday;
        $absentToday = max(0, $totalEmployees - $markedCount);

        // 6. Get list of today's attendance records to display on dashboard
        $todayRecords = Attendance::with('user')
            ->where('date', $today)
            ->orderBy('login_time', 'desc')
            ->get();

        // Pass all metrics into the view
        return view('admin.dashboard', [
            'totalEmployees' => $totalEmployees,
            'presentToday'   => $presentToday,
            'halfDayToday'   => $halfDayToday,
            'onLeaveToday'   => $onLeaveToday,
            'absentToday'    => $absentToday,
            'todayRecords'   => $todayRecords,
            'currentDate'    => Carbon::today()->format('D, d M Y'),
        ]);
    }
}
