@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="mb-4">
        <h2 class="mb-1"><i class="fas fa-folder text-primary"></i> Dokumen Saya</h2>
        <p class="text-muted mb-2">Kelola dan upload dokumen skripsi dan persyaratan</p>
        <small class="text-muted">
            <i class="fas fa-info-circle"></i> Total {{ count($applications) }} aplikasi dengan dokumen
        </small>
    </div>

    <!-- Info Card -->
    @if(count($applications) > 0)
    <div class="alert alert-info border-left" style="border-left: 4px solid #17a2b8 !important;">
        <div class="d-flex align-items-center">
            <i class="fas fa-file-upload fa-2x mr-3"></i>
            <div>
                <h5 class="mb-1">Manajemen Dokumen</h5>
                <p class="mb-0">Pastikan semua dokumen yang diupload sesuai dengan format dan persyaratan yang telah ditentukan. Ukuran maksimal file adalah 5MB.</p>
            </div>
        </div>
    </div>
    @endif

    @if(count($applications) > 0)
        @foreach($applications as $app)
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-graduation-cap"></i> 
                        {{ strtoupper($app->type) }} - {{ ucfirst($app->stage) }}
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Status:</strong>
                            @if($app->status == 'submitted')
                                <span class="badge badge-warning">Submitted</span>
                            @elseif($app->status == 'approved')
                                <span class="badge badge-success">Approved</span>
                            @elseif($app->status == 'rejected')
                                <span class="badge badge-danger">Rejected</span>
                            @elseif($app->status == 'scheduled')
                                <span class="badge badge-info">Scheduled</span>
                            @elseif($app->status == 'done')
                                <span class="badge badge-secondary">Done</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <strong>Tanggal:</strong> {{ $app->submitted_at ?? $app->created_at->format('d M Y') }}
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Untuk upload dan mengelola dokumen, silakan kunjungi detail aplikasi di bawah ini.
                    </div>

                    <h5 class="mt-4"><i class="fas fa-paperclip"></i> Quick Links</h5>
                    <div class="btn-group-vertical w-100" role="group">
                        @if($app->type == 'skripsi')
                            @if($app->stage == 'registration')
                                <a href="{{ route('frontend.skripsi-registrations.index') }}" class="btn btn-outline-primary text-left mb-2">
                                    <i class="fas fa-file-upload"></i> Upload Dokumen Pendaftaran
                                </a>
                            @elseif($app->stage == 'seminar')
                                <a href="{{ route('frontend.skripsi-seminars.index') }}" class="btn btn-outline-primary text-left mb-2">
                                    <i class="fas fa-file-upload"></i> Pendaftaran Reviewer Proposal
                                </a>
                                @can('application_result_seminar_access')
                                <a href="{{ route('frontend.application-result-seminars.index') }}" class="btn btn-outline-danger text-left mb-2">
                                    <i class="fas fa-clipboard-check"></i> Laporan Hasil Review Proposal
                                </a>
                                @endcan
                            @elseif($app->stage == 'defense')
                                <a href="{{ route('frontend.skripsi-defenses.index') }}" class="btn btn-outline-primary text-left mb-2">
                                    <i class="fas fa-file-upload"></i> Upload Dokumen Sidang
                                </a>
                            @endif
                        @elseif($app->type == 'mbkm')
                            @if($app->stage == 'registration')
                                <a href="{{ route('frontend.mbkm-registrations.index') }}" class="btn btn-outline-primary text-left mb-2">
                                    <i class="fas fa-file-upload"></i> Upload Dokumen Pendaftaran MBKM
                                </a>
                            @elseif($app->stage == 'seminar')
                                <a href="{{ route('frontend.mbkm-seminars.index') }}" class="btn btn-outline-primary text-left mb-2">
                                    <i class="fas fa-file-upload"></i> Upload Dokumen Seminar MBKM
                                </a>
                            @endif
                        @endif
                        
                        <a href="{{ route('frontend.applications.show', $app->id) }}" class="btn btn-primary text-left">
                            <i class="fas fa-eye"></i> Lihat Detail Aplikasi
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                <h3>Belum Ada Dokumen</h3>
                <p class="text-muted mb-4">Anda belum memiliki dokumen. Mulai dengan membuat aplikasi skripsi terlebih dahulu.</p>
                <a href="{{ route('frontend.choose-path') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-route"></i> Pilih Jalur Skripsi
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
