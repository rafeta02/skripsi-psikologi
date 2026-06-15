@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <h2 class="mb-1 text-white font-weight-bold">
                        <i class="fas fa-award mr-2"></i> Detail Hasil Sidang Final
                    </h2>
                    <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                        Informasi lengkap hasil sidang skripsi/MBKM
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Content -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card-modern mb-4">
                <div class="card-modern-body">
                    <h4 class="font-weight-bold mb-3">Hasil Sidang</h4>
                    
                    <div class="mb-4">
                        <label class="text-muted mb-1">Status Kelulusan</label>
                        <h5 class="font-weight-semibold">
                            @if($applicationResultDefense->result === 'passed')
                                <span class="badge badge-success badge-lg">
                                    <i class="fas fa-check-circle"></i> Lulus
                                </span>
                            @elseif($applicationResultDefense->result === 'revision')
                                <span class="badge badge-warning badge-lg">
                                    <i class="fas fa-edit"></i> Revisi
                                </span>
                            @elseif($applicationResultDefense->result === 'failed')
                                <span class="badge badge-danger badge-lg">
                                    <i class="fas fa-times-circle"></i> Tidak Lulus
                                </span>
                            @else
                                <span class="badge badge-secondary badge-lg">{{ $applicationResultDefense->result }}</span>
                            @endif
                        </h5>
                    </div>

                    @if($applicationResultDefense->result === 'revision' && $applicationResultDefense->revision_deadline)
                        <div class="alert alert-info mb-4">
                            <h6 class="font-weight-bold mb-2"><i class="fas fa-info-circle"></i> Revisi Diperlukan</h6>
                            <p class="mb-0">
                                <strong>Batas waktu revisi:</strong>
                                {{ \Carbon\Carbon::parse($applicationResultDefense->revision_deadline)->translatedFormat('l, d F Y') }}
                            </p>
                        </div>
                    @endif

                    @if($applicationResultDefense->final_title)
                        <div class="mb-4">
                            <label class="text-muted mb-1">Judul Akhir Skripsi</label>
                            <p class="font-weight-semibold mb-0">{{ $applicationResultDefense->final_title }}</p>
                        </div>
                    @endif

                    @if($applicationResultDefense->note)
                        <div class="mb-4">
                            <label class="text-muted mb-1">Catatan Penguji</label>
                            <div class="alert alert-light mb-0">{{ $applicationResultDefense->note }}</div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">Tanggal Dibuat</label>
                            <p class="font-weight-semibold">
                                {{ $applicationResultDefense->created_at->format('d M Y H:i') }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">Terakhir Diupdate</label>
                            <p class="font-weight-semibold">
                                {{ $applicationResultDefense->updated_at->format('d M Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents (similar to result seminar) -->
            <div class="card-modern">
                <div class="card-modern-body">
                    <h4 class="font-weight-bold mb-3">Dokumen</h4>
                    @include('partials.defense-result-documents', ['record' => $applicationResultDefense])
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Application Info -->
            @if($applicationResultDefense->application)
                <div class="card-modern mb-4">
                    <div class="card-modern-body">
                        <h5 class="font-weight-bold mb-3">Informasi Aplikasi</h5>
                        
                        <div class="mb-3">
                            <label class="text-muted mb-1">Tipe</label>
                            <p class="font-weight-semibold text-uppercase">
                                <span class="badge badge-primary">{{ $applicationResultDefense->application->type }}</span>
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted mb-1">Tahap</label>
                            <p class="font-weight-semibold text-capitalize">
                                <span class="badge badge-info">{{ $applicationResultDefense->application->stage }}</span>
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted mb-1">Status Aplikasi</label>
                            <p class="font-weight-semibold text-capitalize">
                                <span class="badge badge-secondary">{{ $applicationResultDefense->application->status }}</span>
                            </p>
                        </div>

                        <div class="mb-0">
                            <label class="text-muted mb-1">Validasi Admin</label>
                            <div>{!! $applicationResultDefense->adminValidationStatusHtml() !!}</div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->
            @if(in_array($applicationResultDefense->result, ['passed', 'revision']) && $applicationResultDefense->isFinalizedByAdmin())
                <div class="card-modern mb-4">
                    <div class="card-modern-body">
                        @include('partials.mahasiswa-graduation-documents', [
                            'applicationId' => $applicationResultDefense->application_id,
                            'finalScore' => $applicationResultDefense->final_score,
                            'finalGradeLetter' => $applicationResultDefense->final_grade_letter,
                        ])
                    </div>
                </div>
            @elseif($applicationResultDefense->result === 'passed')
                <div class="card-modern">
                    <div class="card-modern-body">
                        @if($applicationResultDefense->isValidatedByAdmin())
                            <div class="alert alert-success mb-0">
                                <i class="fas fa-check-circle"></i> <strong>Laporan divalidasi admin.</strong>
                                <p class="mb-0 mt-2 small">Dosen pembimbing dan penguji sedang mengisi penilaian sidang.</p>
                            </div>
                        @else
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-clock"></i> <strong>Menunggu validasi admin.</strong>
                                <p class="mb-0 mt-2 small">Setelah disetujui, penilaian akan dibuka untuk dosen pembimbing dan penguji.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @elseif($applicationResultDefense->result === 'revision')
                <div class="card-modern">
                    <div class="card-modern-body">
                        @if($applicationResultDefense->isValidatedByAdmin() && !$applicationResultDefense->isFinalizedByAdmin())
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-edit"></i> <strong>Revisi diperlukan.</strong>
                                <p class="mb-0 mt-2 small">Selesaikan revisi sesuai batas waktu. Penilaian dosen sedang berjalan.</p>
                            </div>
                        @else
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-edit"></i> <strong>Revisi diperlukan.</strong>
                                <p class="mb-0 mt-2 small">Selesaikan revisi sesuai batas waktu yang ditetapkan.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @elseif($applicationResultDefense->result === 'failed')
                <div class="card-modern">
                    <div class="card-modern-body">
                        @if($applicationResultDefense->isValidatedByAdmin())
                            <div class="alert alert-info mb-3">
                                <i class="fas fa-redo"></i> <strong>Sidang tidak lulus — validasi admin selesai.</strong>
                                <p class="mb-0 mt-2 small">Anda dapat mendaftar ulang sidang skripsi melalui menu Pendaftaran Sidang.</p>
                            </div>
                            <a href="{{ route('frontend.skripsi-defenses.create') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-file-alt mr-1"></i> Daftar Ulang Sidang Skripsi
                            </a>
                        @else
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-clock"></i> <strong>Menunggu validasi admin.</strong>
                                <p class="mb-0 mt-2 small">Setelah divalidasi, Anda dapat mendaftar ulang sidang skripsi.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Back Button -->
    <div class="row mt-4">
        <div class="col-lg-12">
            <a href="{{ route('frontend.application-result-defenses.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
