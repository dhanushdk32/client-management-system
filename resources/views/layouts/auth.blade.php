<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login - Client Portal')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background-color: #f4f6fb;
            font-family: 'Inter', 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .auth-card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            overflow: hidden;
            background: #fff;
            max-width: 900px;
            width: 100%;
        }
        .auth-left {
            padding: 50px 40px;
        }
        .auth-right {
            background-color: #eef2ff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 50px;
            text-align: center;
        }
        .logo-icon {
            color: #2b3a8c;
            font-size: 24px;
        }
        .brand-text {
            color: #2b3a8c;
            font-weight: 700;
            font-size: 22px;
            margin-left: 10px;
        }
        .btn-primary {
            background-color: #3b5998;
            border-color: #3b5998;
            padding: 10px;
            font-weight: 500;
        }
        .btn-primary:hover {
            background-color: #2b3a8c;
            border-color: #2b3a8c;
        }
        .form-control {
            padding: 12px;
            border-radius: 8px;
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #3b5998;
        }
        a {
            text-decoration: none;
            color: #3b5998;
        }
        a:hover {
            color: #2b3a8c;
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