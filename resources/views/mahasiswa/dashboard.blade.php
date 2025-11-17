@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="mb-4">
        <h2><i class="fas fa-tachometer-alt"></i> Dashboard Mahasiswa</h2>
        <p class="text-muted">Selamat datang, {{ $mahasiswa->nama }}</p>
    </div>

    <!-- Phase Progress Indicator -->
    <div class="card mb-4" style="background: linear-gradient(135deg, #22004C 0%, #4A0080 100%); color: white;">
        <div class="card-body">
            <h4 class="mb-3"><i class="fas fa-route"></i> Progress Skripsi Anda</h4>
            
            <!-- Progress Bar -->
            <div class="progress mb-3" style="height: 30px; background-color: rgba(255,255,255,0.3);">
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ ($currentPhase / 4) * 100 }}%;" aria-valuenow="{{ ($currentPhase / 4) * 100 }}" aria-valuemin="0" aria-valuemax="100">
                    <strong>{{ round(($currentPhase / 4) * 100) }}%</strong>
                </div>
            </div>
            
            <!-- Phase Steps -->
            <div class="row text-center mb-3">
                <div class="col">
                    <div class="phase-step {{ $currentPhase >= 0 ? 'active' : '' }}">
                        <div class="phase-icon {{ $currentPhase >= 1 ? 'completed' : ($currentPhase == 0 ? 'current' : '') }}">
                            @if($currentPhase >= 1)
                                <i class="fas fa-check-circle"></i>
                            @else
                                <i class="fas fa-circle"></i>
                            @endif
                        </div>
                        <small class="d-block mt-2">Pendaftaran</small>
                    </div>
                </div>
                <div class="col">
                    <div class="phase-step {{ $currentPhase >= 1 ? 'active' : '' }}">
                        <div class="phase-icon {{ $currentPhase >= 2 ? 'completed' : ($currentPhase == 1 ? 'current' : '') }}">
                            @if($currentPhase >= 2)
                                <i class="fas fa-check-circle"></i>
                            @else
                                <i class="fas fa-circle"></i>
                            @endif
                        </div>
                        <small class="d-block mt-2">Seminar</small>
                    </div>
                </div>
                <div class="col">
                    <div class="phase-step {{ $currentPhase >= 2 ? 'active' : '' }}">
                        <div class="phase-icon {{ $currentPhase >= 3 ? 'completed' : ($currentPhase == 2 ? 'current' : '') }}">
                            @if($currentPhase >= 3)
                                <i class="fas fa-check-circle"></i>
                            @else
                                <i class="fas fa-circle"></i>
                            @endif
                        </div>
                        <small class="d-block mt-2">Sidang</small>
                    </div>
                </div>
                <div class="col">
                    <div class="phase-step {{ $currentPhase >= 3 ? 'active' : '' }}">
                        <div class="phase-icon {{ $currentPhase >= 4 ? 'completed' : ($currentPhase == 3 ? 'current' : '') }}">
                            @if($currentPhase >= 4)
                                <i class="fas fa-check-circle"></i>
                            @else
                                <i class="fas fa-circle"></i>
                            @endif
                        </div>
                        <small class="d-block mt-2">Nilai</small>
                    </div>
                </div>
            </div>
            
            <!-- Current Phase Info -->
            <div class="alert alert-light mb-0">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-1 text-dark"><strong>{{ $phaseName }}</strong></h5>
                        <p class="mb-1 text-dark">{{ $phaseDescription }}</p>
                        <small class="text-muted"><i class="fas fa-info-circle"></i> Langkah selanjutnya: {{ $nextStep }}</small>
                    </div>
                    <div class="col-md-4 text-right">
                        @if($currentPhase == 0)
                            <a href="{{ route('frontend.choose-path') }}" class="btn btn-success btn-lg">
                                <i class="fas fa-route"></i> Mulai Daftar
                            </a>
                        @elseif($currentPhase == 1 && $activeApplication)
                            @if($activeApplication->type == 'skripsi')
                                <a href="{{ route('frontend.skripsi-seminars.index') }}" class="btn btn-success btn-lg">
                                    <i class="fas fa-presentation"></i> Daftar Seminar
                                </a>
                            @else
                                <a href="{{ route('frontend.mbkm-seminars.index') }}" class="btn btn-success btn-lg">
                                    <i class="fas fa-presentation"></i> Daftar Seminar
                                </a>
                            @endif
                        @elseif($currentPhase == 2 && $activeApplication)
                            <a href="{{ route('frontend.skripsi-defenses.index') }}" class="btn btn-success btn-lg">
                                <i class="fas fa-graduation-cap"></i> Daftar Sidang
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .phase-step {
            opacity: 0.5;
        }
        .phase-step.active {
            opacity: 1;
        }
        .phase-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin: 0 auto;
        }
        .phase-icon.current {
            background: #ffc107;
            color: #000;
            animation: pulse 2s infinite;
        }
        .phase-icon.completed {
            background: #28a745;
            color: white;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
    </style>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalApplications }}</h3>
                    <p>Total Aplikasi</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <a href="{{ route('mahasiswa.aplikasi') }}" class="small-box-footer">
                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $activeApplications }}</h3>
                    <p>Aplikasi Aktif</p>
                </div>
                <div class="icon">
                    <i class="fas fa-spinner"></i>
                </div>
                <a href="{{ route('mahasiswa.aplikasi') }}" class="small-box-footer">
                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $completedApplications }}</h3>
                    <p>Aplikasi Selesai</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <a href="{{ route('mahasiswa.aplikasi') }}" class="small-box-footer">
                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                </a>
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
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title"><i class="fas fa-chart-line"></i> Aplikasi Aktif</h3>
                    </div>
                    <div class="card-body">
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
                                    @if($activeApplication->status == 'submitted')
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

                        <div class="mt-3">
                            <a href="{{ route('frontend.applications.show', $activeApplication->id) }}" class="btn btn-primary">
                                <i class="fas fa-eye"></i> Lihat Detail Aplikasi
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Upcoming Schedules -->
                @if(count($schedules) > 0)
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h3 class="card-title"><i class="fas fa-calendar-alt"></i> Jadwal Terbaru</h3>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                @foreach($schedules as $schedule)
                                    <li class="list-group-item">
                                        <strong class="text-primary">{{ $schedule->schedule_type }}</strong>
                                        <p class="mb-1"><i class="far fa-clock"></i> {{ $schedule->waktu }}</p>
                                        <p class="mb-0 text-muted small">
                                            <i class="fas fa-map-marker-alt"></i> 
                                            {{ $schedule->ruang->name ?? $schedule->custom_place ?? 'Online' }}
                                        </p>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="card-footer text-center">
                                <a href="{{ route('mahasiswa.jadwal') }}" class="btn btn-sm btn-info">Lihat Semua Jadwal</a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
                        <h3>Belum Ada Aplikasi Aktif</h3>
                        <p class="text-muted">Anda belum memiliki aplikasi skripsi yang aktif. Pilih jalur skripsi untuk memulai.</p>
                        <a href="{{ route('frontend.choose-path') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-route"></i> Pilih Jalur Skripsi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Recent Applications -->
    @if(count($recentApplications) > 0)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-history"></i> Aplikasi Terbaru</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Jenis</th>
                                        <th>Tahap</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentApplications as $app)
                                        <tr>
                                            <td class="text-uppercase"><strong>{{ $app->type }}</strong></td>
                                            <td class="text-capitalize">{{ $app->stage }}</td>
                                            <td>
                                                @if($app->status == 'submitted')
                                                    <span class="badge badge-warning">Submitted</span>
                                                @elseif($app->status == 'approved')
                                                    <span class="badge badge-success">Approved</span>
                                                @elseif($app->status == 'rejected')
                                                    <span class="badge badge-danger">Rejected</span>
                                                @elseif($app->status == 'revision')
                                                    <span class="badge badge-warning"><i class="fas fa-edit"></i> Perlu Revisi</span>
                                                @elseif($app->status == 'scheduled')
                                                    <span class="badge badge-info">Scheduled</span>
                                                @elseif($app->status == 'done')
                                                    <span class="badge badge-secondary">Done</span>
                                                @endif
                                            </td>
                                            <td>{{ $app->submitted_at ?? $app->created_at->format('d M Y') }}</td>
                                            <td>
                                                <a href="{{ route('frontend.applications.show', $app->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i> Lihat
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
