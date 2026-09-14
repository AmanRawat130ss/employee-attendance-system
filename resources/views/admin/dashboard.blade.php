@extends('layouts.admin')

@section('title', 'Dashboard')
@section('topbar_title', 'Dashboard')

@section('content')
<div>

    <!-- Page Header -->
    <div class="page-header" style="display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
        <div>
            <h1>Overview</h1>
            <p>Today's attendance snapshot and activity feed</p>
        </div>
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <a href="{{ route('admin.reports.daily') }}" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px; text-decoration: none;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                Daily Report
            </a>
            <a href="{{ route('admin.reports.monthly') }}" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px; text-decoration: none;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                Monthly Report
            </a>
            <a href="{{ route('admin.employees.create') }}" class="btn btn-dark btn-sm" style="display: inline-flex; align-items: center; gap: 6px; text-decoration: none;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="14" height="14">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Employee
            </a>
        </div>
    </div>

    <!-- Metric Cards -->
    <div class="metric-grid" style="grid-template-columns: repeat(5, 1fr);">

        <div class="metric-card">
            <div class="metric-label">Total Staff</div>
            <div class="metric-value">{{ $totalEmployees }}</div>
            <div class="metric-desc">Active employees registered</div>
        </div>

        <div class="metric-card accent-green">
            <div class="metric-label">Present Today</div>
            <div class="metric-value">{{ $presentToday }}</div>
            <div class="metric-desc">&ge; 8 hours completed</div>
        </div>

        <div class="metric-card accent-red">
            <div class="metric-label">Absent Today</div>
            <div class="metric-value">{{ $absentToday }}</div>
            <div class="metric-desc">Not clocked in yet</div>
        </div>

        <div class="metric-card accent-amber">
            <div class="metric-label">Half Day</div>
            <div class="metric-value">{{ $halfDayToday }}</div>
            <div class="metric-desc">&lt; 8 hours logged</div>
        </div>

        <div class="metric-card accent-indigo">
            <div class="metric-label">On Leave</div>
            <div class="metric-value">{{ $onLeaveToday }}</div>
            <div class="metric-desc">Approved day off</div>
        </div>

    </div>

    <!-- Today's Activity Table -->
    <div class="card">
        <div class="card-header">
            <div>
                <h2>Today's Activity</h2>
                <p>Real-time punch records for {{ $currentDate }}</p>
            </div>
            <a href="{{ route('admin.employees.index') }}" class="link-text">All Employees &rarr;</a>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Clock In</th>
                        <th>Clock Out</th>
                        <th>Hours</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($todayRecords as $record)
                        <tr>
                            <td>
                                <div class="cell-name">{{ $record->user->name ?? 'Unknown' }}</div>
                                <div class="cell-sub">{{ $record->user->employee_id ?? 'N/A' }} &middot; {{ $record->user->email ?? '' }}</div>
                            </td>
                            <td>{{ $record->user->department ?? 'General' }}</td>
                            <td class="cell-mono">{{ $record->login_time ?? '—' }}</td>
                            <td class="cell-mono">{{ $record->logout_time ?? 'Still working' }}</td>
                            <td class="cell-mono" style="font-weight: 600;">{{ $record->total_hours > 0 ? $record->total_hours . ' hrs' : '—' }}</td>
                            <td>
                                @if($record->status == 'Present')
                                    <span class="badge badge-present">Present</span>
                                @elseif($record->status == 'Half Day')
                                    <span class="badge badge-halfday">Half Day</span>
                                @elseif($record->status == 'Leave')
                                    <span class="badge badge-leave">On Leave</span>
                                @else
                                    <span class="badge badge-absent">{{ $record->status }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">No attendance records for today yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
