@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, #2980b9 0%, #1a5276 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <h2 class="mb-1 text-white font-weight-bold">
                        <i class="fas fa-clipboard-check mr-2"></i> Detail Hasil Review Proposal
                    </h2>
                    <p class="mb-0" style="color: rgba(255,255,255,0.9);">Skripsi Reguler</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card-modern mb-4">
                <div class="card-modern-body">
                    <h4 class="font-weight-bold mb-3">Hasil Review</h4>
                    @php
                        $badge = match($applicationResultReview->result) {
                            'passed' => ['success', 'Lulus'],
                            'revision' => ['warning', 'Revisi'],
                            'failed' => ['danger', 'Tidak Lulus'],
                            default => ['secondary', $applicationResultReview->result],
                        };
                    @endphp
                    <span class="badge badge-{{ $badge[0] }} badge-lg">{{ $badge[1] }}</span>
                    @if($applicationResultReview->result === 'passed')
                        @if($applicationResultReview->isValidatedByAdmin())
                            <span class="badge badge-success ml-1">Divalidasi Admin</span>
                        @else
                            <span class="badge badge-warning ml-1">Menunggu Validasi Admin</span>
                        @endif
                    @endif

                    @if($applicationResultReview->revision_deadline)
                        <p class="mt-3 mb-0"><strong>Tenggat Revisi:</strong> {{ $applicationResultReview->revision_deadline }}</p>
                    @endif
                    @if($applicationResultReview->note)
                        <p class="mt-3 mb-0 text-muted">{{ $applicationResultReview->note }}</p>
                    @endif
                </div>
            </div>

            @if($applicationResultReview->form_document && count($applicationResultReview->form_document) > 0)
                <div class="card-modern mb-4">
                    <div class="card-modern-body">
                        <h4 class="font-weight-bold mb-3">Form Penilaian Reviewer</h4>
                        @foreach($applicationResultReview->form_document as $document)
                            <a href="{{ $document->getUrl() }}" target="_blank" class="btn btn-outline-primary btn-sm mr-2 mb-2">
                                <i class="fas fa-file-pdf"></i> {{ $document->file_name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($applicationResultReview->latest_script)
                <div class="card-modern mb-4">
                    <div class="card-modern-body">
                        <h4 class="font-weight-bold mb-3">Naskah Proposal Terbaru</h4>
                        <a href="{{ $applicationResultReview->latest_script->getUrl() }}" target="_blank" class="btn btn-outline-primary">
                            <i class="fas fa-file-pdf"></i> {{ $applicationResultReview->latest_script->file_name }}
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card-modern">
                <div class="card-modern-body">
                    <h5 class="font-weight-bold mb-3">Aksi</h5>
                    @if($applicationResultReview->result === 'passed')
                        @if($canAccessDefense['allowed'] ?? false)
                            <div class="alert alert-success">
                                Laporan hasil lulus sudah divalidasi admin. Anda dapat mendaftar sidang skripsi.
                            </div>
                            @can('skripsi_defense_create')
                                <a href="{{ route('frontend.skripsi-defenses.create') }}" class="btn btn-success btn-block">
                                    <i class="fas fa-graduation-cap"></i> Daftar Sidang Skripsi
                                </a>
                            @endcan
                        @else
                            <div class="alert alert-warning mb-0">
                                {{ $canAccessDefense['message'] ?? 'Menunggu validasi admin.' }}
                            </div>
                        @endif
                    @elseif($applicationResultReview->result === 'failed')
                        <div class="alert alert-danger mb-3">Review proposal tidak lulus. Perbaiki pendaftaran reviewer.</div>
                        @can('skripsi_seminar_edit')
                            @php
                                $retrySeminarId = app(\App\Services\FormAccessService::class)
                                    ->getSkripsiSeminarForFailedRetry(auth()->user()->mahasiswa_id)?->id;
                            @endphp
                            @if($retrySeminarId)
                                <a href="{{ route('frontend.skripsi-seminars.edit', $retrySeminarId) }}" class="btn btn-danger btn-block">
                                    <i class="fas fa-edit"></i> Perbaiki Pendaftaran Reviewer
                                </a>
                            @endif
                        @endcan
                    @endif
                    <a href="{{ route('frontend.application-result-reviews.index') }}" class="btn btn-secondary btn-block mt-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
