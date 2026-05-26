@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, #e67e22 0%, #d35400 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-1 text-white font-weight-bold">
                                <i class="fas fa-exclamation-circle mr-2"></i> Laporan Masalah
                            </h2>
                            <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                                Laporkan kendala atau masalah terkait skripsi/MBKM
                            </p>
                        </div>
                        <div class="col-md-4 text-right">
                            @can('application_report_create')
                                <a href="{{ route('frontend.application-reports.create') }}" class="btn btn-light btn-lg shadow">
                                    <i class="fas fa-plus-circle"></i> Buat Laporan
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <!-- Reports List -->
    <div class="row">
        <div class="col-lg-12">
            @if($applicationReports->count() > 0)
                @foreach($applicationReports as $report)
                    <div class="card-modern mb-4">
                        <div class="card-modern-body">
                            <div class="row align-items-start">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-start mb-3">
                                        <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #e67e22, #d35400); border-radius: var(--radius-base); display: flex; align-items: center; justify-content: center; margin-right: var(--spacing-3);">
                                            <i class="fas fa-exclamation-triangle" style="font-size: 20px; color: white;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h4 class="mb-1 font-weight-bold">
                                                Laporan #{{ $report->id }}
                                                @if($report->period)
                                                    <small class="text-muted">— {{ App\Models\ApplicationReport::PERIOD_SELECT[$report->period] ?? $report->period }}</small>
                                                @endif
                                            </h4>
                                            <p class="text-muted mb-2">
                                                <i class="far fa-calendar"></i> {{ $report->created_at->format('d M Y H:i') }}
                                            </p>
                                            @if($report->report_text)
                                                <p class="mb-2">{{ Str::limit(strip_tags($report->report_text), 150) }}</p>
                                            @endif
                                            <div class="d-flex flex-wrap gap-2">
                                                @if($report->status == 'submitted')
                                                    <span class="badge badge-warning">
                                                        <i class="fas fa-clock"></i> Menunggu Tinjauan Admin
                                                    </span>
                                                @elseif($report->status == 'reviewed')
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check-circle"></i> Sudah Ditinjau
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 text-right">
                                    <a href="{{ route('frontend.application-reports.show', $report->id) }}" class="btn-modern btn-modern-primary">
                                        <i class="fas fa-eye"></i> Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="card-modern">
                    <div class="card-modern-body text-center py-5">
                        <div style="width: 100px; height: 100px; background: var(--gray-100); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--spacing-4);">
                            <i class="fas fa-clipboard-list fa-3x text-muted"></i>
                        </div>
                        <h4 class="text-muted mb-3">Belum Ada Laporan</h4>
                        <p class="text-muted mb-4">Anda belum membuat laporan masalah</p>
                        @can('application_report_create')
                            <a href="{{ route('frontend.application-reports.create') }}" class="btn-modern btn-modern-primary btn-modern-lg">
                                <i class="fas fa-plus-circle"></i> Buat Laporan Sekarang
                            </a>
                        @endcan
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
