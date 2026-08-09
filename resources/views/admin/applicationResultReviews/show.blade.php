@extends('layouts.admin')
@section('content')

@php
    $mahasiswa = $applicationResultReview->application?->mahasiswa;
    $reviewers = collect([
        $applicationResultReview->reviewer1Assignment?->lecturer,
        $applicationResultReview->reviewer2Assignment?->lecturer,
    ])->filter()->values();

    $documentSections = [
        [
            'label' => 'Form Umpan Balik Reviewer 1',
            'items' => collect($applicationResultReview->reviewer_feedback_forms ?? [])->take(1),
            'type' => 'pdf',
            'icon' => 'fa-file-pdf text-danger',
        ],
        [
            'label' => 'Form Umpan Balik Reviewer 2',
            'items' => collect($applicationResultReview->reviewer_feedback_forms ?? [])->slice(1, 1),
            'type' => 'pdf',
            'icon' => 'fa-file-pdf text-danger',
        ],
        [
            'label' => 'Surat Permohonan Review Proposal',
            'items' => $applicationResultReview->application_letter
                ? collect([$applicationResultReview->application_letter])
                : collect(),
            'type' => 'pdf',
            'icon' => 'fa-file-pdf text-danger',
        ],
        [
            'label' => 'Berita Acara Review Proposal',
            'items' => $applicationResultReview->minutes_document
                ? collect([$applicationResultReview->minutes_document])
                : collect(),
            'type' => 'pdf',
            'icon' => 'fa-file-pdf text-danger',
        ],
        [
            'label' => 'Naskah Proposal',
            'items' => $applicationResultReview->proposal_manuscript
                ? collect([$applicationResultReview->proposal_manuscript])
                : collect(),
            'type' => 'pdf',
            'icon' => 'fa-file-pdf text-danger',
        ],
        [
            'label' => 'Lembar Etika Penelitian',
            'items' => $applicationResultReview->research_ethics_form
                ? collect([$applicationResultReview->research_ethics_form])
                : collect(),
            'type' => 'pdf',
            'icon' => 'fa-file-pdf text-danger',
        ],
    ];
@endphp

