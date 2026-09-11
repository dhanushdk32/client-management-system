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
            background-color: #fee2e2;
            font-family: 'Inter', 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .auth-card {
            border-radius: 15px;
            box-shadow: 0 10px 35px rgba(127, 29, 29, 0.08);
            border: 1px solid #fecaca;
            overflow: hidden;
            background: #fff;
            max-width: 900px;
            width: 100%;
        }
        .auth-left {
            padding: 50px 40px;
        }
        .auth-right {
            background-color: #fef2f2;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 50px;
            text-align: center;
        }
        .logo-icon {
            color: #991b1b;
            font-size: 24px;
        }
        .brand-text {
            color: #991b1b;
            font-weight: 700;
            font-size: 22px;
            margin-left: 10px;
        }
        .btn-primary {
            background-color: #991b1b;
            border-color: #991b1b;
            padding: 10px;
            font-weight: 500;
        }
        .btn-primary:hover {
            background-color: #7f1d1d;
            border-color: #7f1d1d;
        }
        .form-control {
            padding: 12px;
            border-radius: 8px;
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(153, 27, 27, 0.15);
            border-color: #991b1b;
        }
        a {
            text-decoration: none;
            color: #991b1b;
        }
        a:hover {
            color: #7f1d1d;
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