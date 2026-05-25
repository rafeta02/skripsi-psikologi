@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, var(--primary-500) 0%, var(--secondary-500) 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-1 text-white font-weight-bold">
                                <i class="fas fa-presentation mr-2"></i> Pendaftaran Seminar Proposal
                            </h2>
                            <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                                Daftar untuk mengikuti seminar proposal skripsi
                            </p>
                        </div>
                        <div class="col-md-4 text-right">
                            @can('skripsi_seminar_create')
                                <a href="{{ route('frontend.skripsi-seminars.create') }}" class="btn btn-light btn-lg shadow">
                                    <i class="fas fa-plus-circle"></i> Daftar Seminar
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
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show">
            {{ session('warning') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    @if(isset($retrySeminar) && $retrySeminar)
        <div class="alert alert-danger border-left mb-4" style="border-left: 4px solid #dc3545 !important;">
            <h5 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Review Proposal Tidak Lulus</h5>
            <p class="mb-2">Perbaiki dokumen pendaftaran reviewer dan kirim ulang. Penugasan reviewer sebelumnya akan dikosongkan; admin akan menugaskan reviewer baru setelah Anda mengirim ulang.</p>
            @can('skripsi_seminar_edit')
            <a href="{{ route('frontend.skripsi-seminars.edit', $retrySeminar->id) }}" class="btn btn-danger">
                <i class="fas fa-edit"></i> Perbaiki & Unggah Ulang Pendaftaran
            </a>
            @endcan
        </div>
    @endif

    <!-- Seminars List -->
    <div class="row">
        <div class="col-lg-12">
            @if($skripsiSeminars->count() > 0)
                @foreach($skripsiSeminars as $seminar)
                    <div class="card-modern mb-4">
                        <div class="card-modern-body">
                            <div class="row align-items-start">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-start mb-3">
                                        <div style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--primary-500), var(--secondary-500)); border-radius: var(--radius-base); display: flex; align-items: center; justify-content: center; margin-right: var(--spacing-3);">
                                            <i class="fas fa-presentation" style="font-size: 20px; color: white;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h4 class="mb-1 font-weight-bold">Seminar Proposal</h4>
                                            <p class="mb-2 text-muted">
                                                <i class="fas fa-book mr-2"></i>{{ $seminar->title ?? 'Judul Proposal' }}
                                            </p>
                                            <div class="d-flex flex-wrap gap-2">
                                                @if($seminar->application)
                                                    @if($seminar->application->status == 'submitted')
                                                        <span class="badge-modern badge-modern-warning">
                                                            <i class="fas fa-clock"></i> Menunggu Review
                                                        </span>
                                                    @elseif($seminar->application->status == 'approved')
                                                        <span class="badge-modern badge-modern-success">
                                                            <i class="fas fa-check-circle"></i> Disetujui
                                                        </span>
                                                    @elseif($seminar->application->status == 'scheduled')
                                                        <span class="badge-modern badge-modern-info">
                                                            <i class="fas fa-calendar-check"></i> Terjadwal
                                                        </span>
                                                    @elseif($seminar->application->status == 'revision')
                                                        <span class="badge-modern badge-modern-warning">
                                                            <i class="fas fa-edit"></i> Revisi
                                                        </span>
                                                    @elseif($seminar->application->status == 'rejected')
                                                        <span class="badge-modern badge-modern-danger">
                                                            <i class="fas fa-times-circle"></i>
                                                            {{ ($retrySeminar && (int) $retrySeminar->id === (int) $seminar->id) ? 'Review Gagal — Perlu Unggah Ulang' : 'Ditolak' }}
                                                        </span>
                                                    @elseif($seminar->application->status == 'done')
                                                        <span class="badge-modern badge-modern-secondary">
                                                            <i class="fas fa-flag-checkered"></i> Selesai
                                                        </span>
                                                    @endif
                                                @endif
                                                
                                                <span class="badge-modern badge-modern-outline">
                                                    <i class="far fa-calendar"></i> {{ $seminar->created_at->format('d M Y') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    @if($seminar->description)
                                        <div class="mb-3">
                                            <p class="text-muted mb-0">{{ Str::limit($seminar->description, 200) }}</p>
                                        </div>
                                    @endif

                                    <!-- Documents -->
                                    @if($seminar->proposal_document || $seminar->approval_document || $seminar->plagiarism_document)
                                        <div class="mb-3">
                                            <h6 class="font-weight-semibold mb-2">Dokumen Terlampir:</h6>
                                            <div class="d-flex flex-wrap gap-2">
                                                @if($seminar->proposal_document)
                                                    <a href="{{ $seminar->proposal_document->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-file-pdf"></i> Proposal
                                                    </a>
                                                @endif
                                                @if($seminar->approval_document)
                                                    <a href="{{ $seminar->approval_document->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-success">
                                                        <i class="fas fa-file-pdf"></i> Persetujuan
                                                    </a>
                                                @endif
                                                @if($seminar->plagiarism_document)
                                                    <a href="{{ $seminar->plagiarism_document->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                        <i class="fas fa-file-pdf"></i> Plagiarism Check
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-4 text-right">
                                    <div class="d-flex flex-column gap-2">
                                        @can('skripsi_seminar_show')
                                            <a href="{{ route('frontend.skripsi-seminars.show', $seminar->id) }}" class="btn-modern btn-modern-primary">
                                                <i class="fas fa-eye"></i> Lihat Detail
                                            </a>
                                        @endcan
                                        
                                        @can('skripsi_seminar_edit')
                                            @php
                                                $canEditSeminar = $seminar->application && (
                                                    in_array($seminar->application->status, ['revision', 'submitted'])
                                                    || ($retrySeminar && (int) $retrySeminar->id === (int) $seminar->id)
                                                );
                                            @endphp
                                            @if($canEditSeminar)
                                                <a href="{{ route('frontend.skripsi-seminars.edit', $seminar->id) }}" class="btn-modern btn-modern-outline">
                                                    <i class="fas fa-edit"></i>
                                                    {{ ($retrySeminar && (int) $retrySeminar->id === (int) $seminar->id) ? 'Unggah Ulang' : 'Edit' }}
                                                </a>
                                            @endif
                                        @endcan
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
                            <i class="fas fa-presentation fa-3x text-muted"></i>
                        </div>
                        <h4 class="text-muted mb-3">Belum Ada Pendaftaran Seminar</h4>
                        <p class="text-muted mb-4">Anda belum mendaftar untuk seminar proposal</p>
                        @can('skripsi_seminar_create')
                            <a href="{{ route('frontend.skripsi-seminars.create') }}" class="btn-modern btn-modern-primary btn-modern-lg">
                                <i class="fas fa-plus-circle"></i> Daftar Seminar Sekarang
                            </a>
                        @endcan
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
