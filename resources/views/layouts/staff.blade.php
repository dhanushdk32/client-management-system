<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'RORIRI - Staff Portal')</title>
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
            --text-dark: #1e293b;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Outfit', sans-serif;
            background-color: var(--body-bg);
            color: var(--text-dark);
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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
        }

        .user-profile-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            padding: 4px 8px;
            border-radius: 8px;
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
        }

        /* Sidebar */
        .roriri-sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            padding: 16px 0;
            flex-shrink: 0;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0 12px;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 4px;
            flex: 1;
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

        .main-workspace {
            flex: 1;
            padding: 24px 30px;
            overflow-y: auto;
        }

        .card {
            border: 1px solid #f1f5f9;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.02);
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

        body.dark-theme .topbar-icon-btn {
            background-color: #334155;
            color: #f1f5f9;
            border-color: #475569;
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

        body.dark-theme .bg-light {
            background-color: #334155 !important;
        }

        body.dark-theme .text-dark {
            color: #f8fafc !important;
        }

        body.dark-theme .text-muted {
            color: #94a3b8 !important;
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
        <div class="brand-section">
            <a href="{{ route('staff.dashboard') }}" class="brand-logo">
                <div class="brand-logo-icon">
                    <img src="{{ asset('images/roriri_logo.png') }}" alt="RORIRI" width="30" height="30" style="border-radius: 50%; object-fit: cover;">
                </div>
                <h1 class="brand-logo-text">RORIRI</h1>
            </a>
            <span class="badge bg-primary-subtle text-primary border rounded-pill px-3 py-1 fw-semibold">Staff Console</span>
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
                        <i class="fa-solid fa-building"></i>
                        <span>Clients & Projects</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('staff.tickets.index') }}" class="menu-link {{ request()->routeIs('staff.tickets.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-ticket"></i>
                        <span>Support Tickets</span>
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
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100 btn-sm rounded-3 py-2">
                        <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Workspace Area -->
        <main class="main-workspace">
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
        });
    </script>
    @yield('scripts')
    @stack('scripts')
</body>
</html>
