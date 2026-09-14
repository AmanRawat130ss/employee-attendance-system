@extends('layouts.admin')

@section('title', 'Edit Employee')
@section('topbar_title', 'Employee Management')

@section('content')
<div style="max-width: 680px;">

    <!-- Breadcrumb & Title -->
    <div class="page-header">
        <a href="{{ route('admin.employees.index') }}" class="link-text" style="font-size: 12px;">&larr; Back to Employees</a>
        <h1 style="margin-top: 8px;">Edit Employee</h1>
        <p>Update profile for <strong>{{ $employee->name }}</strong></p>
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
        <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-field">
                    <label>Full Name <span class="req">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $employee->name) }}" required>
                </div>

                <div class="form-field">
                    <label>Employee ID <span class="req">*</span></label>
                    <input type="text" name="employee_id" value="{{ old('employee_id', $employee->employee_id) }}" required style="font-family: 'SFMono-Regular', Consolas, monospace;">
                </div>

                <div class="form-field">
                    <label>Email Address <span class="req">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $employee->email) }}" required>
                </div>

                <div class="form-field">
                    <label>New Password <span style="font-weight: 400; color: #9ca3af; text-transform: none;">(leave blank to keep current)</span></label>
                    <input type="password" name="password" placeholder="••••••••">
                </div>

                <div class="form-field">
                    <label>Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $employee->phone) }}">
                </div>

                <div class="form-field">
                    <label>Department</label>
                    <input type="text" name="department" value="{{ old('department', $employee->department) }}">
                </div>

                <div class="form-field">
                    <label>Designation</label>
                    <input type="text" name="designation" value="{{ old('designation', $employee->designation) }}">
                </div>

                <div class="form-field">
                    <label>Account Status <span class="req">*</span></label>
                    <select name="status">
                        <option value="active" {{ old('status', $employee->status) == 'active' ? 'selected' : '' }}>Active (Can log in)</option>
                        <option value="inactive" {{ old('status', $employee->status) == 'inactive' ? 'selected' : '' }}>Inactive (Login blocked)</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.employees.index') }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-dark">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Update Employee
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
