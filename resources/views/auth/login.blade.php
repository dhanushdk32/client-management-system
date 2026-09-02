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
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --roriri-blue: #0284c7;
            --roriri-blue-dark: #0369a1;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            margin: 0;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06), 0 1px 3px rgba(0, 0, 0, 0.04);
            border: 1px solid #f1f5f9;
            padding: 36px 32px;
            overflow: hidden;
            position: relative;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #0284c7 0%, #10b981 50%, #f59e0b 100%);
        }

        .brand-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .brand-logo-img {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 12px;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.15);
        }

        .brand-title {
            font-family: 'Outfit', sans-serif;
            font-size: 24px;
            font-weight: 800;
            color: #0284c7;
            letter-spacing: 0.5px;
            margin: 0;
        }

        .brand-subtitle {
            font-size: 13.5px;
            color: #64748b;
            margin-top: 4px;
        }

        .role-hint-pill {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 12px;
            color: #475569;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 14px;
            border: 1px solid #cbd5e1;
            font-size: 14px;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: #0284c7;
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
        }

        .input-group-text {
            background-color: transparent;
            border-color: #cbd5e1;
            color: #94a3b8;
            cursor: pointer;
        }

        .btn-submit {
            background-color: #0284c7;
            border: none;
            color: #ffffff;
            font-weight: 700;
            font-size: 15px;
            padding: 12px;
            border-radius: 10px;
            width: 100%;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
        }

        .btn-submit:hover {
            background-color: #0369a1;
            transform: translateY(-1px);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .forgot-link {
            font-size: 13px;
            color: #0284c7;
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .portal-badges {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #f1f5f9;
        }

        .portal-badge-item {
            font-size: 11.5px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 6px;
            background-color: #f1f5f9;
            color: #475569;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <!-- Logo & Header -->
        <div class="brand-header">
            <img src="{{ asset('images/roriri_logo.png') }}" alt="RORIRI Logo" class="brand-logo-img">
            <h1 class="brand-title">RORIRI</h1>
            <p class="brand-subtitle">Unified Portal Login (Admin &bull; Staff &bull; Client)</p>
        </div>

        <div class="role-hint-pill">
            <i class="fa-solid fa-shield-halved text-primary"></i>
            <span>Auto-detects your account role automatically</span>
        </div>

        @if(session('success'))
            <div class="alert alert-success py-2 px-3 small rounded-3 mb-3">
                <i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger py-2 px-3 small rounded-3 mb-3">
                <i class="fa-solid fa-circle-exclamation me-1"></i> {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger py-2 px-3 small rounded-3 mb-3">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST" autocomplete="off">
            @csrf

            <!-- Email Input -->
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-group">
                    <input type="email" name="email" id="email" class="form-control" placeholder="name@company.com" value="{{ old('email') }}" autocomplete="off" required autofocus>
                </div>
            </div>

            <!-- Password Input -->
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label for="password" class="form-label mb-0">Password</label>
                    <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                </div>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" autocomplete="new-password" required>
                    <button class="btn btn-outline-secondary input-group-text" type="button" id="togglePassword">
                        <i class="fa-regular fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label text-muted small" for="remember">
                    Keep me signed in
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-submit">
                <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Sign In to Portal
            </button>
        </form>

        <!-- Supported Roles Badges -->
        <div class="portal-badges">
            <span class="portal-badge-item"><i class="fa-solid fa-user-shield me-1 text-primary"></i> Admin</span>
            <span class="portal-badge-item"><i class="fa-solid fa-user-gear me-1 text-info"></i> Staff</span>
            <span class="portal-badge-item"><i class="fa-solid fa-building me-1 text-success"></i> Client</span>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const passwordField = document.getElementById('password');
            const emailField = document.getElementById('email');

            // Prevent browser password manager auto-fill override
            if (passwordField) {
                passwordField.value = '';
            }

            if (togglePassword && passwordField) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordField.setAttribute('type', type);
                    const icon = this.querySelector('i');
                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
                });
            }
        });
    </script>
</body>
</html>