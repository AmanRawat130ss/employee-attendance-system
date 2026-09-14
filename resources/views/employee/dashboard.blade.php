@extends('layouts.employee')

@section('title', 'My Dashboard')

@section('content')
<div id="dashboardTop">

    <!-- Greeting Header -->
    <div style="display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 16px; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 24px; font-weight: 800; letter-spacing: -0.5px; color: #18181b; margin-bottom: 4px;">Welcome back, {{ Auth::user()->name }}</h1>
            <p style="font-size: 14px; color: #71717a;">
                <span>{{ Auth::user()->department ?? 'Engineering' }}</span> &middot;
                <span>{{ Auth::user()->designation ?? 'Team Member' }}</span> &middot;
                <span style="color: #059669; font-weight: 700; font-variant-numeric: tabular-nums;">{{ Auth::user()->employee_id }}</span>
            </p>
        </div>

        <!-- Shift Live Card -->
        <div style="background: #fff; border: 1px solid #e5e2db; border-radius: 12px; padding: 10px 18px; display: flex; align-items: center; gap: 12px; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
            <div style="width: 8px; height: 8px; background: {{ (!$todayAttendance || !$todayAttendance->login_time) ? '#a1a1aa' : (!$todayAttendance->logout_time ? '#d97706' : '#059669') }}; border-radius: 50%;"></div>
            <div>
                <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; color: #71717a;">Today's Shift</div>
                <div style="font-size: 13.5px; font-weight: 700; color: #18181b;">
                    @if(!$todayAttendance || !$todayAttendance->login_time)
                        Not Clocked In
                    @elseif(!$todayAttendance->logout_time)
                        In Progress (from {{ $todayAttendance->login_time }})
                    @else
                        Completed ({{ $todayAttendance->total_hours }} hrs)
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Punch Action Card -->
    <div style="background: #fff; border: 1px solid #e5e2db; border-radius: 16px; padding: 24px 28px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">

            <!-- Status Info -->
            <div style="flex: 1; min-width: 280px;">
                <div style="font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: #a1a1aa; margin-bottom: 6px;">Daily Attendance Punch</div>

                @if(!$todayAttendance || $todayAttendance->login_time == null)
                    <h2 style="font-size: 20px; font-weight: 800; color: #18181b; letter-spacing: -0.3px; margin-bottom: 4px;">Ready to start your workday?</h2>
                    <p style="font-size: 13.5px; color: #71717a; line-height: 1.5;">Click below to clock in. Minimum 8 hours of logged time qualifies for full-day present status.</p>

                @elseif($todayAttendance->logout_time == null)
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                        <span style="width: 8px; height: 8px; background: #d97706; border-radius: 50%;"></span>
                        <span style="font-size: 13.5px; font-weight: 700; color: #d97706;">Shift currently active</span>
                    </div>
                    <h2 style="font-size: 20px; font-weight: 800; color: #18181b; letter-spacing: -0.3px;">
                        Clocked in at <span style="color: #059669; font-variant-numeric: tabular-nums;">{{ $todayAttendance->login_time }}</span>
                    </h2>
                    <p style="font-size: 13.5px; color: #71717a; margin-top: 4px;">When finishing your day, punch out to record your total working hours.</p>

                @else
                    <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 4px;">
                        <svg width="18" height="18" fill="none" stroke="#059669" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span style="font-size: 13.5px; font-weight: 700; color: #059669;">Daily Shift Completed</span>
                    </div>
                    <h2 style="font-size: 20px; font-weight: 800; color: #18181b; letter-spacing: -0.3px;">
                        <span style="color: #059669; font-variant-numeric: tabular-nums;">{{ $todayAttendance->total_hours }}</span> hours recorded
                    </h2>
                    <div style="display: flex; flex-wrap: wrap; gap: 12px; margin-top: 6px; font-size: 13px; color: #71717a;">
                        <span>In: <strong style="color: #18181b; font-variant-numeric: tabular-nums;">{{ $todayAttendance->login_time }}</strong></span>
                        <span>&middot;</span>
                        <span>Out: <strong style="color: #18181b; font-variant-numeric: tabular-nums;">{{ $todayAttendance->logout_time }}</strong></span>
                        <span>&middot;</span>
                        <span>Status: <strong style="color: {{ $todayAttendance->status == 'Present' ? '#059669' : '#d97706' }};">{{ $todayAttendance->status }}</strong></span>
                    </div>
                @endif
            </div>

            <!-- Action Button -->
            <div>
                @if(!$todayAttendance || $todayAttendance->login_time == null)
                    <form action="{{ route('employee.attendance.clockIn') }}" method="POST">
                        @csrf
                        <button type="submit" style="padding: 13px 30px; background: #059669; color: #fff; border: none; border-radius: 10px; font-size: 14.5px; font-weight: 700; font-family: inherit; cursor: pointer; display: flex; align-items: center; gap: 9px; transition: all 0.15s; box-shadow: 0 4px 10px rgba(5, 150, 105, 0.25);">
                            <svg width="19" height="19" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            Clock In
                        </button>
                    </form>

                @elseif($todayAttendance->logout_time == null)
                    <form action="{{ route('employee.attendance.clockOut') }}" method="POST">
                        @csrf
                        <button type="submit" style="padding: 13px 30px; background: #d97706; color: #fff; border: none; border-radius: 10px; font-size: 14.5px; font-weight: 700; font-family: inherit; cursor: pointer; display: flex; align-items: center; gap: 9px; transition: all 0.15s; box-shadow: 0 4px 10px rgba(217, 119, 6, 0.25);">
                            <svg width="19" height="19" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Clock Out
                        </button>
                    </form>

                @else
                    <div style="padding: 11px 22px; background: #f4f4f5; color: #71717a; border: 1px solid #e4e4e7; border-radius: 10px; font-size: 13.5px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                        <svg width="17" height="17" fill="none" stroke="#059669" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Done for today
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Summary Metrics Grid -->
    <div class="metric-grid">
        <div class="metric-card">
            <div class="metric-label">Days Logged</div>
            <div class="metric-value">{{ $totalDaysWorked }}</div>
            <div class="metric-desc">Total working shifts</div>
        </div>

        <div class="metric-card accent-blue">
            <div class="metric-label">Total Hours</div>
            <div class="metric-value">{{ $totalHours }}</div>
            <div class="metric-desc">Lifetime recorded hours</div>
        </div>

        <div class="metric-card accent-green">
            <div class="metric-label">Full Days</div>
            <div class="metric-value">{{ $presentDays }}</div>
            <div class="metric-desc">&ge; 8.0 hours shifts</div>
        </div>

        <div class="metric-card accent-amber">
            <div class="metric-label">Half Days</div>
            <div class="metric-value">{{ $halfDays }}</div>
            <div class="metric-desc">&lt; 8.0 hours shifts</div>
        </div>
    </div>

    <!-- History Table Card -->
    <div class="card" id="attendanceHistory">
        <div class="card-header">
            <div>
                <h2>My Attendance Records</h2>
                <p>
                    Showing {{ $history->firstItem() ?? 0 }} – {{ $history->lastItem() ?? 0 }} of {{ $history->total() }} total entries
                </p>
            </div>

            <!-- Month Filters -->
            <div class="filter-pills">
                <a href="{{ route('employee.dashboard', ['month' => 'all']) }}" class="filter-pill {{ ($selectedMonth == 'all' || empty($selectedMonth)) ? 'active' : '' }}">
                    All Months
                </a>
                @foreach($availableMonths as $m)
                    <a href="{{ route('employee.dashboard', ['month' => $m->ym]) }}" class="filter-pill {{ $selectedMonth == $m->ym ? 'active' : '' }}">
                        {{ $m->label }}
                    </a>
                @endforeach
                <a href="{{ route('employee.attendance.export', ['month' => $selectedMonth]) }}" class="filter-pill" style="display: inline-flex; align-items: center; gap: 5px; background: #0f172a; color: #fff; border-color: #0f172a; text-decoration: none;" title="Download your attendance report in CSV format">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Export Report
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Clock In</th>
                        <th>Clock Out</th>
                        <th>Hours Worked</th>
                        <th>Status</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history as $row)
                        <tr>
                            <td style="font-weight: 700; color: #18181b;">
                                {{ \Carbon\Carbon::parse($row->date)->format('D, d M Y') }}
                            </td>

                            <!-- Clock In -->
                            <td class="tabular-nums">
                                @if(in_array($row->status, ['Holiday', 'Leave', 'Absent']))
                                    <span style="color: #a1a1aa; font-weight: 400;">—</span>
                                @else
                                    {{ $row->login_time ?? '—' }}
                                @endif
                            </td>

                            <!-- Clock Out -->
                            <td class="tabular-nums">
                                @if(in_array($row->status, ['Holiday', 'Leave', 'Absent']))
                                    <span style="color: #a1a1aa; font-weight: 400;">—</span>
                                @elseif($row->logout_time)
                                    {{ $row->logout_time }}
                                @elseif($row->login_time && $row->date == \Carbon\Carbon::today()->toDateString())
                                    <span style="color: #d97706; font-weight: 700;">In Progress</span>
                                @else
                                    <span style="color: #a1a1aa; font-weight: 400;">—</span>
                                @endif
                            </td>

                            <!-- Hours -->
                            <td class="tabular-nums">
                                @if($row->total_hours > 0)
                                    {{ number_format($row->total_hours, 2) }} hrs
                                @else
                                    <span style="color: #a1a1aa; font-weight: 400;">—</span>
                                @endif
                            </td>

                            <!-- Status Badge -->
                            <td>
                                @if($row->status == 'Present')
                                    <span class="badge badge-present">Present</span>
                                @elseif($row->status == 'Half Day')
                                    <span class="badge badge-halfday">Half Day</span>
                                @elseif($row->status == 'Leave')
                                    <span class="badge badge-leave">Leave</span>
                                @elseif($row->status == 'Holiday')
                                    <span class="badge badge-holiday">Holiday</span>
                                @else
                                    <span class="badge badge-absent">{{ $row->status }}</span>
                                @endif
                            </td>

                            <!-- Notes -->
                            <td style="font-size: 13px; color: #71717a;">
                                {{ $row->notes ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                No attendance records found for this period.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Clean Pagination Footer -->
        @if($history->hasPages())
            <div class="table-pagination">
                <div style="font-weight: 500;">
                    Page {{ $history->currentPage() }} of {{ $history->lastPage() }}
                </div>
                <div class="pagination-links">
                    {{-- Previous Page Link --}}
                    @if ($history->onFirstPage())
                        <span class="pg-btn disabled">&laquo; Prev</span>
                    @else
                        <a href="{{ $history->previousPageUrl() }}" class="pg-btn">&laquo; Prev</a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($history->getUrlRange(max(1, $history->currentPage() - 2), min($history->lastPage(), $history->currentPage() + 2)) as $page => $url)
                        @if ($page == $history->currentPage())
                            <span class="pg-btn active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="pg-btn">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($history->hasMorePages())
                        <a href="{{ $history->nextPageUrl() }}" class="pg-btn">Next &raquo;</a>
                    @else
                        <span class="pg-btn disabled">Next &raquo;</span>
                    @endif
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
