@extends('layouts.frontend')
@section('content')
<link rel="stylesheet" href="{{ asset('css/modern-form.css') }}">
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="detail-card">
                <div class="detail-header">
                    <div>
                        <h2>Detail Pendaftaran MBKM</h2>
                        <p>Informasi lengkap pendaftaran skripsi MBKM</p>
                    </div>
                    <div>
                        @if($mbkmRegistration->application->status == 'submitted')
                            <span class="status-badge pending large">
                                <i class="fas fa-clock mr-2"></i> Menunggu Verifikasi
                            </span>
                        @elseif($mbkmRegistration->application->status == 'approved')
                            <span class="status-badge approved large">
                                <i class="fas fa-check-circle mr-2"></i> Disetujui
                            </span>
                        @elseif($mbkmRegistration->application->status == 'rejected')
                            <span class="status-badge rejected large">
                                <i class="fas fa-times-circle mr-2"></i> Ditolak
                            </span>
                        @elseif($mbkmRegistration->application->status == 'revision')
                            <span class="badge badge-warning large" style="font-size: 16px; padding: 10px 20px;">
                                <i class="fas fa-edit mr-2"></i> Perlu Revisi
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Supervisor Approval Status -->
                @if($mbkmRegistration->application->status == 'approved')
                    @php
                        $supervisorAssignment = \App\Models\ApplicationAssignment::where('application_id', $mbkmRegistration->application_id)
                            ->where('role', 'supervisor')
                            ->with('lecturer')
                            ->first();
                    @endphp

                    @if($supervisorAssignment && $supervisorAssignment->status == 'assigned')
                        <div class="alert alert-warning mb-4" style="margin: 20px 30px 0 30px; border-left: 5px solid #ffc107;">
                            <h5 class="alert-heading">
                                <i class="fas fa-hourglass-half mr-2"></i> 
                                Menunggu Persetujuan Dosen Pembimbing
                            </h5>
                            <hr>
                            <p class="mb-2"><strong>Status:</strong></p>
                            <p class="mb-3">Admin telah menyetujui pendaftaran MBKM Anda. Saat ini menunggu persetujuan dari dosen pembimbing yang Anda pilih.</p>
                            <div class="bg-white p-3 rounded mb-0" style="border-left: 3px solid #ffc107;">
                                <strong><i class="fas fa-user-tie mr-2"></i>Dosen Pembimbing:</strong><br>
                                {{ $supervisorAssignment->lecturer->nama ?? 'N/A' }}
                            </div>
                        </div>
                    @elseif($supervisorAssignment && $supervisorAssignment->status == 'accepted')
                        <div class="alert alert-success mb-4" style="margin: 20px 30px 0 30px; border-left: 5px solid #28a745;">
                            <h5 class="alert-heading">
                                <i class="fas fa-check-circle mr-2"></i> 
                                Dosen Pembimbing Telah Menyetujui
                            </h5>
                            <hr>
                            <p class="mb-3">Selamat! Dosen pembimbing telah menyetujui untuk membimbing MBKM dan skripsi Anda.</p>
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
                        <div class="alert alert-danger mb-4" style="margin: 20px 30px 0 30px; border-left: 5px solid #dc3545;">
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
                                            <p class="card-text small">Perbaiki data pendaftaran MBKM Anda atau pilih dosen pembimbing yang berbeda. Setelah di-edit, status akan kembali ke "Menunggu Review" oleh admin.</p>
                                            <a href="{{ route('frontend.mbkm-registrations.edit', $mbkmRegistration->id) }}" class="btn btn-warning btn-block">
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
                @if($mbkmRegistration->application->status == 'rejected')
                    <div class="alert alert-danger mb-4" style="margin: 20px 30px 0 30px; border-left: 5px solid #dc3545;">
                        <h5 class="alert-heading">
                            <i class="fas fa-times-circle mr-2"></i> 
                            Pendaftaran Anda Ditolak
                        </h5>
                        <hr>
                        <p class="mb-3"><strong>Alasan Penolakan:</strong></p>
                        <div class="bg-white p-3 rounded mb-3" style="border-left: 3px solid #dc3545;">
                            {{ $mbkmRegistration->rejection_reason ?? 'Tidak ada alasan yang diberikan.' }}
                        </div>
                        
                        <p class="mb-3"><strong>Pilihan Tindakan:</strong></p>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="card h-100 border-info">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-exchange-alt text-info mr-2"></i>
                                            Beralih ke Skripsi Reguler
                                        </h6>
                                        <p class="card-text small">Karena pendaftaran MBKM Anda ditolak, Anda dapat membatalkan pendaftaran ini dan mendaftar melalui jalur Skripsi Reguler.</p>
                                        <form action="{{ route('frontend.mbkm-registrations.destroy', $mbkmRegistration->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin membatalkan pendaftaran MBKM? Anda dapat mendaftar Skripsi Reguler setelahnya.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-info btn-block">
                                                <i class="fas fa-times-circle mr-2"></i> Batalkan & Daftar Reguler
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Revision Alert -->
                @if($mbkmRegistration->application->status == 'revision')
                    <div class="alert alert-warning mb-4" style="margin: 20px 30px 0 30px; border-left: 5px solid #ffc107;">
                        <h5 class="alert-heading">
                            <i class="fas fa-exclamation-triangle mr-2"></i> 
                            Pendaftaran Anda Perlu Direvisi
                        </h5>
                        <hr>
                        <p class="mb-3"><strong>Catatan dari Admin:</strong></p>
                        <div class="bg-white p-3 rounded mb-3" style="border-left: 3px solid #ffc107;">
                            {{ $mbkmRegistration->revision_notes ?? 'Tidak ada catatan khusus.' }}
                        </div>
                        
                        <p class="mb-3"><strong>Pilihan Tindakan:</strong></p>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 border-warning">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-edit text-warning mr-2"></i>
                                            Opsi 1: Revisi Pendaftaran
                                        </h6>
                                        <p class="card-text small">Perbaiki data pendaftaran MBKM Anda sesuai catatan admin di atas.</p>
                                        <a href="{{ route('frontend.mbkm-registrations.edit', $mbkmRegistration->id) }}" class="btn btn-warning btn-block">
                                            <i class="fas fa-edit mr-2"></i> Revisi Sekarang
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 border-info">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-exchange-alt text-info mr-2"></i>
                                            Opsi 2: Beralih ke Skripsi Reguler
                                        </h6>
                                        <p class="card-text small">Batalkan pendaftaran MBKM dan daftar melalui jalur Skripsi Reguler.</p>
                                        <form action="{{ route('frontend.mbkm-registrations.destroy', $mbkmRegistration->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin membatalkan pendaftaran MBKM? Anda dapat mendaftar Skripsi Reguler setelahnya.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-info btn-block">
                                                <i class="fas fa-times-circle mr-2"></i> Batalkan & Daftar Reguler
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="detail-body">
                    <!-- Informasi Mahasiswa -->
                    <div class="detail-section">
                        <h3 class="section-title">
                            <i class="fas fa-user mr-2"></i> Informasi Mahasiswa
                        </h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Nama:</span>
                                    <span class="info-value">{{ $mbkmRegistration->application->mahasiswa->nama ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">NIM:</span>
                                    <span class="info-value">{{ $mbkmRegistration->application->mahasiswa->nim ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Total SKS:</span>
                                    <span class="info-value">{{ $mbkmRegistration->total_sks_taken ?? '-' }} SKS</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Jumlah SKS MKP:</span>
                                    <span class="info-value">{{ $mbkmRegistration->sks_mkp_taken ?? '-' }} SKS</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Nilai Mata Kuliah -->
                    <div class="detail-section">
                        <h3 class="section-title">
                            <i class="fas fa-chart-line mr-2"></i> Nilai Mata Kuliah
                        </h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Nilai MK Kuantitatif:</span>
                                    <span class="info-value">{{ $mbkmRegistration->nilai_mk_kuantitatif ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Nilai MK Kualitatif:</span>
                                    <span class="info-value">{{ $mbkmRegistration->nilai_mk_kualitatif ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Nilai MK Statistika Dasar:</span>
                                    <span class="info-value">{{ $mbkmRegistration->nilai_mk_statistika_dasar ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Nilai MK Statistika Lanjutan:</span>
                                    <span class="info-value">{{ $mbkmRegistration->nilai_mk_statistika_lanjutan ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Nilai MK Konstruksi Tes:</span>
                                    <span class="info-value">{{ $mbkmRegistration->nilai_mk_konstruksi_tes ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Nilai MK TPS:</span>
                                    <span class="info-value">{{ $mbkmRegistration->nilai_mk_tps ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi MBKM -->
                    <div class="detail-section">
                        <h3 class="section-title">
                            <i class="fas fa-graduation-cap mr-2"></i> Informasi MBKM
                        </h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Kelompok Riset:</span>
                                    <span class="info-value">{{ $mbkmRegistration->research_group->name ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Tema Penelitian:</span>
                                    <span class="info-value">{{ $mbkmRegistration->theme->name ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Dosen Pembimbing:</span>
                                    <span class="info-value">{{ $mbkmRegistration->preference_supervision->nama ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Judul -->
                    <div class="detail-section">
                        <h3 class="section-title">
                            <i class="fas fa-book mr-2"></i> Judul Penelitian
                        </h3>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="info-item">
                                    <span class="info-label">Judul Kegiatan MBKM:</span>
                                    <span class="info-value">{{ $mbkmRegistration->title_mbkm ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="info-item">
                                    <span class="info-label">Judul Skripsi:</span>
                                    <span class="info-value">{{ $mbkmRegistration->title ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Anggota Kelompok MBKM -->
                    @if($mbkmRegistration->groupMembers && $mbkmRegistration->groupMembers->count() > 0)
                    <div class="detail-section">
                        <h3 class="section-title">
                            <i class="fas fa-users mr-2"></i> Anggota Kelompok MBKM
                        </h3>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="25%">NIM</th>
                                        <th width="50%">Nama</th>
                                        <th width="20%">Peran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mbkmRegistration->groupMembers as $index => $member)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $member->mahasiswa->nim ?? '-' }}</td>
                                        <td>{{ $member->mahasiswa->nama ?? '-' }}</td>
                                        <td>
                                            @if($member->role == 'ketua')
                                                <span class="badge badge-primary">Ketua</span>
                                            @else
                                                <span class="badge badge-secondary">Anggota</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- Dokumen -->
                    <div class="detail-section">
                        <h3 class="section-title">
                            <i class="fas fa-folder-open mr-2"></i> Dokumen
                        </h3>

                        <div class="row">
                            @if($mbkmRegistration->khs_all && count($mbkmRegistration->khs_all) > 0)
                                @foreach($mbkmRegistration->khs_all as $key => $media)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="document-card">
                                            <div class="document-card-icon">
                                                <i class="fas fa-file-pdf fa-3x"></i>
                                            </div>
                                            <div class="document-card-body">
                                                <div class="document-card-label">KHS Semester</div>
                                                <h5 class="document-card-title">{{ $media->file_name ?? 'KHS ' . ($key + 1) }}</h5>
                                                <p class="document-card-size">
                                                    <i class="fas fa-hdd mr-1"></i>{{ number_format(($media->size ?? 0) / 1024, 2) }} KB
                                                </p>
                                            </div>
                                            <div class="document-card-actions">
                                                <button class="btn btn-sm btn-primary preview-document" data-url="{{ $media->getUrl() }}" data-name="{{ $media->file_name ?? 'KHS ' . ($key + 1) }}">
                                                    <i class="fas fa-eye mr-1"></i> Lihat
                                                </button>
                                                <a href="{{ $media->getUrl() }}" class="btn btn-sm btn-success" download>
                                                    <i class="fas fa-download mr-1"></i> Download
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            @if($mbkmRegistration->krs_latest)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="document-card">
                                        <div class="document-card-icon">
                                            <i class="fas fa-file-pdf fa-3x"></i>
                                        </div>
                                        <div class="document-card-body">
                                            <div class="document-card-label">KRS</div>
                                            <h5 class="document-card-title">{{ $mbkmRegistration->krs_latest->file_name ?? 'KRS Semester Terakhir' }}</h5>
                                            <p class="document-card-size">
                                                <i class="fas fa-hdd mr-1"></i>{{ number_format(($mbkmRegistration->krs_latest->size ?? 0) / 1024, 2) }} KB
                                            </p>
                                        </div>
                                        <div class="document-card-actions">
                                            <button class="btn btn-sm btn-primary preview-document" data-url="{{ $mbkmRegistration->krs_latest->getUrl() }}" data-name="{{ $mbkmRegistration->krs_latest->file_name ?? 'KRS Semester Terakhir' }}">
                                                <i class="fas fa-eye mr-1"></i> Lihat
                                            </button>
                                            <a href="{{ $mbkmRegistration->krs_latest->getUrl() }}" class="btn btn-sm btn-success" download>
                                                <i class="fas fa-download mr-1"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($mbkmRegistration->spp)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="document-card">
                                        <div class="document-card-icon">
                                            <i class="fas fa-receipt fa-3x"></i>
                                        </div>
                                        <div class="document-card-body">
                                            <div class="document-card-label">SPP</div>
                                            <h5 class="document-card-title">{{ $mbkmRegistration->spp->file_name ?? 'Bukti Pembayaran SPP' }}</h5>
                                            <p class="document-card-size">
                                                <i class="fas fa-hdd mr-1"></i>{{ number_format(($mbkmRegistration->spp->size ?? 0) / 1024, 2) }} KB
                                            </p>
                                        </div>
                                        <div class="document-card-actions">
                                            <button class="btn btn-sm btn-primary preview-document" data-url="{{ $mbkmRegistration->spp->getUrl() }}" data-name="{{ $mbkmRegistration->spp->file_name ?? 'Bukti Pembayaran SPP' }}">
                                                <i class="fas fa-eye mr-1"></i> Lihat
                                            </button>
                                            <a href="{{ $mbkmRegistration->spp->getUrl() }}" class="btn btn-sm btn-success" download>
                                                <i class="fas fa-download mr-1"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($mbkmRegistration->proposal_mbkm)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="document-card">
                                        <div class="document-card-icon">
                                            <i class="fas fa-file-alt fa-3x"></i>
                                        </div>
                                        <div class="document-card-body">
                                            <div class="document-card-label">Proposal MBKM</div>
                                            <h5 class="document-card-title">{{ $mbkmRegistration->proposal_mbkm->file_name ?? 'Proposal MBKM' }}</h5>
                                            <p class="document-card-size">
                                                <i class="fas fa-hdd mr-1"></i>{{ number_format(($mbkmRegistration->proposal_mbkm->size ?? 0) / 1024, 2) }} KB
                                            </p>
                                        </div>
                                        <div class="document-card-actions">
                                            <button class="btn btn-sm btn-primary preview-document" data-url="{{ $mbkmRegistration->proposal_mbkm->getUrl() }}" data-name="{{ $mbkmRegistration->proposal_mbkm->file_name ?? 'Proposal MBKM' }}">
                                                <i class="fas fa-eye mr-1"></i> Lihat
                                            </button>
                                            <a href="{{ $mbkmRegistration->proposal_mbkm->getUrl() }}" class="btn btn-sm btn-success" download>
                                                <i class="fas fa-download mr-1"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($mbkmRegistration->recognition_form)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="document-card">
                                        <div class="document-card-icon">
                                            <i class="fas fa-file-signature fa-3x"></i>
                                        </div>
                                        <div class="document-card-body">
                                            <div class="document-card-label">Formulir Rekognisi</div>
                                            <h5 class="document-card-title">{{ $mbkmRegistration->recognition_form->file_name ?? 'Formulir Rekognisi' }}</h5>
                                            <p class="document-card-size">
                                                <i class="fas fa-hdd mr-1"></i>{{ number_format(($mbkmRegistration->recognition_form->size ?? 0) / 1024, 2) }} KB
                                            </p>
                                        </div>
                                        <div class="document-card-actions">
                                            <button class="btn btn-sm btn-primary preview-document" data-url="{{ $mbkmRegistration->recognition_form->getUrl() }}" data-name="{{ $mbkmRegistration->recognition_form->file_name ?? 'Formulir Rekognisi' }}">
                                                <i class="fas fa-eye mr-1"></i> Lihat
                                            </button>
                                            <a href="{{ $mbkmRegistration->recognition_form->getUrl() }}" class="btn btn-sm btn-success" download>
                                                <i class="fas fa-download mr-1"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="detail-actions">
                    <a href="{{ route('frontend.mbkm-registrations.index') }}" class="btn-back">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    @can('mbkm_registration_edit')
                        <a href="{{ route('frontend.mbkm-registrations.edit', $mbkmRegistration->id) }}" class="btn-primary-custom">
                            <i class="fas fa-edit mr-2"></i> Edit Data
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Document Preview Modal -->
<div class="modal fade" id="documentPreviewModal" tabindex="-1" role="dialog" aria-labelledby="documentPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentPreviewModalLabel">
                    <i class="fas fa-file-pdf mr-2"></i>
                    <span id="documentName">Preview Dokumen</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0" style="height: 75vh;">
                <iframe id="documentFrame" style="width: 100%; height: 100%; border: none;"></iframe>
            </div>
            <div class="modal-footer">
                <a id="downloadDocumentBtn" href="#" class="btn btn-success" download>
                    <i class="fas fa-download mr-2"></i> Download
                </a>
                <a id="openNewTabBtn" href="#" class="btn btn-primary" target="_blank">
                    <i class="fas fa-external-link-alt mr-2"></i> Buka di Tab Baru
                </a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-2"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .modal-xl {
        max-width: 90%;
    }
    
    .modal-body {
        background-color: #525659;
    }
    
    #documentFrame {
        background-color: white;
    }

    /* Document Card Styles */
    .document-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .document-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border-color: #4299e1;
    }

    .document-card-icon {
        text-align: center;
        color: #4299e1;
        margin-bottom: 15px;
        padding: 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .document-card-body {
        flex: 1;
        margin-bottom: 15px;
    }

    .document-card-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        color: #718096;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .document-card-title {
        font-size: 14px;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 10px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        min-height: 40px;
    }

    .document-card-size {
        font-size: 12px;
        color: #a0aec0;
        margin: 0;
    }

    .document-card-actions {
        display: flex;
        gap: 8px;
        border-top: 1px solid #e2e8f0;
        padding-top: 15px;
    }

    .document-card-actions .btn {
        flex: 1;
        font-size: 13px;
        padding: 8px 12px;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .document-card-actions .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }

    .document-card-actions .btn-primary:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    .document-card-actions .btn-success {
        background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
        border: none;
        color: #2d3748;
    }

    .document-card-actions .btn-success:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(132, 250, 176, 0.4);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .document-card-actions {
            flex-direction: column;
        }
        
        .document-card-actions .btn {
            width: 100%;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle preview button clicks
        document.querySelectorAll('.preview-document').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const fileUrl = this.getAttribute('data-url');
                const fileName = this.getAttribute('data-name');
                
                // Update modal title
                document.getElementById('documentName').textContent = fileName;
                
                // Set iframe source
                document.getElementById('documentFrame').src = fileUrl;
                
                // Update download and open in new tab buttons
                document.getElementById('downloadDocumentBtn').href = fileUrl;
                document.getElementById('openNewTabBtn').href = fileUrl;
                
                // Show modal
                $('#documentPreviewModal').modal('show');
            });
        });
        
        // Clear iframe when modal is closed to stop loading
        $('#documentPreviewModal').on('hidden.bs.modal', function () {
            document.getElementById('documentFrame').src = '';
        });
    });
</script>

@endsection