@extends('layouts.admin')
@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Dashboard Admin</h1>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        @if(session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $stats['total_applications'] }}</h3>
                        <p>Total Aplikasi</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <a href="{{ route('admin.applications.index') }}" class="small-box-footer">
                        Lihat <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $stats['pending_verifications'] }}</h3>
                        <p>Perlu Verifikasi</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <a href="{{ route('admin.applications.index') }}?status=submitted" class="small-box-footer">
                        Review <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $stats['approved_applications'] }}</h3>
                        <p>Disetujui</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <a href="{{ route('admin.applications.index') }}?status=approved" class="small-box-footer">
                        Lihat <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $stats['ongoing_defenses'] }}</h3>
                        <p>Sidang Berlangsung</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <a href="{{ route('admin.skripsi-defenses.index') }}" class="small-box-footer">
                        Lihat <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Secondary Stats -->
        <div class="row">
            <div class="col-lg-4">
                <div class="info-box">
                    <span class="info-box-icon bg-primary"><i class="fas fa-user-graduate"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Mahasiswa</span>
                        <span class="info-box-number">{{ $stats['total_mahasiswa'] }}</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-chalkboard-teacher"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Dosen</span>
                        <span class="info-box-number">{{ $stats['total_dosen'] }}</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="fas fa-tasks"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Assignment Pending</span>
                        <span class="info-box-number">{{ $stats['pending_assignments'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Verifications Summary -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">
                            <i class="fas fa-tasks"></i> Verifikasi Pending
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="callout callout-warning">
                                    <h5>Pendaftaran</h5>
                                    <p class="mb-0">
                                        <strong>{{ $pendingRegistrations }}</strong> pendaftaran menunggu verifikasi
                                    </p>
                                    <a href="{{ route('admin.skripsi-registrations.index') }}" class="btn btn-sm btn-warning mt-2">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="callout callout-info">
                                    <h5>Seminar</h5>
                                    <p class="mb-0">
                                        <strong>{{ $pendingSeminars }}</strong> seminar menunggu verifikasi
                                    </p>
                                    <a href="{{ route('admin.skripsi-seminars.index') }}" class="btn btn-sm btn-info mt-2">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="callout callout-danger">
                                    <h5>Sidang</h5>
                                    <p class="mb-0">
                                        <strong>{{ $pendingDefenses }}</strong> sidang menunggu verifikasi
                                    </p>
                                    <a href="{{ route('admin.skripsi-defenses.index') }}" class="btn btn-sm btn-danger mt-2">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">
                            <i class="fas fa-bolt"></i> Quick Actions
                        </h3>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('admin.monitoring') }}" class="btn btn-primary btn-lg mr-2 mb-2">
                            <i class="fas fa-chart-line"></i> Monitoring Dashboard
                        </a>
                        <a href="{{ route('admin.application-assignments.create') }}" class="btn btn-success btn-lg mr-2 mb-2">
                            <i class="fas fa-user-plus"></i> Assign Dosen
                        </a>
                        <a href="{{ route('admin.applications.index') }}" class="btn btn-info btn-lg mr-2 mb-2">
                            <i class="fas fa-list"></i> Lihat Semua Aplikasi
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Applications -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">
                            <i class="fas fa-clock"></i> Aplikasi Terbaru
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Mahasiswa</th>
                                    <th>Tipe</th>
                                    <th>Tahap</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentApplications as $app)
                                    <tr>
                                        <td>
                                            {{ $app->mahasiswa->nama ?? 'N/A' }}<br>
                                            <small class="text-muted">{{ $app->mahasiswa->nim ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $app->type == 'skripsi' ? 'primary' : 'info' }}">
                                                {{ strtoupper($app->type) }}
                                            </span>
                                        </td>
                                        <td>{{ ucfirst($app->stage) }}</td>
                                        <td>
                                            @if($app->status == 'submitted')
                                                <span class="badge badge-warning">Submitted</span>
                                            @elseif($app->status == 'approved')
                                                <span class="badge badge-success">Approved</span>
                                            @elseif($app->status == 'rejected')
                                                <span class="badge badge-danger">Rejected</span>
                                            @else
                                                <span class="badge badge-secondary">{{ $app->status }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $app->created_at->format('d M Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.applications.show', $app->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada aplikasi terbaru</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
@endsection