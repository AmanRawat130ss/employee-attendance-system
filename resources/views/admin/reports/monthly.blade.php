@extends('layouts.admin')

@section('title', 'Monthly Report')
@section('topbar_title', 'Reports')

@section('content')
<div>

    <!-- Page Header & Filter Toolbar -->
    <div class="page-header" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1>Monthly Attendance Report</h1>
            <p>
                @if($selectedEmployee)
                    Detailed day-by-day attendance timesheet for <strong>{{ $selectedEmployee->name }}</strong> ({{ $monthName }})
                @elseif($employeeFilter === 'all_detailed')
                    All staff chronological daily punch logs for <strong>{{ $monthName }}</strong>
                @else
                    Employee attendance performance summary for <strong>{{ $monthName }}</strong>
                @endif
            </p>
        </div>

        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
            <form action="{{ route('admin.reports.monthly') }}" method="GET" style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                <!-- Employee Filter Dropdown -->
                <select name="employee_id" onchange="this.form.submit()" style="padding: 9px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 13px; font-weight: 500; font-family: inherit; background: #fff; outline: none; cursor: pointer; min-width: 255px;">
                    <option value="summary" {{ $employeeFilter === 'summary' ? 'selected' : '' }}>📊 All Staff — Summary Overview</option>
                    <option value="all_detailed" {{ $employeeFilter === 'all_detailed' ? 'selected' : '' }}>📋 All Staff — Combined Daily Logs</option>
                    <optgroup label="── Individual Employees ──">
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ (string)$employeeFilter === (string)$emp->id ? 'selected' : '' }}>
                                👤 {{ $emp->name }} ({{ $emp->employee_id ?? 'EMP' }})
                            </option>
                        @endforeach
                    </optgroup>
                </select>

                <!-- Month Selector -->
                <select name="month" onchange="this.form.submit()" style="padding: 9px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 13px; font-family: inherit; background: #fff; outline: none; cursor: pointer;">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endfor
                </select>

                <!-- Year Selector -->
                <select name="year" onchange="this.form.submit()" style="padding: 9px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 13px; font-family: inherit; background: #fff; outline: none; font-variant-numeric: tabular-nums; cursor: pointer;">
                    @for($y = date('Y'); $y >= date('Y') - 3; $y--)
                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>

                <button type="submit" class="btn btn-dark btn-sm" title="Apply filter">Filter</button>
            </form>

            <!-- Export CSV Button (Adapts to chosen view) -->
            <a href="{{ route('admin.reports.monthly.export', ['month' => $selectedMonth, 'year' => $selectedYear, 'employee_id' => $employeeFilter]) }}" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px; text-decoration: none;" title="Download CSV Report">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export CSV
            </a>

            <!-- Print Button -->
            <button onclick="window.print()" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px;" title="Print or Save PDF">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                Print
            </button>
        </div>
    </div>


    {{-- ========================================================================= --}}
    {{-- SCENARIO 1: INDIVIDUAL EMPLOYEE DAY-BY-DAY BREAKDOWN                      --}}
    {{-- ========================================================================= --}}
    @if($selectedEmployee)

        <!-- Individual Employee Banner -->
        <div class="card" style="padding: 16px 22px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; border-left: 4px solid #6366f1;">
            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="width: 44px; height: 44px; border-radius: 50%; background: #6366f1; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 17px;">
                    {{ strtoupper(substr($selectedEmployee->name, 0, 1)) }}
                </div>
                <div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="font-size: 18px; font-weight: 700; color: #0f172a;">{{ $selectedEmployee->name }}</span>
                        <span class="badge" style="background: #e0e7ff; color: #4338ca;">{{ $selectedEmployee->employee_id ?? 'EMP' }}</span>
                    </div>
                    <div style="font-size: 13px; color: #64748b; margin-top: 2px;">
                        {{ $selectedEmployee->department ?? 'General' }} &middot; {{ $selectedEmployee->designation ?? 'Employee' }} &middot; {{ $selectedEmployee->email }}
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.reports.monthly', ['month' => $selectedMonth, 'year' => $selectedYear, 'employee_id' => 'summary']) }}" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px; text-decoration: none;">
                &larr; Back to Staff Summary
            </a>
        </div>

        <!-- Individual Monthly KPI Strip -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 14px; margin-bottom: 20px;">
            <div class="card" style="padding: 14px 18px; margin: 0; border-left: 3px solid #0f172a;">
                <div style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b;">Days in Month</div>
                <div style="font-size: 22px; font-weight: 700; color: #0f172a; margin-top: 4px; font-variant-numeric: tabular-nums;">{{ $daysInMonth }}</div>
            </div>
            <div class="card" style="padding: 14px 18px; margin: 0; border-left: 3px solid #10b981;">
                <div style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #059669;">Full Days (8h+)</div>
                <div style="font-size: 22px; font-weight: 700; color: #059669; margin-top: 4px; font-variant-numeric: tabular-nums;">{{ $individualStats['presentCount'] }}</div>
            </div>
            <div class="card" style="padding: 14px 18px; margin: 0; border-left: 3px solid #f59e0b;">
                <div style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #d97706;">Half Days (<8h)</div>
                <div style="font-size: 22px; font-weight: 700; color: #d97706; margin-top: 4px; font-variant-numeric: tabular-nums;">{{ $individualStats['halfDayCount'] }}</div>
            </div>
            <div class="card" style="padding: 14px 18px; margin: 0; border-left: 3px solid #6366f1;">
                <div style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #6366f1;">Leaves</div>
                <div style="font-size: 22px; font-weight: 700; color: #6366f1; margin-top: 4px; font-variant-numeric: tabular-nums;">{{ $individualStats['leaveCount'] }}</div>
            </div>
            <div class="card" style="padding: 14px 18px; margin: 0; border-left: 3px solid #94a3b8;">
                <div style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b;">Holidays / Off</div>
                <div style="font-size: 22px; font-weight: 700; color: #475569; margin-top: 4px; font-variant-numeric: tabular-nums;">{{ $individualStats['holidayCount'] }}</div>
            </div>
            <div class="card" style="padding: 14px 18px; margin: 0; border-left: 3px solid #ef4444;">
                <div style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #dc2626;">Absent</div>
                <div style="font-size: 22px; font-weight: 700; color: #dc2626; margin-top: 4px; font-variant-numeric: tabular-nums;">{{ $individualStats['absentCount'] }}</div>
            </div>
            <div class="card" style="padding: 14px 18px; margin: 0; border-left: 3px solid #8b5cf6;">
                <div style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #7c3aed;">Total Worked</div>
                <div style="font-size: 22px; font-weight: 700; color: #7c3aed; margin-top: 4px; font-variant-numeric: tabular-nums;">{{ $individualStats['totalHours'] }} hrs</div>
            </div>
        </div>

        <!-- Day-by-Day Detailed Table -->
        <div class="card">
            <div class="card-header" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                <div>
                    <h2 style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0;">Day-by-Day Attendance Log &middot; {{ $monthName }}</h2>
                    <p style="font-size: 12px; color: #64748b; margin-top: 2px;">Shows complete calendar day records including weekends and leaves</p>
                </div>
                <span class="badge badge-present">{{ $individualStats['presentCount'] }} Days Worked</span>
            </div>

            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 110px;">Date</th>
                            <th style="width: 80px;">Day</th>
                            <th>Clock In</th>
                            <th>Clock Out</th>
                            <th>Hours Worked</th>
                            <th>Status</th>
                            <th>Notes & Remarks</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($individualDays as $day)
                            <tr style="{{ $day['is_weekend'] ? 'background: #fbfbf9;' : '' }}">
                                <td class="cell-mono" style="font-weight: 600; color: #334155;">{{ $day['formatted'] }}</td>
                                <td>
                                    <span style="font-size: 12px; font-weight: 600; padding: 2px 7px; border-radius: 4px; {{ $day['is_weekend'] ? 'background: #f1f5f9; color: #64748b;' : 'background: #eef2ff; color: #4338ca;' }}">
                                        {{ $day['day_name'] }}
                                    </span>
                                </td>
                                <td class="cell-mono">{{ $day['login_time'] ?? '—' }}</td>
                                <td class="cell-mono">{{ $day['logout_time'] ?? '—' }}</td>
                                <td class="cell-mono" style="font-weight: 600; color: {{ $day['total_hours'] >= 8 ? '#059669' : ($day['total_hours'] > 0 ? '#d97706' : '#94a3b8') }};">
                                    {{ $day['total_hours'] > 0 ? $day['total_hours'] . ' hrs' : '—' }}
                                </td>
                                <td>
                                    @if($day['status'] == 'Present')
                                        <span class="badge badge-present">Present</span>
                                    @elseif($day['status'] == 'Half Day')
                                        <span class="badge badge-halfday">Half Day</span>
                                    @elseif($day['status'] == 'Leave')
                                        <span class="badge badge-leave">Leave</span>
                                    @elseif($day['status'] == 'Holiday')
                                        <span class="badge badge-holiday">Holiday</span>
                                    @elseif($day['status'] == 'Upcoming')
                                        <span class="badge" style="background: #f8fafc; color: #94a3b8; border: 1px dashed #cbd5e1;">Upcoming</span>
                                    @else
                                        <span class="badge badge-absent">Absent</span>
                                    @endif
                                </td>
                                <td style="font-size: 12.5px; color: #64748b;">
                                    {{ $day['notes'] ?? '—' }}
                                </td>
                                <td style="text-align: right;">
                                    @if($day['record_id'])
                                        <a href="{{ route('admin.attendances.edit', $day['record_id']) }}" class="link-text" style="font-size: 12px; font-weight: 600;">Edit</a>
                                    @else
                                        <span style="color: #cbd5e1;">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>


    {{-- ========================================================================= --}}
    {{-- SCENARIO 2: ALL EMPLOYEES COMBINED DETAILED LOGS (MIX DATA)               --}}
    {{-- ========================================================================= --}}
    @elseif($employeeFilter === 'all_detailed')

        <!-- View Mode Navigation Tabs -->
        <div style="display: flex; gap: 8px; margin-bottom: 20px;">
            <a href="{{ route('admin.reports.monthly', ['month' => $selectedMonth, 'year' => $selectedYear, 'employee_id' => 'summary']) }}" class="btn btn-secondary btn-sm" style="text-decoration: none;">
                📊 Summary Overview
            </a>
            <a href="{{ route('admin.reports.monthly', ['month' => $selectedMonth, 'year' => $selectedYear, 'employee_id' => 'all_detailed']) }}" class="btn btn-dark btn-sm" style="text-decoration: none;">
                📋 Combined Daily Logs (Active)
            </a>
        </div>

        <div class="card">
            <div class="card-header" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                <div>
                    <h2 style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0;">Combined Daily Attendance Records &middot; {{ $monthName }}</h2>
                    <p style="font-size: 12px; color: #64748b; margin-top: 2px;">Chronological attendance logs across all staff members</p>
                </div>
                <span class="badge badge-present">{{ $detailedLogs->total() }} Total Entries</span>
            </div>

            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Employee</th>
                            <th>Department</th>
                            <th>Clock In</th>
                            <th>Clock Out</th>
                            <th>Total Hours</th>
                            <th>Status</th>
                            <th>Notes</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($detailedLogs as $log)
                            <tr>
                                <td class="cell-mono" style="font-weight: 600;">{{ Carbon\Carbon::parse($log->date)->format('d M Y, D') }}</td>
                                <td>
                                    <div class="cell-name">{{ $log->user->name ?? 'Unknown' }}</div>
                                    <div class="cell-sub">{{ $log->user->employee_id ?? 'N/A' }}</div>
                                </td>
                                <td style="font-size: 13px;">{{ $log->user->department ?? 'General' }}</td>
                                <td class="cell-mono">{{ $log->login_time ?? '—' }}</td>
                                <td class="cell-mono">{{ $log->logout_time ?? '—' }}</td>
                                <td class="cell-mono" style="font-weight: 600; color: {{ $log->total_hours >= 8 ? '#059669' : ($log->total_hours > 0 ? '#d97706' : '#94a3b8') }};">
                                    {{ $log->total_hours > 0 ? $log->total_hours . ' hrs' : '—' }}
                                </td>
                                <td>
                                    @if($log->status == 'Present')
                                        <span class="badge badge-present">Present</span>
                                    @elseif($log->status == 'Half Day')
                                        <span class="badge badge-halfday">Half Day</span>
                                    @elseif($log->status == 'Leave')
                                        <span class="badge badge-leave">Leave</span>
                                    @elseif($log->status == 'Holiday')
                                        <span class="badge badge-holiday">Holiday</span>
                                    @else
                                        <span class="badge badge-absent">{{ $log->status }}</span>
                                    @endif
                                </td>
                                <td style="font-size: 12.5px; color: #64748b;">{{ $log->notes ?? '—' }}</td>
                                <td style="text-align: right;">
                                    <a href="{{ route('admin.attendances.edit', $log->id) }}" class="link-text" style="font-size: 12px; font-weight: 600;">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="empty-state">No attendance records logged for {{ $monthName }}.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($detailedLogs->hasPages())
                <div style="padding: 14px 22px; border-top: 1px solid #e5e7eb; background: #faf9f6; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                    <div style="font-size: 13px; font-weight: 500; color: #64748b;">
                        Showing <strong style="color: #0f172a;">{{ $detailedLogs->firstItem() }}</strong> to <strong style="color: #0f172a;">{{ $detailedLogs->lastItem() }}</strong> of <strong style="color: #0f172a;">{{ $detailedLogs->total() }}</strong> records
                    </div>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        @if ($detailedLogs->onFirstPage())
                            <span class="btn btn-secondary btn-sm" style="opacity: 0.5; cursor: not-allowed; padding: 6px 12px; font-size: 12px;">&laquo; Prev</span>
                        @else
                            <a href="{{ $detailedLogs->previousPageUrl() }}" class="btn btn-secondary btn-sm" style="text-decoration: none; padding: 6px 12px; font-size: 12px;">&laquo; Prev</a>
                        @endif

                        @foreach ($detailedLogs->getUrlRange(max(1, $detailedLogs->currentPage() - 2), min($detailedLogs->lastPage(), $detailedLogs->currentPage() + 2)) as $page => $url)
                            @if ($page == $detailedLogs->currentPage())
                                <span class="btn btn-dark btn-sm" style="padding: 6px 12px; min-width: 34px; text-align: center; font-weight: 700; font-size: 12px;">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="btn btn-secondary btn-sm" style="padding: 6px 12px; min-width: 34px; text-align: center; text-decoration: none; font-size: 12px;">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if ($detailedLogs->hasMorePages())
                            <a href="{{ $detailedLogs->nextPageUrl() }}" class="btn btn-secondary btn-sm" style="text-decoration: none; padding: 6px 12px; font-size: 12px;">Next &raquo;</a>
                        @else
                            <span class="btn btn-secondary btn-sm" style="opacity: 0.5; cursor: not-allowed; padding: 6px 12px; font-size: 12px;">Next &raquo;</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>


    {{-- ========================================================================= --}}
    {{-- SCENARIO 3: ALL EMPLOYEES SUMMARY OVERVIEW (DEFAULT)                      --}}
    {{-- ========================================================================= --}}
    @else

        <!-- View Mode Navigation Tabs -->
        <div style="display: flex; gap: 8px; margin-bottom: 20px;">
            <a href="{{ route('admin.reports.monthly', ['month' => $selectedMonth, 'year' => $selectedYear, 'employee_id' => 'summary']) }}" class="btn btn-dark btn-sm" style="text-decoration: none;">
                📊 Summary Overview (Active)
            </a>
            <a href="{{ route('admin.reports.monthly', ['month' => $selectedMonth, 'year' => $selectedYear, 'employee_id' => 'all_detailed']) }}" class="btn btn-secondary btn-sm" style="text-decoration: none;">
                📋 Combined Daily Logs (Mix Data)
            </a>
        </div>

        <!-- Monthly Metric Overview -->
        @php
            $sumPresent = collect($summaryData)->sum('presentCount');
            $sumHalfDay = collect($summaryData)->sum('halfDayCount');
            $sumLeave   = collect($summaryData)->sum('leaveCount');
            $sumAbsent  = collect($summaryData)->sum('absentCount');
            $sumHours   = round(collect($summaryData)->sum('totalHours'), 1);
        @endphp
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 14px; margin-bottom: 20px;">
            <div class="card" style="padding: 14px 18px; margin: 0; border-left: 3px solid #10b981;">
                <div style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #059669;">Total Present Days</div>
                <div style="font-size: 22px; font-weight: 700; color: #059669; margin-top: 4px;">{{ $sumPresent }}</div>
            </div>
            <div class="card" style="padding: 14px 18px; margin: 0; border-left: 3px solid #f59e0b;">
                <div style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #d97706;">Total Half Days</div>
                <div style="font-size: 22px; font-weight: 700; color: #d97706; margin-top: 4px;">{{ $sumHalfDay }}</div>
            </div>
            <div class="card" style="padding: 14px 18px; margin: 0; border-left: 3px solid #6366f1;">
                <div style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #6366f1;">Total Leaves</div>
                <div style="font-size: 22px; font-weight: 700; color: #6366f1; margin-top: 4px;">{{ $sumLeave }}</div>
            </div>
            <div class="card" style="padding: 14px 18px; margin: 0; border-left: 3px solid #ef4444;">
                <div style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #dc2626;">Total Absent Days</div>
                <div style="font-size: 22px; font-weight: 700; color: #dc2626; margin-top: 4px;">{{ $sumAbsent }}</div>
            </div>
            <div class="card" style="padding: 14px 18px; margin: 0; border-left: 3px solid #0f172a;">
                <div style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280;">Total Hours Logged</div>
                <div style="font-size: 22px; font-weight: 700; color: #0f172a; margin-top: 4px; font-variant-numeric: tabular-nums;">{{ $sumHours }} hrs</div>
            </div>
        </div>

        <!-- Monthly Summary Table -->
        <div class="card">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Employee</th>
                            <th>Department</th>
                            <th style="text-align: center;">Days</th>
                            <th style="text-align: center; color: #059669;">Present</th>
                            <th style="text-align: center; color: #d97706;">Half Day</th>
                            <th style="text-align: center; color: #6366f1;">Leave</th>
                            <th style="text-align: center; color: #dc2626;">Absent</th>
                            <th style="text-align: right;">Total Hours</th>
                            <th style="text-align: right;">Detailed Breakdown</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($summaryData as $row)
                            <tr>
                                <td class="cell-mono" style="font-weight: 600; color: #6366f1;">{{ $row['employee']->employee_id ?? '—' }}</td>
                                <td>
                                    <a href="{{ route('admin.reports.monthly', ['month' => $selectedMonth, 'year' => $selectedYear, 'employee_id' => $row['employee']->id]) }}" style="text-decoration: none; color: inherit;">
                                        <div class="cell-name" style="color: #0f172a; font-weight: 600;">{{ $row['employee']->name }}</div>
                                        <div class="cell-sub">{{ $row['employee']->email }}</div>
                                    </a>
                                </td>
                                <td style="font-size: 13px;">{{ $row['employee']->department ?? 'General' }}</td>
                                <td style="text-align: center;" class="cell-mono">{{ $daysInMonth }}</td>
                                <td style="text-align: center;">
                                    <span class="badge badge-present" style="font-variant-numeric: tabular-nums;">{{ $row['presentCount'] }}</span>
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge badge-halfday" style="font-variant-numeric: tabular-nums;">{{ $row['halfDayCount'] }}</span>
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge badge-leave" style="font-variant-numeric: tabular-nums;">{{ $row['leaveCount'] }}</span>
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge badge-absent" style="font-variant-numeric: tabular-nums;">{{ $row['absentCount'] }}</span>
                                </td>
                                <td style="text-align: right; font-weight: 700; font-family: inherit; font-size: 13.5px; color: #0f172a; font-variant-numeric: tabular-nums;">
                                    {{ $row['totalHours'] }} hrs
                                </td>
                                <td style="text-align: right;">
                                    <a href="{{ route('admin.reports.monthly', ['month' => $selectedMonth, 'year' => $selectedYear, 'employee_id' => $row['employee']->id]) }}" class="btn btn-secondary btn-sm" style="font-size: 12px; padding: 5px 10px; display: inline-flex; align-items: center; gap: 4px; text-decoration: none;">
                                        <span>Daily Log</span>
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="empty-state">No data available for this month.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    @endif

</div>
@endsection

