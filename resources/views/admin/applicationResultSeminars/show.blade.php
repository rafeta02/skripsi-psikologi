@extends('layouts.admin')
@section('content')

<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <a href="{{ route('admin.application-result-seminars.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Student Information Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user-graduate mr-2"></i>Informasi Mahasiswa</h5>
                </div>
                <div class="card-body">
                    @if($applicationResultSeminar->application && $applicationResultSeminar->application->mahasiswa)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Nama Mahasiswa:</label>
                                    <p class="form-control-plaintext">{{ $applicationResultSeminar->application->mahasiswa->nama }}</p>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">NIM:</label>
                                    <p class="form-control-plaintext">{{ $applicationResultSeminar->application->mahasiswa->nim }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Program Studi:</label>
                                    <p class="form-control-plaintext">
                                        {{ $applicationResultSeminar->application->mahasiswa->prodi->name ?? '-' }}
                                    </p>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">Jenjang:</label>
                                    <p class="form-control-plaintext">
                                        {{ $applicationResultSeminar->application->mahasiswa->jenjang->name ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-muted">Data mahasiswa tidak tersedia</p>
                    @endif
                </div>
            </div>

            @include('admin.partials.mbkm-group-context', [
                'mbkmGroupRegistration' => $mbkmGroupRegistration ?? null,
                'mode' => 'compact',
            ])

            @if(!empty($mbkmGroupRegistration))
            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-1"></i>
                Hasil seminar ini berlaku untuk <strong>seluruh anggota kelompok</strong>. Setelah lulus &amp; divalidasi admin, setiap anggota mendaftar sidang secara individu.
            </div>
            @endif

            <!-- Result Seminar Details Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-file-check mr-2"></i>Detail Hasil Seminar</h5>
                </div>
    <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Hasil Seminar:</label>
                                <p class="form-control-plaintext">
                                    @if($applicationResultSeminar->result)
                                        @php
                                            $resultLabels = [
                                                'minor' => '<span class="badge badge-success badge-lg">Layak Dilaksanakan dengan perbaikan minor</span>',
                                                'mayor' => '<span class="badge badge-info badge-lg">Layak Dilaksanakan dengan perbaikan mayor</span>',
                                                'passed' => '<span class="badge badge-success badge-lg">Lulus</span>',
                                                'revision' => '<span class="badge badge-warning badge-lg">Revisi</span>',
                                                'failed' => '<span class="badge badge-danger badge-lg">Tidak Lulus</span>',
                                            ];
                                        @endphp
                                        {!! $resultLabels[$applicationResultSeminar->result] ?? e($applicationResultSeminar->resultLabel()) !!}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
        <div class="form-group">
                                <label class="font-weight-bold">Batas Waktu Revisi:</label>
                                <p class="form-control-plaintext">
                                    {{ $applicationResultSeminar->revision_deadline ?? 'Tidak ada' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($applicationResultSeminar->meeting_recording_link)
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label class="font-weight-bold">Tautan Record Meeting:</label>
                                    <p class="form-control-plaintext mb-0">
                                        <a href="{{ $applicationResultSeminar->meeting_recording_link }}" target="_blank" class="btn btn-sm btn-info">
                                            <i class="fas fa-video mr-1"></i> Buka Rekaman
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Documents Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-file-pdf mr-2"></i>Dokumen</h5>
                </div>
                <div class="card-body">
                    @php
                        $documentSections = [
                            [
                                'label' => 'Form Review Kelayakan Proposal MBKM Riset',
                                'items' => collect($applicationResultSeminar->form_document ?? []),
                                'type' => 'pdf',
                                'icon' => 'fa-file-pdf text-danger',
                            ],
                            [
                                'label' => 'Presensi Peserta',
                                'items' => $applicationResultSeminar->attendance_document
                                    ? collect([$applicationResultSeminar->attendance_document])
                                    : collect(),
                                'type' => 'pdf',
                                'icon' => 'fa-file-pdf text-danger',
                            ],
                            [
                                'label' => 'KRS Semester Terbaru',
                                'items' => $applicationResultSeminar->krs_latest
                                    ? collect([$applicationResultSeminar->krs_latest])
                                    : collect(),
                                'type' => 'pdf',
                                'icon' => 'fa-file-pdf text-danger',
                            ],
                            [
                                'label' => 'Dokumentasi Seminar (Screenshot atau Foto)',
                                'items' => collect($applicationResultSeminar->documentation ?? []),
                                'type' => 'image',
                                'icon' => 'fa-image text-info',
                            ],
                            [
                                'label' => 'Naskah Proposal MBKM (KKN dan Skripsi Hasil Revisi)',
                                'items' => $applicationResultSeminar->latest_script
                                    ? collect([$applicationResultSeminar->latest_script])
                                    : collect(),
                                'type' => 'pdf',
                                'icon' => 'fa-file-pdf text-danger',
                            ],
                        ];

                        if ($applicationResultSeminar->report_document && count($applicationResultSeminar->report_document) > 0) {
                            $documentSections[] = [
                                'label' => 'Berita Acara Seminar (data lama)',
                                'items' => collect($applicationResultSeminar->report_document),
                                'type' => 'pdf',
                                'icon' => 'fa-file-pdf text-muted',
                            ];
                        }
                    @endphp

                    @foreach($documentSections as $section)
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">{{ $section['label'] }}:</label>
                            @if($section['items']->count() > 0)
                                <div class="list-group">
                                    @foreach($section['items'] as $media)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div class="mr-3">
                                                <i class="fas {{ $section['icon'] }} mr-2"></i>
                                                <small class="text-muted">{{ $media->file_name }}</small>
                                            </div>
                                            <div class="btn-group flex-shrink-0">
                                                <a href="{{ $media->getUrl() }}" target="_blank" class="btn btn-sm btn-primary" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-info preview-doc"
                                                        data-url="{{ $media->getUrl() }}"
                                                        data-type="{{ $section['type'] }}"
                                                        data-name="{{ $media->file_name }}"
                                                        title="Preview">
                                                    <i class="fas fa-expand"></i>
                                                </button>
                                                <a href="{{ $media->getUrl() }}" download class="btn btn-sm btn-success" title="Download">
                                                    <i class="fas fa-download"></i>
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

            <!-- Action History Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-history mr-2"></i>Riwayat Aksi</h5>
                </div>
                <div class="card-body">
                    @if($applicationResultSeminar->application && $applicationResultSeminar->application->actions && count($applicationResultSeminar->application->actions) > 0)
                        <div class="timeline">
                            @foreach($applicationResultSeminar->application->actions->sortByDesc('created_at') as $action)
                                <div class="timeline-item mb-3 pb-3 border-bottom">
                                    <div class="d-flex">
                                        <div class="mr-3">
                                            @php
                                                $iconMap = [
                                                    'result_seminar_approved' => 'check-circle text-success',
                                                    'result_seminar_rejected' => 'times-circle text-danger',
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
                        <p class="text-muted">Belum ada riwayat aksi</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Status Validasi Laporan -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Status Validasi Laporan</h5>
                </div>
                <div class="card-body text-center">
                    <h3 class="mb-3">
                        {!! $applicationResultSeminar->adminValidationStatusHtml() !!}
                    </h3>
                    @if($applicationResultSeminar->application)
                        <p class="text-muted mb-0">Stage: <strong>{{ ucfirst($applicationResultSeminar->application->stage) }}</strong></p>
                    @endif
                </div>
            </div>

            @php
                $adminValidated = $applicationResultSeminar->isValidatedByAdmin();
            @endphp

            @if($applicationResultSeminar->isEligibleOutcome() && $adminValidated)
                <div class="card shadow-sm mb-4 border-success">
                    <div class="card-body text-center">
                        <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                        <p class="mb-0 text-success font-weight-bold">Sudah divalidasi — mahasiswa dapat mendaftar sidang skripsi.</p>
                    </div>
                </div>
            @endif

            <!-- Actions Card -->
            @if($applicationResultSeminar->application && $applicationResultSeminar->application->status !== 'rejected' && !$adminValidated)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-tasks mr-2"></i>Aksi</h5>
                    </div>
                    <div class="card-body">
                        @if($applicationResultSeminar->isEligibleOutcome())
                            <button type="button" class="btn btn-success btn-block mb-2" data-toggle="modal" data-target="#approveModal">
                                <i class="fas fa-check mr-1"></i> Validasi Hasil Layak Dilaksanakan
                            </button>
                        @endif
                        @if(!$applicationResultSeminar->isEligibleOutcome() || !$adminValidated)
                            <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#rejectModal">
                                <i class="fas fa-times mr-1"></i> Tolak Laporan
                            </button>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Document Preview Modal -->
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

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle mr-2"></i>
                    Setujui Hasil Seminar
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="approveForm">
                <div class="modal-body">
                    <div class="alert alert-success">
                        <i class="fas fa-info-circle mr-2"></i>
                        Validasi laporan hasil <strong>Layak Dilaksanakan</strong>. Setelah disetujui, mahasiswa dapat mendaftar sidang skripsi.
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

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-times-circle mr-2"></i>
                    Tolak Hasil Seminar
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

    // Approve Form Submit
    $('#approveForm').on('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...');
        
        $.ajax({
            url: '{{ route("admin.application-result-seminars.approve", $applicationResultSeminar->id) }}',
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

    // Reject Form Submit
    $('#rejectForm').on('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...');
        
        $.ajax({
            url: '{{ route("admin.application-result-seminars.reject", $applicationResultSeminar->id) }}',
            method: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#rejectModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Hasil Seminar Ditolak',
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

    // Clear modal on close
    $('.modal').on('hidden.bs.modal', function() {
        const form = $(this).find('form')[0];
        if (form) {
            form.reset();
        }
    });
});
</script>
@endsection
