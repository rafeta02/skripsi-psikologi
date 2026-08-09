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
                            'approved_no_revision', 'passed' => ['success', $applicationResultReview->resultLabel()],
                            'approved_minor_revision' => ['info', $applicationResultReview->resultLabel()],
                            'approved_major_revision' => ['warning', $applicationResultReview->resultLabel()],
                            'revision' => ['warning', $applicationResultReview->resultLabel()],
                            'failed' => ['danger', $applicationResultReview->resultLabel()],
                            default => ['secondary', $applicationResultReview->resultLabel()],
                        };
                    @endphp
                    <span class="badge badge-{{ $badge[0] }} badge-lg px-3 py-2">{{ $badge[1] }}</span>
                    @if($applicationResultReview->isEligibleOutcome())
                        {!! ' ' . $applicationResultReview->adminValidationStatusHtml() !!}
                    @endif
                    <p class="text-muted small mb-0 mt-3">
                        <i class="far fa-calendar-alt mr-1"></i>
                        Dikirim {{ $applicationResultReview->created_at->format('d M Y H:i') }}
                    </p>
                </div>
            </div>

            <div class="card-modern mb-4">
                <div class="card-modern-body">
                    <h4 class="font-weight-bold mb-4">
                        <i class="fas fa-folder-open text-primary mr-2"></i>Dokumen Laporan
                    </h4>

                    @if($applicationResultReview->reviewer_feedback_forms && count($applicationResultReview->reviewer_feedback_forms) > 0)
                        <div class="mb-4">
                            <h6 class="font-weight-semibold text-uppercase text-muted small mb-3">
                                Form Umpan Balik Reviewer
                            </h6>
                            <div class="list-group list-group-flush border rounded">
                                @foreach($applicationResultReview->reviewer_feedback_forms as $index => $document)
                                    <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap py-3">
                                        <div class="d-flex align-items-start mb-2 mb-md-0 mr-3">
                                            <div class="rounded d-flex align-items-center justify-content-center mr-3 flex-shrink-0"
                                                 style="width: 44px; height: 44px; background: rgba(220, 53, 69, 0.1);">
                                                <i class="fas fa-file-pdf fa-lg text-danger"></i>
                                            </div>
                                            <div>
                                                <div class="font-weight-semibold">Reviewer {{ $index + 1 }}</div>
                                                <small class="text-muted d-block">{{ $document->file_name }}</small>
                                                @if($document->size)
                                                    <small class="text-muted">{{ number_format($document->size / 1024 / 1024, 2) }} MB</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="btn-group btn-group-sm flex-wrap">
                                            <a href="{{ $document->getUrl() }}" target="_blank" rel="noopener" class="btn btn-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <button type="button" class="btn btn-info preview-doc" data-url="{{ $document->getUrl() }}">
                                                <i class="fas fa-expand"></i> Preview
                                            </button>
                                            <a href="{{ $document->getUrl() }}" download class="btn btn-success">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        @foreach([
                            ['field' => 'application_letter', 'label' => 'Surat Permohonan Review Proposal', 'icon' => 'fa-envelope-open-text', 'color' => '#17a2b8'],
                            ['field' => 'minutes_document', 'label' => 'Berita Acara Review Proposal', 'icon' => 'fa-file-signature', 'color' => '#6f42c1'],
                            ['field' => 'proposal_manuscript', 'label' => 'Naskah Proposal', 'icon' => 'fa-book', 'color' => '#dc3545'],
                            ['field' => 'research_ethics_form', 'label' => 'Lembar Etika Penelitian', 'icon' => 'fa-balance-scale', 'color' => '#28a745'],
                        ] as $doc)
                            @if($applicationResultReview->{$doc['field']})
                                @php $media = $applicationResultReview->{$doc['field']}; @endphp
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100 border shadow-sm">
                                        <div class="card-body text-center d-flex flex-column">
                                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mx-auto mb-3"
                                                 style="width: 56px; height: 56px; background: {{ $doc['color'] }}15;">
                                                <i class="fas {{ $doc['icon'] }} fa-lg" style="color: {{ $doc['color'] }};"></i>
                                            </div>
                                            <h6 class="font-weight-semibold mb-2">{{ $doc['label'] }}</h6>
                                            <small class="text-muted d-block mb-1 text-truncate px-2" title="{{ $media->file_name }}">
                                                {{ $media->file_name }}
                                            </small>
                                            @if($media->size)
                                                <small class="text-muted d-block mb-3">
                                                    {{ number_format($media->size / 1024 / 1024, 2) }} MB
                                                </small>
                                            @else
                                                <div class="mb-3"></div>
                                            @endif
                                            <div class="btn-group btn-group-sm flex-wrap justify-content-center mt-auto">
                                                <a href="{{ $media->getUrl() }}" target="_blank" rel="noopener" class="btn btn-primary">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                <button type="button" class="btn btn-info preview-doc" data-url="{{ $media->getUrl() }}">
                                                    <i class="fas fa-expand"></i> Preview
                                                </button>
                                                <a href="{{ $media->getUrl() }}" download class="btn btn-success">
                                                    <i class="fas fa-download"></i> Download
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    @php
                        $hasAnyDoc = ($applicationResultReview->reviewer_feedback_forms && count($applicationResultReview->reviewer_feedback_forms) > 0)
                            || $applicationResultReview->application_letter
                            || $applicationResultReview->minutes_document
                            || $applicationResultReview->proposal_manuscript
                            || $applicationResultReview->research_ethics_form;
                    @endphp
                    @if(!$hasAnyDoc)
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-file-alt fa-2x mb-2 d-block"></i>
                            Belum ada dokumen terlampir.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card-modern">
                <div class="card-modern-body">
                    <h5 class="font-weight-bold mb-3">Aksi</h5>
                    @if($applicationResultReview->isEligibleOutcome())
                        @if($canAccessDefense['allowed'] ?? false)
                            <div class="alert alert-success">
                                Laporan hasil review sudah divalidasi admin. Anda dapat mendaftar sidang skripsi.
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

<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-file-pdf mr-2"></i>
                    Preview Dokumen
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" style="height: 80vh;">
                <iframe id="pdfViewer" style="width: 100%; height: 100%; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.preview-doc').on('click', function() {
        const url = $(this).data('url');
        $('#pdfViewer').attr('src', url);
        $('#previewModal').modal('show');
    });

    $('#previewModal').on('hidden.bs.modal', function() {
        $('#pdfViewer').attr('src', '');
    });
});
</script>
@endpush
