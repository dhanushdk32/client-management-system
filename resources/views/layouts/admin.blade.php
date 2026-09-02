<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'RORIRI - Admin Management')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts: Plus Jakarta Sans / Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
            width: 250px;
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

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .topbar-icon-btn {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #475569;
            background: #ffffff;
            border: 1px solid transparent;
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 16px;
        }

        .topbar-icon-btn:hover {
            background: #f1f5f9;
            color: #0284c7;
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
            object-fit: cover;
            background-color: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0284c7;
            font-weight: 700;
            font-size: 14px;
        }

        .user-info-text {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .user-info-text .user-name {
            font-size: 14px;
            font-weight: 700;
            color: #1e293b;
        }

        .user-info-text .user-role {
            font-size: 12px;
            color: #64748b;
        }

        /* App Wrapper */
        .app-container {
            display: flex;
            flex: 1;
            position: relative;
        }

        /* Sidebar Styling */
        .roriri-sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            padding: 16px 0;
            transition: width 0.3s ease, transform 0.3s ease;
            z-index: 1000;
            flex-shrink: 0;
        }

        .roriri-sidebar.collapsed {
            width: 0;
            padding: 0;
            overflow: hidden;
            border: none;
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
            font-size: 14px;
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

        .menu-category-header {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            padding: 14px 14px 6px 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .menu-category-badge {
            background-color: #0284c7;
            color: #ffffff;
            font-size: 10px;
            font-weight: 800;
            padding: 2px 5px;
            border-radius: 4px;
            letter-spacing: 0;
        }

        /* Main Workspace Content */
        .main-workspace {
            flex: 1;
            padding: 24px 30px;
            background-color: var(--body-bg);
            overflow-y: auto;
        }

        /* Active Entity Tab Header (from Screenshot 2) */
        .active-entity-tab-pill {
            background-color: #9bd39b;
            border-radius: 10px;
            padding: 6px 14px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            margin-bottom: 24px;
        }

        .active-tab-logo-circle {
            width: 22px;
            height: 22px;
            background-color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(0,0,0,0.08);
        }

        .active-tab-logo-circle img {
            width: 16px;
            height: 16px;
            object-fit: contain;
        }

        .active-tab-text {
            font-size: 13.5px;
            font-weight: 600;
            color: #1e293b;
        }

        .active-tab-close {
            background: none;
            border: none;
            font-size: 15px;
            font-weight: 700;
            color: #1e293b;
            cursor: pointer;
            padding: 0 2px;
            line-height: 1;
            display: flex;
            align-items: center;
            opacity: 0.8;
            transition: opacity 0.2s;
        }

        .active-tab-close:hover {
            opacity: 1;
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

        body.dark-theme .user-name {
            color: #f8fafc;
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

        body.dark-theme .form-control::placeholder {
            color: #64748b;
        }

        body.dark-theme .input-group-text {
            background-color: #334155 !important;
            border-color: #475569 !important;
            color: #cbd5e1 !important;
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

        body.dark-theme .modal-content {
            background-color: #1e293b;
            color: #f8fafc;
            border: 1px solid #334155;
        }

        body.dark-theme .modal-header,
        body.dark-theme .modal-footer {
            border-color: #334155;
        }

        body.dark-theme .roriri-footer {
            background-color: #1e293b;
            border-top-color: #334155;
            color: #94a3b8;
        }

        body.dark-theme .border-top {
            border-top-color: #334155 !important;
        }
        body.dark-theme .border-bottom {
            border-bottom-color: #334155 !important;
        }
        body.dark-theme .border {
            border-color: #334155 !important;
        }

        body.dark-theme .bg-light {
            background-color: #334155 !important;
        }

        body.dark-theme .text-dark {
            color: #f8fafc !important;
        }

        body.dark-theme .text-muted {
            color: #94a3b8 !important;
        }

        body.dark-theme .sidebar-logout-btn {
            background-color: #1e293b;
        }

        /* Footer */
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
        <div class="brand-section">
            <a href="{{ route('admin.dashboard') }}" class="brand-logo">
                <div class="brand-logo-icon">
                    <img src="{{ asset('images/roriri_logo.png') }}" alt="RORIRI" width="30" height="30" style="border-radius: 50%; object-fit: cover;">
                </div>
                <h1 class="brand-logo-text">RORIRI</h1>
            </a>
            <button class="sidebar-toggle-btn" id="sidebarToggle" title="Toggle Navigation">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>

        <div class="topbar-right">
            <!-- Theme Toggle Icon -->
            <button type="button" class="topbar-icon-btn" title="Toggle Dark/Light Theme" id="themeToggleBtn" style="border:none; cursor:pointer;">
                <i class="fa-regular fa-moon" id="themeIcon"></i>
            </button>

            <!-- Quick App Grid Icon -->
            <a href="#" class="topbar-icon-btn" title="Quick Navigation" data-bs-toggle="dropdown">
                <i class="fa-solid fa-table-cells-large"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 p-2 rounded-3" style="width: 240px;">
                <li><a class="dropdown-item py-2 rounded-2" href="{{ route('admin.clients.index') }}"><i class="fa-solid fa-users text-primary me-2"></i> Client Management</a></li>
                <li><a class="dropdown-item py-2 rounded-2" href="{{ route('admin.staff.index') }}"><i class="fa-solid fa-user-gear text-info me-2"></i> Staff Team</a></li>
                <li><a class="dropdown-item py-2 rounded-2" href="{{ route('admin.tickets.index') }}"><i class="fa-solid fa-ticket text-warning me-2"></i> Support Desk</a></li>
                <li><a class="dropdown-item py-2 rounded-2" href="{{ route('admin.reports.index') }}"><i class="fa-solid fa-chart-line text-success me-2"></i> Analytics & Reports</a></li>
            </ul>

            <!-- Admin Profile -->
            <div class="dropdown">
                <a href="#" class="user-profile-badge" data-bs-toggle="dropdown">
                    <div class="user-avatar-circle">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>
                    <div class="user-info-text">
                        <span class="user-name">{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</span>
                        <span class="user-role">Admin</span>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 p-2 rounded-3">
                    <li><a class="dropdown-item py-2 rounded-2" href="{{ route('admin.settings.index') }}"><i class="fa-solid fa-gear me-2 text-muted"></i> Account Settings</a></li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="dropdown-item py-2 rounded-2 text-danger">
                                <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- App Container -->
    <div class="app-container">
        <!-- RORIRI Clean Sidebar matching Screenshot -->
        <aside class="roriri-sidebar" id="roririSidebar">
            <ul class="sidebar-menu">
                <!-- Dashboard -->
                <li class="menu-item">
                    <a href="{{ route('admin.dashboard') }}" class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-house"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Clients -->
                <li class="menu-item">
                    <a href="{{ route('admin.clients.index') }}" class="menu-link {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-building"></i>
                        <span>Clients</span>
                    </a>
                </li>

                <!-- Staff Team -->
                <li class="menu-item">
                    <a href="{{ route('admin.staff.index') }}" class="menu-link {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-user-tie"></i>
                        <span>Staff Team</span>
                    </a>
                </li>

                <!-- Services -->
                <li class="menu-item">
                    <a href="{{ route('admin.services.index') }}" class="menu-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-briefcase"></i>
                        <span>Services</span>
                    </a>
                </li>

                <!-- Documents -->
                <li class="menu-item">
                    <a href="{{ route('admin.documents.index') }}" class="menu-link {{ request()->routeIs('admin.documents.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-file-lines"></i>
                        <span>Documents</span>
                    </a>
                </li>

                <!-- Support Requests / Tickets -->
                <li class="menu-item">
                    <a href="{{ route('admin.tickets.index') }}" class="menu-link {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-ticket"></i>
                        <span>Requests / Tickets</span>
                    </a>
                </li>

                <!-- Notifications -->
                <li class="menu-item">
                    <a href="{{ route('admin.notifications.index') }}" class="menu-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-bell"></i>
                        <span>Notifications</span>
                    </a>
                </li>

                <!-- Reports -->
                <li class="menu-item">
                    <a href="{{ route('admin.reports.index') }}" class="menu-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-line"></i>
                        <span>Reports</span>
                    </a>
                </li>

                <!-- Activity Logs -->
                <li class="menu-item">
                    <a href="{{ route('admin.activity.index') }}" class="menu-link {{ request()->routeIs('admin.activity.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span>Activity Logs</span>
                    </a>
                </li>

                <!-- Settings -->
                <li class="menu-item">
                    <a href="{{ route('admin.settings.index') }}" class="menu-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-gear"></i>
                        <span>Settings</span>
                    </a>
                </li>
            </ul>

            <!-- Clean Logout Button matching Screenshot -->
            <div class="p-3 border-top mt-auto">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="sidebar-logout-btn">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Workspace Area -->
        <main class="main-workspace">
            <!-- Active Entity Tab (From Screenshot 2) -->
            <div class="active-entity-tab-pill">
                <div class="active-tab-logo-circle">
                    <img src="{{ asset('images/roriri_logo.png') }}" alt="Logo">
                </div>
                <span class="active-tab-text">RORIRI Software Solution</span>
                <button type="button" class="active-tab-close" title="Close Tab">&times;</button>
            </div>

            @yield('content')
        </main>
    </div>

    <!-- RORIRI Footer -->
    <footer class="roriri-footer">
        Copyright &copy; 2026. All right reserved.
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Dark Mode Logic with LocalStorage persistence
        function applySavedTheme() {
            const savedTheme = localStorage.getItem('roriri_theme') || 'light';
            const themeIcon = document.getElementById('themeIcon');
            
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

        // Apply theme immediately before render
        applySavedTheme();

        document.addEventListener('DOMContentLoaded', function() {
            applySavedTheme();

            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('roririSidebar');

            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                });
            }

            const themeToggleBtn = document.getElementById('themeToggleBtn');
            if (themeToggleBtn) {
                themeToggleBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const isDark = document.body.classList.contains('dark-theme');
                    const newTheme = isDark ? 'light' : 'dark';
                    localStorage.setItem('roriri_theme', newTheme);
                    applySavedTheme();
                });
            }
        });
    </script>
    @yield('scripts')
    @stack('scripts')
    @stack('modals')
</body>
</html>
