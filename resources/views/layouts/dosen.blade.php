<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard Dosen - CDC Fakultas Psikologi UNS')</title>

    <link rel="shortcut icon" href="{{ asset('jobcy/images/favicon.ico') }}">

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    <link href="{{ asset('css/design-system.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/form-components.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/shared-components.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/accessibility.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/frontend.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/loading.css') }}" rel="stylesheet" />
    
    <style>
        :root {
            /* Dosen Theme Colors */
            --dosen-primary: #22004C;
            --dosen-secondary: #4A0080;
            --dosen-accent: #6c2d9e;
            --dosen-light: #9d4edd;
            
            /* Compatibility aliases */
            --primary-color: #22004C;
            --primary-light: #4A0080;
            --secondary-color: #6c2d9e;
            
            /* Also define primary-500 and secondary-500 for modern components */
            --primary-500: #22004C;
            --secondary-500: #4A0080;
        }

        /* Enhanced Navigation Styles */
        .navbar .nav-link {
            position: relative;
            padding: var(--spacing-2) var(--spacing-4) !important;
            border-radius: var(--radius-base);
            transition: all var(--transition-base);
            font-weight: var(--font-weight-medium);
            display: flex;
            align-items: center;
            gap: var(--spacing-2);
        }
        
        .navbar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.25);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }
        
        .navbar .nav-link.active::before {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 30px;
            height: 3px;
            background: white;
            border-radius: var(--radius-full);
        }

        .navbar .nav-link:hover:not(.active) {
            background-color: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }
        
        .navbar .nav-link i {
            font-size: 16px;
        }

        /* ====================================
           MODAL FIX - Prevent Glitching
           ==================================== */
        
        /* Force proper z-index hierarchy */
        .modal {
            z-index: 99999 !important;
            overflow-x: hidden !important;
            overflow-y: auto !important;
        }
        
        .modal-backdrop {
            z-index: 99998 !important;
            background-color: rgba(0, 0, 0, 0.6) !important;
        }
        
        .modal-backdrop.show {
            opacity: 0.6 !important;
        }
        
        /* Prevent body scroll and layout shift */
        body.modal-open {
            overflow: hidden !important;
            padding-right: 0 !important;
        }
        
        /* Smooth modal transitions */
        .modal.fade .modal-dialog {
            transition: transform 0.3s ease-out, opacity 0.3s ease-out !important;
            transform: translate(0, -50px) !important;
        }
        
        .modal.show .modal-dialog {
            transform: none !important;
        }
        
        /* Center modal properly */
        .modal-dialog {
            position: relative;
            margin: 1.75rem auto;
            pointer-events: none;
        }
        
        .modal-dialog-centered {
            display: flex;
            align-items: center;
            min-height: calc(100% - 3.5rem);
        }
        
        /* Ensure modal content is visible */
        .modal-content {
            position: relative;
            display: flex;
            flex-direction: column;
            width: 100%;
            pointer-events: auto;
            background-color: #fff;
            background-clip: padding-box;
            border: none;
            border-radius: 12px;
            outline: 0;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3) !important;
        }
        
        /* Prevent any overflow issues */
        .modal-header,
        .modal-body,
        .modal-footer {
            position: relative;
            z-index: 1;
        }
        
        /* Remove any conflicting animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        /* Ensure backdrop doesn't flicker */
        .modal-backdrop.fade {
            opacity: 0;
            transition: opacity 0.15s linear !important;
        }
        
        .modal-backdrop.fade.show {
            opacity: 0.6 !important;
        }
        
        /* Fix for multiple modals */
        .modal-open .modal {
            overflow-x: hidden !important;
            overflow-y: auto !important;
        }
    </style>
    
    @yield('styles')
    @stack('styles')
</head>

<div class="loading" style="display: none" id="loadingSpinner">
    <div style="color: blue" class="loading-content la-ball-spin-fade la-3x">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>

