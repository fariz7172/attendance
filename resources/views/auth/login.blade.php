<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Face Attendance System</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --color-primary: #6366f1;
            --color-primary-dark: #4f46e5;
            --color-primary-light: #818cf8;
            --color-pastel-purple: #d4b5e9;
            --color-pastel-blue: #a8d5e5;
            --color-text-primary: #1e293b;
            --color-text-secondary: #64748b;
            --color-bg-primary: #f8f9fc;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            position: relative;
            overflow: hidden;
        }

        /* Animated Background Shapes */
        .shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.6;
            z-index: -1;
            animation: float 10s infinite ease-in-out;
        }

        .shape-1 {
            width: 400px;
            height: 400px;
            background: var(--color-pastel-purple);
            top: -100px;
            left: -100px;
            animation-delay: 0s;
        }

        .shape-2 {
            width: 300px;
            height: 300px;
            background: var(--color-pastel-blue);
            bottom: -50px;
            right: -50px;
            animation-delay: 2s;
        }

        .shape-3 {
            width: 200px;
            height: 200px;
            background: var(--color-primary-light);
            top: 40%;
            left: 60%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(20px, -20px); }
        }

        .login-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 40px;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            transform: translateY(20px);
            opacity: 0;
            animation: slideUp 0.6s forwards;
        }

        @keyframes slideUp {
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .brand-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .brand-logo {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-pastel-purple) 100%);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            margin-bottom: 16px;
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
            animation: pulse 3s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.4); }
            70% { box-shadow: 0 0 0 15px rgba(99, 102, 241, 0); }
            100% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0); }
        }

        .brand-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--color-text-primary);
            margin-bottom: 4px;
        }

        .brand-subtitle {
            color: var(--color-text-secondary);
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px 14px 44px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            background: rgba(255, 255, 255, 0.8);
            transition: var(--transition);
            color: var(--color-text-primary);
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--color-primary);
            background: white;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .form-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--color-text-secondary);
            transition: var(--transition);
        }

        .form-control:focus + .form-icon {
            color: var(--color-primary);
        }

        .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--color-text-secondary);
            cursor: pointer;
            background: none;
            border: none;
            font-size: 14px;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .custom-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 13px;
            color: var(--color-text-secondary);
        }

        .custom-checkbox input {
            display: none;
        }

        .checkbox-box {
            width: 18px;
            height: 18px;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            background: white;
        }

        .custom-checkbox input:checked + .checkbox-box {
            background: var(--color-primary);
            border-color: var(--color-primary);
            color: white;
        }

        .forgot-link {
            color: var(--color-primary);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(99, 102, 241, 0.3);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            border-left: 3px solid #ef4444;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 32px 24px;
                border-radius: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Animated Shapes -->
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="shape shape-3"></div>

    <div class="login-card">
        <div class="brand-header">
            <div class="brand-logo">
                <i class="fas fa-fingerprint"></i>
            </div>
            <h1 class="brand-title">Face Attend</h1>
            <p class="brand-subtitle">Silakan login untuk melanjutkan</p>
        </div>

        <!-- Session Status -->
        @if(session('status'))
            <div style="color: #22c55e; margin-bottom: 16px; font-size: 14px; text-align: center;">
                {{ session('status') }}
            </div>
        @endif

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="form-group">
                <input id="email" type="email" name="email" class="form-control" placeholder="Email Address" value="{{ old('email') }}" required autofocus autocomplete="username">
                <i class="fas fa-envelope form-icon"></i>
            </div>

            <!-- Password -->
            <div class="form-group">
                <input id="password" type="password" name="password" class="form-control" placeholder="Password" required autocomplete="current-password">
                <i class="fas fa-lock form-icon"></i>
                <button type="button" class="toggle-password" onclick="togglePassword()">
                    <i class="fas fa-eye" id="eyeIcon"></i>
                </button>
            </div>

            <!-- Remember Me -->
            <div class="remember-forgot">
                <label class="custom-checkbox">
                    <input type="checkbox" name="remember" id="remember_me">
                    <div class="checkbox-box">
                        <i class="fas fa-check" style="font-size: 10px;"></i>
                    </div>
                    <span>Ingat Saya</span>
                </label>
                
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">Lupa Password?</a>
                @endif
            </div>

            <button type="submit" class="btn-submit">
                Masuk Sekarang
            </button>
        </form>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
