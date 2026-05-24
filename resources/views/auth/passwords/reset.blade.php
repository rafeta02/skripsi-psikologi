<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password - {{ trans('panel.site_title') }}</title>
    
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

        .reset-container {
            width: 100%;
            max-width: 450px;
        }

        .reset-header {
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

        .reset-title {
            color: var(--white);
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .reset-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
        }

        .reset-card {
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

        .text-danger {
            display: block;
            color: var(--danger);
            font-size: 0.875rem;
            margin-top: 0.5rem;
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

        .reset-footer {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
        }

        .reset-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .reset-footer a:hover {
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

        .password-strength {
            margin-top: 0.5rem;
            font-size: 0.875rem;
            color: var(--text-light);
        }

        @media (max-width: 480px) {
            .reset-card {
                padding: 1.5rem;
            }

            .reset-title {
                font-size: 1.5rem;
            }

            .welcome-text h2 {
                font-size: 1.25rem;
            }
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="reset-header">
            <a href="{{ url('/') }}" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
            </a>
            <h1 class="reset-title">{{ trans('panel.site_title') }}</h1>
            <p class="reset-subtitle">Fakultas Psikologi Universitas Sebelas Maret</p>
        </div>

        <div class="reset-card">
            <div class="welcome-text">
                <h2>{{ trans('global.reset_password') }}</h2>
                <p>Masukkan password baru Anda</p>
            </div>

            <form method="POST" action="{{ route('password.request') }}">
                @csrf

                <input name="token" value="{{ $token }}" type="hidden">

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope input-icon"></i>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" 
                            value="{{ $email ?? old('email') }}" 
                            placeholder="masukkan@email.anda"
                            required 
                            autocomplete="email" 
                            autofocus
                        >
                    </div>
                    @if($errors->has('email'))
                        <span class="text-danger">
                            {{ $errors->first('email') }}
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password Baru</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" 
                            placeholder="Masukkan password baru"
                            required
                        >
                    </div>
                    @if($errors->has('password'))
                        <span class="text-danger">
                            {{ $errors->first('password') }}
                        </span>
                    @endif
                    <div class="password-strength">
                        <i class="fas fa-info-circle"></i> Password minimal 8 karakter
                    </div>
                </div>

                <div class="form-group">
                    <label for="password-confirm" class="form-label">Konfirmasi Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input 
                            id="password-confirm" 
                            type="password" 
                            name="password_confirmation" 
                            class="form-control" 
                            placeholder="Konfirmasi password baru"
                            required
                        >
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ trans('global.reset_password') }}
                </button>
            </form>

            <div class="reset-footer">
                <a href="{{ route('login') }}">
                    <i class="fas fa-arrow-left"></i> Kembali ke Login
                </a>
            </div>
        </div>

        <div class="back-home">
            <a href="{{ url('/') }}">
                <i class="fas fa-home"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>
