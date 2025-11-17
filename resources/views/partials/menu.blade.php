<aside class="main-sidebar sidebar-dark-primary elevation-4" style="min-height: 917px;">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <span class="brand-text font-weight-light">{{ trans('panel.site_title') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs("admin.home") ? "active" : "" }}" href="{{ route("admin.home") }}">
                        <i class="fas fa-fw fa-tachometer-alt nav-icon">
                        </i>
                        <p>
                            {{ trans('global.dashboard') }}
                        </p>
                    </a>
                </li>
                @can('user_management_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/permissions*") ? "menu-open" : "" }} {{ request()->is("admin/roles*") ? "menu-open" : "" }} {{ request()->is("admin/users*") ? "menu-open" : "" }} {{ request()->is("admin/audit-logs*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is("admin/permissions*") ? "active" : "" }} {{ request()->is("admin/roles*") ? "active" : "" }} {{ request()->is("admin/users*") ? "active" : "" }} {{ request()->is("admin/audit-logs*") ? "active" : "" }}" href="#">
                            <i class="fa-fw nav-icon fas fa-users">

                            </i>
                            <p>
                                {{ trans('cruds.userManagement.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('permission_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.permissions.index") }}" class="nav-link {{ request()->is("admin/permissions") || request()->is("admin/permissions/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-unlock-alt">

                                        </i>
                                        <p>
                                            {{ trans('cruds.permission.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('role_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.roles.index") }}" class="nav-link {{ request()->is("admin/roles") || request()->is("admin/roles/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-briefcase">

                                        </i>
                                        <p>
                                            {{ trans('cruds.role.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('user_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.users.index") }}" class="nav-link {{ request()->is("admin/users") || request()->is("admin/users/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-user">

                                        </i>
                                        <p>
                                            {{ trans('cruds.user.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('audit_log_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.audit-logs.index") }}" class="nav-link {{ request()->is("admin/audit-logs") || request()->is("admin/audit-logs/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-file-alt">

                                        </i>
                                        <p>
                                            {{ trans('cruds.auditLog.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('blog_master_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/article-categories*") ? "menu-open" : "" }} {{ request()->is("admin/article-tags*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is("admin/article-categories*") ? "active" : "" }} {{ request()->is("admin/article-tags*") ? "active" : "" }}" href="#">
                            <i class="fa-fw nav-icon fab fa-blogger-b">

                            </i>
                            <p>
                                {{ trans('cruds.blogMaster.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('article_category_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.article-categories.index") }}" class="nav-link {{ request()->is("admin/article-categories") || request()->is("admin/article-categories/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-align-justify">

                                        </i>
                                        <p>
                                            {{ trans('cruds.articleCategory.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('article_tag_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.article-tags.index") }}" class="nav-link {{ request()->is("admin/article-tags") || request()->is("admin/article-tags/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-tag">

                                        </i>
                                        <p>
                                            {{ trans('cruds.articleTag.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('post_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.posts.index") }}" class="nav-link {{ request()->is("admin/posts") || request()->is("admin/posts/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon far fa-newspaper">

                            </i>
                            <p>
                                {{ trans('cruds.post.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('master_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/jenjangs*") ? "menu-open" : "" }} {{ request()->is("admin/faculties*") ? "menu-open" : "" }} {{ request()->is("admin/prodis*") ? "menu-open" : "" }} {{ request()->is("admin/keilmuans*") ? "menu-open" : "" }} {{ request()->is("admin/research-groups*") ? "menu-open" : "" }} {{ request()->is("admin/ruangs*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is("admin/jenjangs*") ? "active" : "" }} {{ request()->is("admin/faculties*") ? "active" : "" }} {{ request()->is("admin/prodis*") ? "active" : "" }} {{ request()->is("admin/keilmuans*") ? "active" : "" }} {{ request()->is("admin/research-groups*") ? "active" : "" }} {{ request()->is("admin/ruangs*") ? "active" : "" }}" href="#">
                            <i class="fa-fw nav-icon fas fa-cogs">

                            </i>
                            <p>
                                {{ trans('cruds.master.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('jenjang_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.jenjangs.index") }}" class="nav-link {{ request()->is("admin/jenjangs") || request()->is("admin/jenjangs/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-chart-line">

                                        </i>
                                        <p>
                                            {{ trans('cruds.jenjang.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('faculty_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.faculties.index") }}" class="nav-link {{ request()->is("admin/faculties") || request()->is("admin/faculties/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fab fa-facebook-f">

                                        </i>
                                        <p>
                                            {{ trans('cruds.faculty.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('prodi_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.prodis.index") }}" class="nav-link {{ request()->is("admin/prodis") || request()->is("admin/prodis/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-puzzle-piece">

                                        </i>
                                        <p>
                                            {{ trans('cruds.prodi.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('keilmuan_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.keilmuans.index") }}" class="nav-link {{ request()->is("admin/keilmuans") || request()->is("admin/keilmuans/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon far fa-address-card">

                                        </i>
                                        <p>
                                            {{ trans('cruds.keilmuan.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('research_group_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.research-groups.index") }}" class="nav-link {{ request()->is("admin/research-groups") || request()->is("admin/research-groups/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fab fa-themeisle">

                                        </i>
                                        <p>
                                            {{ trans('cruds.researchGroup.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('ruang_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.ruangs.index") }}" class="nav-link {{ request()->is("admin/ruangs") || request()->is("admin/ruangs/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-hospital">

                                        </i>
                                        <p>
                                            {{ trans('cruds.ruang.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('civitum_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/mahasiswas*") ? "menu-open" : "" }} {{ request()->is("admin/dosens*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is("admin/mahasiswas*") ? "active" : "" }} {{ request()->is("admin/dosens*") ? "active" : "" }}" href="#">
                            <i class="fa-fw nav-icon fas fa-users">

                            </i>
                            <p>
                                {{ trans('cruds.civitum.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('mahasiswa_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.mahasiswas.index") }}" class="nav-link {{ request()->is("admin/mahasiswas") || request()->is("admin/mahasiswas/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-users">

                                        </i>
                                        <p>
                                            {{ trans('cruds.mahasiswa.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('dosen_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.dosens.index") }}" class="nav-link {{ request()->is("admin/dosens") || request()->is("admin/dosens/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-user">

                                        </i>
                                        <p>
                                            {{ trans('cruds.dosen.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                {{-- Skripsi Management - Reorganized Menu --}}
                <li class="nav-item has-treeview {{ request()->is("admin/skripsi*") || request()->is("admin/mbkm*") || request()->is("admin/application*") ? "menu-open" : "" }}">
                    <a class="nav-link nav-dropdown-toggle {{ request()->is("admin/skripsi*") || request()->is("admin/mbkm*") || request()->is("admin/application*") ? "active" : "" }}" href="#">
                        <i class="fa-fw nav-icon fas fa-graduation-cap"></i>
                        <p>
                            Skripsi Management
                            <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        {{-- Dashboard --}}
                        <li class="nav-item">
                            <a href="{{ route("admin.skripsi.dashboard") }}" class="nav-link {{ request()->is("admin/skripsi/dashboard") ? "active" : "" }}">
                                <i class="fa-fw nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        
                        @can('application_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.applications.index") }}" class="nav-link {{ request()->is("admin/applications") || request()->is("admin/applications/*") ? "active" : "" }}">
                                    <i class="fa-fw nav-icon fas fa-list"></i>
                                    <p>Semua Aplikasi</p>
                                </a>
                            </li>
                        @endcan

                        {{-- Jalur Reguler Section --}}
                        <li class="nav-header" style="padding-left: 1rem; color: #c2c7d0; font-size: 0.75rem;">JALUR REGULER</li>
                        
                        @can('skripsi_registration_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.skripsi-registrations.index") }}" class="nav-link {{ request()->is("admin/skripsi-registrations") || request()->is("admin/skripsi-registrations/*") ? "active" : "" }}">
                                    <i class="fa-fw nav-icon fas fa-file-signature"></i>
                                    <p>Pendaftaran Reguler</p>
                                </a>
                            </li>
                        @endcan
                        
                        @can('skripsi_seminar_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.skripsi-seminars.index") }}" class="nav-link {{ request()->is("admin/skripsi-seminars") || request()->is("admin/skripsi-seminars/*") ? "active" : "" }}">
                                    <i class="fa-fw nav-icon fas fa-chalkboard-teacher"></i>
                                    <p>Seminar Reguler</p>
                                </a>
                            </li>
                        @endcan

                        {{-- Jalur MBKM Section --}}
                        <li class="nav-header" style="padding-left: 1rem; color: #c2c7d0; font-size: 0.75rem;">JALUR MBKM</li>
                        
                        @can('mbkm_registration_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.mbkm-registrations.index") }}" class="nav-link {{ request()->is("admin/mbkm-registrations") || request()->is("admin/mbkm-registrations/*") ? "active" : "" }}">
                                    <i class="fa-fw nav-icon fas fa-user-graduate"></i>
                                    <p>Pendaftaran MBKM</p>
                                </a>
                            </li>
                        @endcan
                        
                        @can('mbkm_seminar_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.mbkm-seminars.index") }}" class="nav-link {{ request()->is("admin/mbkm-seminars") || request()->is("admin/mbkm-seminars/*") ? "active" : "" }}">
                                    <i class="fa-fw nav-icon fas fa-users-class"></i>
                                    <p>Seminar MBKM</p>
                                </a>
                            </li>
                        @endcan
                        
                        @can('mbkm_group_member_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.mbkm-group-members.index") }}" class="nav-link {{ request()->is("admin/mbkm-group-members") || request()->is("admin/mbkm-group-members/*") ? "active" : "" }}">
                                    <i class="fa-fw nav-icon fas fa-user-friends"></i>
                                    <p>Anggota Kelompok MBKM</p>
                                </a>
                            </li>
                        @endcan

                        {{-- Sidang Section --}}
                        <li class="nav-header" style="padding-left: 1rem; color: #c2c7d0; font-size: 0.75rem;">SIDANG SKRIPSI</li>
                        
                        @can('skripsi_defense_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.skripsi-defenses.index") }}" class="nav-link {{ request()->is("admin/skripsi-defenses") || request()->is("admin/skripsi-defenses/*") ? "active" : "" }}">
                                    <i class="fa-fw nav-icon fas fa-gavel"></i>
                                    <p>Pendaftaran Sidang</p>
                                </a>
                            </li>
                        @endcan

                        {{-- Jadwal & Penugasan Section --}}
                        <li class="nav-header" style="padding-left: 1rem; color: #c2c7d0; font-size: 0.75rem;">JADWAL & PENUGASAN</li>
                        
                        @can('application_schedule_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.application-schedules.index") }}" class="nav-link {{ request()->is("admin/application-schedules") || request()->is("admin/application-schedules/*") ? "active" : "" }}">
                                    <i class="fa-fw nav-icon fas fa-calendar-alt"></i>
                                    <p>Jadwal Seminar/Sidang</p>
                                </a>
                            </li>
                        @endcan
                        
                        @can('application_assignment_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.application-assignments.index") }}" class="nav-link {{ request()->is("admin/application-assignments") || request()->is("admin/application-assignments/*") ? "active" : "" }}">
                                    <i class="fa-fw nav-icon fas fa-user-check"></i>
                                    <p>Penugasan Dosen</p>
                                </a>
                            </li>
                        @endcan

                        {{-- Hasil & Penilaian Section --}}
                        <li class="nav-header" style="padding-left: 1rem; color: #c2c7d0; font-size: 0.75rem;">HASIL & PENILAIAN</li>
                        
                        @can('application_result_seminar_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.application-result-seminars.index") }}" class="nav-link {{ request()->is("admin/application-result-seminars") || request()->is("admin/application-result-seminars/*") ? "active" : "" }}">
                                    <i class="fa-fw nav-icon fas fa-clipboard-check"></i>
                                    <p>Hasil Seminar</p>
                                </a>
                            </li>
                        @endcan
                        
                        @can('application_result_defense_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.application-result-defenses.index") }}" class="nav-link {{ request()->is("admin/application-result-defenses") || request()->is("admin/application-result-defenses/*") ? "active" : "" }}">
                                    <i class="fa-fw nav-icon fas fa-award"></i>
                                    <p>Hasil Sidang</p>
                                </a>
                            </li>
                        @endcan
                        
                        @can('application_score_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.application-scores.index") }}" class="nav-link {{ request()->is("admin/application-scores") || request()->is("admin/application-scores/*") ? "active" : "" }}">
                                    <i class="fa-fw nav-icon fas fa-star"></i>
                                    <p>Penilaian Dosen</p>
                                </a>
                            </li>
                        @endcan

                        {{-- Laporan Section --}}
                        <li class="nav-header" style="padding-left: 1rem; color: #c2c7d0; font-size: 0.75rem;">MONITORING</li>
                        
                        @can('application_report_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.application-reports.index") }}" class="nav-link {{ request()->is("admin/application-reports") || request()->is("admin/application-reports/*") ? "active" : "" }}">
                                    <i class="fa-fw nav-icon fas fa-flag"></i>
                                    <p>Laporan Mahasiswa</p>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route("admin.systemCalendar") }}" class="nav-link {{ request()->is("admin/system-calendar") || request()->is("admin/system-calendar/*") ? "active" : "" }}">
                        <i class="fas fa-fw fa-calendar nav-icon">

                        </i>
                        <p>
                            {{ trans('global.systemCalendar') }}
                        </p>
                    </a>
                </li>
                @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
                    @can('profile_password_edit')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('profile/password') || request()->is('profile/password/*') ? 'active' : '' }}" href="{{ route('profile.password.edit') }}">
                                <i class="fa-fw fas fa-key nav-icon">
                                </i>
                                <p>
                                    {{ trans('global.change_password') }}
                                </p>
                            </a>
                        </li>
                    @endcan
                @endif
                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                        <p>
                            <i class="fas fa-fw fa-sign-out-alt nav-icon">

                            </i>
                            <p>{{ trans('global.logout') }}</p>
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>