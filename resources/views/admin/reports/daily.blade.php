@extends('layouts.admin')

@section('title', 'Daily Report')
@section('topbar_title', 'Reports')

@section('content')
<div>

    <!-- Page Header -->
    <div class="page-header" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1>Daily Attendance Report</h1>
            <p>Attendance records for all employees on <strong>{{ $formattedDate }}</strong></p>
        </div>

        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
            <form action="{{ route('admin.reports.daily') }}" method="GET" style="display: flex; align-items: center; gap: 8px;">
                <input
                    type="date"
                    name="date"
                    value="{{ $selectedDate }}"
                    onchange="this.form.submit()"
                    style="padding: 9px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 13px; font-family: inherit; background: #fff; outline: none; cursor: pointer;"
                >
                <button type="submit" class="btn btn-dark btn-sm" title="Apply filter">Filter</button>
            </form>

            <a href="{{ route('admin.reports.daily.export', ['date' => $selectedDate]) }}" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px; text-decoration: none;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export CSV
            </a>

            <button onclick="window.print()" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                Print
            </button>
        </div>
    </div>

    <!-- Quick Summary KPI row -->
    @php
        $totalEmp = count($reportData);
        $presCount = collect($reportData)->where('status', 'Present')->count();
        $halfCount = collect($reportData)->where('status', 'Half Day')->count();
        $leaveCount = collect($reportData)->where('status', 'Leave')->count();
        $absCount = collect($reportData)->where('status', 'Absent')->count();
    @endphp
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 14px; margin-bottom: 20px;">
        <div class="card" style="padding: 14px 18px; margin: 0;">
            <div style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280;">Total Staff</div>
            <div style="font-size: 22px; font-weight: 700; color: #0f172a; margin-top: 4px;">{{ $totalEmp }}</div>
        </div>
        <div class="card" style="padding: 14px 18px; margin: 0; border-left: 3px solid #10b981;">
            <div style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #059669;">Present</div>
            <div style="font-size: 22px; font-weight: 700; color: #059669; margin-top: 4px;">{{ $presCount }}</div>
        </div>
        <div class="card" style="padding: 14px 18px; margin: 0; border-left: 3px solid #f59e0b;">
            <div style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #d97706;">Half Day</div>
            <div style="font-size: 22px; font-weight: 700; color: #d97706; margin-top: 4px;">{{ $halfCount }}</div>
        </div>
        <div class="card" style="padding: 14px 18px; margin: 0; border-left: 3px solid #6366f1;">
            <div style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #6366f1;">Leave</div>
            <div style="font-size: 22px; font-weight: 700; color: #6366f1; margin-top: 4px;">{{ $leaveCount }}</div>
        </div>
        <div class="card" style="padding: 14px 18px; margin: 0; border-left: 3px solid #ef4444;">
            <div style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #dc2626;">Absent</div>
            <div style="font-size: 22px; font-weight: 700; color: #dc2626; margin-top: 4px;">{{ $absCount }}</div>
        </div>
    </div>

    <!-- Report Table -->
    <div class="card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Clock In</th>
                        <th>Clock Out</th>
                        <th>Hours</th>
                        <th>Status</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reportData as $row)
                        <tr>
                            <td class="cell-mono" style="font-weight: 600; color: #6366f1;">{{ $row['employee']->employee_id ?? '—' }}</td>
                            <td>
                                <div class="cell-name">{{ $row['employee']->name }}</div>
                                <div class="cell-sub">{{ $row['employee']->email }}</div>
                            </td>
                            <td style="font-size: 13px;">{{ $row['employee']->department ?? 'General' }}</td>
                            <td class="cell-mono">{{ $row['login_time'] ?? '—' }}</td>
                            <td class="cell-mono">{{ $row['logout_time'] ?? '—' }}</td>
                            <td class="cell-mono" style="font-weight: 600;">{{ $row['total_hours'] > 0 ? $row['total_hours'] . ' hrs' : '0.00' }}</td>
                            <td>
                                @if($row['status'] == 'Present')
                                    <span class="badge badge-present">Present</span>
                                @elseif($row['status'] == 'Half Day')
                                    <span class="badge badge-halfday">Half Day</span>
                                @elseif($row['status'] == 'Leave')
                                    <span class="badge badge-leave">Leave</span>
                                @elseif($row['status'] == 'Holiday')
                                    <span class="badge badge-holiday">Holiday</span>
                                @else
                                    <span class="badge badge-absent">Absent</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                @if($row['record_id'])
                                    <a href="{{ route('admin.attendances.edit', $row['record_id']) }}" class="link-text" style="font-size: 12px;">Edit</a>
                                @else
                                    <span style="color: #d1d5db;">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty-state">No employees registered in the system.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
