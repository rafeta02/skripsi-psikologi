@extends('layouts.frontend')
@section('content')
<link rel="stylesheet" href="{{ asset('css/modern-form.css') }}">
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="detail-card">
                <div class="detail-header">
                    <div>
                        <h2>Detail Jadwal</h2>
                        <p>Informasi lengkap jadwal seminar/sidang</p>
                    </div>
                    <div>
                        @if($applicationSchedule->status == 'pending')
                            <span class="status-badge pending large">
                                <i class="fas fa-clock mr-2"></i> Menunggu Persetujuan
                            </span>
                        @elseif($applicationSchedule->status == 'approved')
                            <span class="status-badge approved large">
                                <i class="fas fa-check-circle mr-2"></i> Disetujui
                            </span>
                        @elseif($applicationSchedule->status == 'rejected')
                            <span class="status-badge rejected large">
                                <i class="fas fa-times-circle mr-2"></i> Ditolak
                            </span>
                        @endif
                    </div>
                </div>

                <div class="detail-body">
                    <!-- Informasi Aplikasi -->
                    <div class="detail-section">
                        <h3 class="section-title">
                            <i class="fas fa-file-alt mr-2"></i> Informasi Aplikasi
                        </h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Mahasiswa:</span>
                                    <span class="info-value">{{ $applicationSchedule->application->mahasiswa->nama ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">NIM:</span>
                                    <span class="info-value">{{ $applicationSchedule->application->mahasiswa->nim ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Tipe Aplikasi:</span>
                                    <span class="info-value text-capitalize">{{ $applicationSchedule->application->type ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Stage:</span>
                                    <span class="info-value text-capitalize">{{ $applicationSchedule->application->stage ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Jadwal -->
                    <div class="detail-section">
                        <h3 class="section-title">
                            <i class="fas fa-calendar-alt mr-2"></i> Detail Jadwal
                        </h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Tipe Jadwal:</span>
                                    <span class="info-value">
                                        @if($applicationSchedule->schedule_type == 'seminar' || $applicationSchedule->schedule_type == 'skripsi_seminar')
                                            <span class="badge badge-info">
                                                <i class="fas fa-presentation mr-1"></i> Seminar Proposal
                                            </span>
                                        @elseif($applicationSchedule->schedule_type == 'defense' || $applicationSchedule->schedule_type == 'skripsi_defense')
                                            <span class="badge badge-warning">
                                                <i class="fas fa-gavel mr-1"></i> Sidang Skripsi
                                            </span>
                                        @elseif($applicationSchedule->schedule_type == 'mbkm_seminar')
                                            <span class="badge badge-success">
                                                <i class="fas fa-users mr-1"></i> Seminar MBKM
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">
                                                <i class="fas fa-calendar mr-1"></i> {{ ucfirst($applicationSchedule->schedule_type) }}
                                            </span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Waktu Pelaksanaan:</span>
                                    <span class="info-value">
                                        <i class="fas fa-clock mr-1 text-muted"></i>
                                        {{ $applicationSchedule->waktu ? \Carbon\Carbon::parse($applicationSchedule->waktu)->format('l, d F Y - H:i') : '-' }} WIB
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tempat Pelaksanaan -->
                    <div class="detail-section">
                        <h3 class="section-title">
                            <i class="fas fa-map-marker-alt mr-2"></i> Tempat Pelaksanaan
                        </h3>
                        <div class="row">
                            @if($applicationSchedule->ruang)
                                <div class="col-md-12">
                                    <div class="info-item">
                                        <span class="info-label">Ruangan:</span>
                                        <span class="info-value">
                                            <i class="fas fa-door-open mr-1 text-muted"></i>
                                            {{ $applicationSchedule->ruang->nama ?? '-' }}
                                        </span>
                                    </div>
                                </div>
                            @endif

                            @if($applicationSchedule->custom_place)
                                <div class="col-md-12">
                                    <div class="info-item">
                                        <span class="info-label">Tempat Lain:</span>
                                        <span class="info-value">
                                            <i class="fas fa-building mr-1 text-muted"></i>
                                            {{ $applicationSchedule->custom_place }}
                                        </span>
                                    </div>
                                </div>
                            @endif

                            @if($applicationSchedule->online_meeting)
                                <div class="col-md-12">
                                    <div class="info-item">
                                        <span class="info-label">Link Meeting Online:</span>
                                        <span class="info-value">
                                            <i class="fas fa-video mr-1 text-muted"></i>
                                            <a href="{{ $applicationSchedule->online_meeting }}" target="_blank" class="text-primary">
                                                {{ $applicationSchedule->online_meeting }}
                                                <i class="fas fa-external-link-alt ml-1"></i>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            @endif

                            @if(!$applicationSchedule->ruang && !$applicationSchedule->custom_place && !$applicationSchedule->online_meeting)
                                <div class="col-md-12">
                                    <div class="alert alert-warning mb-0">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        Tempat pelaksanaan belum ditentukan
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Catatan -->
                    @if($applicationSchedule->note)
                        <div class="detail-section">
                            <h3 class="section-title">
                                <i class="fas fa-sticky-note mr-2"></i> Catatan
                            </h3>
                            <div class="note-box">
                                {{ $applicationSchedule->note }}
                            </div>
                        </div>
                    @endif

                    <!-- Dokumen -->
                    @if($applicationSchedule->approval_form && count($applicationSchedule->approval_form) > 0)
                        <div class="detail-section">
                            <h3 class="section-title">
                                <i class="fas fa-file-pdf mr-2"></i> Form Persetujuan
                            </h3>
                            <div class="document-list">
                                @foreach($applicationSchedule->approval_form as $key => $media)
                                    <div class="document-item">
                                        <div class="document-icon">
                                            <i class="fas fa-file-pdf"></i>
                                        </div>
                                        <div class="document-info">
                                            <div class="document-name">{{ $media['file_name'] ?? 'Form Persetujuan ' . ($key + 1) }}</div>
                                            <div class="document-meta">
                                                <span><i class="fas fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($media['created_at'] ?? now())->format('d M Y') }}</span>
                                                <span><i class="fas fa-hdd mr-1"></i>{{ number_format(($media['size'] ?? 0) / 1024, 2) }} KB</span>
                                            </div>
                                        </div>
                                        <div class="document-actions">
                                            <a href="{{ $media['url'] ?? '#' }}" class="btn-icon" target="_blank" title="Lihat">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ $media['url'] ?? '#' }}" class="btn-icon" download title="Download">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Feedback Admin -->
                    @if($applicationSchedule->admin_feedback)
                        <div class="detail-section">
                            <h3 class="section-title">
                                <i class="fas fa-comment-dots mr-2"></i> Feedback Admin
                            </h3>
                            <div class="alert alert-info mb-0">
                                <strong>Admin:</strong> {{ $applicationSchedule->admin_feedback }}
                            </div>
                        </div>
                    @endif
                </div>

                <div class="detail-actions">
                    <a href="{{ route('frontend.application-schedules.index') }}" class="btn-back">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    @can('application_schedule_edit')
                        @if($applicationSchedule->status == 'pending' || $applicationSchedule->status == 'rejected')
                            <a href="{{ route('frontend.application-schedules.edit', $applicationSchedule->id) }}" class="btn-primary-custom">
                                <i class="fas fa-edit mr-2"></i> Edit Jadwal
                            </a>
                        @endif
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection