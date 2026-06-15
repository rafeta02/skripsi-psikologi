@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, #16a085 0%, #27ae60 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-1 text-white font-weight-bold">
                                <i class="fas fa-graduation-cap mr-2"></i> Pendaftaran Sidang Skripsi
                            </h2>
                            <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                                Kelola pengajuan sidang skripsi/MBKM Anda
                            </p>
                        </div>
                        <div class="col-md-4 text-right">
                            @can('skripsi_defense_create')
                                @if($defenseAccess['allowed'] ?? false)
                                    <a href="{{ route('frontend.skripsi-defenses.create') }}" class="btn btn-light btn-lg shadow">
                                        <i class="fas fa-plus-circle"></i> Daftar Sidang
                                    </a>
                                @else
                                    <button class="btn btn-light btn-lg shadow" disabled title="{{ $defenseAccess['message'] ?? '' }}" style="opacity: .75;">
                                        <i class="fas fa-lock"></i> Daftar Sidang
                                    </button>
                                @endif
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

    @if($defenseAccess['retry_after_failed'] ?? false)
        <div class="alert alert-info">
            <i class="fas fa-redo"></i>
            Hasil sidang sebelumnya <strong>tidak lulus</strong> dan sudah divalidasi admin. Anda dapat mendaftar ulang sidang skripsi.
        </div>
    @endif

    @if(($defenseAccess['allowed'] ?? true) === false && ($defenseAccess['message'] ?? null))
        <div class="alert alert-warning">
            <i class="fas fa-info-circle"></i> {{ $defenseAccess['message'] }}
        </div>
    @endif

    @if(($skripsiDefenses ?? collect())->contains(fn ($defense) => $defense->isAccepted()))
        @include('partials.siakad-upload-warning')
    @endif

    <div class="row">
        <div class="col-lg-12">
            @if(($skripsiDefenses ?? collect())->count() > 0)
                @foreach($skripsiDefenses as $defense)
                    <div class="card-modern mb-4">
                        <div class="card-modern-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h4 class="mb-1 font-weight-bold">Pengajuan Sidang #{{ $defense->id }}</h4>
                                    <p class="text-muted mb-0">
                                        <i class="far fa-calendar"></i>
                                        {{ $defense->created_at ? $defense->created_at->format('d M Y H:i') : '-' }}
                                    </p>
                                </div>
                                <div class="col-md-4 text-right">
                                    @can('skripsi_defense_show')
                                        <a href="{{ route('frontend.skripsi-defenses.show', $defense->id) }}" class="btn-modern btn-modern-primary">
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
                        <div style="width: 100px; height: 100px; background: var(--gray-100); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--spacing-4);">
                            <i class="fas fa-graduation-cap fa-3x text-muted"></i>
                        </div>
                        <h4 class="text-muted mb-3">Belum Ada Pengajuan Sidang</h4>
                        <p class="text-muted mb-4">Silakan ajukan sidang jika sudah memenuhi prasyarat.</p>
                        @can('skripsi_defense_create')
                            @if($defenseAccess['allowed'] ?? false)
                                <a href="{{ route('frontend.skripsi-defenses.create') }}" class="btn-modern btn-modern-primary btn-modern-lg">
                                    <i class="fas fa-plus-circle"></i> Daftar Sidang Sekarang
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

