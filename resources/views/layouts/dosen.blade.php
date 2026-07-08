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
    
    <link href="{{ asset('css/mahasiswa-portal.css') }}" rel="stylesheet" />
    
    <style>
        :root {
            --dosen-primary: #22004C;
            --dosen-secondary: #4A0080;
            --primary-color: #22004C;
            --primary-500: #22004C;
            --secondary-500: #4A0080;
        }

        /* Modal fix */
        
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

<body class="mhs-body">
    @include('sweetalert::alert')
    <div class="mhs-wrapper">
        @include('partials.dosen.sidebar')

        <div class="mhs-main">
            <header class="mhs-topbar">
                <button class="mhs-sidebar-toggle" id="mhsSidebarToggle" type="button">
                    <i class="fas fa-bars"></i> Menu
                </button>
                <div class="mhs-topbar-user">
                    @if(($portalStats['menunggu_respons'] ?? 0) > 0)
                        <a href="{{ route('dosen.task-assignments') }}" class="btn btn-sm btn-warning mr-2">
                            <i class="fas fa-tasks"></i>
                            {{ $portalStats['menunggu_respons'] }} penugasan baru
                        </a>
                    @endif
                    <strong>{{ Auth::user()->name }}</strong>
                    <a href="{{ route('dosen.profile') }}" class="btn btn-sm btn-link ml-2">Profil</a>
                    <a href="#" class="btn btn-sm btn-outline-secondary ml-1" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Keluar</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                </div>
            </header>

            <main class="mhs-content">
                @if(session('message'))
                    <div class="alert alert-success alert-dismissible fade show">{{ session('message') }}<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>
                @endif
                @if($errors->count() > 0)
                    <div class="alert alert-danger">
                        <ul class="list-unstyled mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif
                @yield('content')
            </main>

            <footer class="text-center text-muted py-3" style="font-size: 0.8rem; border-top: 1px solid #e9ecef;">
                SIMSKRIPSI &mdash; Portal Dosen Fakultas Psikologi UNS &copy; {{ date('Y') }}
            </footer>
        </div>
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
            $('#mhsSidebarToggle, #mhsSidebarOverlay').on('click', function() {
                $('#mhsSidebar').toggleClass('open');
                $('#mhsSidebarOverlay').toggleClass('show');
            });

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
