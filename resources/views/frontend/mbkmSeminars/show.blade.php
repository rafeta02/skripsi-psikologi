@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, #3498db 0%, #2ecc71 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1 text-white font-weight-bold">
                                <i class="fas fa-chalkboard-teacher mr-2"></i> Detail Review Kelayakan Proposal
                            </h2>
                            <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                                Informasi lengkap pendaftaran Review Kelayakan Proposal
                            </p>
                        </div>
                        <div>
                            @if(!empty($isKetua) && $mbkmSeminar->application && in_array($mbkmSeminar->application->status, ['revision', 'submitted'], true))
                                <a href="{{ route('frontend.mbkm-seminars.edit', $mbkmSeminar->id) }}" class="btn btn-light">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(!empty($isGroupFollower))
        <div class="alert alert-info">
            <i class="fas fa-users mr-1"></i>
            Ini pengajuan <strong>ketua kelompok</strong>. Anda anggota — status progress mengikuti form ini (lanjutan MbkmRegistration).
        </div>
    @endif

    @if(!empty($registration) && $registration->groupMembers->count() > 0)
        <div class="card-modern mb-4">
            <div class="card-modern-body py-3">
                <h6 class="font-weight-bold mb-2">Anggota Kelompok</h6>
                <ul class="list-unstyled mb-0 small">
                    @foreach($registration->groupMembers->sortByDesc(fn ($m) => $m->role === 'ketua') as $member)
                        <li class="mb-1">
                            <span class="badge badge-{{ $member->role === 'ketua' ? 'success' : 'secondary' }} mr-1">{{ $member->role }}</span>
                            {{ $member->mahasiswa->nama ?? '-' }}
                            <span class="text-muted">({{ $member->mahasiswa->nim ?? '-' }})</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Detail Content -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card-modern mb-4">
                <div class="card-modern-body">
                    <h4 class="font-weight-bold mb-3">Informasi Review Kelayakan Proposal</h4>
                    
                    <div class="mb-4">
                        <label class="text-muted mb-1">Judul MBKM</label>
                        <h5 class="font-weight-semibold">{{ $mbkmSeminar->title ?? '-' }}</h5>
                    </div>

                    @if($mbkmSeminar->description)
                        <div class="mb-4">
                            <label class="text-muted mb-1">Deskripsi / Abstrak</label>
                            <p class="text-justify">{{ $mbkmSeminar->description }}</p>
                        </div>
                    @endif

                    @if($mbkmSeminar->notes)
                        <div class="mb-4">
                            <label class="text-muted mb-1">Catatan</label>
                            <p>{{ $mbkmSeminar->notes }}</p>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">Tanggal Dibuat</label>
                            <p class="font-weight-semibold">
                                <i class="far fa-calendar"></i> {{ $mbkmSeminar->created_at->format('d M Y H:i') }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">Terakhir Diupdate</label>
                            <p class="font-weight-semibold">
                                <i class="far fa-calendar"></i> {{ $mbkmSeminar->updated_at->format('d M Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents -->
            <div class="card-modern">
                <div class="card-modern-body">
                    <h4 class="font-weight-bold mb-3">Dokumen</h4>
                    
                    <div class="row">
                        @if($mbkmSeminar->proposal_document)
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 border">
                                    <div class="card-body text-center">
                                        <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                        <h6 class="mb-2">Proposal MBKM</h6>
                                        <a href="{{ $mbkmSeminar->proposal_document->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($mbkmSeminar->approval_document)
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 border">
                                    <div class="card-body text-center">
                                        <i class="fas fa-file-pdf fa-3x text-success mb-3"></i>
                                        <h6 class="mb-2">Persetujuan Pembimbing</h6>
                                        <a href="{{ $mbkmSeminar->approval_document->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($mbkmSeminar->plagiarism_document)
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 border">
                                    <div class="card-body text-center">
                                        <i class="fas fa-file-pdf fa-3x text-info mb-3"></i>
                                        <h6 class="mb-2">Plagiarism Check</h6>
                                        <a href="{{ $mbkmSeminar->plagiarism_document->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(!$mbkmSeminar->proposal_document && !$mbkmSeminar->approval_document && !$mbkmSeminar->plagiarism_document)
                            <div class="col-12 text-center text-muted py-4">
                                <i class="fas fa-folder-open fa-3x mb-3"></i>
                                <p>Tidak ada dokumen terlampir</p>
                            </div>
                        @endif
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
                    
                    @if($mbkmSeminar->application)
                        @if($mbkmSeminar->application->status == 'submitted')
                            <div class="alert alert-warning">
                                <i class="fas fa-clock"></i> <strong>Menunggu Review</strong>
                                <p class="mb-0 mt-2 small">Pendaftaran Anda sedang dalam proses review</p>
                            </div>
                        @elseif($mbkmSeminar->application->status == 'approved')
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> <strong>Disetujui</strong>
                                <p class="mb-0 mt-2 small">Pendaftaran Anda telah disetujui. Lanjutkan ke penjadwalan seminar.</p>
                            </div>
                        @elseif($mbkmSeminar->application->status == 'scheduled')
                            <div class="alert alert-info">
                                <i class="fas fa-calendar-check"></i> <strong>Terjadwal</strong>
                                <p class="mb-0 mt-2 small">Review Kelayakan Proposal Anda telah dijadwalkan</p>
                            </div>
                        @elseif($mbkmSeminar->application->status == 'revision')
                            <div class="alert alert-warning">
                                <i class="fas fa-edit"></i> <strong>Revisi</strong>
                                <p class="mb-0 mt-2 small">Silakan lakukan revisi sesuai catatan</p>
                            </div>
                        @elseif($mbkmSeminar->application->status == 'rejected')
                            <div class="alert alert-danger">
                                <i class="fas fa-times-circle"></i> <strong>Ditolak</strong>
                                <p class="mb-0 mt-2 small">Pendaftaran Anda ditolak</p>
                            </div>
                        @elseif($mbkmSeminar->application->status == 'done')
                            <div class="alert alert-secondary">
                                <i class="fas fa-flag-checkered"></i> <strong>Selesai</strong>
                                <p class="mb-0 mt-2 small">Seminar telah selesai dilaksanakan</p>
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
            @if($mbkmSeminar->application)
                <div class="card-modern">
                    <div class="card-modern-body">
                        <h5 class="font-weight-bold mb-3">Informasi Aplikasi</h5>
                        
                        <div class="mb-3">
                            <label class="text-muted mb-1">Tipe</label>
                            <p class="font-weight-semibold text-uppercase">{{ $mbkmSeminar->application->type }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted mb-1">Tahap</label>
                            <p class="font-weight-semibold text-capitalize">{{ $mbkmSeminar->application->stage }}</p>
                        </div>

                        @if($mbkmSeminar->application->submitted_at)
                            <div class="mb-3">
                                <label class="text-muted mb-1">Tanggal Submit</label>
                                <p class="font-weight-semibold">
                                    {{ \Carbon\Carbon::parse($mbkmSeminar->application->submitted_at)->format('d M Y H:i') }}
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
            <a href="{{ route('frontend.mbkm-seminars.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>
    </div>
</div>
@endsection
