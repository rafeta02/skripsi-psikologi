@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <h2 class="mb-1 text-white font-weight-bold">
                        <i class="fas fa-clipboard-check mr-2"></i> Detail Hasil Review Proposal
                    </h2>
                    <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                        Informasi lengkap hasil review proposal
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
                    <h4 class="font-weight-bold mb-3">Hasil Review</h4>
                    
                    <div class="mb-4">
                        <label class="text-muted mb-1">Status Hasil</label>
                        <h5 class="font-weight-semibold">
                            @if($applicationResultSeminar->result === 'passed')
                                <span class="badge badge-success badge-lg">
                                    <i class="fas fa-check-circle"></i> Lulus (Passed)
                                </span>
                            @elseif($applicationResultSeminar->result === 'revision')
                                <span class="badge badge-warning badge-lg">
                                    <i class="fas fa-edit"></i> Revisi (Revision)
                                </span>
                            @elseif($applicationResultSeminar->result === 'failed')
                                <span class="badge badge-danger badge-lg">
                                    <i class="fas fa-times-circle"></i> Tidak Lulus (Failed)
                                </span>
                            @else
                                <span class="badge badge-secondary badge-lg">{{ $applicationResultSeminar->result }}</span>
                            @endif
                        </h5>
                    </div>

                    @if($applicationResultSeminar->result === 'revision' && $applicationResultSeminar->revision_deadline)
                        <div class="alert alert-warning mb-4">
                            <h6 class="font-weight-bold mb-2">
                                <i class="fas fa-exclamation-triangle"></i> Perhatian: Revisi Diperlukan
                            </h6>
                            <p class="mb-1"><strong>Tenggat Waktu Revisi:</strong></p>
                            <p class="mb-0">
                                <i class="far fa-calendar"></i> 
                                {{ \Carbon\Carbon::parse($applicationResultSeminar->revision_deadline)->translatedFormat('l, d F Y') }}
                            </p>
                        </div>
                    @endif

                    @if($applicationResultSeminar->notes)
                        <div class="mb-4">
                            <label class="text-muted mb-1">Catatan dari Reviewer</label>
                            <div class="alert alert-light">
                                {{ $applicationResultSeminar->notes }}
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">Tanggal Dibuat</label>
                            <p class="font-weight-semibold">
                                {{ $applicationResultSeminar->created_at->format('d M Y H:i') }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">Terakhir Diupdate</label>
                            <p class="font-weight-semibold">
                                {{ $applicationResultSeminar->updated_at->format('d M Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents -->
            <div class="card-modern">
                <div class="card-modern-body">
                    <h4 class="font-weight-bold mb-3">Dokumen</h4>
                    
                    <!-- Report Documents -->
                    @if($applicationResultSeminar->report_document && $applicationResultSeminar->report_document->count() > 0)
                        <div class="mb-4">
                            <h6 class="font-weight-semibold mb-2">Berita Acara / Laporan:</h6>
                            <div class="row">
                                @foreach($applicationResultSeminar->report_document as $document)
                                    <div class="col-md-4 mb-3">
                                        <div class="card h-100 border">
                                            <div class="card-body text-center">
                                                <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                                <h6 class="mb-2">{{ $document->file_name }}</h6>
                                                <a href="{{ $document->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-download"></i> Download
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Attendance Document -->
                    @if($applicationResultSeminar->attendance_document)
                        <div class="mb-4">
                            <h6 class="font-weight-semibold mb-2">Daftar Hadir:</h6>
                            <div class="card border">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-file-pdf fa-2x text-success"></i>
                                            <span class="ml-2 font-weight-semibold">Daftar Hadir</span>
                                        </div>
                                        <a href="{{ $applicationResultSeminar->attendance_document->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Form Documents -->
                    @if($applicationResultSeminar->form_document && $applicationResultSeminar->form_document->count() > 0)
                        <div class="mb-4">
                            <h6 class="font-weight-semibold mb-2">Form Penilaian Reviewer:</h6>
                            <div class="row">
                                @foreach($applicationResultSeminar->form_document as $document)
                                    <div class="col-md-4 mb-3">
                                        <div class="card h-100 border">
                                            <div class="card-body text-center">
                                                <i class="fas fa-file-pdf fa-3x text-info mb-3"></i>
                                                <h6 class="mb-2">{{ $document->file_name }}</h6>
                                                <a href="{{ $document->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-download"></i> Download
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if((!$applicationResultSeminar->report_document || $applicationResultSeminar->report_document->count() == 0) 
                        && !$applicationResultSeminar->attendance_document 
                        && (!$applicationResultSeminar->form_document || $applicationResultSeminar->form_document->count() == 0))
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-folder-open fa-3x mb-3"></i>
                            <p>Tidak ada dokumen terlampir</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Application Info -->
            @if($applicationResultSeminar->application)
                <div class="card-modern mb-4">
                    <div class="card-modern-body">
                        <h5 class="font-weight-bold mb-3">Informasi Aplikasi</h5>
                        
                        <div class="mb-3">
                            <label class="text-muted mb-1">Tipe</label>
                            <p class="font-weight-semibold text-uppercase">
                                <span class="badge badge-primary">{{ $applicationResultSeminar->application->type }}</span>
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted mb-1">Tahap</label>
                            <p class="font-weight-semibold text-capitalize">
                                <span class="badge badge-info">{{ $applicationResultSeminar->application->stage }}</span>
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted mb-1">Status Validasi</label>
                            <p class="font-weight-semibold">
                                {!! $applicationResultSeminar->adminValidationStatusHtml() !!}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="card-modern">
                <div class="card-modern-body">
                    <h5 class="font-weight-bold mb-3">Aksi</h5>
                    @if($applicationResultSeminar->result === 'revision')
                        <a href="{{ route('frontend.choose-path') }}" class="btn btn-warning btn-block mb-2">
                            <i class="fas fa-edit"></i> Mulai Revisi
                        </a>
                    @endif
                    @if($applicationResultSeminar->result === 'passed')
                        @if($canAccessDefense['allowed'] ?? false)
                            <div class="alert alert-success mb-3">
                                <i class="fas fa-check-circle"></i> Laporan hasil lulus sudah divalidasi admin. Anda dapat mendaftar sidang skripsi.
                            </div>
                            @can('skripsi_defense_create')
                                <a href="{{ route('frontend.skripsi-defenses.create') }}" class="btn btn-success btn-block">
                                    <i class="fas fa-graduation-cap"></i> Daftar Sidang Skripsi
                                </a>
                            @endcan
                        @else
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-hourglass-half"></i>
                                {{ $canAccessDefense['message'] ?? 'Menunggu validasi admin sebelum dapat mendaftar sidang skripsi.' }}
                            </div>
                        @endif
                    @endif
                    @if($applicationResultSeminar->result === 'failed')
                        <div class="alert alert-danger mb-3">
                            <i class="fas fa-times-circle"></i> Review proposal tidak lulus. Perbaiki pendaftaran reviewer dan unggah ulang dokumen.
                        </div>
                        @can('skripsi_seminar_edit')
                            @php
                                $retrySeminarId = app(\App\Services\FormAccessService::class)
                                    ->getSkripsiSeminarForFailedRetry(auth()->user()->mahasiswa_id)?->id;
                            @endphp
                            @if($retrySeminarId)
                                <a href="{{ route('frontend.skripsi-seminars.edit', $retrySeminarId) }}" class="btn btn-danger btn-block">
                                    <i class="fas fa-edit"></i> Perbaiki Pendaftaran Reviewer
                                </a>
                            @endif
                        @endcan
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="row mt-4">
        <div class="col-lg-12">
            <a href="{{ route('frontend.application-result-seminars.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
