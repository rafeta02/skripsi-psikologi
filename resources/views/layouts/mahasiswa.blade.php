<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard Mahasiswa - CDC Fakultas Psikologi UNS')</title>

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
            --primary-color: #22004C;
            --primary-light: #3d0a6b;
            --primary-dark: #190038;
            --accent-color: #6c2d9e;
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

        .card {
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            border-top-left-radius: 10px !important;
            border-top-right-radius: 10px !important;
        }

        .btn {
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .badge {
            padding: 5px 10px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .small-box {
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .small-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .small-box > .small-box-footer {
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        .content-wrapper {
            background-color: #f4f6f9;
            min-height: calc(100vh - 100px);
        }

        .table {
            background-color: white;
        }

        .table thead th {
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            color: #495057;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        .border-left-primary {
            border-left: 4px solid var(--primary-color) !important;
        }

        .bg-primary {
            background-color: var(--primary-color) !important;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-light);
            border-color: var(--primary-light);
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        /* Enhanced User Dropdown */
        .user-menu .dropdown-toggle {
            padding: var(--spacing-2) var(--spacing-4) !important;
            border-radius: var(--radius-base);
            transition: all var(--transition-base);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .user-menu .dropdown-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .user-menu .dropdown-menu {
            border-radius: var(--radius-lg);
            border: none;
            box-shadow: var(--shadow-lg);
            margin-top: var(--spacing-2);
            min-width: 250px;
        }
        
        .user-menu .user-header {
            border-radius: var(--radius-lg) var(--radius-lg) 0 0;
            padding: var(--spacing-6) var(--spacing-4);
        }
        
        .user-menu .user-footer {
            padding: var(--spacing-4);
            display: flex;
            gap: var(--spacing-2);
        }
        
        .user-menu .user-footer .btn {
            flex: 1;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-light);
        }

        /* Loading animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card, .small-box {
            animation: fadeIn 0.5s ease;
        }

        /* Enhanced Responsive Navigation */
        @media (max-width: 768px) {
            .navbar-brand span {
                font-size: var(--font-size-sm);
            }
            
            .navbar-collapse {
                background: rgba(0, 0, 0, 0.05);
                padding: var(--spacing-3);
                border-radius: var(--radius-base);
                margin-top: var(--spacing-3);
            }
            
            .navbar .nav-link {
                margin-bottom: var(--spacing-2);
            }
            
            .navbar .nav-link.active::before {
                display: none;
            }
            
            .navbar .nav-link.active {
                background: rgba(255, 255, 255, 0.3);
            }
            
            .card-body {
                padding: 1rem;
            }
            
            .user-menu .dropdown-toggle span {
                display: none !important;
            }
        }
        
        /* Navbar toggle button enhancement */
        .navbar-toggler {
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: var(--radius-base);
            padding: var(--spacing-2);
        }
        
        .navbar-toggler:focus {
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.2);
        }
        
        .navbar-toggler-icon {
            width: 24px;
            height: 24px;
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
        <nav class="main-header navbar navbar-expand-md navbar-dark elevation-3" style="background: linear-gradient(135deg, var(--primary-500) 0%, var(--secondary-500) 100%);">
            <div class="container">
                <a href="{{ route('mahasiswa.dashboard') }}" class="navbar-brand mr-5 d-flex align-items-center" style="transition: transform var(--transition-base);" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
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
                        {{-- Dashboard - Always visible --}}
                        <li class="nav-item">
                            <a href="{{ route('mahasiswa.dashboard') }}" class="nav-link {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        
                        {{-- Aplikasi Saya - Visible from Phase 0 (always show) --}}
                        <li class="nav-item">
                            <a href="{{ route('mahasiswa.aplikasi') }}" class="nav-link {{ request()->routeIs('mahasiswa.aplikasi') ? 'active' : '' }}">
                                <i class="fas fa-file-alt"></i> Aplikasi Saya
                            </a>
                        </li>
                        
                        {{-- Bimbingan - Always show --}}
                        <li class="nav-item">
                            <a href="{{ route('mahasiswa.bimbingan') }}" class="nav-link {{ request()->routeIs('mahasiswa.bimbingan') ? 'active' : '' }}">
                                <i class="fas fa-users"></i> Bimbingan
                            </a>
                        </li>
                        
                        {{-- Jadwal - Always show --}}
                        <li class="nav-item">
                            <a href="{{ route('mahasiswa.jadwal') }}" class="nav-link {{ request()->routeIs('mahasiswa.jadwal') ? 'active' : '' }}">
                                <i class="fas fa-calendar-alt"></i> Jadwal
                            </a>
                        </li>
                        
                        {{-- Dokumen - Always show --}}
                        <li class="nav-item">
                            <a href="{{ route('mahasiswa.dokumen') }}" class="nav-link {{ request()->routeIs('mahasiswa.dokumen') ? 'active' : '' }}">
                                <i class="fas fa-folder"></i> Dokumen
                            </a>
                        </li>

                        @can('application_report_access')
                        <li class="nav-item">
                            <a href="{{ route('frontend.application-reports.index') }}" class="nav-link {{ request()->routeIs('frontend.application-reports.*') ? 'active' : '' }}">
                                <i class="fas fa-flag"></i> Laporan Kendala
                            </a>
                        </li>
                        @endcan

                        @if(($allowedForms['application_result_seminar']['allowed'] ?? false))
                        <li class="nav-item">
                            <a href="{{ route('frontend.application-result-seminars.index') }}" class="nav-link {{ request()->routeIs('frontend.application-result-seminars.*') ? 'active' : '' }}">
                                <i class="fas fa-clipboard-check"></i> Laporan Review
                            </a>
                        </li>
                        @endif

                        @if(($allowedForms['defense_result']['allowed'] ?? false))
                        <li class="nav-item">
                            <a href="{{ route('frontend.application-result-defenses.index') }}" class="nav-link {{ request()->routeIs('frontend.application-result-defenses.*') ? 'active' : '' }}">
                                <i class="fas fa-award"></i> Hasil Sidang
                            </a>
                        </li>
                        @endif
                        
                        {{-- Profile - Always visible --}}
                        <li class="nav-item">
                            <a href="{{ route('mahasiswa.profile') }}" class="nav-link {{ request()->routeIs('mahasiswa.profile') ? 'active' : '' }}">
                                <i class="fas fa-user"></i> Profile
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Right navbar links -->
                <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                            <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                            <i class="far fa-user ml-2"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            <!-- User image -->
                            <li class="user-header" style="background-color: #22004C;">
                                <img src="{{ asset('img/user.png') }}" class="img-circle elevation-2" alt="User Image">
                                <p>
                                    {{ Auth::user()->name }}
                                    <small>{{ Auth::user()->email }}</small>
                                </p>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <a href="{{ route('mahasiswa.profile') }}" class="btn btn-primary btn-flat">Profile</a>
                                <a class="btn btn-danger btn-flat float-right" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sign out</a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header" style="padding-top: 30px">
                @yield('breadcrumb')
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                @if(session('message'))
                    <div class="container">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('message') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                @endif
                @if($errors->count() > 0)
                    <div class="container">
                        <div class="alert alert-danger">
                            <ul class="list-unstyled mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                @yield('content')
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        <footer class="main-footer" style="background: linear-gradient(135deg, #22004C 0%, #3d0a6b 100%); color: white;">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <strong>SIMSKRIPSI - Fakultas Psikologi UNS</strong>
                        <p class="mb-0">Sistem Informasi Manajemen Skripsi</p>
                    </div>
                    <div class="col-md-6 text-right">
                        <div class="float-right d-none d-sm-inline">
                            <i class="fas fa-calendar-alt"></i> Version {{ date('Y') }}
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12 text-center">
                        <small>&copy; {{ date('Y') }} Fakultas Psikologi Universitas Sebelas Maret. All rights reserved.</small>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
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
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    
    <script>
        // Auto-hide alerts after 5 seconds
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert:not(.alert-permanent)').fadeOut('slow');
            }, 5000);

            // Add smooth scrolling
            $('a[href^="#"]').on('click', function(event) {
                var target = $(this.getAttribute('href'));
                if(target.length) {
                    event.preventDefault();
                    $('html, body').stop().animate({
                        scrollTop: target.offset().top - 100
                    }, 1000);
                }
            });

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Initialize popovers
            $('[data-toggle="popover"]').popover();

            // Confirm delete actions
            $('.btn-danger[data-confirm]').on('click', function(e) {
                e.preventDefault();
                var href = $(this).attr('href');
                var message = $(this).data('confirm') || 'Apakah Anda yakin ingin menghapus data ini?';
                
                Swal.fire({
                    title: 'Konfirmasi',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    }
                });
            });

            // Loading spinner for form submissions
            $('form').on('submit', function() {
                $('#loadingSpinner').show();
            });

            // Add animation to cards on scroll
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate__animated', 'animate__fadeInUp');
                    }
                });
            }, {
                threshold: 0.1
            });

            document.querySelectorAll('.card, .small-box').forEach((el) => {
                observer.observe(el);
            });
        });
    </script>
    
    @yield('scripts')
    @stack('scripts')
</body>

</html>
