@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <h2><i class="fas fa-folder"></i> Dokumen Saya</h2>
        <p class="text-muted">Kelola dokumen skripsi dan persyaratan</p>
    </div>

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
                                    <i class="fas fa-file-upload"></i> Upload Dokumen Seminar
                                </a>
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
