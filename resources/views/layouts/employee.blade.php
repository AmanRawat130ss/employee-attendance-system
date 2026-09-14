<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Dashboard') — AttendEase Employee</title>

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
            width: 250px;
            min-height: 100vh;
            background: #18181b;
            color: #d1d5db;
            padding: 24px 18px 20px;
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
            gap: 12px;
            padding: 0 6px 22px;
            border-bottom: 1px solid #27272a;
            margin-bottom: 20px;
        }

        .sidebar-logo .logo-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #059669, #10b981);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.25);
        }

        .sidebar-logo .logo-text {
            font-size: 17px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.4px;
        }

        .sidebar-logo .portal-tag {
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
            padding: 3px 7px;
            border-radius: 5px;
            margin-left: auto;
        }

        .sidebar-nav {
            flex: 1;
            list-style: none;
        }

        .nav-section-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #71717a;
            padding: 0 12px;
            margin-bottom: 8px;
            margin-top: 22px;
        }

        .nav-section-label:first-child { margin-top: 0; }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 600;
            color: #a1a1aa;
            text-decoration: none;
            transition: all 0.15s;
            margin-bottom: 3px;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.07);
            color: #f4f4f5;
        }

        .nav-item.active {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
        }

        .nav-item svg {
            width: 19px;
            height: 19px;
            flex-shrink: 0;
            opacity: 0.85;
        }

        .nav-item.active svg { opacity: 1; }

        /* Sidebar Profile Card */
        .sidebar-emp-card {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 12px;
            padding: 14px 14px;
            margin: 10px 0 20px;
        }

        .sidebar-emp-row {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            margin-bottom: 6px;
        }

        .sidebar-emp-row:last-child { margin-bottom: 0; }
        .sidebar-emp-row .label { color: #71717a; font-weight: 500; }
        .sidebar-emp-row .val { color: #e4e4e7; font-weight: 600; }
        .sidebar-emp-row .val-green { color: #34d399; font-weight: 700; font-variant-numeric: tabular-nums; }

        .sidebar-footer {
            padding: 16px 6px 0;
            border-top: 1px solid #27272a;
            margin-top: auto;
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 11px;
            margin-bottom: 14px;
        }

        .sidebar-user .user-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #059669, #10b981);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .sidebar-user .user-info { overflow: hidden; }

        .sidebar-user .user-name {
            font-size: 13.5px;
            font-weight: 600;
            color: #f4f4f5;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-user .user-role {
            font-size: 11.5px;
            color: #71717a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .btn-logout {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            width: 100%;
            padding: 9px 12px;
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.16);
            border-radius: 9px;
            color: #fca5a5;
            font-size: 12.5px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.15s;
        }

        .btn-logout:hover {
            background: rgba(239, 68, 68, 0.2);
            color: #fee2e2;
            border-color: rgba(239, 68, 68, 0.3);
        }

        .btn-logout svg { width: 15px; height: 15px; }

        /* ---- Main Wrap ---- */
        .main-wrap {
            margin-left: 250px;
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            width: calc(100% - 250px);
            transition: margin-left 0.25s ease, width 0.25s ease;
        }

        .topbar {
            height: 60px;
            background: #fff;
            border-bottom: 1px solid #e7e5e0;
            padding: 0 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 30;
        }

        .topbar-title {
            font-size: 14px;
            font-weight: 600;
            color: #3f3f46;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .topbar-title .muted { color: #a1a1aa; }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .topbar-clock {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f8f7f4;
            border: 1px solid #e5e2db;
            border-radius: 9px;
            padding: 6px 14px;
            font-size: 12.5px;
            color: #3f3f46;
        }

        .topbar-clock .time {
            font-weight: 700;
            color: #18181b;
            font-variant-numeric: tabular-nums;
        }

        .main-content {
            flex: 1;
            padding: 28px 32px 40px;
        }

        .page-footer {
            padding: 16px 32px;
            border-top: 1px solid #e7e5e0;
            font-size: 12px;
            color: #71717a;
            text-align: center;
            background: #fff;
        }

        /* ---- Flash alerts ---- */
        .flash-alert {
            padding: 12px 18px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 500;
            margin-bottom: 22px;
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
            width: 7px;
            height: 7px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* ---- Cards ---- */
        .card {
            background: #fff;
            border: 1px solid #e5e2db;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
            margin-bottom: 24px;
        }

        .card-header {
            padding: 18px 24px;
            border-bottom: 1px solid #eeebe4;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
        }

        .card-header h2 {
            font-size: 16px;
            font-weight: 700;
            color: #18181b;
            letter-spacing: -0.2px;
        }

        .card-header p {
            font-size: 12.5px;
            color: #71717a;
            margin-top: 2px;
        }

        /* Table Responsive Container */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* ---- Tables ---- */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 680px;
        }

        .data-table thead th {
            background: #f9f8f5;
            padding: 12px 20px;
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #52525b;
            text-align: left;
            border-bottom: 1px solid #eeebe4;
        }

        .data-table tbody td {
            padding: 14px 20px;
            font-size: 13.5px;
            border-bottom: 1px solid #f4f2ec;
            color: #27272a;
            vertical-align: middle;
        }

        .data-table tbody tr:last-child td { border-bottom: none; }
        .data-table tbody tr:hover { background: #faf9f6; }

        .tabular-nums {
            font-variant-numeric: tabular-nums;
            font-weight: 600;
            color: #18181b;
        }

        .data-table .empty-state {
            text-align: center;
            padding: 44px 20px;
            color: #71717a;
            font-size: 13.5px;
        }

        /* ---- Status Badges ---- */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.2px;
        }

        .badge-present { background: #ecfdf5; color: #047857; }
        .badge-halfday { background: #fffbeb; color: #b45309; }
        .badge-leave   { background: #eef2ff; color: #4f46e5; }
        .badge-holiday { background: #f3f4f6; color: #52525b; }
        .badge-absent  { background: #fef2f2; color: #b91c1c; }

        /* Metric cards */
        .metric-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .metric-card {
            background: #fff;
            border: 1px solid #e5e2db;
            border-radius: 14px;
            padding: 18px 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        }

        .metric-card .metric-label {
            font-size: 12.5px;
            font-weight: 600;
            color: #71717a;
            margin-bottom: 6px;
        }

        .metric-card .metric-value {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.6px;
            color: #18181b;
            font-variant-numeric: tabular-nums;
        }

        .metric-card .metric-desc {
            font-size: 11.5px;
            color: #71717a;
            margin-top: 4px;
        }

        .metric-card.accent-blue .metric-value { color: #2563eb; }
        .metric-card.accent-green .metric-value { color: #059669; }
        .metric-card.accent-amber .metric-value { color: #d97706; }
        .metric-card.accent-purple .metric-value { color: #7c3aed; }

        /* Custom Pagination */
        .table-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 22px;
            border-top: 1px solid #eeebe4;
            background: #f9f8f5;
            font-size: 12.5px;
            color: #71717a;
            flex-wrap: wrap;
            gap: 12px;
        }

        .pagination-links {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .pg-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            height: 32px;
            padding: 0 10px;
            border-radius: 8px;
            background: #fff;
            border: 1px solid #d4d4d8;
            color: #27272a;
            font-size: 12.5px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s;
        }

        .pg-btn:hover {
            border-color: #059669;
            color: #059669;
            background: #f0fdf4;
        }

        .pg-btn.active {
            background: #059669;
            border-color: #059669;
            color: #fff;
            font-weight: 700;
        }

        .pg-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
            background: #f4f4f5;
        }

        /* Filter pill buttons */
        .filter-pills {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .filter-pill {
            padding: 6px 13px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            color: #52525b;
            background: #f4f4f5;
            text-decoration: none;
            border: 1px solid transparent;
            transition: all 0.15s;
        }

        .filter-pill:hover {
            background: #e4e4e7;
            color: #18181b;
        }

        .filter-pill.active {
            background: #059669;
            color: #fff;
            font-weight: 700;
        }

        /* Mobile sidebar */
        .mobile-toggle {
            display: none;
            padding: 8px;
            background: #fff;
            border: 1px solid #d4d4d8;
            border-radius: 8px;
            cursor: pointer;
        }

        .mobile-toggle svg { width: 20px; height: 20px; color: #27272a; }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(2px);
            z-index: 40;
        }

        /* ---- Responsive Design ---- */
        @media (max-width: 1024px) {
            .metric-grid { grid-template-columns: repeat(2, 1fr); }
            .main-content { padding: 24px 20px 32px; }
            .topbar { padding: 0 20px; }
        }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .sidebar-overlay.show { display: block; }
            .main-wrap { margin-left: 0; width: 100%; }
            .mobile-toggle { display: flex; }
            .main-content { padding: 18px 14px 28px; }
            .topbar { padding: 0 14px; }
            .metric-grid { grid-template-columns: 1fr; }
            .card-header { flex-direction: column; align-items: flex-start; }
            .filter-pills { width: 100%; overflow-x: auto; padding-bottom: 4px; }
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
                <svg width="18" height="18" fill="none" stroke="#fff" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="logo-text">AttendEase</span>
            <span class="portal-tag">Staff</span>
        </div>

        <ul class="sidebar-nav">
            <li class="nav-section-label">Navigation</li>
            <a href="#dashboardTop" id="navDashboard" class="nav-item active" onclick="switchNavTab('dashboard')">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>
            <a href="#attendanceHistory" id="navAttendance" class="nav-item" onclick="switchNavTab('attendance')">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                My Attendance
            </a>
            <a href="{{ route('employee.attendance.export') }}" class="nav-item" title="Download my attendance records as CSV">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Download Report
            </a>

            <li class="nav-section-label">My Details</li>
            <div class="sidebar-emp-card">
                <div class="sidebar-emp-row">
                    <span class="label">Emp ID</span>
                    <span class="val val-green">{{ Auth::user()->employee_id ?? 'EMP001' }}</span>
                </div>
                <div class="sidebar-emp-row">
                    <span class="label">Department</span>
                    <span class="val">{{ Auth::user()->department ?? 'Engineering' }}</span>
                </div>
                <div class="sidebar-emp-row">
                    <span class="label">Status</span>
                    <span class="val" style="color: #34d399;">Active Staff</span>
                </div>
            </div>
        </ul>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-role">{{ Auth::user()->designation ?? 'Employee' }}</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
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
                <button class="mobile-toggle" onclick="toggleSidebar()" aria-label="Toggle navigation">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div class="topbar-title">
                    <span class="muted">Employee Portal</span>
                    <span>/</span>
                    <span>@yield('title', 'My Dashboard')</span>
                </div>
            </div>
            <div class="topbar-right">
                <div class="topbar-clock">
                    <span style="color: #71717a;">{{ now()->format('D, d M Y') }}</span>
                    <span style="color: #d4d4d8;">&middot;</span>
                    <span class="time" id="topLiveClock">--:--:--</span>
                </div>
            </div>
        </header>

        <main class="main-content">
            @if(session('success'))
                <div class="flash-alert flash-success">
                    <span class="flash-dot" style="background: #10b981;"></span>
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
            &copy; {{ date('Y') }} Employee Attendance Management System &middot; AttendEase Portal
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

        function updateClock() {
            var now = new Date();
            var h = String(now.getHours()).padStart(2, '0');
            var m = String(now.getMinutes()).padStart(2, '0');
            var s = String(now.getSeconds()).padStart(2, '0');
            var topEl = document.getElementById('topLiveClock');
            if (topEl) topEl.textContent = h + ':' + m + ':' + s;
        }
        updateClock();
        setInterval(updateClock, 1000);

        function switchNavTab(tab) {
            var navDash = document.getElementById('navDashboard');
            var navAtt = document.getElementById('navAttendance');
            if (!navDash || !navAtt) return;

            if (tab === 'attendance') {
                navAtt.classList.add('active');
                navDash.classList.remove('active');
            } else {
                navDash.classList.add('active');
                navAtt.classList.remove('active');
            }

            if (window.innerWidth <= 768) {
                closeSidebar();
            }
        }

        // Auto update active link based on scroll position or URL
        function checkActiveSection() {
            var histCard = document.getElementById('attendanceHistory');
            var navDash = document.getElementById('navDashboard');
            var navAtt = document.getElementById('navAttendance');
            if (!histCard || !navDash || !navAtt) return;

            var rect = histCard.getBoundingClientRect();
            // If the top of attendance table card is within 250px of top of screen
            if (rect.top <= 250) {
                navAtt.classList.add('active');
                navDash.classList.remove('active');
            } else {
                navDash.classList.add('active');
                navAtt.classList.remove('active');
            }
        }

        window.addEventListener('scroll', checkActiveSection, { passive: true });

        window.addEventListener('DOMContentLoaded', function() {
            var urlParams = new URLSearchParams(window.location.search);
            var hash = window.location.hash;

            if (hash === '#attendanceHistory' || urlParams.has('page') || (urlParams.has('month') && urlParams.get('month') !== 'all')) {
                switchNavTab('attendance');
                if (urlParams.has('page') || urlParams.has('month')) {
                    setTimeout(function() {
                        var el = document.getElementById('attendanceHistory');
                        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, 150);
                }
            } else {
                switchNavTab('dashboard');
            }
        });

        window.addEventListener('hashchange', function() {
            if (window.location.hash === '#attendanceHistory') {
                switchNavTab('attendance');
            } else {
                switchNavTab('dashboard');
            }
        });
    </script>

    @yield('scripts')
</body>
</html>
