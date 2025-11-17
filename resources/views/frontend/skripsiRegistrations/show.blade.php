@extends('layouts.frontend')
@section('content')
<style>
    .detail-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    
    .detail-header {
        background: linear-gradient(135deg, #22004C 0%, #4A0080 100%);
        color: white;
        padding: 2rem;
    }
    
    .detail-header h2 {
        margin: 0 0 0.5rem;
        font-size: 1.75rem;
        font-weight: 600;
    }
    
    .detail-header .subtitle {
        opacity: 0.9;
        font-size: 0.95rem;
    }
    
    .detail-body {
        padding: 2.5rem;
    }
    
    .info-section {
        margin-bottom: 2.5rem;
    }
    
    .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e2e8f0;
        display: flex;
        align-items: center;
    }
    
    .section-title i {
        margin-right: 0.5rem;
        color: #22004C;
    }
    
    .info-row {
        display: grid;
        grid-template-columns: 200px 1fr;
        gap: 1rem;
        padding: 1rem 0;
        border-bottom: 1px solid #f7fafc;
    }
    
    .info-row:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 600;
        color: #4a5568;
        font-size: 0.95rem;
    }
    
    .info-value {
        color: #2d3748;
        font-size: 0.95rem;
    }
    
    .info-value.empty {
        color: #a0aec0;
        font-style: italic;
    }
    
    .document-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .document-item {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        background: #f7fafc;
        border-radius: 8px;
        transition: all 0.2s;
    }
    
    .document-item:hover {
        background: #edf2f7;
        transform: translateX(4px);
    }
    
    .document-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #22004C 0%, #4A0080 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-right: 1rem;
    }
    
    .document-info {
        flex: 1;
    }
    
    .document-name {
        font-weight: 500;
        color: #2d3748;
        font-size: 0.9rem;
    }
    
    .document-link {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        background: #22004C;
        color: white;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.2s;
    }
    
    .document-link:hover {
        background: #4A0080;
        color: white;
        text-decoration: none;
        transform: translateY(-1px);
    }
    
    .document-link i {
        margin-right: 0.5rem;
    }
    
    .action-buttons {
        display: flex;
        gap: 1rem;
        padding: 2rem 2.5rem;
        background: #f8f9fa;
        border-top: 1px solid #e9ecef;
    }
    
    .btn-custom {
        padding: 0.65rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.95rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }
    
    .btn-back {
        background: #e2e8f0;
        color: #4a5568;
    }
    
    .btn-back:hover {
        background: #cbd5e0;
        color: #4a5568;
        text-decoration: none;
    }
    
    .btn-edit {
        background: linear-gradient(135deg, #22004C 0%, #4A0080 100%);
        color: white;
    }
    
    .btn-edit:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(34, 0, 76, 0.4);
        color: white;
        text-decoration: none;
    }
    
    .status-badge {
        display: inline-block;
        padding: 0.35rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    
    .status-submitted {
        background: #fef3c7;
        color: #92400e;
    }
    
    .status-approved {
        background: #d1fae5;
        color: #065f46;
    }
    
    .status-rejected {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .abstract-text {
        line-height: 1.7;
        color: #4a5568;
        white-space: pre-wrap;
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="detail-card">
                <!-- Header -->
                <div class="detail-header">
                    <h2>Detail Pendaftaran Skripsi</h2>
                    <div class="subtitle">Informasi lengkap pendaftaran topik skripsi Anda</div>
                </div>

                <!-- Body -->
                <div class="detail-body">
                
                    <!-- Supervisor Approval Status -->
                    @if($skripsiRegistration->application && $skripsiRegistration->application->status == 'approved')
                        @php
                            $supervisorAssignment = \App\Models\ApplicationAssignment::where('application_id', $skripsiRegistration->application_id)
                                ->where('role', 'supervisor')
                                ->with('lecturer')
                                ->first();
                        @endphp

                        @if($supervisorAssignment && $supervisorAssignment->status == 'assigned')
                            <div class="alert alert-warning mb-4" style="border-left: 5px solid #ffc107;">
                                <h5 class="alert-heading">
                                    <i class="fas fa-hourglass-half mr-2"></i> 
                                    Menunggu Persetujuan Dosen Pembimbing
                                </h5>
                                <hr>
                                <p class="mb-2"><strong>Status:</strong></p>
                                <p class="mb-3">Admin telah menyetujui pendaftaran Anda dan menugaskan dosen pembimbing. Saat ini menunggu persetujuan dari dosen pembimbing yang ditugaskan.</p>
                                <div class="bg-white p-3 rounded mb-0" style="border-left: 3px solid #ffc107;">
                                    <strong><i class="fas fa-user-tie mr-2"></i>Dosen Pembimbing:</strong><br>
                                    {{ $supervisorAssignment->lecturer->nama ?? 'N/A' }}
                                </div>
                            </div>
                        @elseif($supervisorAssignment && $supervisorAssignment->status == 'accepted')
                            <div class="alert alert-success mb-4" style="border-left: 5px solid #28a745;">
                                <h5 class="alert-heading">
                                    <i class="fas fa-check-circle mr-2"></i> 
                                    Dosen Pembimbing Telah Menyetujui
                                </h5>
                                <hr>
                                <p class="mb-3">Selamat! Dosen pembimbing telah menyetujui untuk membimbing skripsi Anda.</p>
                                <div class="bg-white p-3 rounded mb-2" style="border-left: 3px solid #28a745;">
                                    <strong><i class="fas fa-user-tie mr-2"></i>Dosen Pembimbing:</strong><br>
                                    {{ $supervisorAssignment->lecturer->nama ?? 'N/A' }}
                                </div>
                                @if($supervisorAssignment->note)
                                    <div class="bg-white p-3 rounded mb-0" style="border-left: 3px solid #28a745;">
                                        <strong><i class="fas fa-comment mr-2"></i>Catatan Dosen:</strong><br>
                                        {{ $supervisorAssignment->note }}
                                    </div>
                                @endif
                            </div>
                        @elseif($supervisorAssignment && $supervisorAssignment->status == 'rejected')
                            <div class="alert alert-danger mb-4" style="border-left: 5px solid #dc3545;">
                                <h5 class="alert-heading">
                                    <i class="fas fa-times-circle mr-2"></i> 
                                    Dosen Pembimbing Menolak
                                </h5>
                                <hr>
                                <p class="mb-3">Dosen pembimbing menolak penugasan pembimbingan. Anda dapat mengedit pendaftaran untuk memilih dosen pembimbing yang berbeda atau memperbaiki data pendaftaran.</p>
                                <div class="bg-white p-3 rounded mb-2" style="border-left: 3px solid #dc3545;">
                                    <strong><i class="fas fa-user-tie mr-2"></i>Dosen yang Menolak:</strong><br>
                                    {{ $supervisorAssignment->lecturer->nama ?? 'N/A' }}
                                </div>
                                @if($supervisorAssignment->note)
                                    <div class="bg-white p-3 rounded mb-3" style="border-left: 3px solid #dc3545;">
                                        <strong><i class="fas fa-comment mr-2"></i>Alasan Penolakan:</strong><br>
                                        {{ $supervisorAssignment->note }}
                                    </div>
                                @endif
                                
                                <p class="mb-3"><strong>Pilihan Tindakan:</strong></p>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="card h-100 border-warning">
                                            <div class="card-body">
                                                <h6 class="card-title">
                                                    <i class="fas fa-edit text-warning mr-2"></i>
                                                    Edit Pendaftaran
                                                </h6>
                                                <p class="card-text small">Perbaiki data pendaftaran Anda atau pilih dosen pembimbing yang berbeda. Setelah di-edit, status akan kembali ke "Menunggu Review" oleh admin.</p>
                                                <a href="{{ route('frontend.skripsi-registrations.edit', $skripsiRegistration->id) }}" class="btn btn-warning btn-block">
                                                    <i class="fas fa-edit mr-2"></i> Edit Pendaftaran Sekarang
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif

                    <!-- Rejection Alert -->
                    @if($skripsiRegistration->application && $skripsiRegistration->application->status == 'rejected')
                        <div class="alert alert-danger mb-4" style="border-left: 5px solid #dc3545;">
                            <h5 class="alert-heading">
                                <i class="fas fa-times-circle mr-2"></i> 
                                Pendaftaran Anda Ditolak
                            </h5>
                            <hr>
                            <p class="mb-3"><strong>Alasan Penolakan:</strong></p>
                            <div class="bg-white p-3 rounded mb-3" style="border-left: 3px solid #dc3545;">
                                {{ $skripsiRegistration->rejection_reason ?? 'Tidak ada alasan yang diberikan.' }}
                            </div>
                            <p class="mb-0 text-muted">
                                <i class="fas fa-info-circle mr-1"></i>
                                Anda dapat menghubungi admin untuk informasi lebih lanjut atau memperbaiki data pendaftaran Anda.
                            </p>
                        </div>
                    @endif
                    
                    <!-- Revision Alert -->
                    @if($skripsiRegistration->application && $skripsiRegistration->application->status == 'revision' && $skripsiRegistration->revision_notes)
                        <div class="alert alert-warning mb-4" style="border-left: 5px solid #ffc107;">
                            <h5 class="alert-heading">
                                <i class="fas fa-exclamation-triangle mr-2"></i> 
                                Pendaftaran Anda Perlu Direvisi
                            </h5>
                            <hr>
                            <p class="mb-3"><strong>Catatan dari Admin:</strong></p>
                            <div class="bg-white p-3 rounded mb-3" style="border-left: 3px solid #ffc107;">
                                {{ $skripsiRegistration->revision_notes }}
                            </div>
                            <a href="{{ route('frontend.skripsi-registrations.edit', $skripsiRegistration->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit mr-2"></i> Revisi Sekarang
                            </a>
                        </div>
                    @endif
                    <!-- Basic Information -->
                    <div class="info-section">
                        <div class="section-title">
                            <i class="fas fa-info-circle"></i>
                            Informasi Dasar
                        </div>
                        
                        <div class="info-row">
                            <div class="info-label">Status Aplikasi</div>
                            <div class="info-value">
                                @if($skripsiRegistration->application)
                                    <span class="status-badge status-{{ $skripsiRegistration->application->status }}">
                                        {{ ucfirst($skripsiRegistration->application->status ?? 'N/A') }}
                                    </span>
                                @else
                                    <span class="info-value empty">Tidak ada aplikasi</span>
                                @endif
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">Tema Keilmuan</div>
                            <div class="info-value">
                                {{ $skripsiRegistration->theme->name ?? '-' }}
                            </div>
                        </div>
                    </div>

                    <!-- Topic Information -->
                    <div class="info-section">
                        <div class="section-title">
                            <i class="fas fa-book"></i>
                            Topik Skripsi
                        </div>
                        
                        <div class="info-row">
                            <div class="info-label">Judul Skripsi</div>
                            <div class="info-value">
                                <strong>{{ $skripsiRegistration->title ?? '-' }}</strong>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">Abstrak</div>
                            <div class="info-value">
                                @if($skripsiRegistration->abstract)
                                    <div class="abstract-text">{{ $skripsiRegistration->abstract }}</div>
                                @else
                                    <span class="empty">Belum ada abstrak</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Lecturers Information -->
                    <div class="info-section">
                        <div class="section-title">
                            <i class="fas fa-user-tie"></i>
                            Informasi Dosen
                        </div>
                        
                        <div class="info-row">
                            <div class="info-label">Dosen TPS</div>
                            <div class="info-value">
                                {{ $skripsiRegistration->tps_lecturer->nama ?? '-' }}
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">Preferensi Pembimbing</div>
                            <div class="info-value">
                                {{ $skripsiRegistration->preference_supervision->nama ?? '-' }}
                            </div>
                        </div>
                    </div>

                    <!-- Documents -->
                    <div class="info-section">
                        <div class="section-title">
                            <i class="fas fa-file-alt"></i>
                            Dokumen Persyaratan
                        </div>
                        
                        <div class="info-row">
                            <div class="info-label">KHS (Semua Semester)</div>
                            <div class="info-value">
                                @if($skripsiRegistration->khs_all && count($skripsiRegistration->khs_all) > 0)
                                    <div class="document-list">
                                        @foreach($skripsiRegistration->khs_all as $key => $media)
                                            <div class="document-item">
                                                <div class="document-icon">
                                                    <i class="fas fa-file-pdf"></i>
                                                </div>
                                                <div class="document-info">
                                                    <div class="document-name">KHS File {{ $key + 1 }}</div>
                                                </div>
                                                <a href="{{ $media->getUrl() }}" target="_blank" class="document-link">
                                                    <i class="fas fa-eye"></i> Lihat File
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="empty">Belum ada file KHS</span>
                                @endif
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">KRS (Semester Terakhir)</div>
                            <div class="info-value">
                                @if($skripsiRegistration->krs_latest)
                                    <div class="document-list">
                                        <div class="document-item">
                                            <div class="document-icon">
                                                <i class="fas fa-file-pdf"></i>
                                            </div>
                                            <div class="document-info">
                                                <div class="document-name">KRS Semester Terakhir</div>
                                            </div>
                                            <a href="{{ $skripsiRegistration->krs_latest->getUrl() }}" target="_blank" class="document-link">
                                                <i class="fas fa-eye"></i> Lihat File
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <span class="empty">Belum ada file KRS</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a class="btn-custom btn-back" href="{{ route('frontend.skripsi-registrations.index') }}">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                    </a>
                    @can('skripsi_registration_edit')
                        <a class="btn-custom btn-edit" href="{{ route('frontend.skripsi-registrations.edit', $skripsiRegistration->id) }}">
                            <i class="fas fa-edit mr-2"></i> Edit Pendaftaran
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection