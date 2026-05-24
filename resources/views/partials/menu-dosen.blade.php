<aside class="main-sidebar elevation-4" style="min-height: 100vh;">
    <!-- Brand Logo -->
    <a href="{{ route('dosen.dashboard') }}" class="brand-link d-flex align-items-center">
        <div style="width: 40px; height: 40px; background: white; border-radius: var(--radius-base); padding: 4px; margin-right: 12px; display: flex; align-items: center; justify-content: center;">
            <img src="{{ asset('img/logo-uns.png') }}" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
        </div>
        <span class="brand-text">SIMSKRIPSI</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel d-flex align-items-center">
            <div class="image">
                <img src="{{ asset('img/user.png') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info flex-grow-1">
                <a href="{{ route('dosen.profile') }}" class="d-block">{{ Auth::user()->name }}</a>
                <small style="color: rgba(255, 255, 255, 0.8); font-size: var(--font-size-xs);">
                    <i class="fas fa-chalkboard-teacher mr-1"></i> Dosen
                </small>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav style="margin-top: var(--spacing-6); padding-bottom: var(--spacing-6);">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                <!-- Main Section -->
                <li class="nav-header" style="color: rgba(255, 255, 255, 0.5); font-size: var(--font-size-xs); text-transform: uppercase; letter-spacing: 1px; padding: var(--spacing-4) var(--spacing-4) var(--spacing-2);">
                    Main Menu
                </li>
                
                <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dosen.dashboard') ? 'active' : '' }}" href="{{ route('dosen.dashboard') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Management Section -->
                <li class="nav-header" style="color: rgba(255, 255, 255, 0.5); font-size: var(--font-size-xs); text-transform: uppercase; letter-spacing: 1px; padding: var(--spacing-4) var(--spacing-4) var(--spacing-2); margin-top: var(--spacing-3);">
                    Management
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

                <!-- Account Section -->
                <li class="nav-header" style="color: rgba(255, 255, 255, 0.5); font-size: var(--font-size-xs); text-transform: uppercase; letter-spacing: 1px; padding: var(--spacing-4) var(--spacing-4) var(--spacing-2); margin-top: var(--spacing-3);">
                    Account
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
