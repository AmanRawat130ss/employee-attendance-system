@extends('layouts.admin')

@section('title', 'Edit Attendance')
@section('topbar_title', 'Attendance Management')

@section('content')
<div style="max-width: 620px;">

    <!-- Breadcrumb & Title -->
    <div class="page-header">
        <a href="{{ route('admin.attendances.index') }}" class="link-text" style="font-size: 12px;">&larr; Back to Attendance Logs</a>
        <h1 style="margin-top: 8px;">Manual Override</h1>
        <p>Update punch times or status for <strong>{{ $attendance->user->name }}</strong></p>
    </div>

    <!-- Validation Errors -->
    @if($errors->any())
        <div class="error-box">
            @foreach($errors->all() as $error)
                <div class="err-item">
                    <span class="err-dot"></span>
                    {{ $error }}
                </div>
            @endforeach
        </div>
    @endif

    <!-- Form Card -->
    <div class="card" style="padding: 28px;">

        <!-- Employee Info Strip -->
        <div style="display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; background: #faf9f7; border: 1px solid #f0ede8; border-radius: 10px; margin-bottom: 24px; font-size: 13px;">
            <div>
                <span style="color: #9ca3af;">Employee:</span>
                <strong style="margin-left: 4px;">{{ $attendance->user->name }} ({{ $attendance->user->employee_id ?? 'N/A' }})</strong>
            </div>
            <div>
                <span style="color: #9ca3af;">Date:</span>
                <strong style="margin-left: 4px; font-family: 'SFMono-Regular', Consolas, monospace; color: #059669;">{{ \Carbon\Carbon::parse($attendance->date)->format('D, d M Y') }}</strong>
            </div>
        </div>

        <form action="{{ route('admin.attendances.update', $attendance->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-field">
                    <label>Clock In Time</label>
                    <input
                        type="time"
                        name="login_time"
                        value="{{ old('login_time', $attendance->login_time ? substr($attendance->login_time, 0, 5) : '') }}"
                        style="font-family: 'SFMono-Regular', Consolas, monospace;"
                    >
                </div>

                <div class="form-field">
                    <label>Clock Out Time</label>
                    <input
                        type="time"
                        name="logout_time"
                        value="{{ old('logout_time', $attendance->logout_time ? substr($attendance->logout_time, 0, 5) : '') }}"
                        style="font-family: 'SFMono-Regular', Consolas, monospace;"
                    >
                </div>

                <div class="form-field span-full">
                    <label>Attendance Status <span class="req">*</span></label>
                    <select name="status" required>
                        <option value="Present" {{ old('status', $attendance->status) == 'Present' ? 'selected' : '' }}>Present (Full shift)</option>
                        <option value="Half Day" {{ old('status', $attendance->status) == 'Half Day' ? 'selected' : '' }}>Half Day (Partial shift)</option>
                        <option value="Leave" {{ old('status', $attendance->status) == 'Leave' ? 'selected' : '' }}>Leave (Approved day off)</option>
                        <option value="Holiday" {{ old('status', $attendance->status) == 'Holiday' ? 'selected' : '' }}>Holiday (Company holiday)</option>
                        <option value="Absent" {{ old('status', $attendance->status) == 'Absent' ? 'selected' : '' }}>Absent (Unannounced)</option>
                    </select>
                </div>

                <div class="form-field span-full">
                    <label>Notes / Reason</label>
                    <input
                        type="text"
                        name="notes"
                        value="{{ old('notes', $attendance->notes) }}"
                        placeholder="e.g. Adjusted per manager request, medical leave approved"
                    >
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.attendances.index') }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-dark">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Changes
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
