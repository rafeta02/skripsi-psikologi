@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, #e67e22 0%, #d35400 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <h2 class="mb-1 text-white font-weight-bold">
                        <i class="fas fa-exclamation-circle mr-2"></i> Detail Laporan Masalah
                    </h2>
                    <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                        Informasi lengkap laporan dan tanggapan
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Content -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card-modern mb-4">
                <div class="card-modern-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h4 class="font-weight-bold">{{ $applicationReport->title }}</h4>
                        @if($applicationReport->priority == 'high')
                            <span class="badge badge-danger badge-lg">Prioritas Tinggi</span>
                        @elseif($applicationReport->priority == 'medium')
                            <span class="badge badge-warning badge-lg">Prioritas Sedang</span>
                        @else
                            <span class="badge badge-secondary badge-lg">Prioritas Rendah</span>
                        @endif
                    </div>
                    
                    <div class="mb-4">
                        <label class="text-muted mb-1">Deskripsi Masalah</label>
                        <p class="text-justify">{{ $applicationReport->description }}</p>
                    </div>

                    @if($applicationReport->response)
                        <div class="alert alert-info mb-4">
                            <h6 class="font-weight-bold mb-2">
                                <i class="fas fa-reply"></i> Tanggapan Admin
                            </h6>
                            <p class="mb-0">{{ $applicationReport->response }}</p>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">Tanggal Dibuat</label>
                            <p class="font-weight-semibold">{{ $applicationReport->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">Terakhir Diupdate</label>
                            <p class="font-weight-semibold">{{ $applicationReport->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Evidence Documents -->
            @if($applicationReport->evidence_document && $applicationReport->evidence_document->count() > 0)
                <div class="card-modern">
                    <div class="card-modern-body">
                        <h4 class="font-weight-bold mb-3">Bukti Pendukung</h4>
                        <div class="row">
                            @foreach($applicationReport->evidence_document as $document)
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100 border">
                                        <div class="card-body text-center">
                                            @if(in_array($document->mime_type, ['image/jpeg', 'image/png', 'image/jpg']))
                                                <img src="{{ $document->getUrl() }}" class="img-fluid mb-2" style="max-height: 150px;">
                                            @else
                                                <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                            @endif
                                            <h6 class="mb-2 text-truncate">{{ $document->file_name }}</h6>
                                            <a href="{{ $document->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Status -->
            <div class="card-modern mb-4">
                <div class="card-modern-body">
                    <h5 class="font-weight-bold mb-3">Status Laporan</h5>
                    @if($applicationReport->status == 'pending')
                        <div class="alert alert-warning">
                            <i class="fas fa-clock"></i> <strong>Menunggu Tanggapan</strong>
                            <p class="mb-0 mt-2 small">Laporan Anda sedang ditinjau</p>
                        </div>
                    @elseif($applicationReport->status == 'in_progress')
                        <div class="alert alert-info">
                            <i class="fas fa-spinner"></i> <strong>Dalam Proses</strong>
                            <p class="mb-0 mt-2 small">Laporan sedang ditindaklanjuti</p>
                        </div>
                    @elseif($applicationReport->status == 'resolved')
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> <strong>Selesai</strong>
                            <p class="mb-0 mt-2 small">Masalah telah diselesaikan</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Application Info -->
            @if($applicationReport->application)
                <div class="card-modern">
                    <div class="card-modern-body">
                        <h5 class="font-weight-bold mb-3">Informasi Aplikasi</h5>
                        <div class="mb-3">
                            <label class="text-muted mb-1">Tipe</label>
                            <p class="font-weight-semibold text-uppercase">
                                <span class="badge badge-primary">{{ $applicationReport->application->type }}</span>
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted mb-1">Tahap</label>
                            <p class="font-weight-semibold text-capitalize">
                                <span class="badge badge-info">{{ $applicationReport->application->stage }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Back Button -->
    <div class="row mt-4">
        <div class="col-lg-12">
            <a href="{{ route('frontend.application-reports.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>
    </div>
</div>
@endsection
