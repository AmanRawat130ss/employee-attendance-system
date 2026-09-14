@extends('layouts.auth')

@section('title', 'Sign In')

@section('content')
<div class="form-card">
    <h1>Welcome back</h1>
    <p class="subtitle">Sign in to your attendance portal</p>

    <!-- Demo Accounts Quick Fill -->
    <div class="demo-box">
        <div class="demo-header">
            <div class="demo-label">
                <span class="dot"></span>
                Quick Demo Accounts
            </div>
            <span class="demo-hint">Click to fill &middot; 2x to login</span>
        </div>

        <!-- Admin Account -->
        <button type="button" class="demo-btn-admin" onclick="fillCredentials('admin@ems.com', 'password123')" ondblclick="fillCredentials('admin@ems.com', 'password123', true)" title="Click to fill or double-click to login">
            <div style="display: flex; align-items: center;">
                <span class="demo-admin-badge">Admin</span>
                <span class="demo-admin-title">System Administrator</span>
            </div>
            <span class="demo-admin-email">admin@ems.com &rarr;</span>
        </button>

        <!-- Employee Accounts -->
        <div class="demo-subheading">
            <span>Employees (6)</span>
        </div>
        <div class="demo-grid">
            <button type="button" class="demo-emp-card" onclick="fillCredentials('aman@ems.com', 'password123')" ondblclick="fillCredentials('aman@ems.com', 'password123', true)" title="Click to fill or double-click to login">
                <span class="name">Aman</span>
                <span class="email">aman@ems.com</span>
            </button>
            <button type="button" class="demo-emp-card" onclick="fillCredentials('chandler@ems.com', 'password123')" ondblclick="fillCredentials('chandler@ems.com', 'password123', true)" title="Click to fill or double-click to login">
                <span class="name">Chandler Bing</span>
                <span class="email">chandler@ems.com</span>
            </button>
            <button type="button" class="demo-emp-card" onclick="fillCredentials('joey@ems.com', 'password123')" ondblclick="fillCredentials('joey@ems.com', 'password123', true)" title="Click to fill or double-click to login">
                <span class="name">Joey Tribbiani</span>
                <span class="email">joey@ems.com</span>
            </button>
            <button type="button" class="demo-emp-card" onclick="fillCredentials('ross@ems.com', 'password123')" ondblclick="fillCredentials('ross@ems.com', 'password123', true)" title="Click to fill or double-click to login">
                <span class="name">Ross Geller</span>
                <span class="email">ross@ems.com</span>
            </button>
            <button type="button" class="demo-emp-card" onclick="fillCredentials('monica@ems.com', 'password123')" ondblclick="fillCredentials('monica@ems.com', 'password123', true)" title="Click to fill or double-click to login">
                <span class="name">Monica Geller</span>
                <span class="email">monica@ems.com</span>
            </button>
            <button type="button" class="demo-emp-card" onclick="fillCredentials('phoebe@ems.com', 'password123')" ondblclick="fillCredentials('phoebe@ems.com', 'password123', true)" title="Click to fill or double-click to login">
                <span class="name">Phoebe Buffay</span>
                <span class="email">phoebe@ems.com</span>
            </button>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success">
            <span class="alert-dot" style="background: #34d399;"></span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <span class="alert-dot" style="background: #ef4444;"></span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <div>
                @foreach($errors->all() as $error)
                    <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 2px;">
                        <span class="alert-dot" style="background: #ef4444;"></span>
                        {{ $error }}
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Login Form -->
    <form action="{{ route('login.submit') }}" method="POST" id="loginForm">
        @csrf

        <div class="field-group">
            <label for="email">Email address</label>
            <input
                type="email"
                name="email"
                id="email"
                value="{{ old('email') }}"
                required
                autofocus
                placeholder="you@company.com"
            >
        </div>

        <div class="field-group">
            <label for="password">Password</label>
            <input
                type="password"
                name="password"
                id="password"
                required
                placeholder="Enter your password"
            >
        </div>

        <div class="checkbox-row">
            <label>
                <input type="checkbox" name="remember">
                Remember me
            </label>
            <span class="hint">Default: password123</span>
        </div>

        <button type="submit" class="btn-primary">Sign in</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function fillCredentials(email, password, autoSubmit = false) {
        var emailField = document.getElementById('email');
        var passField = document.getElementById('password');
        var form = document.getElementById('loginForm');

        if (emailField && passField) {
            emailField.value = email;
            passField.value = password;

            // highlight fields
            emailField.style.borderColor = '#6366f1';
            passField.style.borderColor = '#6366f1';
            emailField.style.background = '#f5f7ff';
            passField.style.background = '#f5f7ff';

            setTimeout(function() {
                emailField.style.borderColor = '#d1d5db';
                passField.style.borderColor = '#d1d5db';
                emailField.style.background = '#fafaf8';
                passField.style.background = '#fafaf8';
            }, 600);

            if (autoSubmit && form) {
                form.submit();
            }
        }
    }
</script>
@endsection
