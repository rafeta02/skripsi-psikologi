@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, #27ae60 0%, #1e8449 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-1 text-white font-weight-bold">
                                <i class="fas fa-award mr-2"></i> Hasil Sidang Skripsi
                            </h2>
                            <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                                Laporan hasil pelaksanaan sidang skripsi/MBKM
                            </p>
                        </div>
                        <div class="col-md-4 text-right">
                            @can('application_result_defense_create')
                                @if($canCreate['allowed'] ?? false)
                                    <a href="{{ route('frontend.application-result-defenses.create') }}" class="btn btn-light btn-lg shadow">
                                        <i class="fas fa-plus-circle"></i> Laporkan Hasil
                                    </a>
                                @endif
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>
    @endif

    @if(!($canCreate['allowed'] ?? false) && ($canCreate['message'] ?? null))
        <div class="alert alert-info"><i class="fas fa-info-circle"></i> {{ $canCreate['message'] }}</div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            @if($applicationResultDefenses->count() > 0)
                @foreach($applicationResultDefenses as $result)
                    <div class="card-modern mb-4">
                        <div class="card-modern-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h4 class="font-weight-bold mb-2">Laporan Hasil Sidang</h4>
                                    <p class="text-muted mb-2">
                                        <i class="far fa-calendar"></i> {{ $result->created_at->format('d M Y H:i') }}
                                    </p>
                                    @php
                                        $resultBadge = match($result->result) {
                                            'passed' => ['success', 'Lulus'],
                                            'revision' => ['warning', 'Revisi'],
                                            'failed' => ['danger', 'Tidak Lulus'],
                                            default => ['secondary', ucfirst($result->result ?? '-')],
                                        };
                                    @endphp
                                    <span class="badge badge-{{ $resultBadge[0] }} badge-lg px-3 py-2">{{ $resultBadge[1] }}</span>
                                </div>
                                <div class="col-md-4 text-right">
                                    @can('application_result_defense_show')
                                        <a href="{{ route('frontend.application-result-defenses.show', $result->id) }}" class="btn-modern btn-modern-primary">
                                            <i class="fas fa-eye"></i> Lihat Detail
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="card-modern">
                    <div class="card-modern-body text-center py-5">
                        <i class="fas fa-award fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted mb-3">Belum Ada Laporan Hasil Sidang</h4>
                        <p class="text-muted mb-4">Setelah sidang dilaksanakan dan jadwal diverifikasi admin, laporkan hasil sidang di sini.</p>
                        @can('application_result_defense_create')
                            @if($canCreate['allowed'] ?? false)
                                <a href="{{ route('frontend.application-result-defenses.create') }}" class="btn btn-success btn-lg">
                                    <i class="fas fa-plus-circle"></i> Laporkan Hasil Sidang
                                </a>
                            @endif
                        @endcan
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
