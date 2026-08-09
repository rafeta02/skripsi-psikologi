@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, var(--primary-500) 0%, var(--secondary-500) 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-1 text-white font-weight-bold">
                                <i class="fas fa-chalkboard-teacher mr-2"></i> Review Kelayakan Proposal (MBKM)
                            </h2>
                            <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                                Lanjutan dari pendaftaran MBKM kelompok — 1 form per kelompok (diisi ketua)
                            </p>
                        </div>
                        <div class="col-md-4 text-right">
                            @if(!empty($canCreate))
                                <a href="{{ route('frontend.mbkm-seminars.create') }}" class="btn btn-light btn-lg shadow">
                                    <i class="fas fa-plus-circle"></i> Daftar Sekarang
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

    @if(!empty($isGroupFollower))
        <div class="alert alert-info">
            <i class="fas fa-users mr-1"></i>
            Anda anggota kelompok. Form Review Kelayakan Proposal (MBKM) diisi oleh <strong>ketua</strong>.
            Status progress Anda mengikuti pengajuan kelompok (lanjutan dari MbkmRegistration).
            @if($registrationApp)
                <div class="mt-2">
                    <a href="{{ route('frontend.mbkm.show', $registrationApp->id) }}" class="btn btn-sm btn-outline-primary">
                        Lihat Pendaftaran MBKM Kelompok
                    </a>
                </div>
            @endif
        </div>
    @elseif($registration)
        <div class="card-modern mb-4">
            <div class="card-modern-body py-3">
                <h6 class="font-weight-bold mb-2">
                    <i class="fas fa-link text-primary mr-1"></i> Lanjutan MbkmRegistration
                </h6>
                <div class="row small">
                    <div class="col-md-4 mb-1">
                        <span class="text-muted">Judul kegiatan:</span>
                        <strong>{{ $registration->title_mbkm ?? '-' }}</strong>
                    </div>
                    <div class="col-md-4 mb-1">
                        <span class="text-muted">Research group:</span>
                        <strong>{{ $registration->research_group->name ?? '-' }}</strong>
                    </div>
                    <div class="col-md-4 mb-1">
                        <span class="text-muted">Anggota:</span>
                        <strong>{{ $registration->groupMembers->count() }} orang</strong>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(empty($canCreate) && empty($isGroupFollower) && $mbkmSeminars->count() === 0 && !empty($access['message']))
        <div class="alert alert-warning">
            <i class="fas fa-info-circle mr-1"></i> {{ $access['message'] }}
            @if($registrationApp)
                <div class="mt-2">
                    <a href="{{ route('frontend.mbkm.show', $registrationApp->id) }}" class="btn btn-sm btn-outline-secondary">
                        Kembali ke Pendaftaran MBKM
                    </a>
                </div>
            @endif
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            @if($mbkmSeminars->count() > 0)
                @foreach($mbkmSeminars as $seminar)
                    <div class="card-modern mb-4">
                        <div class="card-modern-body">
                            <div class="row align-items-start">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-start mb-3">
                                        <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #3498db, #2ecc71); border-radius: var(--radius-base); display: flex; align-items: center; justify-content: center; margin-right: var(--spacing-3);">
                                            <i class="fas fa-chalkboard-teacher" style="font-size: 20px; color: white;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h4 class="mb-1 font-weight-bold">Review Kelayakan Proposal (MBKM)</h4>
                                            <p class="mb-2 text-muted">
                                                <i class="fas fa-book mr-2"></i>{{ $seminar->title ?? 'Judul MBKM' }}
                                            </p>
                                            <div class="d-flex flex-wrap gap-2">
                                                @if(!empty($isGroupFollower))
                                                    <span class="badge-modern badge-modern-outline">
                                                        <i class="fas fa-users"></i> Form ketua kelompok
                                                    </span>
                                                @endif
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
                                                            <i class="fas fa-times-circle"></i> Ditolak
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
                                                        <i class="fas fa-file-pdf"></i> Plagiarism
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-4 text-right">
                                    <div class="d-flex flex-column gap-2">
                                        <a href="{{ route('frontend.mbkm-seminars.show', $seminar->id) }}" class="btn-modern btn-modern-primary">
                                            <i class="fas fa-eye"></i> Lihat Detail
                                        </a>

                                        @if(empty($isGroupFollower) && $seminar->application && in_array($seminar->application->status, ['revision', 'submitted'], true))
                                            <a href="{{ route('frontend.mbkm-seminars.edit', $seminar->id) }}" class="btn-modern btn-modern-outline">
                                                <i class="fas fa-edit"></i> Edit
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
                            <i class="fas fa-chalkboard-teacher fa-3x text-muted"></i>
                        </div>
                        @if(!empty($isGroupFollower))
                            <h4 class="text-muted mb-3">Belum Ada Pengajuan dari Ketua</h4>
                            <p class="text-muted mb-4">Menunggu ketua kelompok mendaftarkan Review Kelayakan Proposal (MBKM). Progress Anda akan ikut otomatis.</p>
                        @else
                            <h4 class="text-muted mb-3">Belum Ada Pendaftaran Review Kelayakan Proposal (MBKM)</h4>
                            <p class="text-muted mb-4">Satu form untuk seluruh kelompok. Pastikan pendaftaran MBKM sudah disetujui dosen pembimbing.</p>
                            @if(!empty($canCreate))
                                <a href="{{ route('frontend.mbkm-seminars.create') }}" class="btn-modern btn-modern-primary btn-modern-lg">
                                    <i class="fas fa-plus-circle"></i> Daftar Sekarang
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
