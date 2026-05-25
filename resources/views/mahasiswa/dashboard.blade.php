@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <!-- Hero Header with Gradient -->
    <div class="card-modern mb-4" style="background: linear-gradient(135deg, var(--primary-500) 0%, var(--secondary-500) 100%); border: none;">
        <div class="card-modern-body" style="padding: var(--spacing-8);">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center mb-3">
                        <div class="mr-3" style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user-graduate" style="font-size: 28px; color: white;"></i>
                        </div>
                        <div>
                            <h2 class="mb-1 text-white font-weight-bold">Selamat Datang, {{ $mahasiswa->nama }}!</h2>
                            <p class="mb-0" style="color: rgba(255,255,255,0.9); font-size: var(--font-size-lg);">
                                <i class="fas fa-id-card"></i> NIM: {{ $mahasiswa->nim }}
                            </p>
                        </div>
                    </div>
                    <div class="d-flex gap-4 flex-wrap" style="color: rgba(255,255,255,0.9);">
                        <span><i class="fas fa-calendar-alt"></i> {{ now()->translatedFormat('l, d F Y') }}</span>
                        <span><i class="fas fa-clock"></i> {{ now()->format('H:i') }} WIB</span>
                    </div>
                </div>
                <div class="col-md-4 text-right">
                    <a href="{{ route('frontend.choose-path') }}" class="btn btn-modern btn-modern-lg" style="background: white; color: var(--primary-500); box-shadow: var(--shadow-md);">
                        <i class="fas fa-plus-circle"></i> Aplikasi Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Phase Progress Indicator -->
    <div class="card-modern mb-4" style="background: white; border: 2px solid var(--gray-200);">
        <div class="card-modern-header bg-gradient-primary" style="padding: var(--spacing-6);">
            <h4 class="mb-0 text-white d-flex align-items-center">
                <i class="fas fa-chart-line mr-3"></i> Progress Skripsi Anda
            </h4>
        </div>
        <div class="card-modern-body" style="padding: var(--spacing-6);">
            <!-- Enhanced Phase Timeline -->
            <div class="phase-timeline mb-4">
                <div class="timeline-container">
                    <!-- Phase 1: Pendaftaran -->
                    <div class="timeline-item {{ $currentPhase >= 0 ? 'active' : 'inactive' }}">
                        <div class="timeline-dot {{ $currentPhase >= 1 ? 'completed' : ($currentPhase == 0 ? 'current' : 'pending') }}">
                            @if($currentPhase >= 1)
                                <i class="fas fa-check"></i>
                            @else
                                <span>1</span>
                            @endif
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-title">Pendaftaran</div>
                            <div class="timeline-subtitle">Daftar Topik Skripsi</div>
                        </div>
                    </div>
                    <div class="timeline-line {{ $currentPhase >= 1 ? 'completed' : '' }}"></div>
                    
                    <!-- Phase 2: Seminar -->
                    <div class="timeline-item {{ $currentPhase >= 1 ? 'active' : 'inactive' }}">
                        <div class="timeline-dot {{ $currentPhase >= 2 ? 'completed' : ($currentPhase == 1 ? 'current' : 'pending') }}">
                            @if($currentPhase >= 2)
                                <i class="fas fa-check"></i>
                            @else
                                <span>2</span>
                            @endif
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-title">Seminar</div>
                            <div class="timeline-subtitle">Seminar Proposal</div>
                        </div>
                    </div>
                    <div class="timeline-line {{ $currentPhase >= 2 ? 'completed' : '' }}"></div>
                    
                    <!-- Phase 3: Sidang -->
                    <div class="timeline-item {{ $currentPhase >= 2 ? 'active' : 'inactive' }}">
                        <div class="timeline-dot {{ $currentPhase >= 3 ? 'completed' : ($currentPhase == 2 ? 'current' : 'pending') }}">
                            @if($currentPhase >= 3)
                                <i class="fas fa-check"></i>
                            @else
                                <span>3</span>
                            @endif
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-title">Sidang</div>
                            <div class="timeline-subtitle">Sidang Skripsi</div>
                        </div>
                    </div>
                    <div class="timeline-line {{ $currentPhase >= 3 ? 'completed' : '' }}"></div>
                    
                    <!-- Phase 4: Nilai -->
                    <div class="timeline-item {{ $currentPhase >= 3 ? 'active' : 'inactive' }}">
                        <div class="timeline-dot {{ $currentPhase >= 4 ? 'completed' : ($currentPhase == 3 ? 'current' : 'pending') }}">
                            @if($currentPhase >= 4)
                                <i class="fas fa-check"></i>
                            @else
                                <span>4</span>
                            @endif
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-title">Nilai</div>
                            <div class="timeline-subtitle">Penilaian Akhir</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Current Phase Info Card -->
            <div class="alert-modern alert-modern-info">
                <div class="alert-modern-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div class="alert-modern-content">
                    <div class="alert-modern-title" style="font-size: var(--font-size-lg);">{{ $phaseName }}</div>
                    <div class="alert-modern-message">
                        <p class="mb-2">{{ $phaseDescription }}</p>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-arrow-right" style="color: var(--info);"></i>
                            <strong>Langkah selanjutnya:</strong> {{ $nextStep }}
                        </div>
                    </div>
                    @if($currentPhase == 0)
                        <a href="{{ route('frontend.choose-path') }}" class="btn-modern btn-modern-primary mt-3">
                            <i class="fas fa-route"></i> Mulai Daftar
                        </a>
                    @elseif($currentPhase == 1 && $activeApplication && ($supervisorAccepted ?? false))
                        @if($activeApplication->type == 'skripsi')
                            <a href="{{ route('frontend.skripsi-seminars.index') }}" class="btn-modern btn-modern-primary mt-3">
                                <i class="fas fa-presentation"></i> Daftar Seminar
                            </a>
                        @else
                            <a href="{{ route('frontend.mbkm-seminars.index') }}" class="btn-modern btn-modern-primary mt-3">
                                <i class="fas fa-presentation"></i> Daftar Seminar
                            </a>
                        @endif
                    @elseif($currentPhase == 2 && $activeApplication)
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            @can('application_result_seminar_access')
                            <a href="{{ route('frontend.application-result-seminars.index') }}" class="btn-modern btn-modern-primary">
                                <i class="fas fa-clipboard-check"></i> Laporan Hasil Review
                            </a>
                            @endcan
                            @if($allowedForms['skripsi_defense']['allowed'] ?? false)
                                <a href="{{ route('frontend.skripsi-defenses.index') }}" class="btn-modern btn-modern-outline" style="color: var(--primary-500); border-color: var(--primary-500);">
                                    <i class="fas fa-graduation-cap"></i> Daftar Sidang
                                </a>
                            @else
                                <span class="btn-modern btn-modern-outline disabled" style="opacity: 0.65; cursor: not-allowed;" title="{{ $allowedForms['skripsi_defense']['message'] ?? '' }}">
                                    <i class="fas fa-graduation-cap"></i> Daftar Sidang
                                </span>
                                @if($allowedForms['skripsi_defense']['message'] ?? null)
                                    <small class="text-muted d-block w-100 mt-1">{{ $allowedForms['skripsi_defense']['message'] }}</small>
                                @endif
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Enhanced Timeline Styles */
        .phase-timeline {
            padding: var(--spacing-4) 0;
        }
        
        .timeline-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
        }
        
        .timeline-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            position: relative;
            z-index: 2;
        }
        
        .timeline-item.inactive {
            opacity: 0.4;
        }
        
        .timeline-item.active {
            opacity: 1;
        }
        
        .timeline-dot {
            width: 56px;
            height: 56px;
            border-radius: var(--radius-full);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: var(--font-weight-bold);
            font-size: var(--font-size-lg);
            margin-bottom: var(--spacing-3);
            transition: all var(--transition-base);
            box-shadow: var(--shadow-base);
        }
        
        .timeline-dot.pending {
            background: var(--gray-200);
            color: var(--gray-600);
            border: 3px solid var(--gray-300);
        }
        
        .timeline-dot.current {
            background: var(--warning);
            color: white;
            border: 3px solid var(--warning-dark);
            animation: pulse-glow 2s infinite;
            box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7);
        }
        
        .timeline-dot.completed {
            background: var(--success);
            color: white;
            border: 3px solid var(--success-dark);
        }
        
        @keyframes pulse-glow {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7);
            }
            50% {
                box-shadow: 0 0 0 10px rgba(255, 193, 7, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(255, 193, 7, 0);
            }
        }
        
        .timeline-content {
            text-align: center;
        }
        
        .timeline-title {
            font-weight: var(--font-weight-semibold);
            color: var(--gray-900);
            font-size: var(--font-size-sm);
            margin-bottom: var(--spacing-1);
        }
        
        .timeline-subtitle {
            font-size: var(--font-size-xs);
            color: var(--gray-600);
        }
        
        .timeline-line {
            position: absolute;
            top: 28px;
            height: 4px;
            background: var(--gray-300);
            transition: background var(--transition-base);
            z-index: 1;
        }
        
        .timeline-line.completed {
            background: var(--success);
        }
        
        .timeline-line:nth-of-type(1) {
            left: calc(12.5% + 28px);
            width: calc(25% - 56px);
        }
        
        .timeline-line:nth-of-type(2) {
            left: calc(37.5% + 28px);
            width: calc(25% - 56px);
        }
        
        .timeline-line:nth-of-type(3) {
            left: calc(62.5% + 28px);
            width: calc(25% - 56px);
        }
        
        /* Responsive Timeline */
        @media (max-width: 768px) {
            .timeline-container {
                flex-direction: column;
            }
            
            .timeline-item {
                flex-direction: row;
                width: 100%;
                margin-bottom: var(--spacing-6);
            }
            
            .timeline-dot {
                margin-right: var(--spacing-4);
                margin-bottom: 0;
            }
            
            .timeline-content {
                text-align: left;
                flex: 1;
            }
            
            .timeline-line {
                display: none;
            }
        }
    </style>

    <!-- Enhanced Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card-modern hover-lift" style="border-left: 4px solid var(--info);">
                <div class="card-modern-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="text-xs font-weight-semibold text-info text-uppercase mb-1">Total Aplikasi</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $totalApplications }}</div>
                        </div>
                        <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #17a2b8, #138496); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-sm);">
                            <i class="fas fa-file-alt" style="font-size: 20px; color: white;"></i>
                        </div>
                    </div>
                    <a href="{{ route('mahasiswa.aplikasi') }}" class="btn-modern btn-modern-sm btn-modern-outline w-100">
                        Lihat Detail <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card-modern hover-lift" style="border-left: 4px solid var(--warning);">
                <div class="card-modern-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="text-xs font-weight-semibold text-warning text-uppercase mb-1">Aplikasi Aktif</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $activeApplications }}</div>
                        </div>
                        <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #ffc107, #e0a800); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-sm);">
                            <i class="fas fa-spinner fa-pulse" style="font-size: 20px; color: white;"></i>
                        </div>
                    </div>
                    <a href="{{ route('mahasiswa.aplikasi') }}" class="btn-modern btn-modern-sm btn-modern-outline w-100">
                        Lihat Detail <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card-modern hover-lift" style="border-left: 4px solid var(--success);">
                <div class="card-modern-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="text-xs font-weight-semibold text-success text-uppercase mb-1">Aplikasi Selesai</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $completedApplications }}</div>
                        </div>
                        <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #28a745, #1e7e34); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-sm);">
                            <i class="fas fa-check-circle" style="font-size: 20px; color: white;"></i>
                        </div>
                    </div>
                    <a href="{{ route('mahasiswa.aplikasi') }}" class="btn-modern btn-modern-sm btn-modern-outline w-100">
                        Lihat Detail <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Revision Request Alert -->
    @if($activeApplication && $activeApplication->status == 'revision')
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <h5 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Revisi Diperlukan!</h5>
            <p>Admin telah meminta Anda untuk merevisi aplikasi <strong>{{ strtoupper($activeApplication->type) }}</strong> Anda.</p>
            @if(isset($activeApplication->revision_notes) && $activeApplication->revision_notes)
                <hr>
                <p class="mb-0"><strong>Catatan Revisi:</strong><br>{{ $activeApplication->revision_notes }}</p>
            @endif
            <hr>
            @if($activeApplication->type == 'mbkm')
                <a href="{{ route('frontend.mbkm-registrations.index') }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Revisi Pendaftaran MBKM
                </a>
            @elseif($activeApplication->type == 'skripsi')
                <a href="{{ route('frontend.skripsi-registrations.index') }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Revisi Pendaftaran Skripsi
                </a>
            @endif
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Active Application -->
    @if($activeApplication)
        <div class="row">
            <div class="col-lg-8">
                <div class="card-modern">
                    <div class="card-modern-header bg-gradient-primary">
                        <h3 class="mb-0 text-white d-flex align-items-center">
                            <i class="fas fa-chart-line mr-2"></i> Aplikasi Aktif
                        </h3>
                    </div>
                    <div class="card-modern-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong>Jenis:</strong>
                                <p class="text-uppercase">{{ $activeApplication->type }}</p>
                            </div>
                            <div class="col-md-4">
                                <strong>Tahap:</strong>
                                <p class="text-capitalize">{{ $activeApplication->stage }}</p>
                            </div>
                            <div class="col-md-4">
                                <strong>Status:</strong>
                                <p>
                                    @if($activeApplication->stage == 'registration')
                                        @include('partials.mahasiswa-registration-status', ['application' => $activeApplication])
                                    @elseif($activeApplication->status == 'submitted')
                                        <span class="badge badge-warning">Submitted</span>
                                    @elseif($activeApplication->status == 'approved')
                                        <span class="badge badge-success">Approved</span>
                                    @elseif($activeApplication->status == 'rejected')
                                        <span class="badge badge-danger">Rejected</span>
                                    @elseif($activeApplication->status == 'revision')
                                        <span class="badge badge-warning"><i class="fas fa-edit"></i> Perlu Revisi</span>
                                    @elseif($activeApplication->status == 'scheduled')
                                        <span class="badge badge-info">Scheduled</span>
                                    @else
                                        <span class="badge badge-secondary">{{ $activeApplication->status }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        @if(count($assignments) > 0)
                            <div class="mt-4">
                                <h5><i class="fas fa-user-tie"></i> Dosen Pembimbing & Penguji</h5>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Role</th>
                                                <th>Nama Dosen</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($assignments as $assignment)
                                                <tr>
                                                    <td class="text-capitalize">{{ $assignment->role }}</td>
                                                    <td>{{ $assignment->lecturer->nama ?? 'N/A' }}</td>
                                                    <td>
                                                        @if($assignment->status == 'assigned')
                                                            <span class="badge badge-warning">Assigned</span>
                                                        @elseif($assignment->status == 'accepted')
                                                            <span class="badge badge-success">Accepted</span>
                                                        @else
                                                            <span class="badge badge-danger">Rejected</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        <div class="mt-4">
                            <a href="{{ route('frontend.applications.show', $activeApplication->id) }}" class="btn-modern btn-modern-primary">
                                <i class="fas fa-eye"></i> Lihat Detail Aplikasi
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Upcoming Schedules -->
                @if(count($schedules) > 0)
                    <div class="card-modern">
                        <div class="card-modern-header" style="background: linear-gradient(135deg, var(--info), var(--info-dark));">
                            <h3 class="mb-0 text-white d-flex align-items-center">
                                <i class="fas fa-calendar-alt mr-2"></i> Jadwal Terbaru
                            </h3>
                        </div>
                        <div class="card-modern-body p-0">
                            <ul class="list-group list-group-flush">
                                @foreach($schedules as $schedule)
                                    <li class="list-group-item border-0" style="border-bottom: 1px solid var(--gray-200) !important; transition: background var(--transition-fast);" onmouseover="this.style.background='var(--gray-50)'" onmouseout="this.style.background='transparent'">
                                        <div class="d-flex align-items-start">
                                            <div style="width: 48px; height: 48px; background: var(--info-light); border-radius: var(--radius-base); display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-right: var(--spacing-3);">
                                                <i class="fas fa-calendar" style="color: var(--info); font-size: 18px;"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="font-weight-semibold text-primary mb-1">{{ $schedule->schedule_type }}</div>
                                                <div class="text-sm text-gray-600 mb-1">
                                                    <i class="far fa-clock mr-1"></i> {{ $schedule->waktu }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    <i class="fas fa-map-marker-alt mr-1"></i> 
                                                    {{ $schedule->ruang->name ?? $schedule->custom_place ?? 'Online' }}
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="card-modern-footer text-center">
                                <a href="{{ route('mahasiswa.jadwal') }}" class="btn-modern btn-modern-sm btn-modern-ghost">
                                    Lihat Semua Jadwal <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @else
        <!-- Enhanced Empty State -->
        <div class="row">
            <div class="col-12">
                <div class="card-modern" style="background: linear-gradient(135deg, var(--gray-50) 0%, white 100%);">
                    <div class="card-modern-body text-center" style="padding: var(--spacing-12) var(--spacing-6);">
                        <div style="width: 120px; height: 120px; background: var(--primary-50); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--spacing-6);">
                            <i class="fas fa-clipboard-list" style="font-size: 56px; color: var(--primary-500);"></i>
                        </div>
                        <h3 class="mb-3">Belum Ada Aplikasi Aktif</h3>
                        <p class="text-gray-600 mb-4" style="max-width: 500px; margin: 0 auto var(--spacing-6);">
                            Anda belum memiliki aplikasi skripsi yang aktif. Mulai perjalanan skripsi Anda dengan memilih jalur yang sesuai.
                        </p>
                        <a href="{{ route('frontend.choose-path') }}" class="btn-modern btn-modern-primary btn-modern-lg hover-lift">
                            <i class="fas fa-route"></i> Pilih Jalur Skripsi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection
