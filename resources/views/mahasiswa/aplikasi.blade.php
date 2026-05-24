@extends('layouts.mahasiswa')

@push('styles')
<style>
    .border-left {
        border-left-width: 4px !important;
    }
    
    .badge-lg {
        font-size: 14px;
        padding: 8px 16px;
        border-radius: 20px;
    }
    
    .card {
        border-radius: 12px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }
    
    .card-header {
        border-bottom: none;
        padding: 1.5rem;
    }
    
    .card-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #e9ecef;
        padding: 1.25rem 1.5rem;
    }
    
    .opacity-75 {
        opacity: 0.75;
    }
    
    .text-capitalize {
        text-transform: capitalize;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h2 class="mb-1"><i class="fas fa-file-alt text-primary"></i> Aplikasi Saya</h2>
            <p class="text-muted mb-0">Kelola dan pantau semua aplikasi skripsi Anda</p>
            <small class="text-muted">
                <i class="fas fa-info-circle"></i> 
                @if(count($applications) == 0)
                    Belum ada aplikasi
                @elseif(count($applications) == 1)
                    1 aplikasi aktif
                @else
                    Total {{ count($applications) }} aplikasi
                @endif
            </small>
        </div>
        <div class="col-md-4 text-right">
            @php
                $hasActiveApplication = $applications->whereIn('status', ['submitted', 'approved', 'scheduled', 'revision'])->count() > 0;
            @endphp
            
            @if(!$hasActiveApplication)
                <a href="{{ route('frontend.choose-path') }}" class="btn btn-primary btn-lg shadow">
                    <i class="fas fa-plus-circle"></i> Buat Aplikasi Baru
                </a>
            @else
                <button class="btn btn-secondary btn-lg" disabled title="Anda sudah memiliki aplikasi aktif">
                    <i class="fas fa-check-circle"></i> Sudah Ada Aplikasi Aktif
                </button>
            @endif
        </div>
    </div>

    <!-- Info Card -->
    @if(count($applications) > 0)
        <div class="alert alert-info border-left mb-4" style="border-left: 4px solid #17a2b8 !important;">
            <div class="d-flex align-items-center">
                <i class="fas fa-lightbulb fa-2x mr-3"></i>
                <div>
                    <h5 class="mb-1">Tips</h5>
                    <p class="mb-0">Pastikan Anda selalu cek status aplikasi secara berkala untuk informasi revisi atau persetujuan dari admin. Jika ada catatan revisi, segera perbaiki agar proses tidak tertunda.</p>
                </div>
            </div>
        </div>
    @endif

    @if(count($applications) > 0)
        @foreach($applications as $app)
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); color: white; border-radius: 12px 12px 0 0;">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-0">
                                <i class="fas fa-graduation-cap mr-2"></i>
                                @if($app->type == 'skripsi')
                                    Skripsi Reguler
                                @elseif($app->type == 'mbkm')
                                    Skripsi MBKM
                                @else
                                    {{ ucfirst($app->type) }}
                                @endif
                            </h4>
                            <small class="opacity-75">
                                <i class="far fa-calendar-alt"></i> Didaftarkan: {{ $app->submitted_at ? \Carbon\Carbon::parse($app->submitted_at)->format('d M Y H:i') : $app->created_at->format('d M Y H:i') }}
                            </small>
                        </div>
                        <div class="col-md-4 text-right">
                            @if($app->status == 'submitted')
                                <span class="badge badge-warning badge-lg px-3 py-2" style="font-size: 14px;">
                                    <i class="fas fa-clock"></i> Menunggu Review
                                </span>
                            @elseif($app->status == 'approved')
                                <span class="badge badge-success badge-lg px-3 py-2" style="font-size: 14px;">
                                    <i class="fas fa-check-circle"></i> Disetujui
                                </span>
                            @elseif($app->status == 'rejected')
                                <span class="badge badge-danger badge-lg px-3 py-2" style="font-size: 14px;">
                                    <i class="fas fa-times-circle"></i> Ditolak
                                </span>
                            @elseif($app->status == 'revision')
                                <span class="badge badge-warning badge-lg px-3 py-2" style="font-size: 14px;">
                                    <i class="fas fa-edit"></i> Perlu Revisi
                                </span>
                            @elseif($app->status == 'scheduled')
                                <span class="badge badge-info badge-lg px-3 py-2" style="font-size: 14px;">
                                    <i class="fas fa-calendar-check"></i> Terjadwal
                                </span>
                            @elseif($app->status == 'done')
                                <span class="badge badge-secondary badge-lg px-3 py-2" style="font-size: 14px;">
                                    <i class="fas fa-flag-checkered"></i> Selesai
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small mb-1"><i class="fas fa-layer-group"></i> Tahap Saat Ini</label>
                                <h5 class="mb-0 text-capitalize">
                                    @if($app->stage == 'registration')
                                        <i class="fas fa-file-signature text-primary"></i> Pendaftaran
                                    @elseif($app->stage == 'seminar')
                                        <i class="fas fa-chalkboard-teacher text-info"></i> Seminar
                                    @elseif($app->stage == 'defense')
                                        <i class="fas fa-university text-warning"></i> Sidang
                                    @elseif($app->stage == 'completed')
                                        <i class="fas fa-trophy text-success"></i> Selesai
                                    @else
                                        {{ $app->stage }}
                                    @endif
                                </h5>
                            </div>
                        </div>
                        
                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small mb-1"><i class="fas fa-info-circle"></i> Status Detail</label>
                                <h5 class="mb-0">
                                    @if($app->status == 'submitted')
                                        <span class="text-warning">Menunggu Review Admin</span>
                                    @elseif($app->status == 'approved')
                                        <span class="text-success">Telah Disetujui</span>
                                    @elseif($app->status == 'rejected')
                                        <span class="text-danger">Ditolak oleh Admin</span>
                                    @elseif($app->status == 'revision')
                                        <span class="text-warning">Memerlukan Perbaikan</span>
                                    @elseif($app->status == 'scheduled')
                                        <span class="text-info">Sudah Dijadwalkan</span>
                                    @elseif($app->status == 'done')
                                        <span class="text-secondary">Proses Selesai</span>
                                    @endif
                                </h5>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Revision Notes -->
                    @if($app->status == 'revision' && isset($app->revision_notes))
                        <div class="alert alert-warning border-left mt-3" style="border-left: 4px solid #ffc107 !important; background-color: #fff3cd;">
                            <div class="d-flex">
                                <div class="mr-3">
                                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-2"><strong>Catatan Revisi:</strong></h5>
                                    <p class="mb-0">{{ $app->revision_notes }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- General Notes -->
                    @if($app->notes && $app->status != 'revision')
                        <div class="alert alert-info border-left mt-3" style="border-left: 4px solid #17a2b8 !important;">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-sticky-note fa-lg mr-3 mt-1"></i>
                                <div>
                                    <strong>Catatan:</strong> {{ $app->notes }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="card-footer bg-light" style="border-radius: 0 0 12px 12px;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">
                                <i class="far fa-clock"></i> Terakhir update: {{ $app->updated_at->diffForHumans() }}
                            </small>
                        </div>
                        <div>
                            <a href="{{ route('frontend.applications.show', $app->id) }}" class="btn btn-primary">
                                <i class="fas fa-eye"></i> Lihat Detail
                            </a>
                            @if($app->status == 'revision')
                                @if($app->type == 'mbkm')
                                    <a href="{{ route('frontend.mbkm.edit', $app->id) }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Perbaiki Sekarang
                                    </a>
                                @elseif($app->type == 'skripsi')
                                    <a href="{{ route('frontend.skripsi.edit', $app->id) }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Perbaiki Sekarang
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                <h3>Belum Ada Aplikasi</h3>
                <p class="text-muted mb-4">Anda belum memiliki aplikasi skripsi. Mulai dengan memilih jalur skripsi Anda.</p>
                <a href="{{ route('frontend.choose-path') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-route"></i> Pilih Jalur Skripsi
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
