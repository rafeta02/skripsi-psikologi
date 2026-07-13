@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, #8e44ad 0%, #3498db 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1 text-white font-weight-bold">
                                <i class="fas fa-calendar-alt mr-2"></i> Detail Jadwal
                            </h2>
                            <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                                Informasi lengkap jadwal seminar atau sidang
                            </p>
                        </div>
                        <div>
                            @can('application_schedule_edit')
                                @if($applicationSchedule->application && $applicationSchedule->application->status == 'submitted')
                                    <a href="{{ route('frontend.application-schedules.edit', $applicationSchedule->id) }}" class="btn btn-light">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                @endif
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Content -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card-modern mb-4">
                <div class="card-modern-body">
                    <h4 class="font-weight-bold mb-3">Informasi Jadwal</h4>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="text-muted mb-1">Jenis Acara</label>
                            <h5 class="font-weight-semibold">
                                @if($applicationSchedule->schedule_type === 'mbkm_seminar')
                                    <i class="fas fa-chalkboard-teacher text-primary"></i> Review Kelayakan Proposal
                                @elseif($applicationSchedule->schedule_type === 'skripsi_defense')
                                    <i class="fas fa-graduation-cap text-success"></i> Sidang Skripsi
                                @else
                                    {{ ucfirst(str_replace('_', ' ', $applicationSchedule->schedule_type)) }}
                                @endif
                            </h5>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="text-muted mb-1">Tanggal</label>
                            <p class="font-weight-semibold">
                                <i class="far fa-calendar"></i> {{ \Carbon\Carbon::parse($applicationSchedule->waktu)->translatedFormat('l, d F Y') }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted mb-1">Waktu</label>
                            <p class="font-weight-semibold">
                                <i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($applicationSchedule->waktu)->format('H:i') }} WIB
                            </p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted mb-1">Lokasi</label>
                        @if($applicationSchedule->ruang)
                            <p class="font-weight-semibold">
                                <i class="fas fa-map-marker-alt text-danger"></i> {{ $applicationSchedule->ruang->name }}
                            </p>
                            <small class="text-muted">Pelaksanaan Offline</small>
                        @elseif($applicationSchedule->online_link)
                            <p class="font-weight-semibold mb-2">
                                <i class="fas fa-video text-primary"></i> Online Meeting
                            </p>
                            <a href="{{ $applicationSchedule->online_link }}" target="_blank" class="btn btn-sm btn-primary">
                                <i class="fas fa-external-link-alt"></i> Buka Link Meeting
                            </a>
                        @else
                            <p class="text-muted">Belum ditentukan</p>
                        @endif
                    </div>

                    @if($applicationSchedule->notes)
                        <div class="mb-4">
                            <label class="text-muted mb-1">Catatan</label>
                            <div class="alert alert-light">
                                {{ $applicationSchedule->notes }}
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">Dibuat Pada</label>
                            <p class="font-weight-semibold">
                                {{ $applicationSchedule->created_at->format('d M Y H:i') }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">Terakhir Diupdate</label>
                            <p class="font-weight-semibold">
                                {{ $applicationSchedule->updated_at->format('d M Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Status -->
            <div class="card-modern mb-4">
                <div class="card-modern-body">
                    <h5 class="font-weight-bold mb-3">Status</h5>
                    
                    @if($applicationSchedule->application)
                        @php $scheduleStatus = $applicationSchedule->adminValidationStatus(); @endphp
                        <div class="alert alert-{{ $scheduleStatus['badge'] === 'success' ? 'success' : ($scheduleStatus['badge'] === 'danger' ? 'danger' : 'warning') }}">
                            <i class="fas fa-{{ $scheduleStatus['icon'] }}"></i>
                            <strong>{{ $scheduleStatus['label'] }}</strong>
                            <p class="mb-0 mt-2 small">{{ $scheduleStatus['detail'] }}</p>
                            @if($applicationSchedule->isRejectedByAdmin() && $applicationSchedule->application->notes)
                                <p class="mb-0 mt-2 small"><strong>Catatan admin:</strong> {{ $applicationSchedule->application->notes }}</p>
                            @endif
                        </div>
                        @if($applicationSchedule->application->status == 'done')
                            <div class="alert alert-secondary mt-2">
                                <i class="fas fa-flag-checkered"></i> <strong>Selesai</strong>
                                <p class="mb-0 mt-2 small">Acara telah selesai dilaksanakan</p>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-secondary">
                            <i class="fas fa-info-circle"></i> Status tidak tersedia
                        </div>
                    @endif
                </div>
            </div>

            <!-- Application Info -->
            @if($applicationSchedule->application)
                <div class="card-modern">
                    <div class="card-modern-body">
                        <h5 class="font-weight-bold mb-3">Informasi Aplikasi</h5>
                        
                        <div class="mb-3">
                            <label class="text-muted mb-1">Tipe</label>
                            <p class="font-weight-semibold text-uppercase">
                                <span class="badge badge-primary">{{ $applicationSchedule->application->type }}</span>
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted mb-1">Tahap</label>
                            <p class="font-weight-semibold text-capitalize">
                                <span class="badge badge-info">{{ $applicationSchedule->application->stage }}</span>
                            </p>
                        </div>

                        @if($applicationSchedule->application->mahasiswa)
                            <div class="mb-3">
                                <label class="text-muted mb-1">Mahasiswa</label>
                                <p class="font-weight-semibold">
                                    {{ $applicationSchedule->application->mahasiswa->nama }}
                                </p>
                                <p class="text-sm text-muted">
                                    NIM: {{ $applicationSchedule->application->mahasiswa->nim }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Back Button -->
    <div class="row mt-4">
        <div class="col-lg-12">
            <a href="{{ route('frontend.application-schedules.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>
    </div>
</div>
@endsection
