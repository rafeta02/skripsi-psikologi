@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <h2 class="mb-1 text-white font-weight-bold">
                        <i class="fas fa-award mr-2"></i> Detail Hasil Sidang Final
                    </h2>
                    <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                        Informasi lengkap hasil sidang skripsi/MBKM
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
                    <h4 class="font-weight-bold mb-3">Hasil Sidang</h4>
                    
                    <div class="mb-4">
                        <label class="text-muted mb-1">Status Kelulusan</label>
                        <h5 class="font-weight-semibold">
                            @if($applicationResultDefense->result === 'passed')
                                <span class="badge badge-success badge-lg">
                                    <i class="fas fa-check-circle"></i> Lulus
                                </span>
                            @elseif($applicationResultDefense->result === 'revision')
                                <span class="badge badge-warning badge-lg">
                                    <i class="fas fa-edit"></i> Revisi
                                </span>
                            @elseif($applicationResultDefense->result === 'failed')
                                <span class="badge badge-danger badge-lg">
                                    <i class="fas fa-times-circle"></i> Tidak Lulus
                                </span>
                            @else
                                <span class="badge badge-secondary badge-lg">{{ $applicationResultDefense->result }}</span>
                            @endif
                        </h5>
                    </div>

                    @if($applicationResultDefense->grade)
                        <div class="mb-4">
                            <label class="text-muted mb-1">Nilai Akhir</label>
                            <h3 class="font-weight-bold" style="color: {{ $applicationResultDefense->grade >= 80 ? '#27ae60' : ($applicationResultDefense->grade >= 70 ? '#f39c12' : '#e74c3c') }}">
                                {{ number_format($applicationResultDefense->grade, 2) }}
                            </h3>
                        </div>
                    @endif

                    @if($applicationResultDefense->result === 'passed_with_revision' && $applicationResultDefense->revision_deadline)
                        <div class="alert alert-info mb-4">
                            <h6 class="font-weight-bold mb-2">
                                <i class="fas fa-info-circle"></i> Perhatian: Revisi Diperlukan
                            </h6>
                            <p class="mb-1"><strong>Tenggat Waktu Revisi:</strong></p>
                            <p class="mb-0">
                                <i class="far fa-calendar"></i> 
                                {{ \Carbon\Carbon::parse($applicationResultDefense->revision_deadline)->translatedFormat('l, d F Y') }}
                            </p>
                        </div>
                    @endif

                    @if($applicationResultDefense->notes)
                        <div class="mb-4">
                            <label class="text-muted mb-1">Catatan Penguji</label>
                            <div class="alert alert-light">
                                {{ $applicationResultDefense->notes }}
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">Tanggal Dibuat</label>
                            <p class="font-weight-semibold">
                                {{ $applicationResultDefense->created_at->format('d M Y H:i') }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">Terakhir Diupdate</label>
                            <p class="font-weight-semibold">
                                {{ $applicationResultDefense->updated_at->format('d M Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents (similar to result seminar) -->
            <div class="card-modern">
                <div class="card-modern-body">
                    <h4 class="font-weight-bold mb-3">Dokumen</h4>
                    
                    @if($applicationResultDefense->report_document && $applicationResultDefense->report_document->count() > 0)
                        <div class="mb-4">
                            <h6 class="font-weight-semibold mb-2">Berita Acara Sidang:</h6>
                            <div class="row">
                                @foreach($applicationResultDefense->report_document as $document)
                                    <div class="col-md-4 mb-3">
                                        <div class="card h-100 border">
                                            <div class="card-body text-center">
                                                <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
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
                    @endif

                    @if($applicationResultDefense->attendance_document)
                        <div class="mb-4">
                            <h6 class="font-weight-semibold mb-2">Daftar Hadir:</h6>
                            <div class="card border">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-file-pdf fa-2x text-success"></i>
                                            <span class="ml-2 font-weight-semibold">Daftar Hadir Sidang</span>
                                        </div>
                                        <a href="{{ $applicationResultDefense->attendance_document->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($applicationResultDefense->form_document && $applicationResultDefense->form_document->count() > 0)
                        <div class="mb-4">
                            <h6 class="font-weight-semibold mb-2">Form Penilaian Penguji:</h6>
                            <div class="row">
                                @foreach($applicationResultDefense->form_document as $document)
                                    <div class="col-md-4 mb-3">
                                        <div class="card h-100 border">
                                            <div class="card-body text-center">
                                                <i class="fas fa-file-pdf fa-3x text-info mb-3"></i>
                                                <h6 class="mb-2 text-truncate">{{ $document->file_name }}</h6>
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

                    @if((!$applicationResultDefense->report_document || $applicationResultDefense->report_document->count() == 0) 
                        && !$applicationResultDefense->attendance_document 
                        && (!$applicationResultDefense->form_document || $applicationResultDefense->form_document->count() == 0))
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
            @if($applicationResultDefense->application)
                <div class="card-modern mb-4">
                    <div class="card-modern-body">
                        <h5 class="font-weight-bold mb-3">Informasi Aplikasi</h5>
                        
                        <div class="mb-3">
                            <label class="text-muted mb-1">Tipe</label>
                            <p class="font-weight-semibold text-uppercase">
                                <span class="badge badge-primary">{{ $applicationResultDefense->application->type }}</span>
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted mb-1">Tahap</label>
                            <p class="font-weight-semibold text-capitalize">
                                <span class="badge badge-info">{{ $applicationResultDefense->application->stage }}</span>
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted mb-1">Status</label>
                            <p class="font-weight-semibold text-capitalize">
                                <span class="badge badge-secondary">{{ $applicationResultDefense->application->status }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->
            @if($applicationResultDefense->result === 'passed' || $applicationResultDefense->result === 'passed_with_honor' || $applicationResultDefense->result === 'passed_with_revision')
                <div class="card-modern">
                    <div class="card-modern-body">
                        <h5 class="font-weight-bold mb-3">Selamat!</h5>
                        <div class="alert alert-success mb-0">
                            <i class="fas fa-graduation-cap"></i> 
                            <strong>Anda telah lulus!</strong>
                            <p class="mb-0 mt-2 small">Terima kasih atas dedikasi dan kerja keras Anda.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Back Button -->
    <div class="row mt-4">
        <div class="col-lg-12">
            <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
