<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sign In') — Attendance System</title>

    <!-- Premium Sans-Serif Typography: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f6f5f2;
            color: #1a1a1a;
            min-height: 100vh;
            display: flex;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            font-size: 14px;
            line-height: 1.5;
        }

        /* Left branding panel */
        .auth-brand {
            display: none;
            width: 420px;
            min-height: 100vh;
            background: #1b1b1b;
            color: #fff;
            padding: 48px 40px;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .auth-brand::before {
            content: '';
            position: absolute;
            top: -80px;
            right: -80px;
            width: 260px;
            height: 260px;
            border-radius: 50%;
            background: rgba(99, 102, 241, 0.08);
            filter: blur(60px);
        }

        .auth-brand::after {
            content: '';
            position: absolute;
            bottom: -60px;
            left: -40px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(52, 211, 153, 0.06);
            filter: blur(50px);
        }

        .auth-brand .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            z-index: 1;
        }

        .brand-logo .logo-mark {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #6366f1, #818cf8);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-logo span {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: -0.3px;
        }

        .auth-brand .brand-copy {
            position: relative;
            z-index: 1;
        }

        .brand-copy h2 {
            font-size: 26px;
            font-weight: 700;
            line-height: 1.3;
            letter-spacing: -0.5px;
            margin-bottom: 12px;
        }

        .brand-copy p {
            font-size: 14px;
            color: #9ca3af;
            line-height: 1.7;
        }

        .auth-brand .brand-footer {
            font-size: 12px;
            color: #6b7280;
            position: relative;
            z-index: 1;
        }

        @media (min-width: 960px) {
            .auth-brand { display: flex; }
        }

        @media (max-width: 480px) {
            .auth-form-wrap { padding: 20px 14px; }
            .form-card { padding: 22px 18px; border-radius: 12px; }
        }

        /* Right form panel */
        .auth-form-wrap {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 32px 24px;
            min-height: 100vh;
        }

        .auth-form-inner {
            width: 100%;
            max-width: 450px;
        }

        .auth-form-footer {
            margin-top: 32px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
        }

        /* Form styling */
        .form-card {
            background: #fff;
            border: 1px solid #e8e5e0;
            border-radius: 16px;
            padding: 30px 28px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }

        .form-card h1 {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.4px;
            margin-bottom: 4px;
        }

        .form-card .subtitle {
            font-size: 13.5px;
            color: #6b7280;
            margin-bottom: 22px;
        }

        .field-group { margin-bottom: 18px; }

        .field-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }

        .field-group input,
        .field-group select {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
            background: #fafaf8;
            color: #1a1a1a;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        .field-group input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            background: #fff;
        }

        .field-group input::placeholder { color: #9ca3af; }

        .checkbox-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
        }

        .checkbox-row label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #6b7280;
            cursor: pointer;
        }

        .checkbox-row label input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #6366f1;
        }

        .checkbox-row .hint {
            font-size: 12px;
            color: #9ca3af;
        }

        .btn-primary {
            width: 100%;
            padding: 11px 20px;
            background: #1b1b1b;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
        }

        .btn-primary:hover { background: #333; }
        .btn-primary:active { transform: scale(0.99); }

        /* Demo credentials box */
        .demo-box {
            background: #f8f7f4;
            border: 1px solid #e7e4dc;
            border-radius: 12px;
            padding: 12px 14px;
            margin-bottom: 22px;
        }

        .demo-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 9px;
        }

        .demo-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #57534e;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .demo-label .dot {
            width: 6px;
            height: 6px;
            background: #10b981;
            border-radius: 50%;
            display: inline-block;
        }

        .demo-hint {
            font-size: 11px;
            color: #78716c;
        }

        .demo-btn-admin {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 12px;
            background: #ffffff;
            border: 1px solid #e0dcd4;
            border-radius: 8px;
            cursor: pointer;
            font-family: inherit;
            margin-bottom: 10px;
            transition: border-color 0.15s, box-shadow 0.15s, transform 0.1s;
            text-align: left;
        }

        .demo-btn-admin:hover {
            border-color: #6366f1;
            background: #fbfbfe;
            box-shadow: 0 2px 5px rgba(99, 102, 241, 0.08);
            transform: translateY(-1px);
        }

        .demo-admin-badge {
            background: #1f2937;
            color: #ffffff;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 2px 7px;
            border-radius: 4px;
            margin-right: 8px;
            display: inline-block;
        }

        .demo-admin-title {
            font-size: 12.5px;
            font-weight: 600;
            color: #111827;
        }

        .demo-admin-email {
            font-size: 11.5px;
            color: #6366f1;
            font-weight: 500;
        }

        .demo-subheading {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #78716c;
            margin-bottom: 7px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .demo-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 7px;
        }

        .demo-emp-card {
            padding: 7px 10px;
            background: #ffffff;
            border: 1px solid #e2ded5;
            border-radius: 8px;
            cursor: pointer;
            text-align: left;
            font-family: inherit;
            display: flex;
            flex-direction: column;
            justify-content: center;
            transition: border-color 0.15s, box-shadow 0.15s, transform 0.1s;
        }

        .demo-emp-card:hover {
            border-color: #6366f1;
            background: #fbfbfe;
            box-shadow: 0 2px 5px rgba(99, 102, 241, 0.08);
            transform: translateY(-1px);
        }

        .demo-emp-card .name {
            font-size: 12px;
            font-weight: 600;
            color: #1f2937;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .demo-emp-card .email {
            font-size: 11px;
            color: #6b7280;
            margin-top: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Alert messages */
        .alert {
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 16px;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .alert-success {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .alert-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            margin-top: 6px;
            flex-shrink: 0;
        }
    </style>
</head>
<body>

    <!-- Left Brand Panel -->
    <aside class="auth-brand">
        <div class="brand-logo">
            <div class="logo-mark">
                <svg width="18" height="18" fill="none" stroke="#fff" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span>AttendEase</span>
        </div>

        <div class="brand-copy">
            <h2>Track attendance,<br>not paperwork.</h2>
            <p>A straightforward system for managing employee clock-ins, generating reports, and keeping your team's hours accurate — without the overhead.</p>
        </div>

        <div class="brand-footer">
            &copy; {{ date('Y') }} AttendEase &middot; Built for simplicity
        </div>
    </aside>

    <!-- Right Form Area -->
    <div class="auth-form-wrap">
        <div class="auth-form-inner">
            @yield('content')
        </div>

        <div class="auth-form-footer">
            &copy; {{ date('Y') }} Employee Attendance Management System
        </div>
    </div>

    @yield('scripts')
</body>
</html>
