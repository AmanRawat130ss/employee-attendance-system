@extends('layouts.admin')

@section('title', 'Attendance Logs')
@section('topbar_title', 'Attendance Management')

@section('content')
<div>

    <!-- Page Header -->
    <div class="page-header">
        <h1>Attendance Logs</h1>
        <p>View, filter, and manually update daily attendance records</p>
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <form action="{{ route('admin.attendances.index') }}" method="GET">
            <div class="filter-field">
                <label>Date</label>
                <input type="date" name="date" value="{{ $selectedDate }}">
            </div>

            <div class="filter-field">
                <label>Employee</label>
                <select name="user_id">
                    <option value="">All Employees</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ $selectedUserId == $emp->id ? 'selected' : '' }}>
                            {{ $emp->name }} ({{ $emp->employee_id ?? 'N/A' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-field">
                <label>Status</label>
                <select name="status">
                    <option value="">All Statuses</option>
                    <option value="Present" {{ $selectedStatus == 'Present' ? 'selected' : '' }}>Present</option>
                    <option value="Half Day" {{ $selectedStatus == 'Half Day' ? 'selected' : '' }}>Half Day</option>
                    <option value="Leave" {{ $selectedStatus == 'Leave' ? 'selected' : '' }}>Leave</option>
                    <option value="Holiday" {{ $selectedStatus == 'Holiday' ? 'selected' : '' }}>Holiday</option>
                    <option value="Absent" {{ $selectedStatus == 'Absent' ? 'selected' : '' }}>Absent</option>
                </select>
            </div>

            <div style="display: flex; align-items: center; gap: 6px;">
                <button type="submit" class="btn btn-dark btn-sm">Filter</button>
                @if($selectedDate || $selectedStatus || $selectedUserId)
                    <a href="{{ route('admin.attendances.index') }}" class="btn btn-ghost btn-sm">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Attendance Table -->
    <div class="card">
        <div class="table-responsive">
            <table class="data-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Employee</th>
                    <th>Clock In</th>
                    <th>Clock Out</th>
                    <th>Hours</th>
                    <th>Status</th>
                    <th>Notes</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $row)
                    <tr>
                        <td style="font-weight: 600; white-space: nowrap;">{{ \Carbon\Carbon::parse($row->date)->format('D, d M Y') }}</td>
                        <td>
                            <div class="cell-name">{{ $row->user->name ?? 'Unknown' }}</div>
                            <div class="cell-sub">{{ $row->user->employee_id ?? 'N/A' }} &middot; {{ $row->user->department ?? 'General' }}</div>
                        </td>
                        <td class="cell-mono">{{ $row->login_time ?? '—' }}</td>
                        <td class="cell-mono">{{ $row->logout_time ?? '—' }}</td>
                        <td class="cell-mono" style="font-weight: 600;">{{ $row->total_hours > 0 ? $row->total_hours . ' hrs' : '—' }}</td>
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
                        <td style="max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: #9ca3af; font-size: 12px;">
                            {{ $row->notes ?? '—' }}
                        </td>
                        <td style="text-align: right;">
                            <a href="{{ route('admin.attendances.edit', $row->id) }}" class="btn btn-outline btn-sm">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="empty-state">No records match the selected filters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

</div>
@endsection
