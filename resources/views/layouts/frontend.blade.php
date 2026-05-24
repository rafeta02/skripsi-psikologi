<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'CDC Fakultas Psikologi UNS')</title>

    <link rel="shortcut icon" href="{{ asset('jobcy/images/favicon.ico') }}">

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.0.5/css/adminlte.min.css" rel="stylesheet" />
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
    
    <!-- Custom User Dropdown Styles -->
    <style>
        /* Sticky Footer Fix */
        html {
            height: 100%;
        }
        
        body {
            min-height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        .content-wrapper {
            flex: 1 0 auto;
        }
        
        .main-footer {
            flex-shrink: 0;
        }

        /* User Dropdown Toggle */
        .user-dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .user-dropdown-toggle:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .user-avatar-small {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
        }

        .user-avatar-small i {
            font-size: 1.25rem;
            color: white;
        }

        .user-name-text {
            font-weight: 500;
            color: white;
        }

        /* Dropdown Menu */
        .user-dropdown-menu {
            min-width: 280px !important;
            border: none !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15) !important;
            padding: 0 !important;
            margin-top: 0.5rem !important;
            animation: slideDown 0.3s ease-out;
            transform-origin: top right;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* User Header in Dropdown */
        .user-dropdown-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, #22004C 0%, #4A0080 100%);
            color: white;
            border-radius: 12px 12px 0 0;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar-large {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            flex-shrink: 0;
        }

        .user-avatar-large i {
            font-size: 2.5rem;
            color: white;
        }

        .user-info h4 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
        }

        .user-info p {
            margin: 0.25rem 0 0;
            font-size: 0.9rem;
            opacity: 0.9;
            color: white;
        }

        .user-info small {
            font-size: 0.8rem;
            opacity: 0.8;
            color: white;
        }

        /* Dropdown Items */
        .user-dropdown-item {
            display: flex !important;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1.5rem !important;
            color: #2d3748 !important;
            transition: all 0.2s;
            border: none !important;
        }

        .user-dropdown-item i {
            width: 20px;
            text-align: center;
            color: #22004C;
            font-size: 1rem;
        }

        .user-dropdown-item span {
            font-weight: 500;
        }

        .user-dropdown-item:hover {
            background-color: #f7fafc !important;
            color: #22004C !important;
            padding-left: 2rem !important;
        }

        .user-dropdown-item.logout-item:hover {
            background-color: #fff5f5 !important;
        }

        .user-dropdown-item.logout-item:hover i {
            color: #e53e3e;
        }

        .user-dropdown-item.complete-profile-item {
            background-color: #fffbeb !important;
            border-left: 3px solid #f59e0b !important;
        }

        .user-dropdown-item.complete-profile-item i {
            color: #f59e0b !important;
        }

        .user-dropdown-item.complete-profile-item:hover {
            background-color: #fef3c7 !important;
            border-left-color: #d97706 !important;
        }

        .dropdown-divider {
            margin: 0.5rem 0 !important;
            border-top: 1px solid #e2e8f0 !important;
        }

        /* Navbar Enhancements */
        .navbar-brand {
            transition: all 0.3s;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
        }

        .navbar-nav .nav-link {
            position: relative;
            padding: 0.5rem 1rem !important;
            border-radius: 6px;
            transition: all 0.3s;
            font-weight: 500;
        }

        .navbar-nav .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .navbar-nav .nav-link.active {
            background-color: rgba(255, 255, 255, 0.15);
        }

        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: white;
            transition: width 0.3s;
        }

        .navbar-nav .nav-link:hover::after {
            width: 80%;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .user-dropdown-menu {
                min-width: 260px !important;
            }

            .user-dropdown-header {
                padding: 1rem;
            }

            .user-avatar-large {
                width: 50px;
                height: 50px;
            }

            .user-avatar-large i {
                font-size: 2rem;
            }

            .user-info h4 {
                font-size: 1rem;
            }
        }
    </style>
    
    @yield('styles')
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
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand-md navbar-dark" style="background-color: #22004C">
            <div class="container">
                <a href="{{ route('frontend.home') }}" class="navbar-brand mr-5">
                    <img src="{{ asset('img/logo-cdc-white.png') }}" alt="CDC Fakultas Psikologi UNS" style="height: 40px;">
                </a>

                <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                    <!-- Left navbar links -->
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="{{ route('frontend.home') }}" class="nav-link {{ request()->routeIs('frontend.home') ? 'active' : '' }}">
                                <i class="fas fa-home mr-1"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a id="skripsiMenu" href="#" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false" class="nav-link dropdown-toggle {{ request()->routeIs('mahasiswa.*') || request()->routeIs('dosen.*') ? 'active' : '' }}">
                                <i class="fas fa-graduation-cap mr-1"></i> Skripsi
                            </a>
                            <ul aria-labelledby="skripsiMenu" class="dropdown-menu border-0 shadow" style="border-radius: 10px; overflow: hidden;">
                                @if(Auth::user()->level == 'MAHASISWA')
                                    <li>
                                        <a href="{{ route('mahasiswa.dashboard') }}" class="dropdown-item">
                                            <i class="fas fa-user-graduate mr-2"></i> Dashboard Mahasiswa
                                        </a>
                                    </li>
                                    <li><div class="dropdown-divider"></div></li>
                                    <li>
                                        <a href="{{ route('frontend.choose-path') }}" class="dropdown-item">
                                            <i class="fas fa-route mr-2"></i> Pilih Jalur Skripsi
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('frontend.applications.index') }}" class="dropdown-item">
                                            <i class="fas fa-list mr-2"></i> Riwayat Aplikasi
                                        </a>
                                    </li>
                                @elseif(Auth::user()->level == 'DOSEN')
                                    <li>
                                        <a href="{{ route('dosen.dashboard') }}" class="dropdown-item">
                                            <i class="fas fa-chalkboard-teacher mr-2"></i> Dashboard Dosen
                                        </a>
                                    </li>
                                    <li><div class="dropdown-divider"></div></li>
                                    <li>
                                        <a href="{{ route('dosen.mahasiswa-bimbingan') }}" class="dropdown-item">
                                            <i class="fas fa-users mr-2"></i> Mahasiswa Bimbingan
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('dosen.task-assignments') }}" class="dropdown-item">
                                            <i class="fas fa-tasks mr-2"></i> Tugas Pembimbingan
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                        {{-- <li class="nav-item">
                            <a href="{{ route('alumni-caring') }}" class="nav-link">Alumni Caring</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false" class="nav-link dropdown-toggle">Prestasi</a>
                            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                                <li><a href="{{ route('frontend.prestasi-mahasiswas.index') }}" class="dropdown-item">Prestasi Mahasiswa</a></li>
                                <li><a href="{{ route('frontend.prestasi-mabas.index') }}" class="dropdown-item">Prestasi Mahasiswa Baru</a></li>
                            </ul>
                        </li> --}}
                    </ul>
                </div>

                <!-- Right navbar links -->
                <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle user-dropdown-toggle" data-toggle="dropdown">
                            <div class="user-avatar-small">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <span class="d-none d-md-inline user-name-text">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down ml-2" style="font-size: 0.75rem;"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right user-dropdown-menu">
                            <!-- User Header -->
                            <li class="user-dropdown-header">
                                <div class="user-avatar-large">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <div class="user-info">
                                    <h4>{{ Auth::user()->name }}</h4>
                                    @if (Auth::user()->level == 'MAHASISWA')
                                        @if(Auth::user()->mahasiswa_id && Auth::user()->mahasiswa)
                                            <p>Mahasiswa Psikologi</p>
                                            <small>Angkatan {{ Auth::user()->mahasiswa->tahun_masuk }}</small>
                                        @else
                                            <p>Mahasiswa Psikologi</p>
                                            <small><i class="fas fa-exclamation-triangle"></i> Profil belum lengkap</small>
                                        @endif
                                    @elseif (Auth::user()->level == 'DOSEN')
                                        @if(Auth::user()->dosen_id && Auth::user()->dosen)
                                            <p>Dosen Psikologi</p>
                                            <small>{{ Auth::user()->dosen->nidn ?? Auth::user()->email }}</small>
                                        @else
                                            <p>Dosen Psikologi</p>
                                            <small><i class="fas fa-exclamation-triangle"></i> Profil belum lengkap</small>
                                        @endif
                                    @else
                                        <p>{{ Auth::user()->email }}</p>
                                    @endif
                                </div>
                            </li>
                            
                            <li class="dropdown-divider"></li>
                            
                            <!-- Menu Items -->
                            <li>
                                <a href="{{ route('frontend.home') }}" class="dropdown-item user-dropdown-item">
                                    <i class="fas fa-home"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>
                            
                            @if((Auth::user()->level == 'MAHASISWA' && !Auth::user()->mahasiswa_id) || 
                                (Auth::user()->level == 'DOSEN' && !Auth::user()->dosen_id))
                            <li>
                                <a href="{{ Auth::user()->level == 'MAHASISWA' ? route('frontend.mahasiswa-profile.create') : route('frontend.dosen-profile.create') }}" 
                                   class="dropdown-item user-dropdown-item complete-profile-item">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>
                                        @if(Auth::user()->level == 'MAHASISWA')
                                            Lengkapi Profil Mahasiswa
                                        @else
                                            Lengkapi Profil Dosen
                                        @endif
                                    </span>
                                </a>
                            </li>
                            @endif
                            
                            @if(Auth::user()->level == 'MAHASISWA' && Auth::user()->mahasiswa_id)
                            <li>
                                <a href="{{ route('frontend.mahasiswa-profile.edit') }}" class="dropdown-item user-dropdown-item">
                                    <i class="fas fa-id-card"></i>
                                    <span>Data Mahasiswa</span>
                                </a>
                            </li>
                            @endif
                            
                            @if(Auth::user()->level == 'DOSEN' && Auth::user()->dosen_id)
                            <li>
                                <a href="{{ route('frontend.dosen-profile.edit') }}" class="dropdown-item user-dropdown-item">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                    <span>Data Dosen</span>
                                </a>
                            </li>
                            @endif
                            
                            <li>
                                <a href="{{ route('frontend.profile.index') }}" class="dropdown-item user-dropdown-item">
                                    <i class="fas fa-user-edit"></i>
                                    <span>Profile Settings</span>
                                </a>
                            </li>
                            
                            <li class="dropdown-divider"></li>
                            
                            <li>
                                <a href="#" class="dropdown-item user-dropdown-item logout-item" 
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Sign Out</span>
                                </a>
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
                @yield('breadcumb')
                {{-- <div class="container">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"> Top Navigation <small>Example 3.0</small></h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item"><a href="#">Layout</a></li>
                                <li class="breadcrumb-item active">Top Navigation</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid --> --}}
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                @if(session('message'))
                    <div class="row mb-2">
                        <div class="col-9" style="margin-left: 12.5%;">
                            <div class="alert alert-success" role="alert">{{ session('message') }}</div>
                        </div>
                    </div>
                @endif
                @if($errors->count() > 0)
                <div class="row mb-2">
                    <div class="col-9" style="margin-left: 12.5%;">
                        <div class="alert alert-danger">
                            <ul class="list-unstyled">
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
        <footer class="main-footer" style="background-color: #22004C; color: white; border-top: 3px solid #4A0080;">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline" style="color: rgba(255, 255, 255, 0.8);">
                <i class="far fa-calendar-alt"></i> Version 二千二十四
            </div>
            <!-- Default to the left -->
            <p style="margin: 0; color: white;">
                <i class="fas fa-graduation-cap"></i> Fakultas Psikologi UNS &copy; {{ date('Y') }} All rights reserved.
            </p>
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
        // Global SweetAlert notification handler
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session("success") }}',
                confirmButtonColor: '#28a745',
                timer: 3000,
                showConfirmButton: true
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: '<div style="text-align: left;">{{ session("error") }}</div>',
                confirmButtonColor: '#d33',
                confirmButtonText: 'OK'
            });
        @endif

        @if(session('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                html: '<div style="text-align: left;">{{ session("warning") }}</div>',
                confirmButtonColor: '#f39c12',
                confirmButtonText: 'Saya Mengerti'
            });
        @endif

        @if(session('info'))
            Swal.fire({
                icon: 'info',
                title: 'Informasi',
                html: '<div style="text-align: left;">{{ session("info") }}</div>',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Terdapat Kesalahan Validasi',
                html: '<ul style="text-align: left; margin: 0; padding-left: 20px;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                confirmButtonColor: '#d33',
                confirmButtonText: 'OK',
                width: '600px'
            });
        @endif
    </script>
    @yield('scripts')
</body>

</html>
