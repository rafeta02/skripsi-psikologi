@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <h2 class="mb-1 text-white font-weight-bold">
                        <i class="fas fa-clipboard-check mr-2"></i> Detail Hasil Review Proposal
                    </h2>
                    <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                        Informasi lengkap hasil Review Kelayakan Proposal
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card-modern mb-4">
                <div class="card-modern-body">
                    <h4 class="font-weight-bold mb-3">Hasil Review</h4>

                    <div class="mb-4">
                        <label class="text-muted mb-1">Status Hasil</label>
                        <h5 class="font-weight-semibold">
                            @php
                                $badgeClass = match($applicationResultSeminar->result) {
                                    'minor', 'passed' => 'success',
                                    'mayor' => 'info',
                                    'revision' => 'warning',
                                    'failed' => 'danger',
                                    default => 'secondary',
                                };
                            @endphp
                            <span class="badge badge-{{ $badgeClass }} badge-lg">
                                {{ $applicationResultSeminar->resultLabel() }}
                            </span>
                        </h5>
                    </div>

                    @if($applicationResultSeminar->revision_deadline)
                        <div class="alert alert-warning mb-4">
                            <h6 class="font-weight-bold mb-2">
                                <i class="fas fa-exclamation-triangle"></i> Tenggat Waktu Revisi
                            </h6>
                            <p class="mb-0">
                                <i class="far fa-calendar"></i>
                                {{ \Carbon\Carbon::createFromFormat(config('panel.date_format'), $applicationResultSeminar->revision_deadline)->translatedFormat('l, d F Y') }}
                            </p>
                        </div>
                    @endif

                    @if($applicationResultSeminar->note)
                        <div class="mb-4">
                            <label class="text-muted mb-1">Catatan dari Reviewer</label>
                            <div class="alert alert-light">
                                {{ $applicationResultSeminar->note }}
                            </div>
                        </div>
                    @endif

                    @if($applicationResultSeminar->meeting_recording_link)
                        <div class="mb-4">
                            <label class="text-muted mb-1">Tautan Record Meeting</label>
                            <p>
                                <a href="{{ $applicationResultSeminar->meeting_recording_link }}" target="_blank" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-video"></i> Buka Rekaman
                                </a>
                            </p>
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

            <div class="card-modern">
                <div class="card-modern-body">
                    <h4 class="font-weight-bold mb-3">Dokumen</h4>

                    @if($applicationResultSeminar->form_document && $applicationResultSeminar->form_document->count() > 0)
                        <div class="mb-4">
                            <h6 class="font-weight-semibold mb-2">Form Review Kelayakan Proposal MBKM Riset:</h6>
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

                    @if($applicationResultSeminar->attendance_document)
                        <div class="mb-4">
                            <h6 class="font-weight-semibold mb-2">Presensi Peserta:</h6>
                            <div class="card border">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-file-pdf fa-2x text-success"></i>
                                            <span class="ml-2 font-weight-semibold">{{ $applicationResultSeminar->attendance_document->file_name }}</span>
                                        </div>
                                        <a href="{{ $applicationResultSeminar->attendance_document->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($applicationResultSeminar->krs_latest)
                        <div class="mb-4">
                            <h6 class="font-weight-semibold mb-2">KRS Semester Terbaru:</h6>
                            <div class="card border">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-file-pdf fa-2x text-primary"></i>
                                            <span class="ml-2 font-weight-semibold">{{ $applicationResultSeminar->krs_latest->file_name }}</span>
                                        </div>
                                        <a href="{{ $applicationResultSeminar->krs_latest->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($applicationResultSeminar->documentation && $applicationResultSeminar->documentation->count() > 0)
                        <div class="mb-4">
                            <h6 class="font-weight-semibold mb-2">Dokumentasi Seminar:</h6>
                            <div class="row">
                                @foreach($applicationResultSeminar->documentation as $media)
                                    <div class="col-md-4 mb-3">
                                        <a href="{{ $media->getUrl() }}" target="_blank">
                                            <img src="{{ $media->getUrl() }}" class="img-fluid img-thumbnail" alt="Dokumentasi" style="max-height: 160px; width: 100%; object-fit: cover;">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($applicationResultSeminar->latest_script)
                        <div class="mb-4">
                            <h6 class="font-weight-semibold mb-2">Naskah Proposal MBKM (KKN dan Skripsi Hasil Revisi):</h6>
                            <div class="card border">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-file-pdf fa-2x text-danger"></i>
                                            <span class="ml-2 font-weight-semibold">{{ $applicationResultSeminar->latest_script->file_name }}</span>
                                        </div>
                                        <a href="{{ $applicationResultSeminar->latest_script->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if((!$applicationResultSeminar->form_document || $applicationResultSeminar->form_document->count() == 0)
                        && !$applicationResultSeminar->attendance_document
                        && !$applicationResultSeminar->krs_latest
                        && (!$applicationResultSeminar->documentation || $applicationResultSeminar->documentation->count() == 0)
                        && !$applicationResultSeminar->latest_script)
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-folder-open fa-3x mb-3"></i>
                            <p>Tidak ada dokumen terlampir</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
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

            @if(($canAccessDefense['allowed'] ?? false))
                <div class="card-modern mb-4 border-success">
                    <div class="card-modern-body text-center">
                        <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                        <p class="mb-3">Anda dapat mendaftar sidang skripsi.</p>
                        <a href="{{ route('frontend.skripsi-defenses.create') }}" class="btn btn-success btn-block">
                            <i class="fas fa-gavel"></i> Daftar Sidang
                        </a>
                    </div>
                </div>
            @elseif(!empty($canAccessDefense['pending_admin_validation']))
                <div class="alert alert-warning">
                    <i class="fas fa-hourglass-half"></i> Menunggu validasi admin sebelum mendaftar sidang.
                </div>
            @endif

            <a href="{{ route('frontend.application-result-seminars.index') }}" class="btn btn-secondary btn-block">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>
@endsection
