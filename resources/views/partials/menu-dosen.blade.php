<aside class="main-sidebar sidebar-dark-primary elevation-4" style="min-height: 917px;">
    <!-- Brand Logo -->
    <a href="{{ route('dosen.dashboard') }}" class="brand-link">
        <span class="brand-text font-weight-light">{{ trans('panel.site_title') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('img/user.png') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="{{ route('dosen.profile') }}" class="d-block">{{ Auth::user()->name }}</a>
                <small class="text-muted">Dosen</small>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dosen.dashboard') ? 'active' : '' }}" href="{{ route('dosen.dashboard') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Mahasiswa Bimbingan -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dosen.mahasiswa-bimbingan') ? 'active' : '' }}" href="{{ route('dosen.mahasiswa-bimbingan') }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Mahasiswa Bimbingan</p>
                    </a>
                </li>

                <!-- Task Assignments -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dosen.task-assignments') ? 'active' : '' }}" href="{{ route('dosen.task-assignments') }}">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>Task Assignments</p>
                    </a>
                </li>

                <!-- Application Scores -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dosen.scores') ? 'active' : '' }}" href="{{ route('dosen.scores') }}">
                        <i class="nav-icon fas fa-star"></i>
                        <p>Application Scores</p>
                    </a>
                </li>

                <!-- Profile -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dosen.profile') ? 'active' : '' }}" href="{{ route('dosen.profile') }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Profile</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>{{ trans('global.logout') }}</p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
