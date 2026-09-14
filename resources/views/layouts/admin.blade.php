<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — Admin Panel</title>

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

        /* ---- Sidebar ---- */
        .sidebar {
            width: 240px;
            min-height: 100vh;
            background: #1b1b1b;
            color: #d1d5db;
            padding: 24px 16px 20px;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 50;
            transition: transform 0.25s ease;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 8px 24px;
            border-bottom: 1px solid #2d2d2d;
            margin-bottom: 20px;
        }

        .sidebar-logo .logo-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #6366f1, #818cf8);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .sidebar-logo .logo-text {
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.3px;
        }

        .sidebar-nav {
            flex: 1;
            list-style: none;
        }

        .nav-section-label {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #6b7280;
            padding: 0 12px;
            margin-bottom: 8px;
            margin-top: 20px;
        }

        .nav-section-label:first-child { margin-top: 0; }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            color: #9ca3af;
            text-decoration: none;
            transition: all 0.15s;
            margin-bottom: 2px;
        }

        .nav-item:hover {
            background: rgba(255,255,255,0.06);
            color: #e5e7eb;
        }

        .nav-item.active {
            background: rgba(99,102,241,0.12);
            color: #a5b4fc;
        }

        .nav-item svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            opacity: 0.7;
        }

        .nav-item.active svg { opacity: 1; }

        .sidebar-footer {
            padding: 16px 8px 0;
            border-top: 1px solid #2d2d2d;
            margin-top: auto;
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }

        .sidebar-user .user-avatar {
            width: 32px;
            height: 32px;
            background: #374151;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            color: #e5e7eb;
            flex-shrink: 0;
        }

        .sidebar-user .user-info {
            overflow: hidden;
        }

        .sidebar-user .user-name {
            font-size: 13px;
            font-weight: 600;
            color: #e5e7eb;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-user .user-role {
            font-size: 11px;
            color: #6b7280;
        }

        .btn-logout {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            width: 100%;
            padding: 8px 12px;
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.15);
            border-radius: 8px;
            color: #fca5a5;
            font-size: 12px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: background 0.15s;
        }

        .btn-logout:hover { background: rgba(239, 68, 68, 0.15); }

        .btn-logout svg { width: 14px; height: 14px; }

        /* ---- Main content ---- */
        .main-wrap {
            flex: 1;
            margin-left: 240px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            padding: 16px 32px;
            border-bottom: 1px solid #e8e5e0;
            background: #faf9f7;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 20;
        }

        .topbar-title {
            font-size: 15px;
            font-weight: 600;
            color: #374151;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .topbar-date {
            font-size: 12px;
            color: #9ca3af;
            font-weight: 500;
        }

        .main-content {
            flex: 1;
            padding: 28px 32px 40px;
        }

        .page-footer {
            padding: 16px 32px;
            border-top: 1px solid #e8e5e0;
            font-size: 12px;
            color: #9ca3af;
            text-align: center;
        }

        /* ---- Flash alerts ---- */
        .flash-alert {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .flash-success {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
        }

        .flash-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .flash-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* ---- Common components ---- */
        .page-header { margin-bottom: 28px; }

        .page-header h1 {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.4px;
            color: #1a1a1a;
            margin-bottom: 4px;
        }

        .page-header p {
            font-size: 14px;
            color: #6b7280;
        }

        .card {
            background: #fff;
            border: 1px solid #e8e5e0;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        }

        .card-header {
            padding: 18px 22px;
            border-bottom: 1px solid #f0ede8;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header h2 {
            font-size: 15px;
            font-weight: 600;
            color: #1a1a1a;
        }

        .card-header p {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 2px;
        }

        /* Data tables & Responsive wrapper */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }

        .data-table thead th {
            background: #faf9f7;
            padding: 10px 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            text-align: left;
            border-bottom: 1px solid #f0ede8;
        }

        .data-table tbody td {
            padding: 14px 20px;
            font-size: 13px;
            border-bottom: 1px solid #f5f3ef;
            color: #374151;
            vertical-align: middle;
        }

        .data-table tbody tr:last-child td { border-bottom: none; }
        .data-table tbody tr:hover { background: #fdfcfa; }

        .data-table .cell-name {
            font-weight: 600;
            color: #1a1a1a;
        }

        .data-table .cell-sub {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 1px;
        }

        .data-table .cell-mono {
            font-variant-numeric: tabular-nums;
            font-weight: 600;
            color: #1f2937;
            font-size: 13.5px;
        }

        .data-table .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #9ca3af;
            font-size: 13px;
        }

        /* Status badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-present { background: #ecfdf5; color: #059669; }
        .badge-halfday { background: #fffbeb; color: #d97706; }
        .badge-leave { background: #eef2ff; color: #6366f1; }
        .badge-holiday { background: #f0f9ff; color: #0284c7; }
        .badge-absent { background: #fef2f2; color: #dc2626; }
        .badge-active { background: #ecfdf5; color: #059669; }
        .badge-inactive { background: #fef2f2; color: #dc2626; }

        .badge .badge-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: currentColor;
        }

        /* Metric cards */
        .metric-grid {
            display: grid;
            gap: 16px;
            margin-bottom: 24px;
        }

        .metric-card {
            background: #fff;
            border: 1px solid #e8e5e0;
            border-radius: 14px;
            padding: 20px 22px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        }

        .metric-card .metric-label {
            font-size: 12px;
            font-weight: 500;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .metric-card .metric-value {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: #1a1a1a;
        }

        .metric-card .metric-desc {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 4px;
        }

        .metric-card.accent-green .metric-value { color: #059669; }
        .metric-card.accent-red .metric-value { color: #dc2626; }
        .metric-card.accent-amber .metric-value { color: #d97706; }
        .metric-card.accent-indigo .metric-value { color: #6366f1; }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            font-family: inherit;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.15s;
        }

        .btn svg { width: 15px; height: 15px; }

        .btn-dark {
            background: #1b1b1b;
            color: #fff;
        }

        .btn-dark:hover { background: #333; }

        .btn-outline {
            background: #fff;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        .btn-outline:hover { background: #f9fafb; border-color: #9ca3af; }

        .btn-ghost {
            background: transparent;
            color: #6b7280;
            padding: 6px 8px;
        }

        .btn-ghost:hover { background: #f3f4f6; color: #374151; }

        .btn-sm { padding: 6px 12px; font-size: 12px; }

        .btn-danger {
            background: transparent;
            color: #dc2626;
            padding: 6px 8px;
        }

        .btn-danger:hover { background: #fef2f2; }

        .link-text {
            font-size: 13px;
            font-weight: 500;
            color: #6366f1;
            text-decoration: none;
        }

        .link-text:hover { color: #4f46e5; text-decoration: underline; }

        /* Form elements */
        .form-field { margin-bottom: 20px; }

        .form-field label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }

        .form-field label .req { color: #dc2626; }

        .form-field input,
        .form-field select,
        .form-field textarea {
            width: 100%;
            padding: 9px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            background: #fafaf8;
            color: #1a1a1a;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-field input:focus,
        .form-field select:focus,
        .form-field textarea:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
            background: #fff;
        }

        .form-field input::placeholder { color: #9ca3af; }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-grid .span-full { grid-column: 1 / -1; }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding-top: 20px;
            border-top: 1px solid #f0ede8;
            margin-top: 4px;
        }

        /* Filter bar */
        .filter-bar {
            background: #fff;
            border: 1px solid #e8e5e0;
            border-radius: 14px;
            padding: 18px 22px;
            margin-bottom: 20px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        }

        .filter-bar form {
            display: flex;
            gap: 12px;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .filter-field {
            flex: 1;
            min-width: 160px;
        }

        .filter-field label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            margin-bottom: 5px;
        }

        .filter-field input,
        .filter-field select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 13px;
            font-family: inherit;
            background: #fafaf8;
            color: #1a1a1a;
            outline: none;
            transition: border-color 0.2s;
        }

        .filter-field input:focus,
        .filter-field select:focus {
            border-color: #6366f1;
        }

        /* Error list */
        .error-box {
            padding: 12px 16px;
            border-radius: 10px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            margin-bottom: 20px;
        }

        .error-box .err-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #991b1b;
            padding: 2px 0;
        }

        .error-box .err-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #dc2626;
            flex-shrink: 0;
        }

        /* Responsive: Mobile sidebar toggle */
        .mobile-toggle {
            display: none;
            padding: 8px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            cursor: pointer;
        }

        .mobile-toggle svg { width: 20px; height: 20px; color: #374151; }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.3);
            z-index: 40;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open { transform: translateX(0); }

            .sidebar-overlay.show { display: block; }

            .main-wrap { margin-left: 0; width: 100%; }

            .mobile-toggle { display: flex; }

            .main-content { padding: 18px 14px 28px; }

            .topbar { padding: 12px 14px; }

            .form-grid { grid-template-columns: 1fr; }

            .metric-grid { grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)) !important; }

            .page-header { flex-direction: column; align-items: flex-start !important; gap: 12px; }

            .filter-bar { flex-direction: column; align-items: stretch; }
        }

        @media (max-width: 480px) {
            .metric-grid { grid-template-columns: 1fr !important; }
            .topbar-date { display: none; }
        }
    </style>
</head>
<body>

    <!-- Mobile sidebar overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon">
                <svg width="16" height="16" fill="none" stroke="#fff" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="logo-text">AttendEase</span>
        </div>

        <ul class="sidebar-nav">
            <li class="nav-section-label">Main</li>
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                Dashboard
            </a>

            <li class="nav-section-label">Manage</li>
            <a href="{{ route('admin.employees.index') }}" class="nav-item {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Employees
            </a>
            <a href="{{ route('admin.attendances.index') }}" class="nav-item {{ request()->routeIs('admin.attendances.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                Attendance Logs
            </a>

            <li class="nav-section-label">Reports</li>
            <a href="{{ route('admin.reports.daily') }}" class="nav-item {{ request()->routeIs('admin.reports.daily') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Daily Report
            </a>
            <a href="{{ route('admin.reports.monthly') }}" class="nav-item {{ request()->routeIs('admin.reports.monthly') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Monthly Report
            </a>
        </ul>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-role">Administrator</div>
                </div>
            </div>
            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Sign out
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="main-wrap">
        <header class="topbar">
            <div style="display: flex; align-items: center; gap: 12px;">
                <button class="mobile-toggle" onclick="toggleSidebar()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <span class="topbar-title">@yield('topbar_title', 'Admin Panel')</span>
            </div>
            <div class="topbar-right">
                <span class="topbar-date">{{ now()->format('l, d M Y') }}</span>
            </div>
        </header>

        <main class="main-content">
            @if(session('success'))
                <div class="flash-alert flash-success">
                    <span class="flash-dot" style="background: #34d399;"></span>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="flash-alert flash-error">
                    <span class="flash-dot" style="background: #ef4444;"></span>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="page-footer">
            &copy; {{ date('Y') }} Employee Attendance Management System &middot; Admin Panel
        </footer>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebarOverlay').classList.remove('show');
        }
    </script>
</body>
</html>
