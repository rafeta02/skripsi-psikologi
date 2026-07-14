@extends('layouts.mahasiswa')

@section('title', 'Pendaftaran MBKM')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, var(--primary-500) 0%, var(--secondary-500) 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-1 text-white font-weight-bold">
                                <i class="fas fa-users mr-2"></i> Pendaftaran MBKM
                            </h2>
                            <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                                Kelola pengajuan skripsi jalur MBKM Riset Anda
                            </p>
                        </div>
                        <div class="col-md-4 text-right">
                            @if(empty($isGroupFollower) && $applications->whereIn('status', ['submitted', 'approved', 'scheduled', 'revision'])->isEmpty())
                                <a href="{{ route('frontend.choose-path') }}" class="btn btn-light btn-lg shadow">
                                    <i class="fas fa-plus-circle"></i> Mulai Pendaftaran
                                </a>
                            @endif
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
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show">
            {{ session('info') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    @if(!empty($isGroupFollower))
        <div class="alert alert-info">
            <i class="fas fa-users mr-1"></i>
            Anda anggota kelompok MBKM. Form kelompok diisi oleh <strong>ketua</strong>.
            Lengkapi syarat individu Anda, lalu pantau status pengajuan kelompok.
            <div class="mt-2">
                <a href="{{ route('frontend.mbkm.member-requirements') }}" class="btn btn-sm btn-primary">
                    Lengkapi Syarat Individu
                </a>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            @if($applications->count() > 0)
                @foreach($applications as $application)
                    @php
                        $registration = $application->resolveOwnerMbkmRegistration();
                        $detailAppId = (!empty($application->is_group_mirror) && $application->parent_application_id)
                            ? $application->parent_application_id
                            : $application->id;
                        $canFillForm = empty($isGroupFollower)
                            && $application->stage === 'registration'
                            && empty($application->is_group_mirror)
                            && !$registration
                            && in_array($application->status, ['submitted', 'revision', 'rejected'], true);
                        $canEdit = empty($isGroupFollower)
                            && $registration
                            && empty($application->is_group_mirror)
                            && in_array($application->status, ['submitted', 'revision', 'rejected'], true)
                            && !($registration->isGroupSubmitted() && $application->status !== 'revision');
                    @endphp
                    <div class="card-modern mb-4">
                        <div class="card-modern-body">
                            <div class="row align-items-start">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-start mb-3">
                                        <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #3498db, #2ecc71); border-radius: var(--radius-base); display: flex; align-items: center; justify-content: center; margin-right: var(--spacing-3);">
                                            <i class="fas fa-file-signature" style="font-size: 20px; color: white;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h4 class="mb-1 font-weight-bold">
                                                {{ $registration->title_mbkm ?? 'Pendaftaran MBKM' }}
                                            </h4>
                                            <p class="mb-2 text-muted">
                                                <i class="fas fa-layer-group mr-1"></i>
                                                Tahap: {{ ucfirst($application->stage) }}
                                                @if(!empty($application->is_group_mirror))
                                                    <span class="ml-2">(Status kelompok)</span>
                                                @endif
                                            </p>
                                            <div class="d-flex flex-wrap gap-2">
                                                @if($application->status == 'submitted')
                                                    <span class="badge-modern badge-modern-warning">
                                                        <i class="fas fa-clock"></i> Menunggu Review
                                                    </span>
                                                @elseif($application->status == 'approved')
                                                    <span class="badge-modern badge-modern-success">
                                                        <i class="fas fa-check-circle"></i> Disetujui
                                                    </span>
                                                @elseif($application->status == 'scheduled')
                                                    <span class="badge-modern badge-modern-info">
                                                        <i class="fas fa-calendar-check"></i> Terjadwal
                                                    </span>
                                                @elseif($application->status == 'revision')
                                                    <span class="badge-modern badge-modern-warning">
                                                        <i class="fas fa-edit"></i> Revisi
                                                    </span>
                                                @elseif($application->status == 'rejected')
                                                    <span class="badge-modern badge-modern-danger">
                                                        <i class="fas fa-times-circle"></i> Ditolak
                                                    </span>
                                                @elseif($application->status == 'done')
                                                    <span class="badge-modern badge-modern-secondary">
                                                        <i class="fas fa-flag-checkered"></i> Selesai
                                                    </span>
                                                @else
                                                    <span class="badge-modern badge-modern-outline">
                                                        {{ ucfirst($application->status) }}
                                                    </span>
                                                @endif

                                                @if($registration)
                                                    @if(($registration->group_status ?? 'draft') === 'submitted')
                                                        <span class="badge-modern badge-modern-outline">
                                                            <i class="fas fa-paper-plane"></i> Kelompok diajukan
                                                        </span>
                                                    @else
                                                        <span class="badge-modern badge-modern-outline">
                                                            <i class="fas fa-pencil-alt"></i> Draft kelompok
                                                        </span>
                                                    @endif
                                                @endif

                                                <span class="badge-modern badge-modern-outline">
                                                    <i class="far fa-calendar"></i> {{ $application->created_at->format('d M Y') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    @if($registration)
                                        <div class="small text-muted">
                                            <div><strong>Research group:</strong> {{ $registration->research_group->name ?? '-' }}</div>
                                            <div><strong>Pembimbing:</strong> {{ $registration->preference_supervision->nama ?? '-' }}</div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-4 text-right">
                                    <div class="d-flex flex-column gap-2">
                                        <a href="{{ route('frontend.mbkm.show', $detailAppId) }}" class="btn-modern btn-modern-primary">
                                            <i class="fas fa-eye"></i> Lihat Detail
                                        </a>

                                        @if($canFillForm)
                                            <a href="{{ route('frontend.mbkm.create', $application->id) }}" class="btn-modern btn-modern-outline">
                                                <i class="fas fa-plus-circle"></i> Lengkapi Form
                                            </a>
                                        @elseif($canEdit)
                                            <a href="{{ route('frontend.mbkm.edit', $application->id) }}" class="btn-modern btn-modern-outline">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        @endif

                                        @if(!empty($isGroupFollower))
                                            <a href="{{ route('frontend.mbkm.member-requirements') }}" class="btn-modern btn-modern-outline">
                                                <i class="fas fa-user-edit"></i> Syarat Individu
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="card-modern">
                    <div class="card-modern-body text-center py-5">
                        <div style="width: 100px; height: 100px; background: var(--gray-100); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--spacing-4);">
                            <i class="fas fa-users fa-3x text-muted"></i>
                        </div>
                        @if(!empty($isGroupFollower))
                            <h4 class="text-muted mb-3">Menunggu Data Kelompok</h4>
                            <p class="text-muted mb-4">Status pendaftaran MBKM kelompok akan muncul di sini setelah ketua membuat pengajuan.</p>
                            <a href="{{ route('frontend.mbkm.member-requirements') }}" class="btn-modern btn-modern-primary btn-modern-lg">
                                <i class="fas fa-user-edit"></i> Lengkapi Syarat Individu
                            </a>
                        @else
                            <h4 class="text-muted mb-3">Belum Ada Pendaftaran MBKM</h4>
                            <p class="text-muted mb-4">Mulai dengan memilih jalur skripsi MBKM Riset.</p>
                            <a href="{{ route('frontend.choose-path') }}" class="btn-modern btn-modern-primary btn-modern-lg">
                                <i class="fas fa-route"></i> Pilih Jalur Skripsi
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
