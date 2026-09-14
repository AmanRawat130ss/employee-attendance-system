<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // 1. Show Employee Dashboard & Attendance History
    public function index(Request $request)
    {
        $userId = Auth::id();
        $today = Carbon::today()->toDateString();

        // Get today's attendance record for this employee
        $todayAttendance = Attendance::where('user_id', $userId)
            ->where('date', $today)
            ->first();

        // Month filter (e.g. '2026-09', '2026-08', '2026-07', 'all')
        $selectedMonth = $request->get('month', 'all');

        $historyQuery = Attendance::where('user_id', $userId)
            ->orderBy('date', 'desc');

        if ($selectedMonth !== 'all' && !empty($selectedMonth)) {
            $parts = explode('-', $selectedMonth);
            if (count($parts) === 2) {
                $historyQuery->whereYear('date', $parts[0])
                             ->whereMonth('date', $parts[1]);
            }
        }

        // Paginate history table to 12 records per page so it doesn't stretch down forever
        $history = $historyQuery->paginate(12)->withQueryString();

        // Calculate personal summary stats across all lifetime records
        $allRecords = Attendance::where('user_id', $userId)->get();
        $totalDaysWorked = $allRecords->whereIn('status', ['Present', 'Half Day'])->count();
        $presentDays     = $allRecords->where('status', 'Present')->count();
        $halfDays        = $allRecords->where('status', 'Half Day')->count();
        $totalHours      = round($allRecords->sum('total_hours'), 2);

        // Distinct months for quick filter tabs/dropdown
        $availableMonths = Attendance::where('user_id', $userId)
            ->selectRaw("DATE_FORMAT(date, '%Y-%m') as ym, DATE_FORMAT(date, '%M %Y') as label")
            ->distinct()
            ->orderBy('ym', 'desc')
            ->get();

        return view('employee.dashboard', [
            'todayAttendance'  => $todayAttendance,
            'history'          => $history,
            'totalDaysWorked'  => $totalDaysWorked,
            'presentDays'      => $presentDays,
            'halfDays'         => $halfDays,
            'totalHours'       => $totalHours,
            'todayDate'        => Carbon::today()->format('D, d M Y'),
            'selectedMonth'    => $selectedMonth,
            'availableMonths'  => $availableMonths,
        ]);
    }

    // 2. Handle Clock In (Morning Punch)
    public function clockIn()
    {
        $userId = Auth::id();
        $today = Carbon::today()->toDateString();
        $currentTime = Carbon::now()->format('H:i:s');

        // Check if attendance already exists for today
        $attendance = Attendance::where('user_id', $userId)
            ->where('date', $today)
            ->first();

        // Business Rule: An employee can mark login only once per day
        if ($attendance && $attendance->login_time != null) {
            return back()->with('error', "You have already clocked in today at {$attendance->login_time}!");
        }

        // Create or update today's attendance record
        Attendance::updateOrCreate(
            ['user_id' => $userId, 'date' => $today],
            [
                'login_time'  => $currentTime,
                'status'      => 'Present',
                'notes'       => 'Clocked in via portal',
            ]
        );

        return back()->with('success', "Clocked in successfully at {$currentTime}!");
    }

    // 3. Handle Clock Out (Evening Punch & Hours Calculation)
    public function clockOut()
    {
        $userId = Auth::id();
        $today = Carbon::today()->toDateString();
        $currentTime = Carbon::now()->format('H:i:s');

        // Find today's record
        $attendance = Attendance::where('user_id', $userId)
            ->where('date', $today)
            ->first();

        // Business Rule: Logout cannot be marked without login
        if (!$attendance || $attendance->login_time == null) {
            return back()->with('error', 'You cannot clock out without clocking in first!');
        }

        // Business Rule: Check if already clocked out
        if ($attendance->logout_time != null) {
            return back()->with('error', "You have already clocked out today at {$attendance->logout_time}!");
        }

        // Business Rule: Login time cannot be later than logout time
        $loginCarbon = Carbon::parse($attendance->login_time);
        $logoutCarbon = Carbon::parse($currentTime);

        if ($logoutCarbon->lessThan($loginCarbon)) {
            return back()->with('error', 'Logout time cannot be earlier than login time!');
        }

        // Business Rule: Automatically calculate total working hours
        $totalHours = Attendance::calculateHours($attendance->login_time, $currentTime);

        // Business Rule: Auto determine status (< 4 hours = Half Day, >= 8 hours = Present)
        $status = Attendance::determineStatus($totalHours);

        // Save logout time, calculated hours, and status
        $attendance->update([
            'logout_time' => $currentTime,
            'total_hours' => $totalHours,
            'status'      => $status,
            'notes'       => "Completed shift: {$totalHours} hours worked",
        ]);

        return back()->with('success', "Clocked out successfully at {$currentTime}! Total hours: {$totalHours} hrs (Status: {$status})");
    }

    // 4. Export Employee Attendance History to CSV
    public function export(Request $request)
    {
        $userId = Auth::id();
        $user = Auth::user();
        $selectedMonth = $request->get('month', 'all');

        $query = Attendance::where('user_id', $userId)->orderBy('date', 'desc');

        if ($selectedMonth !== 'all' && !empty($selectedMonth)) {
            $parts = explode('-', $selectedMonth);
            if (count($parts) === 2) {
                $query->whereYear('date', $parts[0])
                      ->whereMonth('date', $parts[1]);
            }
        }

        $records = $query->get();
        $filename = "my_attendance_" . ($selectedMonth !== 'all' ? $selectedMonth : 'all_time') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($records, $user) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Employee Name', 'Date', 'Clock In', 'Clock Out', 'Total Hours', 'Status', 'Notes']);

            foreach ($records as $row) {
                fputcsv($handle, [
                    $user->name,
                    $row->date,
                    $row->login_time ?? '—',
                    $row->logout_time ?? '—',
                    $row->total_hours ?? '0.00',
                    $row->status,
                    $row->notes ?? ''
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
