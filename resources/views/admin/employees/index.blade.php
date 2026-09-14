@extends('layouts.admin')

@section('title', 'Employees')
@section('topbar_title', 'Employee Management')

@section('content')
<div>

    <!-- Page Header -->
    <div class="page-header" style="display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
        <div>
            <h1>Employees</h1>
            <p>View, add, edit, and manage employee accounts</p>
        </div>
        <a href="{{ route('admin.employees.create') }}" class="btn btn-dark">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Employee
        </a>
    </div>

    <!-- Search Bar -->
    <div class="filter-bar">
        <form action="{{ route('admin.employees.index') }}" method="GET">
            <div class="filter-field" style="flex: 3;">
                <label>Search</label>
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Search by name, email, or employee ID..."
                >
            </div>
            <button type="submit" class="btn btn-dark btn-sm">Search</button>
            @if($search)
                <a href="{{ route('admin.employees.index') }}" class="btn btn-ghost btn-sm">Clear</a>
            @endif
        </form>
    </div>

    <!-- Employees Table -->
    <div class="card">
        <div class="table-responsive">
            <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Employee</th>
                    <th>Department / Role</th>
                    <th>Contact</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $emp)
                    <tr>
                        <td class="cell-mono" style="font-weight: 600; color: #6366f1;">{{ $emp->employee_id ?? '—' }}</td>
                        <td>
                            <div class="cell-name">{{ $emp->name }}</div>
                            <div class="cell-sub">{{ $emp->email }}</div>
                        </td>
                        <td>
                            <div style="font-weight: 500;">{{ $emp->designation ?? '—' }}</div>
                            <div class="cell-sub">{{ $emp->department ?? 'General' }}</div>
                        </td>
                        <td class="cell-mono">{{ $emp->phone ?? '—' }}</td>
                        <td>
                            @if($emp->status == 'active')
                                <span class="badge badge-active"><span class="badge-dot"></span> Active</span>
                            @else
                                <span class="badge badge-inactive"><span class="badge-dot"></span> Inactive</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 4px;">
                                <!-- Edit -->
                                <a href="{{ route('admin.employees.edit', $emp->id) }}" class="btn btn-ghost btn-sm" title="Edit">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px; height:15px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>

                                <!-- Toggle Status -->
                                <form action="{{ route('admin.employees.toggleStatus', $emp->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-ghost btn-sm" title="{{ $emp->status == 'active' ? 'Deactivate' : 'Activate' }}">
                                        @if($emp->status == 'active')
                                            <svg fill="none" stroke="#d97706" viewBox="0 0 24 24" style="width:15px; height:15px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                            </svg>
                                        @else
                                            <svg fill="none" stroke="#059669" viewBox="0 0 24 24" style="width:15px; height:15px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @endif
                                    </button>
                                </form>

                                <!-- Delete -->
                                <form action="{{ route('admin.employees.destroy', $emp->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete {{ $emp->name }}? This will also remove their attendance records.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px; height:15px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-state">No employees found. Click "New Employee" to add one.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

</div>
@endsection
