@extends('layouts.dosen')

@section('content')
<div class="container py-4">
    <!-- Hero Header with Gradient -->
    <div class="card-modern mb-4" style="background: linear-gradient(135deg, var(--dosen-primary) 0%, var(--dosen-secondary) 100%); border: none;">
        <div class="card-modern-body" style="padding: var(--spacing-8);">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center mb-3">
                        <div class="mr-3" style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-chalkboard-teacher" style="font-size: 28px; color: white;"></i>
                        </div>
                        <div>
                            <h2 class="mb-1 text-white font-weight-bold">Selamat Datang, {{ $dosen->nama }}!</h2>
                            <p class="mb-0" style="color: rgba(255,255,255,0.9); font-size: var(--font-size-lg);">
                                <span class="mr-4"><i class="fas fa-id-card"></i> NIDN: {{ $dosen->nidn }}</span>
                                <span><i class="fas fa-id-badge"></i> NIP: {{ $dosen->nip }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="d-flex gap-4 flex-wrap" style="color: rgba(255,255,255,0.9);">
                        <span><i class="fas fa-calendar-alt"></i> {{ now()->translatedFormat('l, d F Y') }}</span>
                        <span><i class="fas fa-clock"></i> {{ now()->format('H:i') }} WIB</span>
                    </div>
                </div>
                <div class="col-md-4 text-right">
                    <div style="color: rgba(255,255,255,0.9); font-size: var(--font-size-lg);">
                        <div class="mb-2"><i class="fas fa-users mr-2"></i> <strong>{{ $totalMahasiswaBimbingan }}</strong> Mahasiswa Bimbingan</div>
                        <div><i class="fas fa-tasks mr-2"></i> <strong>{{ $totalTaskAssignments }}</strong> Task Assignment</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card-modern hover-lift" style="border-left: 4px solid var(--info);">
                <div class="card-modern-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="text-xs font-weight-semibold text-info text-uppercase mb-1">Mahasiswa Bimbingan</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $totalMahasiswaBimbingan }}</div>
                        </div>
                        <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #17a2b8, #138496); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-sm);">
                            <i class="fas fa-users" style="font-size: 20px; color: white;"></i>
                        </div>
                    </div>
                    <a href="{{ route('dosen.mahasiswa-bimbingan') }}" class="btn-modern btn-modern-sm btn-modern-outline w-100">
                        Lihat Detail <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card-modern hover-lift" style="border-left: 4px solid var(--warning);">
                <div class="card-modern-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="text-xs font-weight-semibold text-warning text-uppercase mb-1">Task Assignment</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $totalTaskAssignments }}</div>
                        </div>
                        <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #ffc107, #e0a800); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-sm);">
                            <i class="fas fa-tasks" style="font-size: 20px; color: white;"></i>
                        </div>
                    </div>
                    <a href="{{ route('dosen.task-assignments') }}" class="btn-modern btn-modern-sm btn-modern-outline w-100">
                        Lihat Detail <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card-modern hover-lift" style="border-left: 4px solid var(--success);">
                <div class="card-modern-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="text-xs font-weight-semibold text-success text-uppercase mb-1">Bimbingan Selesai</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $completedGuidance }}</div>
                        </div>
                        <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #28a745, #1e7e34); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-sm);">
                            <i class="fas fa-check-circle" style="font-size: 20px; color: white;"></i>
                        </div>
                    </div>
                    <a href="{{ route('dosen.mahasiswa-bimbingan') }}" class="btn-modern btn-modern-sm btn-modern-outline w-100">
                        Lihat Detail <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card-modern hover-lift" style="border-left: 4px solid var(--danger);">
                <div class="card-modern-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="text-xs font-weight-semibold text-danger text-uppercase mb-1">Pending Review</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $pendingReviews }}</div>
                        </div>
                        <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #dc3545, #c82333); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-sm);">
                            <i class="fas fa-clock" style="font-size: 20px; color: white;"></i>
                        </div>
                    </div>
                    <a href="{{ route('dosen.task-assignments') }}" class="btn-modern btn-modern-sm btn-modern-outline w-100">
                        Lihat Detail <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Task Assignments -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card-modern">
                <div class="card-modern-header" style="background: linear-gradient(135deg, var(--dosen-primary), var(--dosen-secondary));">
                    <h3 class="mb-0 text-white d-flex align-items-center">
                        <i class="fas fa-clipboard-list mr-2"></i> Assignment Terbaru
                    </h3>
                </div>
                <div class="card-modern-body p-0">
                    @if(count($recentAssignments) > 0)
                        <div class="table-responsive">
                            <table class="table-modern table-modern-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Mahasiswa</th>
                                        <th>Role</th>
                                        <th>Topik</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentAssignments as $assignment)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div style="width: 32px; height: 32px; background: var(--primary-light); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin-right: var(--spacing-2);">
                                                        <i class="fas fa-user" style="font-size: 12px; color: white;"></i>
                                                    </div>
                                                    <div>
                                                        <div class="font-weight-semibold">{{ $assignment->application->mahasiswa->nama ?? 'N/A' }}</div>
                                                        <div class="text-xs text-gray-600">{{ $assignment->application->mahasiswa->nim ?? 'N/A' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-capitalize">
                                                <span class="badge-modern {{ $assignment->role == 'supervisor' ? 'badge-modern-primary' : 'badge-modern-info' }}">
                                                    {{ $assignment->role }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="text-sm">
                                                    {{ Str::limit($assignment->application->skripsiRegistration->title ?? $assignment->application->mbkmRegistration->title ?? 'N/A', 50) }}
                                                </div>
                                            </td>
                                            <td>
                                                @if($assignment->status == 'assigned')
                                                    <span class="badge-modern badge-modern-warning">Assigned</span>
                                                @elseif($assignment->status == 'accepted')
                                                    <span class="badge-modern badge-modern-success">Accepted</span>
                                                @else
                                                    <span class="badge-modern badge-modern-danger">Rejected</span>
                                                @endif
                                            </td>
                                            <td>
                                                <i class="far fa-calendar mr-1"></i>
                                                {{ $assignment->assigned_at ? \Carbon\Carbon::parse($assignment->assigned_at)->format('d M Y') : 'N/A' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state text-center py-5">
                            <div style="width: 80px; height: 80px; background: var(--gray-100); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--spacing-4);">
                                <i class="fas fa-inbox fa-2x text-muted"></i>
                            </div>
                            <p class="text-muted mb-0">Tidak ada assignment terbaru.</p>
                        </div>
                    @endif
                </div>
                @if(count($recentAssignments) > 0)
                    <div class="card-modern-footer text-center">
                        <a href="{{ route('dosen.task-assignments') }}" class="btn-modern btn-modern-sm btn-modern-ghost">
                            Lihat Semua Assignment <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card-modern mb-4">
                <div class="card-modern-header" style="background: linear-gradient(135deg, var(--success), var(--success-dark));">
                    <h3 class="mb-0 text-white d-flex align-items-center">
                        <i class="fas fa-bolt mr-2"></i> Quick Actions
                    </h3>
                </div>
                <div class="card-modern-body p-3">
                    <a href="{{ route('dosen.mahasiswa-bimbingan') }}" class="btn-modern btn-modern-outline btn-modern-block mb-2">
                        <i class="fas fa-users"></i> Mahasiswa Bimbingan
                    </a>
                    <a href="{{ route('dosen.task-assignments') }}" class="btn-modern btn-modern-outline btn-modern-block mb-2">
                        <i class="fas fa-tasks"></i> Task Assignments
                    </a>
                    <a href="{{ route('dosen.scores') }}" class="btn-modern btn-modern-outline btn-modern-block mb-2">
                        <i class="fas fa-chart-bar"></i> Nilai Mahasiswa
                    </a>
                    <a href="{{ route('dosen.profile') }}" class="btn-modern btn-modern-outline btn-modern-block">
                        <i class="fas fa-user-cog"></i> Profile Settings
                    </a>
                </div>
            </div>

            <!-- Statistics Summary -->
            <div class="card-modern">
                <div class="card-modern-header" style="background: linear-gradient(135deg, var(--info), var(--info-dark));">
                    <h3 class="mb-0 text-white d-flex align-items-center">
                        <i class="fas fa-chart-pie mr-2"></i> Ringkasan
                    </h3>
                </div>
                <div class="card-modern-body">
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-gray-700"><i class="fas fa-user-graduate mr-2"></i> Total Mahasiswa</span>
                            <span class="h5 mb-0 font-weight-bold">{{ $totalMahasiswaBimbingan }}</span>
                        </div>
                    </div>
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-gray-700"><i class="fas fa-clipboard-check mr-2"></i> Task Completed</span>
                            <span class="h5 mb-0 font-weight-bold text-success">{{ $completedGuidance }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-gray-700"><i class="fas fa-hourglass-half mr-2"></i> Pending Review</span>
                            <span class="h5 mb-0 font-weight-bold text-warning">{{ $pendingReviews }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
