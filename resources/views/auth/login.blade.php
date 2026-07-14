<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - SDA-IRC Church Management System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
    <!-- DOMAIN LOGO -->
    <link rel="icon" type="image/jpeg" href="{{ asset('assets/images/logo.png') }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Hero background — same image as website home */
        .login-bg {
            position: fixed; inset: 0;
            background: url('https://images.unsplash.com/photo-1438232992991-995b7058bbb3?w=1920&q=80') center/cover no-repeat;
        }

        .login-overlay {
            position: fixed; inset: 0;
            background: linear-gradient(135deg, rgba(7,20,44,0.85) 0%, rgba(11,29,58,0.75) 50%, rgba(7,20,44,0.9) 100%);
        }

        .login-container {
            position: relative; z-index: 2;
            width: 100%; max-width: 420px;
            padding: 20px;
        }

        /* Glassmorphism Card */
        .login-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 32px 80px rgba(0, 0, 0, 0.3);
        }

        /* Header */
        .login-header {
            text-align: center;
            padding: 36px 28px 28px;
        }

        .login-logo {
            width: 72px; height: 72px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 18px;
            border: 3px solid rgba(255,255,255,0.2);
            box-shadow: 0 4px 16px rgba(0,0,0,0.2);
        }

        .login-logo img {
            width: 100%; height: 100%; object-fit: cover;
        }

        .login-title {
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 6px;
            letter-spacing: -0.3px;
        }

        .login-subtitle {
            font-size: 13px;
            color: rgba(255,255,255,0.55);
        }

        /* Body */
        .login-body { padding: 0 28px 32px; }

        .login-field { margin-bottom: 18px; }

        .login-field label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: rgba(255,255,255,0.7);
            margin-bottom: 7px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

            .login-input-wrap {
                position: relative;
            }

        .login-input-wrap i {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            font-size: 18px; color: rgba(255,255,255,0.4); pointer-events: none;
        }

        .login-input-wrap .login-eye-toggle {
            left: auto;
            right: 14px;
            pointer-events: auto;
            cursor: pointer;
            transition: color 0.2s;
        }

        .login-input-wrap .login-eye-toggle:hover {
            color: rgba(255,255,255,0.8);
        }

        .login-input-wrap input {
            width: 100%; height: 48px;
            padding: 0 44px 0 44px;
            border-radius: 12px;
            border: 1.5px solid rgba(255,255,255,0.15);
            font-size: 14px;
            background: rgba(255,255,255,0.06);
            color: #fff;
            transition: 0.25s;
        }

        .login-input-wrap input::placeholder { color: rgba(255,255,255,0.35); }

        .login-input-wrap input:focus {
            border-color: #28a745;
            background: rgba(255,255,255,0.1);
            box-shadow: 0 0 0 3px rgba(40,167,69,0.2);
            outline: none;
        }

        /* Remember me */
        .login-remember {
            display: flex; align-items: center; gap: 8px;
            margin-bottom: 20px;
        }

        .login-remember input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: #28a745;
            cursor: pointer;
        }

        .login-remember label {
            font-size: 13px;
            color: rgba(255,255,255,0.6);
            cursor: pointer;
        }

        /* Error */
        .login-error {
            font-size: 13px;
            color: #ff6b6b;
            background: rgba(229,57,53,0.12);
            border: 1px solid rgba(229,57,53,0.2);
            padding: 10px 14px;
            border-radius: 10px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Button */
        .login-btn {
            width: 100%; height: 48px;
            border: none; border-radius: 12px;
            background: linear-gradient(135deg, #28a745, #43c55e);
            color: #fff;
            font-size: 15px; font-weight: 600;
            cursor: pointer;
            transition: 0.25s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(40,167,69,0.35);
        }

        /* Back link */
        .login-back {
            display: block;
            text-align: center;
            margin-top: 24px;
            color: rgba(255,255,255,0.4);
            font-size: 13px;
            text-decoration: none;
            transition: 0.2s;
        }

        .login-back:hover { color: rgba(255,255,255,0.7); }

        /* Responsive */
        @media (max-width: 480px) {
            .login-container { padding: 16px; }
            .login-card { border-radius: 20px; }
            .login-header { padding: 28px 20px 20px; }
            .login-body { padding: 0 20px 24px; }
            .login-title { font-size: 16px; }
        }
    </style>
</head>
<body>
    <div class="login-bg"></div>
    <div class="login-overlay"></div>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="SDA-IRC Logo">
                </div>
                <h1 class="login-title">SDA-IRC Church Management System</h1>
                <p class="login-subtitle">Sign in to continue.</p>
            </div>

            <div class="login-body">
                @if($errors->any())
                    <div class="login-error">
                        <i class="mdi mdi-alert-circle-outline"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf

                    <div class="login-field">
                        <label>Username</label>
                        <div class="login-input-wrap">
                            <i class="mdi mdi-account-outline"></i>
                            <input type="text" name="username" value="{{ old('username') }}" placeholder="Enter your username" required autofocus>
                        </div>
                    </div>

                    <div class="login-field">
                        <label>Password</label>
                        <div class="login-input-wrap">
                            <i class="mdi mdi-lock-outline"></i>
                            <input type="password" name="password" id="passwordInput" placeholder="Enter your password" required>
                            <i class="mdi mdi-eye-off-outline login-eye-toggle" id="togglePassword"></i>
                        </div>
                    </div>

                    <div class="login-remember">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">Remember me</label>
                    </div>

                    <button type="submit" class="login-btn">
                        <i class="mdi mdi-login"></i> Sign In
                    </button>
                </form>
            </div>
        </div>

        <a href="{{ route('website.home') }}" class="login-back">
            <i class="mdi mdi-arrow-left"></i> Back to Website
        </a>
    </div>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            var input = document.getElementById('passwordInput');
            if (input.type === 'password') {
                input.type = 'text';
                this.classList.remove('mdi-eye-off-outline');
                this.classList.add('mdi-eye-outline');
            } else {
                input.type = 'password';
                this.classList.remove('mdi-eye-outline');
                this.classList.add('mdi-eye-off-outline');
            }
        });
    </script>
</body>
</html>
