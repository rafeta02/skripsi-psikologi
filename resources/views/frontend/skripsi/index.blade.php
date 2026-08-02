@extends('layouts.mahasiswa')

@section('title', 'Pendaftaran Skripsi Reguler')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, var(--primary-500) 0%, var(--secondary-500) 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-1 text-white font-weight-bold">
                                <i class="fas fa-graduation-cap mr-2"></i> Pendaftaran Skripsi Reguler
                            </h2>
                            <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                                Kelola pengajuan pendaftaran skripsi reguler Anda
                            </p>
                        </div>
                        <div class="col-md-4 text-right">
                            @if($applications->whereIn('status', ['submitted', 'approved', 'scheduled', 'revision'])->isEmpty())
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

    <div class="row">
        <div class="col-lg-12">
            @if($applications->count() > 0)
                @foreach($applications as $application)
                    @php
                        $registration = $application->skripsiRegistration;
                        $regStatus = $application->getRegistrationStatusForMahasiswa();
                        $canFillForm = $application->stage === 'registration'
                            && !$registration
                            && in_array($application->status, ['submitted', 'revision', 'rejected'], true);
                        $canEdit = $registration
                            && in_array($application->status, ['submitted', 'rejected', 'revision'], true);
                    @endphp
                    <div class="card-modern mb-4">
                        <div class="card-modern-body">
                            <div class="row align-items-start">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-start mb-3">
                                        <div style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--primary-500), var(--secondary-500)); border-radius: var(--radius-base); display: flex; align-items: center; justify-content: center; margin-right: var(--spacing-3);">
                                            <i class="fas fa-file-signature" style="font-size: 20px; color: white;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h4 class="mb-1 font-weight-bold">
                                                {{ $registration->title ?? 'Pendaftaran Skripsi Reguler' }}
                                            </h4>
                                            <p class="mb-2 text-muted">
                                                <i class="fas fa-layer-group mr-1"></i>
                                                Tahap: {{ ucfirst($application->stage) }}
                                            </p>
                                            <div class="d-flex flex-wrap gap-2">
                                                <span class="badge badge-{{ $regStatus['badge'] }}">
                                                    <i class="fas fa-{{ $regStatus['icon'] }}"></i> {{ $regStatus['label'] }}
                                                </span>

                                                @if(!$registration)
                                                    <span class="badge-modern badge-modern-outline">
                                                        <i class="fas fa-exclamation-circle"></i> Form belum dilengkapi
                                                    </span>
                                                @endif

                                                <span class="badge-modern badge-modern-outline">
                                                    <i class="far fa-calendar"></i> {{ $application->created_at->format('d M Y') }}
                                                </span>
                                            </div>
                                            @if(!empty($regStatus['detail']))
                                                <small class="text-muted d-block mt-2">{{ $regStatus['detail'] }}</small>
                                            @endif
                                            @if($application->status === 'revision' && $registration?->revision_notes)
                                                <div class="alert alert-warning py-2 px-3 mt-2 mb-0 small text-left">
                                                    <strong>Catatan revisi admin:</strong>
                                                    <div class="mt-1">{{ $registration->revision_notes }}</div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    @if($registration)
                                        <div class="small text-muted">
                                            <div><strong>Tema riset:</strong> {{ $registration->themes_label ?: '-' }}</div>
                                            <div><strong>Preferensi pembimbing:</strong> {{ $registration->preference_supervision->nama ?? '-' }}</div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-4 text-right">
                                    <div class="d-flex flex-column gap-2">
                                        <a href="{{ route('frontend.skripsi-registrations.show', $registration?->id ?? $application->id) }}" class="btn-modern btn-modern-primary">
                                            <i class="fas fa-eye"></i> Lihat Detail
                                        </a>

                                        @if($canFillForm)
                                            <a href="{{ route('frontend.skripsi.create', $application->id) }}" class="btn-modern btn-modern-outline">
                                                <i class="fas fa-plus-circle"></i> Lengkapi Form
                                            </a>
                                        @elseif($canEdit)
                                            <a href="{{ route('frontend.skripsi-registrations.edit', $registration->id) }}" class="btn-modern btn-modern-outline">
                                                <i class="fas fa-edit"></i>
                                                {{ $application->status === 'revision' ? 'Perbaiki Revisi' : 'Edit' }}
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
                            <i class="fas fa-graduation-cap fa-3x text-muted"></i>
                        </div>
                        <h4 class="text-muted mb-3">Belum Ada Pendaftaran Skripsi</h4>
                        <p class="text-muted mb-4">Mulai dengan memilih jalur skripsi reguler.</p>
                        <a href="{{ route('frontend.choose-path') }}" class="btn-modern btn-modern-primary btn-modern-lg">
                            <i class="fas fa-route"></i> Pilih Jalur Skripsi
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
