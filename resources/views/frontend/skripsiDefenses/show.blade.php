@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, #16a085 0%, #27ae60 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1 text-white font-weight-bold">
                                <i class="fas fa-graduation-cap mr-2"></i> Detail Sidang Skripsi
                            </h2>
                            <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                                Informasi lengkap pendaftaran sidang
                            </p>
                        </div>
                        <div>
                            @can('skripsi_defense_edit')
                                @if($skripsiDefense->application && in_array($skripsiDefense->application->status, ['revision', 'submitted']))
                                    <a href="{{ route('frontend.skripsi-defenses.edit', $skripsiDefense->id) }}" class="btn btn-light">
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
                    <h4 class="font-weight-bold mb-3">Informasi Sidang</h4>
                    
                    <div class="mb-4">
                        <label class="text-muted mb-1">Judul</label>
                        <h5 class="font-weight-semibold">{{ $skripsiDefense->title ?? '-' }}</h5>
                    </div>

                    @if($skripsiDefense->abstract)
                        <div class="mb-4">
                            <label class="text-muted mb-1">Abstrak</label>
                            <p class="text-justify">{{ $skripsiDefense->abstract }}</p>
                        </div>
                    @endif

                    @if($skripsiDefense->notes)
                        <div class="mb-4">
                            <label class="text-muted mb-1">Catatan</label>
                            <div class="alert alert-light">
                                {{ $skripsiDefense->notes }}
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">Tanggal Dibuat</label>
                            <p class="font-weight-semibold">
                                {{ $skripsiDefense->created_at->format('d M Y H:i') }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">Terakhir Diupdate</label>
                            <p class="font-weight-semibold">
                                {{ $skripsiDefense->updated_at->format('d M Y H:i') }}
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
                        {{-- Dokumen utama --}}
                        @if($skripsiDefense->defence_document)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 border">
                                    <div class="card-body text-center">
                                        <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                        <h6 class="mb-2">Naskah Skripsi Final</h6>
                                        <a href="{{ $skripsiDefense->defence_document->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($skripsiDefense->plagiarism_report)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 border">
                                    <div class="card-body text-center">
                                        <i class="fas fa-file-pdf fa-3x text-info mb-3"></i>
                                        <h6 class="mb-2">Laporan Plagiarisme</h6>
                                        <a href="{{ $skripsiDefense->plagiarism_report->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($skripsiDefense->publication_statement)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 border">
                                    <div class="card-body text-center">
                                        <i class="fas fa-file-pdf fa-3x text-success mb-3"></i>
                                        <h6 class="mb-2">Pernyataan Publikasi</h6>
                                        <a href="{{ $skripsiDefense->publication_statement->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Dokumen administrasi --}}
                        @if($skripsiDefense->spp_receipt)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 border">
                                    <div class="card-body text-center">
                                        <i class="fas fa-file-invoice-dollar fa-3x text-warning mb-3"></i>
                                        <h6 class="mb-2">Bukti Pembayaran SPP</h6>
                                        <a href="{{ $skripsiDefense->spp_receipt->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($skripsiDefense->krs_latest)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 border">
                                    <div class="card-body text-center">
                                        <i class="fas fa-file-alt fa-3x text-primary mb-3"></i>
                                        <h6 class="mb-2">KRS Terbaru</h6>
                                        <a href="{{ $skripsiDefense->krs_latest->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($skripsiDefense->eap_certificate)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 border">
                                    <div class="card-body text-center">
                                        <i class="fas fa-certificate fa-3x text-success mb-3"></i>
                                        <h6 class="mb-2">Sertifikat EAP</h6>
                                        <a href="{{ $skripsiDefense->eap_certificate->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($skripsiDefense->transcript)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 border">
                                    <div class="card-body text-center">
                                        <i class="fas fa-file-alt fa-3x text-secondary mb-3"></i>
                                        <h6 class="mb-2">Transkrip Nilai</h6>
                                        <a href="{{ $skripsiDefense->transcript->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($skripsiDefense->siakad_supervisor_screenshot)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 border">
                                    <div class="card-body text-center">
                                        <i class="fas fa-desktop fa-3x text-info mb-3"></i>
                                        <h6 class="mb-2">Screenshot Pembimbing SIAKAD</h6>
                                        <a href="{{ $skripsiDefense->siakad_supervisor_screenshot->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Jika benar-benar tidak ada dokumen --}}
                        @if(
                            !$skripsiDefense->defence_document &&
                            !$skripsiDefense->plagiarism_report &&
                            !$skripsiDefense->publication_statement &&
                            !$skripsiDefense->spp_receipt &&
                            !$skripsiDefense->krs_latest &&
                            !$skripsiDefense->eap_certificate &&
                            !$skripsiDefense->transcript &&
                            !$skripsiDefense->siakad_supervisor_screenshot
                        )
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
                    
                    @if($skripsiDefense->application)
                        @if($skripsiDefense->application->status == 'submitted')
                            <div class="alert alert-warning">
                                <i class="fas fa-clock"></i> <strong>Menunggu Review</strong>
                                <p class="mb-0 mt-2 small">Pendaftaran sedang dalam proses review</p>
                            </div>
                        @elseif($skripsiDefense->application->status == 'approved')
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> <strong>Disetujui</strong>
                                <p class="mb-0 mt-2 small">Pendaftaran disetujui. Lanjutkan ke penjadwalan sidang.</p>
                            </div>
                        @elseif($skripsiDefense->application->status == 'scheduled')
                            <div class="alert alert-info">
                                <i class="fas fa-calendar-check"></i> <strong>Terjadwal</strong>
                                <p class="mb-0 mt-2 small">Sidang telah dijadwalkan</p>
                            </div>
                        @elseif($skripsiDefense->application->status == 'revision')
                            <div class="alert alert-warning">
                                <i class="fas fa-edit"></i> <strong>Revisi</strong>
                                <p class="mb-0 mt-2 small">Silakan lakukan revisi sesuai catatan</p>
                            </div>
                        @elseif($skripsiDefense->application->status == 'rejected')
                            <div class="alert alert-danger">
                                <i class="fas fa-times-circle"></i> <strong>Ditolak</strong>
                                <p class="mb-0 mt-2 small">Pendaftaran tidak dapat disetujui</p>
                            </div>
                        @elseif($skripsiDefense->application->status == 'done')
                            <div class="alert alert-secondary">
                                <i class="fas fa-flag-checkered"></i> <strong>Selesai</strong>
                                <p class="mb-0 mt-2 small">Sidang telah selesai dilaksanakan</p>
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
            @if($skripsiDefense->application)
                <div class="card-modern">
                    <div class="card-modern-body">
                        <h5 class="font-weight-bold mb-3">Informasi Aplikasi</h5>
                        
                        <div class="mb-3">
                            <label class="text-muted mb-1">Tipe</label>
                            <p class="font-weight-semibold text-uppercase">
                                <span class="badge badge-{{ $skripsiDefense->application->type == 'mbkm' ? 'primary' : 'success' }}">
                                    {{ $skripsiDefense->application->type }}
                                </span>
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted mb-1">Tahap</label>
                            <p class="font-weight-semibold text-capitalize">
                                <span class="badge badge-info">{{ $skripsiDefense->application->stage }}</span>
                            </p>
                        </div>

                        @if($skripsiDefense->application->submitted_at)
                            <div class="mb-3">
                                <label class="text-muted mb-1">Tanggal Submit</label>
                                <p class="font-weight-semibold">
                                    {{ \Carbon\Carbon::parse($skripsiDefense->application->submitted_at)->format('d M Y H:i') }}
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
            <a href="{{ route('frontend.skripsi-defenses.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>
    </div>
</div>
@endsection
