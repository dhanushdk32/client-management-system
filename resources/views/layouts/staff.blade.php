<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Staff Portal - IT Operations')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
            margin: 0;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }
        
        /* Sidebar Styling */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #1e1b4b 0%, #312e81 100%);
            color: #fff;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 20px rgba(0,0,0,0.08);
            z-index: 100;
        }
        .sidebar-header {
            padding: 24px 20px;
            font-size: 19px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .sidebar-header i {
            color: #818cf8;
        }
        .nav-links {
            list-style: none;
            padding: 20px 12px;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex: 1;
            overflow-y: auto;
        }
        .nav-links li a {
            color: #cbd5e1;
            text-decoration: none;
            padding: 12px 16px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .nav-links li a:hover {
            color: #fff;
            background: rgba(255,255,255,0.1);
            transform: translateX(4px);
        }
        .nav-links li a.active {
            color: #fff;
            background: #4f46e5;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
            font-weight: 600;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }
        .top-header {
            height: 70px;
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        .content-area {
            padding: 30px;
            flex: 1;
            animation: fadeIn 0.4s ease forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.03);
            margin-bottom: 24px;
        }
        .btn-primary {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }
        .btn-primary:hover {
            background-color: #4338ca;
            border-color: #4338ca;
        }
        .text-primary {
            color: #4f46e5 !important;
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <i class="fa-solid fa-code"></i>
            <span>Staff Console</span>
        </div>
        <ul class="nav-links">
            <li>
                <a href="{{ route('staff.dashboard') }}" class="{{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('staff.clients.index') }}" class="{{ request()->routeIs('staff.clients.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-building"></i> Clients & Projects
                </a>
            </li>
            <li>
                <a href="{{ route('staff.tickets.index') }}" class="{{ request()->routeIs('staff.tickets.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-ticket"></i> Support Tickets
                </a>
            </li>
            <li>
                <a href="{{ route('staff.settings') }}" class="{{ request()->routeIs('staff.settings*') ? 'active' : '' }}">
                    <i class="fa-solid fa-gear"></i> My Settings
                </a>
            </li>
        </ul>
        
        <div class="p-3 border-top border-white border-opacity-10">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2 overflow-hidden">
                    <div class="rounded-circle bg-indigo-500 text-white bg-opacity-25 d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px; background-color: #4f46e5;">
                        {{ strtoupper(substr(Auth::guard('staff')->user()->name ?? 'ST', 0, 2)) }}
                    </div>
                    <div class="text-truncate">
                        <div class="small fw-bold text-white text-truncate">{{ Auth::guard('staff')->user()->name ?? 'Staff User' }}</div>
                        <div class="text-white-50 small text-truncate" style="font-size: 11px;">{{ Auth::guard('staff')->user()->designation ?? 'Team Member' }}</div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-link text-white-50 p-1 hover-text-white" title="Logout">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Header -->
        <div class="top-header">
            <div>
                <h5 class="fw-bold mb-0">@yield('page_title', 'Staff Operations')</h5>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-indigo-subtle text-primary border px-3 py-2 rounded-pill">
                    <i class="fa-solid fa-briefcase me-1"></i> {{ Auth::guard('staff')->user()->department ?? 'IT Team' }}
                </span>
                <span class="badge bg-success-subtle text-success border px-3 py-2 rounded-pill">
                    <i class="fa-solid fa-circle me-1 small"></i> Online
                </span>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            @yield('content')
        </div>
    </div>

    <!-- Modals Stack -->
    @stack('modals')

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
    @stack('scripts')
</body>
</html>