<body class="hold-transition layout-top-nav">
    @include('sweetalert::alert')
    <div class="wrapper">
        <!-- Enhanced Navbar -->
        <nav class="main-header navbar navbar-expand-md navbar-dark elevation-3" style="background: linear-gradient(135deg, var(--dosen-primary) 0%, var(--dosen-secondary) 100%);">
            <div class="container">
                <a href="{{ route('dosen.dashboard') }}" class="navbar-brand mr-5 d-flex align-items-center" style="transition: transform var(--transition-base);" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    <div style="width: 40px; height: 40px; background: white; border-radius: var(--radius-base); padding: 4px; margin-right: 10px; display: flex; align-items: center; justify-content: center;">
                        <img src="{{ asset('img/logo-uns.png') }}" alt="Logo UNS" style="width: 100%; height: 100%; object-fit: contain;">
                    </div>
                    <span class="text-white font-weight-bold" style="font-size: var(--font-size-lg); letter-spacing: 0.5px;">SIMSKRIPSI</span>
                </a>

                <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                    <!-- Left navbar links -->
                    <ul class="navbar-nav">
                        {{-- Dashboard --}}
                        <li class="nav-item">
                            <a href="{{ route('dosen.dashboard') }}" class="nav-link {{ request()->routeIs('dosen.dashboard') ? 'active' : '' }}">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        
                        {{-- Mahasiswa Bimbingan --}}
                        <li class="nav-item">
                            <a href="{{ route('dosen.mahasiswa-bimbingan') }}" class="nav-link {{ request()->routeIs('dosen.mahasiswa-bimbingan') ? 'active' : '' }}">
                                <i class="fas fa-users"></i> Mahasiswa Bimbingan
                            </a>
                        </li>
                        
                        {{-- Task Assignments --}}
                        <li class="nav-item">
                            <a href="{{ route('dosen.task-assignments') }}" class="nav-link {{ request()->routeIs('dosen.task-assignments') ? 'active' : '' }}">
                                <i class="fas fa-tasks"></i> Task Assignments
                            </a>
                        </li>
                        
                        {{-- Penilaian Sidang --}}
                        <li class="nav-item">
                            <a href="{{ route('dosen.scores') }}" class="nav-link {{ request()->routeIs(['dosen.scores', 'dosen.application-scores.edit', 'dosen.application-scores.update']) ? 'active' : '' }}">
                                <i class="fas fa-star"></i> Penilaian Sidang
                            </a>
                        </li>
                        
                        {{-- Profile --}}
                        <li class="nav-item">
                            <a href="{{ route('dosen.profile') }}" class="nav-link {{ request()->routeIs('dosen.profile') ? 'active' : '' }}">
                                <i class="fas fa-user"></i> Profile
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Right navbar links -->
                <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="display: flex; align-items: center; gap: 8px; padding: 8px 12px; background: rgba(255, 255, 255, 0.15); border-radius: 8px;">
                            <i class="fas fa-user-circle" style="font-size: 20px;"></i>
                            <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('dosen.profile') }}">
                                <i class="fas fa-user mr-2"></i> Profile
                            </a>
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Content Wrapper -->
        <div class="content-wrapper" style="background: #f4f6f9; min-height: calc(100vh - 57px);">
            @yield('content')
        </div>

        <!-- Main Footer -->
        <footer class="main-footer" style="background: white; border-top: 1px solid #dee2e6;">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <strong>Copyright &copy; 2024 <a href="#" style="color: var(--dosen-primary);">CDC Fakultas Psikologi UNS</a>.</strong>
                        All rights reserved.
                    </div>
                    <div class="col-md-6 text-right">
                        <b>Version</b> 1.0.0
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    
    <script>
        $(document).ready(function() {
            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert:not(.alert-permanent)').fadeOut('slow');
            }, 5000);

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Initialize popovers
            $('[data-toggle="popover"]').popover();
        });
    </script>
    
    @yield('scripts')
    @stack('scripts')
</body>

</html>
