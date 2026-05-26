@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, #e67e22 0%, #d35400 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <h2 class="mb-1 text-white font-weight-bold">
                        <i class="fas fa-flag mr-2"></i> Detail Laporan Kendala
                    </h2>
                    <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                        Informasi lengkap laporan dan catatan admin
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card-modern mb-4">
                <div class="card-modern-body">
                    @if($applicationReport->period)
                        <p class="mb-3">
                            <span class="badge badge-info badge-lg">
                                {{ App\Models\ApplicationReport::PERIOD_SELECT[$applicationReport->period] ?? $applicationReport->period }}
                            </span>
                        </p>
                    @endif

                    <div class="mb-4">
                        <label class="text-muted mb-1">Uraian Kendala</label>
                        <div class="border rounded p-3 bg-light">
                            {!! nl2br(e($applicationReport->report_text)) !!}
                        </div>
                    </div>

                    @if($applicationReport->note)
                        <div class="alert alert-info mb-4">
                            <h6 class="font-weight-bold mb-2">
                                <i class="fas fa-sticky-note"></i> Catatan Admin
                            </h6>
                            <p class="mb-0">{{ $applicationReport->note }}</p>
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

            @if($applicationReport->report_document && count($applicationReport->report_document) > 0)
                <div class="card-modern">
                    <div class="card-modern-body">
                        <h4 class="font-weight-bold mb-3">Bukti Pendukung</h4>
                        <div class="row">
                            @foreach($applicationReport->report_document as $document)
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100 border">
                                        <div class="card-body text-center">
                                            @if(str_starts_with($document->mime_type ?? '', 'image/'))
                                                <img src="{{ $document->getUrl() }}" class="img-fluid mb-2" style="max-height: 150px;" alt="">
                                            @else
                                                <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                            @endif
                                            <h6 class="mb-2 text-truncate">{{ $document->file_name }}</h6>
                                            <a href="{{ $document->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-download"></i> Unduh
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

        <div class="col-lg-4">
            <div class="card-modern mb-4">
                <div class="card-modern-body">
                    <h5 class="font-weight-bold mb-3">Status Laporan</h5>
                    @if($applicationReport->status == 'submitted')
                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-clock"></i> <strong>Menunggu Tinjauan</strong>
                            <p class="mb-0 mt-2 small">Admin akan meninjau laporan Anda</p>
                        </div>
                    @else
                        <div class="alert alert-success mb-0">
                            <i class="fas fa-check-circle"></i> <strong>Sudah Ditinjau</strong>
                        </div>
                    @endif
                </div>
            </div>

            @if($applicationReport->application)
                <div class="card-modern">
                    <div class="card-modern-body">
                        <h5 class="font-weight-bold mb-3">Informasi Aplikasi</h5>
                        <div class="mb-3">
                            <label class="text-muted mb-1">Tipe</label>
                            <p><span class="badge badge-primary">{{ $applicationReport->application->type }}</span></p>
                        </div>
                        <div class="mb-0">
                            <label class="text-muted mb-1">Tahap</label>
                            <p><span class="badge badge-info">{{ $applicationReport->application->stage }}</span></p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-12">
            <a href="{{ route('frontend.application-reports.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>
    </div>
</div>
@endsection
