<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Sistem Skripsi') }} - @yield('title', 'Fakultas Psikologi UNS')</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Design System CSS -->
    <link href="{{ asset('css/design-system.css') }}" rel="stylesheet" />
    
    <style>
        :root {
            --primary-color: #22004C;
            --secondary-color: #4A0080;
            --accent-color: #8B5CF6;
        }
        
        body {
            font-family: 'Source Sans Pro', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .public-navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }
        
        .public-navbar .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .public-navbar .navbar-brand i {
            font-size: 2rem;
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 0.5rem 2rem;
            border-radius: 25px;
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(34, 0, 76, 0.3);
            color: white;
        }
        
        .public-footer {
            background: white;
            padding: 2rem 0;
            margin-top: 4rem;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
        }
        
        .public-footer p {
            margin: 0;
            color: #6c757d;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="public-navbar">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <a href="/" class="navbar-brand">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Sistem Skripsi<br><small style="font-size: 0.7rem; font-weight: 400;">Fakultas Psikologi UNS</small></span>
                </a>
                
                @guest
                    <a href="{{ route('login') }}" class="btn btn-login">
                        <i class="fas fa-sign-in-alt mr-2"></i> Login
                    </a>
                @else
                    @if(Auth::user()->level == 'MAHASISWA')
                        <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-login">
                            <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                        </a>
                    @elseif(Auth::user()->level == 'DOSEN')
                        <a href="{{ route('dosen.dashboard') }}" class="btn btn-login">
                            <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                        </a>
                    @else
                        <a href="{{ route('admin.home') }}" class="btn btn-login">
                            <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                        </a>
                    @endif
                @endguest
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="public-footer">
        <div class="container text-center">
            <p>&copy; {{ date('Y') }} Fakultas Psikologi - Universitas Sebelas Maret</p>
            <p class="small mt-1">Sistem Manajemen Skripsi & MBKM</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
