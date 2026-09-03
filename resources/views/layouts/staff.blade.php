<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RORIRI Software Solutions</title>

    <!-- Permanent Brand Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/roriri_logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/roriri_logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/roriri_logo.png') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts: Plus Jakarta Sans / Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --roriri-blue: #0284c7;
            --roriri-blue-dark: #0369a1;
            --roriri-blue-light: #e0f2fe;
            --sidebar-bg: #ffffff;
            --body-bg: #f8fafc;
            --card-border: #f1f5f9;
            --text-dark: #1e293b;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Outfit', sans-serif;
            background-color: var(--body-bg);
            color: var(--text-dark);
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Top Header Navbar */
        .roriri-topbar {
            height: 68px;
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            position: sticky;
            top: 0;
            z-index: 1050;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }

        .brand-section {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .brand-logo-text {
            font-size: 22px;
            font-weight: 800;
            color: #0284c7;
            letter-spacing: 0.5px;
            margin: 0;
            font-family: 'Outfit', sans-serif;
        }

        .sidebar-toggle-btn {
            background: none;
            border: none;
            font-size: 18px;
            color: #0284c7;
            cursor: pointer;
            padding: 6px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }

        .sidebar-toggle-btn:hover {
            background-color: #f1f5f9;
        }

        .user-profile-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            padding: 4px 8px;
            border-radius: 8px;
            transition: background 0.2s;
        }

        .user-profile-badge:hover {
            background: #f8fafc;
        }

        .user-avatar-circle {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background-color: #e0f2fe;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0284c7;
            font-weight: 700;
            font-size: 14px;
        }

        /* App Container */
        .app-container {
            display: flex;
            flex: 1;
            position: relative;
        }

        /* Sidebar */
        .roriri-sidebar {
            width: 260px;
            min-width: 260px;
            max-width: 260px;
            background-color: var(--sidebar-bg);
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            padding: 16px 0;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), min-width 0.3s cubic-bezier(0.4, 0, 0.2, 1), max-width 0.3s cubic-bezier(0.4, 0, 0.2, 1), padding 0.3s ease;
            z-index: 1000;
            flex-shrink: 0;
            flex-grow: 0;
            position: sticky;
            top: 68px;
            height: calc(100vh - 68px);
            overflow-y: auto;
            overflow-x: hidden;
            box-sizing: border-box;
        }

        .roriri-sidebar.collapsed {
            width: 0 !important;
            min-width: 0 !important;
            max-width: 0 !important;
            padding: 0 !important;
            overflow: hidden !important;
            border-right: none !important;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0 12px;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 4px;
            overflow-y: auto;
            flex: 1;
        }

        .sidebar-menu .menu-item {
            margin-bottom: 2px;
        }

        .sidebar-menu .menu-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 10px;
            color: #475569;
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .sidebar-menu .menu-link:hover {
            color: var(--roriri-blue);
            background-color: #f8fafc;
        }

        .sidebar-menu .menu-link.active {
            color: var(--roriri-blue);
            background-color: var(--roriri-blue-light);
            font-weight: 600;
        }

        .sidebar-menu .menu-link i {
            font-size: 16px;
            width: 20px;
            text-align: center;
            color: #64748b;
        }

        .sidebar-menu .menu-link.active i {
            color: var(--roriri-blue);
        }

        .main-workspace {
            flex: 1;
            min-width: 0;
            padding: 24px 30px;
            background-color: var(--body-bg);
            overflow-x: hidden;
        }

        /* Metric Cards */
        .stat-card-roriri {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.02);
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            height: 100%;
        }

        .stat-card-roriri:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.04);
        }

        .stat-card-label {
            font-size: 13px;
            font-weight: 500;
            color: #64748b;
            margin-bottom: 6px;
        }

        .stat-card-value {
            font-size: 24px;
            font-weight: 800;
            color: #1e293b;
            font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .stat-icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .bg-icon-yellow { background-color: #fffbeb; color: #d97706; }
        .bg-icon-cyan { background-color: #e0f2fe; color: #0284c7; }
        .bg-icon-amber { background-color: #fefce8; color: #ca8a04; }
        .bg-icon-blue { background-color: #eff6ff; color: #2563eb; }
        .bg-icon-teal { background-color: #f0fdf4; color: #16a34a; }
        .bg-icon-green { background-color: #dcfce7; color: #16a34a; }
        .bg-icon-red { background-color: #fef2f2; color: #ef4444; }
        .bg-icon-purple { background-color: #f5f3ff; color: #7c3aed; }
        .bg-icon-gray { background-color: #f1f5f9; color: #64748b; }

        .card {
            border: 1px solid #f1f5f9;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.02);
            background-color: #ffffff;
            margin-bottom: 24px;
        }

        .btn-primary {
            background-color: #0284c7;
            border-color: #0284c7;
        }

        .btn-primary:hover {
            background-color: #0369a1;
            border-color: #0369a1;
        }

        .text-primary {
            color: #0284c7 !important;
        }

        /* Red Logout Button */
        .sidebar-logout-btn {
            border: 1.5px solid #ef4444;
            color: #ef4444;
            background: #ffffff;
            font-weight: 600;
            border-radius: 10px;
            padding: 10px 16px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .sidebar-logout-btn:hover {
            background-color: #fef2f2;
            color: #dc2626;
            border-color: #dc2626;
        }

        /* Dark Theme Styles */
        body.dark-theme {
            background-color: #0f172a;
            color: #f1f5f9;
        }

        body.dark-theme .roriri-topbar {
            background-color: #1e293b;
            border-bottom-color: #334155;
        }

        body.dark-theme .brand-logo-text {
            color: #38bdf8;
        }

        body.dark-theme .topbar-icon-btn,
        body.dark-theme .sidebar-toggle-btn {
            background-color: #334155;
            color: #f1f5f9;
            border-color: #475569;
        }

        body.dark-theme .topbar-icon-btn:hover,
        body.dark-theme .sidebar-toggle-btn:hover {
            background-color: #475569;
            color: #38bdf8;
        }

        body.dark-theme .user-profile-badge {
            background-color: #334155;
            border-color: #475569;
        }

        body.dark-theme .roriri-sidebar {
            background-color: #1e293b;
            border-right-color: #334155;
        }

        body.dark-theme .menu-link {
            color: #cbd5e1;
        }

        body.dark-theme .menu-link:hover {
            background-color: #334155;
            color: #38bdf8;
        }

        body.dark-theme .menu-link.active {
            background-color: rgba(56, 189, 248, 0.15);
            color: #38bdf8;
        }

        body.dark-theme .main-workspace {
            background-color: #0f172a;
        }

        body.dark-theme .card,
        body.dark-theme .stat-card-roriri {
            background-color: #1e293b;
            border-color: #334155;
            color: #f8fafc;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
        }

        body.dark-theme .stat-card-value {
            color: #f8fafc;
        }

        body.dark-theme .stat-card-label {
            color: #94a3b8;
        }

        body.dark-theme .table {
            color: #f1f5f9;
            border-color: #334155;
        }

        body.dark-theme .table-light,
        body.dark-theme thead.table-light tr,
        body.dark-theme thead.table-light th {
            background-color: #334155 !important;
            color: #cbd5e1 !important;
            border-color: #475569;
        }

        body.dark-theme .table-hover>tbody>tr:hover>* {
            background-color: #334155;
            color: #ffffff;
        }

        body.dark-theme .form-control,
        body.dark-theme .form-select {
            background-color: #0f172a !important;
            border-color: #475569 !important;
            color: #f8fafc !important;
        }

        body.dark-theme .dropdown-menu {
            background-color: #1e293b;
            border-color: #334155;
        }

        body.dark-theme .dropdown-item {
            color: #cbd5e1;
        }

        body.dark-theme .dropdown-item:hover {
            background-color: #334155;
            color: #38bdf8;
        }

        body.dark-theme .roriri-footer {
            background-color: #1e293b;
            border-top-color: #334155;
            color: #94a3b8;
        }

        body.dark-theme .border-top,
        body.dark-theme .border-bottom,
        body.dark-theme .border {
            border-color: #334155 !important;
        }

        body.dark-theme .bg-light {
            background-color: #334155 !important;
        }

        body.dark-theme .text-dark {
            color: #f8fafc !important;
        }

        body.dark-theme .text-muted,
        body.dark-theme .text-secondary {
            color: #94a3b8 !important;
        }

        body.dark-theme .modal-content {
            background-color: #1e293b;
            color: #f8fafc;
            border-color: #334155;
        }

        body.dark-theme .modal-header,
        body.dark-theme .modal-footer {
            border-color: #334155;
        }

        body.dark-theme .sidebar-logout-btn {
            background-color: #1e293b;
        }

        .roriri-footer {
            background-color: #ffffff;
            border-top: 1px solid #e2e8f0;
            padding: 16px 24px;
            text-align: center;
            font-size: 13px;
            color: #64748b;
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- RORIRI Topbar -->
    <header class="roriri-topbar">
        <div class="brand-section" style="width: auto; min-width: 260px;">
            <button class="sidebar-toggle-btn me-2" id="sidebarToggle" title="Toggle Navigation Sidebar">
                <i class="fa-solid fa-bars"></i>
            </button>
            <a href="{{ route('staff.dashboard') }}" class="brand-logo">
                <div class="brand-logo-icon">
                    <img src="{{ \App\Models\SystemSetting::getBrandLogoUrl() }}" alt="{{ \App\Models\SystemSetting::get('brand_name', 'RORIRI') }}" width="32" height="32" style="border-radius: 50%; object-fit: contain;">
                </div>
                <h1 class="brand-logo-text" style="font-size: 19px; font-weight: 800; color: #0284c7; white-space: nowrap; margin: 0;">
                    {{ \App\Models\SystemSetting::get('brand_name', 'RORIRI') }} <span style="font-size: 12.5px; font-weight: 600; color: #64748b; letter-spacing: 0;">{{ \App\Models\SystemSetting::get('brand_tagline', 'Software Solutions') }}</span>
                </h1>
            </a>
            <span class="badge bg-primary-subtle text-primary border rounded-pill px-3 py-1 fw-semibold ms-2 d-none d-md-inline-block">Staff Console</span>
        </div>

        <div class="d-flex align-items-center gap-3">
            <!-- Theme Toggle Icon -->
            <button type="button" class="btn btn-sm btn-light border rounded-circle" title="Toggle Dark/Light Theme" id="staffThemeToggleBtn" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                <i class="fa-regular fa-moon" id="staffThemeIcon"></i>
            </button>

            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
                <i class="fa-solid fa-briefcase me-1 text-primary"></i> {{ Auth::guard('staff')->user()->department ?? 'IT Team' }}
            </span>
            <div class="dropdown">
                <a href="#" class="user-profile-badge" data-bs-toggle="dropdown">
                    <div class="user-avatar-circle">
                        {{ strtoupper(substr(Auth::guard('staff')->user()->name ?? 'ST', 0, 2)) }}
                    </div>
                    <div class="d-flex flex-column text-start">
                        <span class="fw-bold text-dark small">{{ Auth::guard('staff')->user()->name ?? 'Staff User' }}</span>
                        <span class="text-muted" style="font-size: 11px;">{{ Auth::guard('staff')->user()->designation ?? 'Team Member' }}</span>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 p-2 rounded-3">
                    <li><a class="dropdown-item py-2 rounded-2" href="{{ route('staff.settings') }}"><i class="fa-solid fa-gear me-2 text-muted"></i> Profile Settings</a></li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <button type="button" class="dropdown-item py-2 rounded-2 text-danger" data-bs-toggle="modal" data-bs-target="#logoutConfirmModal">
                            <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Logout
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- App Container -->
    <div class="app-container">
        <!-- RORIRI White Sidebar -->
        <aside class="roriri-sidebar">
            <ul class="sidebar-menu">
                <li class="menu-item">
                    <a href="{{ route('staff.dashboard') }}" class="menu-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-house"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('staff.clients.index') }}" class="menu-link {{ request()->routeIs('staff.clients.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-users"></i>
                        <span>Clients</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('staff.tickets.index') }}" class="menu-link {{ request()->routeIs('staff.tickets.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-ticket"></i>
                        <span>Support Tickets</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('staff.reports.index') }}" class="menu-link {{ request()->routeIs('staff.reports.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-line"></i>
                        <span>Reports</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('staff.settings') }}" class="menu-link {{ request()->routeIs('staff.settings*') ? 'active' : '' }}">
                        <i class="fa-solid fa-gear"></i>
                        <span>My Settings</span>
                    </a>
                </li>
            </ul>

            <div class="p-3 border-top mt-auto">
                <button type="button" class="btn btn-outline-danger w-100 btn-sm rounded-3 py-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#logoutConfirmModal">
                    <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Logout
                </button>
            </div>
        </aside>

        <!-- Main Workspace Area -->
        <main class="main-workspace">
            @yield('content')
        </main>
    </div>

    <!-- RORIRI Footer -->
    <footer class="roriri-footer">
        Copyright &copy; {{ date('Y') }} {{ \App\Models\SystemSetting::get('company_name', 'RORIRI Software Solutions') }}. All rights reserved.
    </footer>

    <!-- Universal Logout Confirmation Modal -->
    <div class="modal fade" id="logoutConfirmModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-body text-center p-4">
                    <div class="rounded-circle bg-danger-subtle text-danger d-inline-flex align-items-center justify-content-center mb-3" style="width: 54px; height: 54px; font-size: 22px;">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </div>
                    <h5 class="fw-bold mb-1 text-dark">Confirm Logout</h5>
                    <p class="text-muted small mb-4">Are you sure you want to log out of your account?</p>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light border w-100 rounded-3 py-2 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                        <form action="{{ route('logout') }}" method="POST" class="w-100 m-0">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100 rounded-3 py-2 fw-semibold">Yes, Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals Stack -->
    @stack('modals')
    @yield('modals')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function applySavedTheme() {
            const savedTheme = localStorage.getItem('roriri_theme') || 'light';
            const themeIcon = document.getElementById('staffThemeIcon');
            
            if (savedTheme === 'dark') {
                document.body.classList.add('dark-theme');
                document.documentElement.setAttribute('data-bs-theme', 'dark');
                if (themeIcon) {
                    themeIcon.classList.remove('fa-moon', 'fa-regular');
                    themeIcon.classList.add('fa-sun', 'fa-solid', 'text-warning');
                }
            } else {
                document.body.classList.remove('dark-theme');
                document.documentElement.setAttribute('data-bs-theme', 'light');
                if (themeIcon) {
                    themeIcon.classList.remove('fa-sun', 'fa-solid', 'text-warning');
                    themeIcon.classList.add('fa-moon', 'fa-regular');
                }
            }
        }
        applySavedTheme();

        document.addEventListener('DOMContentLoaded', function() {
            applySavedTheme();
            const themeToggleBtn = document.getElementById('staffThemeToggleBtn');
            if (themeToggleBtn) {
                themeToggleBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const isDark = document.body.classList.contains('dark-theme');
                    const newTheme = isDark ? 'light' : 'dark';
                    localStorage.setItem('roriri_theme', newTheme);
                    applySavedTheme();
                });
            }

            // Steady Sidebar Collapse Toggle
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.querySelector('.roriri-sidebar');
            if (sidebarToggle && sidebar) {
                if (localStorage.getItem('roriri_staff_sidebar_collapsed') === 'true') {
                    sidebar.classList.add('collapsed');
                }

                sidebarToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    sidebar.classList.toggle('collapsed');
                    localStorage.setItem('roriri_staff_sidebar_collapsed', sidebar.classList.contains('collapsed'));
                });
            }
        });
    </script>
    @yield('scripts')
    @stack('scripts')
</body>
</html>
