<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', \App\Models\SystemSetting::get('company_name', 'Client Management System'))</title>

    <!-- Brand Favicon -->
    <link rel="icon" type="image/png" href="{{ \App\Models\SystemSetting::getBrandLogoUrl() }}">
    <link rel="shortcut icon" type="image/png" href="{{ \App\Models\SystemSetting::getBrandLogoUrl() }}">
    <link rel="apple-touch-icon" href="{{ \App\Models\SystemSetting::getBrandLogoUrl() }}">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Inter', 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .auth-card {
            border-radius: 12px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04), 0 1px 2px -1px rgba(0, 0, 0, 0.02);
            border: 1px solid #e2e8f0;
            overflow: hidden;
            background: #fff;
            max-width: 900px;
            width: 100%;
        }
        .auth-left {
            padding: 50px 40px;
        }
        .auth-right {
            background-color: #f8fafc;
            border-left: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 50px;
            text-align: center;
        }
        .logo-icon {
            color: #475569;
            font-size: 24px;
        }
        .brand-text {
            color: #0f172a;
            font-weight: 700;
            font-size: 22px;
            margin-left: 10px;
        }
        .btn-primary {
            background-color: #475569;
            border-color: #475569;
            padding: 10px;
            font-weight: 500;
            border-radius: 8px;
        }
        .btn-primary:hover {
            background-color: #334155;
            border-color: #334155;
        }
        .form-control {
            padding: 12px;
            border-radius: 8px;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            color: #0f172a;
        }
        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(71, 85, 105, 0.15);
            border-color: #475569;
        }
        a {
            text-decoration: none;
            color: #475569;
        }
        a:hover {
            color: #334155;
        }
    </style>
</head>

<body>
    <div class="container d-flex justify-content-center">
        @yield('content')
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>