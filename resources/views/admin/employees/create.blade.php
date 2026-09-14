@extends('layouts.admin')

@section('title', 'Add Employee')
@section('topbar_title', 'Employee Management')

@section('content')
<div style="max-width: 680px;">

    <!-- Breadcrumb & Title -->
    <div class="page-header">
        <a href="{{ route('admin.employees.index') }}" class="link-text" style="font-size: 12px;">&larr; Back to Employees</a>
        <h1 style="margin-top: 8px;">New Employee</h1>
        <p>Register a new employee with their credentials and profile</p>
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
        <form action="{{ route('admin.employees.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="form-field">
                    <label>Full Name <span class="req">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Rahul Sharma">
                </div>

                <div class="form-field">
                    <label>Employee ID <span class="req">*</span></label>
                    <input type="text" name="employee_id" value="{{ old('employee_id') }}" required placeholder="e.g. EMP006" style="font-family: 'SFMono-Regular', Consolas, monospace;">
                </div>

                <div class="form-field">
                    <label>Email Address <span class="req">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="e.g. rahul@ems.com">
                </div>

                <div class="form-field">
                    <label>Password <span class="req">*</span></label>
                    <input type="password" name="password" required placeholder="Min 6 characters">
                </div>

                <div class="form-field">
                    <label>Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="e.g. 9811122233">
                </div>

                <div class="form-field">
                    <label>Department</label>
                    <input type="text" name="department" value="{{ old('department') }}" placeholder="e.g. Engineering, HR">
                </div>

                <div class="form-field span-full">
                    <label>Designation / Job Title</label>
                    <input type="text" name="designation" value="{{ old('designation') }}" placeholder="e.g. Full Stack Developer">
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.employees.index') }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-dark">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Employee
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
