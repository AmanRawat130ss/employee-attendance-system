<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class ReportController extends Controller
{
    // 1. Daily Attendance Report (Select a date -> view attendance of all employees)
    public function daily(Request $request)
    {
        // Use selected date or default to today
        $selectedDate = $request->date ?? Carbon::today()->toDateString();

        // Get all active employees
        $employees = User::where('role', 'employee')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        // Get attendance records for the selected date
        $attendances = Attendance::where('date', $selectedDate)
            ->get()
            ->keyBy('user_id');

        // Combine employees with their attendance status on that day
        $reportData = [];
        foreach ($employees as $emp) {
            $record = $attendances->get($emp->id);

            $reportData[] = [
                'employee'    => $emp,
                'login_time'  => $record ? $record->login_time : null,
                'logout_time' => $record ? $record->logout_time : null,
                'total_hours' => $record ? $record->total_hours : 0,
                'status'      => $record ? $record->status : 'Absent',
                'notes'       => $record ? $record->notes : 'No punch recorded',
                'record_id'   => $record ? $record->id : null,
            ];
        }

        return view('admin.reports.daily', [
            'reportData'   => $reportData,
            'selectedDate' => $selectedDate,
            'formattedDate'=> Carbon::parse($selectedDate)->format('D, d M Y'),
        ]);
    }

    // 2. Monthly Attendance Report (Supports Summary, Individual Day-by-Day, and Combined Log)
    public function monthly(Request $request)
    {
        // Use selected month & year or default to current month & year
        $selectedMonth  = (int) ($request->month ?? Carbon::today()->month);
        $selectedYear   = (int) ($request->year ?? Carbon::today()->year);
        $employeeFilter = $request->employee_id ?? 'summary'; // 'summary', 'all_detailed', or numeric user_id

        // Days in the selected month
        $daysInMonth = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->daysInMonth;

        // Get all active employees for the filter dropdown
        $employees = User::where('role', 'employee')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $selectedEmployee = null;
        $individualDays   = [];
        $individualStats  = [];
        $detailedLogs     = null;

        // MODE 1: Individual Employee Day-by-Day Breakdown
        if (is_numeric($employeeFilter)) {
            $selectedEmployee = User::where('role', 'employee')->find($employeeFilter);

            if ($selectedEmployee) {
                // Fetch attendance records for this employee for the entire month
                $empRecords = Attendance::where('user_id', $selectedEmployee->id)
                    ->whereYear('date', $selectedYear)
                    ->whereMonth('date', $selectedMonth)
                    ->get()
                    ->keyBy('date');

                // Build each calendar day (1 to $daysInMonth)
                for ($d = 1; $d <= $daysInMonth; $d++) {
                    $dateObj   = Carbon::createFromDate($selectedYear, $selectedMonth, $d);
                    $dateStr   = $dateObj->toDateString();
                    $dayName   = $dateObj->format('D');
                    $isWeekend = $dateObj->isWeekend();

                    $record = $empRecords->get($dateStr);

                    if ($record) {
                        $status     = $record->status;
                        $loginTime  = $record->login_time;
                        $logoutTime = $record->logout_time;
                        $totalHours = $record->total_hours;
                        $notes      = $record->notes;
                        $recordId   = $record->id;
                    } else {
                        $recordId   = null;
                        $loginTime  = null;
                        $logoutTime = null;
                        $totalHours = 0.00;

                        if ($dateObj->isFuture()) {
                            $status = 'Upcoming';
                            $notes  = 'Upcoming schedule';
                        } elseif ($isWeekend) {
                            $status = 'Holiday';
                            $notes  = 'Weekend Off';
                        } else {
                            $status = 'Absent';
                            $notes  = 'No punch recorded';
                        }
                    }

                    $individualDays[] = [
                        'day_number'  => $d,
                        'date'        => $dateStr,
                        'formatted'   => $dateObj->format('d M Y'),
                        'day_name'    => $dayName,
                        'is_weekend'  => $isWeekend,
                        'status'      => $status,
                        'login_time'  => $loginTime,
                        'logout_time' => $logoutTime,
                        'total_hours' => $totalHours,
                        'notes'       => $notes,
                        'record_id'   => $recordId,
                    ];
                }

                $presCount    = collect($individualDays)->where('status', 'Present')->count();
                $halfCount    = collect($individualDays)->where('status', 'Half Day')->count();
                $leaveCount   = collect($individualDays)->where('status', 'Leave')->count();
                $holidayCount = collect($individualDays)->where('status', 'Holiday')->count();
                $absCount     = collect($individualDays)->where('status', 'Absent')->count();
                $totalHrs     = round(collect($individualDays)->sum('total_hours'), 2);

                $individualStats = [
                    'presentCount' => $presCount,
                    'halfDayCount' => $halfCount,
                    'leaveCount'   => $leaveCount,
                    'holidayCount' => $holidayCount,
                    'absentCount'  => $absCount,
                    'totalHours'   => $totalHrs,
                    'daysInMonth'  => $daysInMonth,
                ];
            }
        } elseif ($employeeFilter === 'all_detailed') {
            // MODE 2: All Employees Detailed Chronological Attendance Logs (Mix Data)
            $detailedLogs = Attendance::with('user')
                ->whereYear('date', $selectedYear)
                ->whereMonth('date', $selectedMonth)
                ->orderBy('date', 'desc')
                ->orderBy('user_id', 'asc')
                ->paginate(30)
                ->withQueryString();
        }

        // MODE 3: Summary table for all employees (calculated for summary view and KPIs)
        $monthlyRecords = Attendance::whereYear('date', $selectedYear)
            ->whereMonth('date', $selectedMonth)
            ->get()
            ->groupBy('user_id');

        $summaryData = [];
        foreach ($employees as $emp) {
            $userRecords = $monthlyRecords->get($emp->id, collect());

            $presentCount = $userRecords->where('status', 'Present')->count();
            $halfDayCount = $userRecords->where('status', 'Half Day')->count();
            $leaveCount   = $userRecords->where('status', 'Leave')->count();
            $holidayCount = $userRecords->where('status', 'Holiday')->count();
            $totalHours   = round($userRecords->sum('total_hours'), 2);

            $totalMarked = $presentCount + $halfDayCount + $leaveCount + $holidayCount;
            $absentCount = max(0, $daysInMonth - $totalMarked);

            $summaryData[] = [
                'employee'     => $emp,
                'presentCount' => $presentCount,
                'halfDayCount' => $halfDayCount,
                'leaveCount'   => $leaveCount,
                'holidayCount' => $holidayCount,
                'absentCount'  => $absentCount,
                'totalHours'   => $totalHours,
            ];
        }

        return view('admin.reports.monthly', [
            'summaryData'      => $summaryData,
            'selectedMonth'    => $selectedMonth,
            'selectedYear'     => $selectedYear,
            'employeeFilter'   => $employeeFilter,
            'employees'        => $employees,
            'selectedEmployee' => $selectedEmployee,
            'individualDays'   => $individualDays,
            'individualStats'  => $individualStats,
            'detailedLogs'     => $detailedLogs,
            'monthName'        => Carbon::createFromDate($selectedYear, $selectedMonth, 1)->format('F Y'),
            'daysInMonth'      => $daysInMonth,
        ]);
    }

    // 3. Export Daily Report to CSV
    public function exportDaily(Request $request)
    {
        $selectedDate = $request->date ?? Carbon::today()->toDateString();
        $employees = User::where('role', 'employee')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $attendances = Attendance::where('date', $selectedDate)
            ->get()
            ->keyBy('user_id');

        $filename = "attendance_daily_{$selectedDate}.csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($employees, $attendances, $selectedDate) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Employee ID', 'Name', 'Email', 'Department', 'Date', 'Clock In', 'Clock Out', 'Total Hours', 'Status', 'Notes']);

            foreach ($employees as $emp) {
                $record = $attendances->get($emp->id);
                fputcsv($handle, [
                    $emp->employee_id ?? 'N/A',
                    $emp->name,
                    $emp->email,
                    $emp->department ?? 'General',
                    $selectedDate,
                    $record ? ($record->login_time ?? '—') : '—',
                    $record ? ($record->logout_time ?? '—') : '—',
                    $record ? $record->total_hours : '0.00',
                    $record ? $record->status : 'Absent',
                    $record ? ($record->notes ?? '') : 'No punch recorded',
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    // 4. Export Monthly Report to CSV (Supports Individual, All Detailed, or Summary)
    public function exportMonthly(Request $request)
    {
        $selectedMonth  = (int) ($request->month ?? Carbon::today()->month);
        $selectedYear   = (int) ($request->year ?? Carbon::today()->year);
        $employeeFilter = $request->employee_id ?? 'summary';
        $daysInMonth    = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->daysInMonth;

        // CASE 1: Individual Employee Day-by-Day Complete Timesheet
        if (is_numeric($employeeFilter)) {
            $emp = User::where('role', 'employee')->find($employeeFilter);
            if ($emp) {
                $cleanName = preg_replace('/[^a-zA-Z0-9_-]/', '_', strtolower($emp->name));
                $filename = "attendance_{$cleanName}_{$selectedYear}_{$selectedMonth}.csv";

                $empRecords = Attendance::where('user_id', $emp->id)
                    ->whereYear('date', $selectedYear)
                    ->whereMonth('date', $selectedMonth)
                    ->get()
                    ->keyBy('date');

                $headers = [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                ];

                $callback = function () use ($emp, $empRecords, $selectedYear, $selectedMonth, $daysInMonth) {
                    $handle = fopen('php://output', 'w');
                    fputcsv($handle, ['Employee ID', 'Name', 'Department', 'Date', 'Day', 'Clock In', 'Clock Out', 'Total Hours', 'Status', 'Notes']);

                    for ($d = 1; $d <= $daysInMonth; $d++) {
                        $dateObj = Carbon::createFromDate($selectedYear, $selectedMonth, $d);
                        $dateStr = $dateObj->toDateString();
                        $record  = $empRecords->get($dateStr);

                        if ($record) {
                            $clockIn  = $record->login_time ?? '—';
                            $clockOut = $record->logout_time ?? '—';
                            $hours    = $record->total_hours;
                            $status   = $record->status;
                            $notes    = $record->notes ?? '';
                        } else {
                            $clockIn  = '—';
                            $clockOut = '—';
                            $hours    = '0.00';
                            if ($dateObj->isFuture()) {
                                $status = 'Upcoming';
                                $notes  = 'Upcoming';
                            } elseif ($dateObj->isWeekend()) {
                                $status = 'Holiday';
                                $notes  = 'Weekend Off';
                            } else {
                                $status = 'Absent';
                                $notes  = 'No punch recorded';
                            }
                        }

                        fputcsv($handle, [
                            $emp->employee_id ?? 'N/A',
                            $emp->name,
                            $emp->department ?? 'General',
                            $dateStr,
                            $dateObj->format('l'),
                            $clockIn,
                            $clockOut,
                            $hours,
                            $status,
                            $notes,
                        ]);
                    }
                    fclose($handle);
                };

                return response()->stream($callback, 200, $headers);
            }
        }

        // CASE 2: All Employees Detailed Chronological Records (Mix Data)
        if ($employeeFilter === 'all_detailed') {
            $filename = "attendance_all_detailed_{$selectedYear}_{$selectedMonth}.csv";
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];

            $logs = Attendance::with('user')
                ->whereYear('date', $selectedYear)
                ->whereMonth('date', $selectedMonth)
                ->orderBy('date', 'desc')
                ->orderBy('user_id', 'asc')
                ->get();

            $callback = function () use ($logs) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, ['Date', 'Day', 'Employee ID', 'Employee Name', 'Department', 'Clock In', 'Clock Out', 'Total Hours', 'Status', 'Notes']);

                foreach ($logs as $log) {
                    $dateObj = Carbon::parse($log->date);
                    fputcsv($handle, [
                        $log->date,
                        $dateObj->format('l'),
                        $log->user->employee_id ?? 'N/A',
                        $log->user->name ?? 'Unknown',
                        $log->user->department ?? 'General',
                        $log->login_time ?? '—',
                        $log->logout_time ?? '—',
                        $log->total_hours,
                        $log->status,
                        $log->notes ?? '',
                    ]);
                }
                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        }

        // CASE 3: All Employees Summary Overview (Default)
        $employees = User::where('role', 'employee')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $monthlyRecords = Attendance::whereYear('date', $selectedYear)
            ->whereMonth('date', $selectedMonth)
            ->get()
            ->groupBy('user_id');

        $filename = "attendance_monthly_summary_{$selectedYear}_{$selectedMonth}.csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($employees, $monthlyRecords, $daysInMonth) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Employee ID', 'Name', 'Email', 'Department', 'Total Days', 'Present Days', 'Half Days', 'Leave Days', 'Absent Days', 'Total Hours Logged']);

            foreach ($employees as $emp) {
                $userRecords = $monthlyRecords->get($emp->id, collect());
                $presentCount = $userRecords->where('status', 'Present')->count();
                $halfDayCount = $userRecords->where('status', 'Half Day')->count();
                $leaveCount   = $userRecords->where('status', 'Leave')->count();
                $holidayCount = $userRecords->where('status', 'Holiday')->count();
                $totalHours   = round($userRecords->sum('total_hours'), 2);

                $totalMarked = $presentCount + $halfDayCount + $leaveCount + $holidayCount;
                $absentCount = max(0, $daysInMonth - $totalMarked);

                fputcsv($handle, [
                    $emp->employee_id ?? 'N/A',
                    $emp->name,
                    $emp->email,
                    $emp->department ?? 'General',
                    $daysInMonth,
                    $presentCount,
                    $halfDayCount,
                    $leaveCount,
                    $absentCount,
                    $totalHours,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