<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <a href="{{ route('admin.application-result-reviews.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user-graduate mr-2"></i>Informasi Mahasiswa</h5>
                </div>
                <div class="card-body">
                    @if($mahasiswa)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold text-muted small mb-1">Nama Mahasiswa</label>
                                    <p class="form-control-plaintext mb-0 h6">{{ $mahasiswa->nama }}</p>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="font-weight-bold text-muted small mb-1">NIM</label>
                                    <p class="form-control-plaintext mb-0">{{ $mahasiswa->nim }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold text-muted small mb-1">Program Studi</label>
                                    <p class="form-control-plaintext mb-0">{{ $mahasiswa->prodi->name ?? '-' }}</p>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="font-weight-bold text-muted small mb-1">Jenjang</label>
                                    <p class="form-control-plaintext mb-0">{{ $mahasiswa->jenjang->name ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-muted mb-0">Data mahasiswa tidak tersedia</p>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-chalkboard-teacher mr-2"></i>Dosen Terkait</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100 bg-light">
                                <label class="font-weight-bold text-muted small mb-2 d-block">Dosen Pembimbing</label>
                                <p class="mb-0 h6">{{ $supervisor?->nama ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3 mt-md-0">
                            <div class="border rounded p-3 h-100 bg-light">
                                <label class="font-weight-bold text-muted small mb-2 d-block">Dosen Reviewer</label>
                                @if($reviewers->isNotEmpty())
                                    @foreach($reviewers as $index => $reviewer)
                                        <p class="mb-1"><span class="badge badge-secondary mr-1">R{{ $index + 1 }}</span> {{ $reviewer->nama }}</p>
                                    @endforeach
                                @else
                                    <p class="mb-0 text-muted">-</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-file-check mr-2"></i>Detail Hasil Review</h5>
                </div>
                <div class="card-body">
                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-muted small mb-2 d-block">Hasil Review</label>
                        @if($applicationResultReview->result)
                            @php
                                $resultClass = match($applicationResultReview->result) {
                                    'approved_no_revision', 'passed' => 'success',
                                    'approved_minor_revision' => 'info',
                                    'approved_major_revision' => 'warning',
                                    'revision' => 'warning',
                                    'failed' => 'danger',
                                    default => 'secondary',
                                };
                            @endphp
                            <span class="badge badge-{{ $resultClass }} badge-lg px-3 py-2">{{ $applicationResultReview->resultLabel() }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-file-pdf mr-2"></i>Dokumen Laporan</h5>
                </div>
                <div class="card-body p-0">
                    @foreach($documentSections as $section)
                        <div class="border-bottom px-4 py-3">
                            <label class="font-weight-bold mb-2 d-block">{{ $section['label'] }}</label>
                            @if($section['items']->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($section['items'] as $media)
                                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center flex-wrap">
                                            <div class="mr-3 mb-2 mb-md-0">
                                                <i class="fas {{ $section['icon'] }} mr-2"></i>
                                                <span class="text-muted small">{{ $media->file_name }}</span>
                                            </div>
                                            <div class="btn-group flex-shrink-0">
                                                <a href="{{ $media->getUrl() }}" target="_blank" class="btn btn-sm btn-primary" title="View">
                                                    <i class="fas fa-eye mr-1"></i> View
                                                </a>
                                                <button type="button" class="btn btn-sm btn-info preview-doc"
                                                        data-url="{{ $media->getUrl() }}"
                                                        data-type="{{ $section['type'] }}"
                                                        data-name="{{ $section['label'] }}"
                                                        title="Preview">
                                                    <i class="fas fa-expand mr-1"></i> Preview
                                                </button>
                                                <a href="{{ $media->getUrl() }}" download class="btn btn-sm btn-success" title="Download">
                                                    <i class="fas fa-download mr-1"></i> Download
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted mb-0">Tidak ada dokumen</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-history mr-2"></i>Riwayat Aksi</h5>
                </div>
                <div class="card-body">
                    @if($applicationResultReview->application && $applicationResultReview->application->actions && count($applicationResultReview->application->actions) > 0)
                        <div class="timeline">
                            @foreach($applicationResultReview->application->actions->sortByDesc('created_at') as $action)
                                <div class="timeline-item mb-3 pb-3 border-bottom">
                                    <div class="d-flex">
                                        <div class="mr-3">
                                            @php
                                                $iconMap = [
                                                    'result_review_approved' => 'check-circle text-success',
                                                    'result_review_rejected' => 'times-circle text-danger',
                                                ];
                                                $icon = $iconMap[$action->action_type] ?? 'info-circle text-info';
                                            @endphp
                                            <i class="fas fa-{{ $icon }} fa-2x"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="mb-1">
                                                <strong>{{ ucfirst(str_replace('_', ' ', $action->action_type)) }}</strong>
                                            </p>
                                            <p class="text-muted mb-1">{{ $action->notes }}</p>
                                            <small class="text-muted">
                                                <i class="far fa-clock mr-1"></i>
                                                {{ $action->created_at->format('d M Y H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">Belum ada riwayat aksi</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Status Validasi Laporan</h5>
                </div>
                <div class="card-body text-center">
                    <h3 class="mb-3">
                        {!! $applicationResultReview->adminValidationStatusHtml() !!}
                    </h3>
                    @if($applicationResultReview->application)
                        <p class="text-muted mb-0">Stage: <strong>{{ ucfirst($applicationResultReview->application->stage) }}</strong></p>
                    @endif
                </div>
            </div>

            @if($applicationResultReview->isEligibleOutcome() && $applicationResultReview->isValidatedByAdmin())
                <div class="card shadow-sm mb-4 border-success">
                    <div class="card-body text-center">
                        <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                        <p class="mb-0 text-success font-weight-bold">Sudah divalidasi — mahasiswa dapat melanjutkan ke tahap sidang.</p>
                    </div>
                </div>
            @endif

            @if($applicationResultReview->application && $applicationResultReview->isEligibleOutcome() && ! $applicationResultReview->isValidatedByAdmin() && ! $applicationResultReview->isRejectedByAdmin())
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-tasks mr-2"></i>Aksi</h5>
                    </div>
                    <div class="card-body">
                        <button type="button" class="btn btn-success btn-block mb-2" data-toggle="modal" data-target="#approveModal">
                            <i class="fas fa-check mr-1"></i> Setujui Hasil Review
                        </button>
                        <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#rejectModal">
                            <i class="fas fa-times mr-1"></i> Tolak Hasil Review
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-file-alt mr-2"></i>
                    <span id="previewModalTitle">Preview Dokumen</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" style="height: 80vh;">
                <iframe id="pdfViewer" style="width: 100%; height: 100%; border: none; display: none;"></iframe>
                <div id="imageViewerWrap" class="h-100 d-none align-items-center justify-content-center overflow-auto">
                    <img id="imageViewer" src="" alt="Preview" class="img-fluid" style="max-height: 100%;">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle mr-2"></i>
                    Setujui Hasil Review
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="approveForm">
                <div class="modal-body">
                    <div class="alert alert-success">
                        <i class="fas fa-info-circle mr-2"></i>
                        Hasil review akan disetujui dan status aplikasi akan diubah menjadi "Disetujui".
                    </div>
                    <div class="form-group">
                        <label for="approve_notes">Catatan (Opsional)</label>
                        <textarea class="form-control" id="approve_notes" name="notes" rows="3"
                                  placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check mr-1"></i> Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-times-circle mr-2"></i>
                    Tolak Hasil Review
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="rejectForm">
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Pastikan Anda memberikan alasan yang jelas untuk penolakan.
                    </div>
                    <div class="form-group">
                        <label for="reject_reason">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="reject_reason" name="reason" rows="4"
                                  placeholder="Jelaskan alasan penolakan..." required minlength="10"></textarea>
                        <small class="form-text text-muted">Minimal 10 karakter</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times mr-1"></i> Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('.preview-doc').on('click', function() {
        const url = $(this).data('url');
        const type = $(this).data('type') || 'pdf';
        const name = $(this).data('name') || 'Preview Dokumen';

        $('#previewModalTitle').text(name);

        if (type === 'image') {
            $('#pdfViewer').hide().attr('src', '');
            $('#imageViewer').attr('src', url);
            $('#imageViewerWrap').removeClass('d-none').addClass('d-flex');
        } else {
            $('#imageViewerWrap').removeClass('d-flex').addClass('d-none');
            $('#imageViewer').attr('src', '');
            $('#pdfViewer').show().attr('src', url);
        }

        $('#previewModal').modal('show');
    });

    $('#previewModal').on('hidden.bs.modal', function() {
        $('#pdfViewer').attr('src', '').hide();
        $('#imageViewer').attr('src', '');
        $('#imageViewerWrap').removeClass('d-flex').addClass('d-none');
    });

    $('#approveForm').on('submit', function(e) {
        e.preventDefault();

        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...');

        $.ajax({
            url: '{{ route("admin.application-result-reviews.approve", $applicationResultReview->id) }}',
            method: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#approveModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(function() {
                    window.location.reload();
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: xhr.responseJSON?.message || 'Terjadi kesalahan'
                });
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('<i class="fas fa-check mr-1"></i> Setujui');
            }
        });
    });

    $('#rejectForm').on('submit', function(e) {
        e.preventDefault();

        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...');

        $.ajax({
            url: '{{ route("admin.application-result-reviews.reject", $applicationResultReview->id) }}',
            method: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#rejectModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Hasil Review Ditolak',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(function() {
                    window.location.reload();
                });
            },
            error: function(xhr) {
                let errorMessage = 'Terjadi kesalahan';
                if (xhr.responseJSON?.errors?.reason) {
                    errorMessage = xhr.responseJSON.errors.reason[0];
                } else if (xhr.responseJSON?.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: errorMessage
                });
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('<i class="fas fa-times mr-1"></i> Tolak');
            }
        });
    });

    $('.modal').on('hidden.bs.modal', function() {
        $(this).find('form')[0].reset();
    });
});
</script>
@endsection
