<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - Client Portal')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Inter', 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f4f6fb;
            color: #333;
            overflow-x: hidden;
        }
        /* Sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #1e293b;
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }
        .sidebar-header {
            padding: 20px;
            display: flex;
            align-items: center;
            font-size: 20px;
            font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-header i {
            margin-right: 10px;
            color: #3b82f6;
        }
        .nav-links {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }
        .nav-links li {
            padding: 5px 20px;
        }
        .nav-links a {
            display: flex;
            align-items: center;
            color: #cbd5e1;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 8px;
            transition: all 0.2s;
            font-size: 14px;
        }
        .nav-links a:hover, .nav-links a.active {
            background-color: #3b82f6;
            color: #fff;
        }
        .nav-links i {
            margin-right: 15px;
            width: 20px;
            text-align: center;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 250px;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Header */
        .top-header {
            background: #fff;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-title {
            font-size: 20px;
            font-weight: 600;
            margin: 0;
        }
        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .user-profile img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
        }
        .user-name {
            font-size: 14px;
            font-weight: 500;
        }

        /* Content Area */
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        .content-area {
            padding: 30px;
            flex: 1;
            animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        
        /* Cards */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            margin-bottom: 24px;
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }
        
        .stat-card {
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        .stat-title {
            font-size: 13px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
        }
        .stat-value {
            font-size: 28px;
            font-weight: 700;
            margin: 10px 0;
            color: #0f172a;
        }
        .stat-trend {
            font-size: 13px;
            font-weight: 500;
        }
        .stat-trend.positive { color: #10b981; }
        .stat-trend.negative { color: #ef4444; }
        
        .stat-card.blue .stat-value { color: #3b82f6; }
        .stat-card.green .stat-value { color: #10b981; }
        .stat-card.orange .stat-value { color: #f59e0b; }
        .stat-card.red .stat-value { color: #ef4444; }
        
        /* Table Rows */
        .table-hover tbody tr {
            transition: all 0.2s ease;
        }
        .table-hover tbody tr:hover {
            background-color: #f8fafc;
            transform: scale(1.002);
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        }

        /* Buttons & Links */
        .btn {
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .btn:active {
            transform: scale(0.95);
        }
        
        /* Sidebar Links Animation */
        .nav-links li {
            animation: slideInRight 0.4s ease forwards;
            opacity: 0;
        }
        .nav-links li:nth-child(1) { animation-delay: 0.1s; }
        .nav-links li:nth-child(2) { animation-delay: 0.15s; }
        .nav-links li:nth-child(3) { animation-delay: 0.2s; }
        .nav-links li:nth-child(4) { animation-delay: 0.25s; }
        .nav-links li:nth-child(5) { animation-delay: 0.3s; }
        .nav-links li:nth-child(6) { animation-delay: 0.35s; }
        .nav-links li:nth-child(7) { animation-delay: 0.4s; }
        .nav-links li:nth-child(8) { animation-delay: 0.45s; }

    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <i class="fa-solid fa-shield-halved"></i>
            Admin Portal
        </div>
        <ul class="nav-links">
            <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fa-solid fa-chart-pie"></i> Dashboard</a></li>
            <li><a href="{{ route('admin.clients.index') }}" class="{{ request()->routeIs('admin.clients.*') ? 'active' : '' }}"><i class="fa-solid fa-users"></i> Clients</a></li>

            <li><a href="{{ route('admin.services.index') }}" class="{{ request()->routeIs('admin.services.*') ? 'active' : '' }}"><i class="fa-solid fa-briefcase"></i> Services</a></li>
            <li><a href="{{ route('admin.documents.index') }}" class="{{ request()->routeIs('admin.documents.*') ? 'active' : '' }}"><i class="fa-solid fa-file-lines"></i> Documents</a></li>
            <li><a href="{{ route('admin.tickets.index') }}" class="{{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}"><i class="fa-solid fa-ticket"></i> Requests / Tickets</a></li>
            <li><a href="{{ route('admin.notifications.index') }}" class="{{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}"><i class="fa-regular fa-bell"></i> Notifications</a></li>
            <li><a href="{{ route('admin.reports.index') }}" class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"><i class="fa-solid fa-chart-line"></i> Reports</a></li>
            <li><a href="{{ route('admin.activity.index') }}" class="{{ request()->routeIs('admin.activity.*') ? 'active' : '' }}"><i class="fa-solid fa-clock-rotate-left"></i> Activity Logs</a></li>
            <li><a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"><i class="fa-solid fa-gear"></i> Settings</a></li>
        </ul>
        <div style="position: absolute; bottom: 20px; width: 100%; padding: 0 20px;">
            <button type="button" class="btn btn-link text-decoration-none text-light p-0" style="font-size: 14px;" data-bs-toggle="modal" data-bs-target="#logoutModal">
                <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Logout
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="top-header">
            <h2 class="header-title">@yield('page_title', 'Dashboard')</h2>
            <div class="user-profile">
                <span class="user-name">{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</span>
                <i class="fa-solid fa-circle-user fa-2x text-muted"></i>
            </div>
        </div>

        <!-- Content -->
        <div class="content-area">
            @yield('content')
        </div>
    </div>

    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow">
                <div class="modal-body text-center p-4">
                    <div class="mb-3">
                        <i class="fa-solid fa-arrow-right-from-bracket text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Ready to Leave?</h5>
                    <p class="text-muted small mb-4">Are you sure you want to log out of the system?</p>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">No</button>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger px-4">Confirm</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals Stack -->
    @stack('modals')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    @yield('scripts')
    @stack('scripts')
</body>
</html>
