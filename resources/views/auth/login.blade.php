<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ trans('panel.site_title') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" rel="stylesheet" />
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #22004C;
            --primary-light: #3d0a6b;
            --primary-dark: #190038;
            --accent-color: #6c2d9e;
            --text-dark: #2d3748;
            --text-light: #718096;
            --bg-light: #f7fafc;
            --white: #ffffff;
            --danger: #e53e3e;
            --success: #38a169;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .login-container {
            width: 100%;
            max-width: 450px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            color: var(--white);
            text-decoration: none;
            margin-bottom: 1rem;
        }

        .logo-icon {
            width: 60px;
            height: 60px;
            background: var(--white);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--primary-color);
            font-weight: 700;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .login-title {
            color: var(--white);
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .login-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
        }

        .login-card {
            background: var(--white);
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .welcome-text {
            text-align: center;
            margin-bottom: 2rem;
        }

        .welcome-text h2 {
            color: var(--primary-color);
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .welcome-text p {
            color: var(--text-light);
            font-size: 0.95rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }

        .alert-info {
            background-color: #e6f7ff;
            border: 1px solid #91d5ff;
            color: #0050b3;
        }

        .alert-danger {
            background-color: #fff1f0;
            border: 1px solid #ffccc7;
            color: #cf1322;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            color: var(--text-dark);
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 1.1rem;
        }

        .form-control {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 3rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            color: var(--text-dark);
            transition: all 0.3s;
            font-family: 'Figtree', sans-serif;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(34, 0, 76, 0.1);
        }

        .form-control.is-invalid {
            border-color: var(--danger);
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(229, 62, 62, 0.1);
        }

        .invalid-feedback {
            display: block;
            color: var(--danger);
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        .form-check {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            margin-right: 0.5rem;
            cursor: pointer;
            accent-color: var(--primary-color);
        }

        .form-check-label {
            color: var(--text-dark);
            font-size: 0.95rem;
            cursor: pointer;
            user-select: none;
        }

        .btn {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Figtree', sans-serif;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            color: var(--white);
            box-shadow: 0 4px 12px rgba(34, 0, 76, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(34, 0, 76, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
        }

        .login-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .login-footer a:hover {
            color: var(--accent-color);
        }

        .back-home {
            text-align: center;
            margin-top: 1.5rem;
        }

        .back-home a {
            color: var(--white);
            text-decoration: none;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: opacity 0.3s;
        }

        .back-home a:hover {
            opacity: 0.8;
        }

        .login-divider {
            text-align: center;
            margin: 1.5rem 0;
            position: relative;
        }

        .login-divider::before,
        .login-divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 40%;
            height: 1px;
            background-color: #e2e8f0;
        }

        .login-divider::before {
            left: 0;
        }

        .login-divider::after {
            right: 0;
        }

        .login-divider span {
            background: var(--white);
            padding: 0 1rem;
            color: var(--text-light);
            font-size: 0.9rem;
            position: relative;
            z-index: 1;
        }

        .btn-sso {
            width: 100%;
            padding: 0;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            background: var(--white);
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            height: 80px;
        }

        .btn-sso:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(34, 0, 76, 0.15);
            border-color: var(--primary-color);
        }

        .sso-logo {
            width: auto;
            height: 60px;
            object-fit: contain;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 1.5rem;
            }

            .login-title {
                font-size: 1.5rem;
            }

            .welcome-text h2 {
                font-size: 1.25rem;
            }

            .btn-sso {
                height: 70px;
            }

            .sso-logo {
                height: 50px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <a href="{{ url('/') }}" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
            </a>
            <h1 class="login-title">{{ trans('panel.site_title') }}</h1>
            <p class="login-subtitle">Fakultas Psikologi Universitas Sebelas Maret</p>
        </div>

        <div class="login-card">
            <div class="welcome-text">
                <h2>Selamat Datang Kembali!</h2>
                <p>Silakan masuk ke akun Anda untuk melanjutkan</p>
            </div>

            @if(session()->has('message'))
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> {{ session()->get('message') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> 
                    @if($errors->has('email'))
                        {{ $errors->first('email') }}
                    @elseif($errors->has('password'))
                        {{ $errors->first('password') }}
                    @else
                        {{ $errors->first() }}
                    @endif
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope input-icon"></i>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" 
                            value="{{ old('email') }}" 
                            placeholder="masukkan@email.anda"
                            required 
                            autocomplete="email" 
                            autofocus
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" 
                            placeholder="Masukkan password Anda"
                            required
                        >
                    </div>
                </div>

                <div class="form-check">
                    <input type="checkbox" name="remember" id="remember" class="form-check-input">
                    <label for="remember" class="form-check-label">
                        {{ trans('global.remember_me') }}
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> {{ trans('global.login') }}
                </button>
            </form>

            <div class="login-divider">
                <span>Atau login dengan:</span>
            </div>

            <a href="{{ route('sso.login') }}" class="btn btn-sso">
                <img src="{{ asset('img/sso.jpg') }}" alt="SSO UNS" class="sso-logo">
            </a>

            @if(Route::has('password.request'))
                <div class="login-footer">
                    <a href="{{ route('password.request') }}">
                        <i class="fas fa-key"></i> {{ trans('global.forgot_password') }}
                    </a>
                </div>
            @endif
        </div>

        <div class="back-home">
            <a href="{{ url('/') }}">
                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>
