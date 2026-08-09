@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, var(--primary-500) 0%, var(--secondary-500) 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1 text-white font-weight-bold">
                                <i class="fas fa-presentation mr-2"></i> Detail Review Kelayakan Proposal (Reguler)
                            </h2>
                            <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                                Informasi lengkap review kelayakan proposal
                            </p>
                        </div>
                        <div>
                            @can('skripsi_seminar_edit')
                                @if(!empty($canEdit['allowed']))
                                    <a href="{{ route('frontend.skripsi-seminars.edit', $skripsiSeminar->id) }}" class="btn btn-light">
                                        <i class="fas fa-edit"></i>
                                        {{ !empty($canEdit['retry_after_failed']) ? 'Unggah Ulang' : 'Edit' }}
                                    </a>
                                @endif
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Content -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card-modern mb-4">
                <div class="card-modern-body">
                    <h4 class="font-weight-bold mb-3">Informasi Seminar</h4>
                    
                    <div class="mb-4">
                        <label class="text-muted mb-1">Judul Proposal</label>
                        <h5 class="font-weight-semibold">{{ $skripsiSeminar->title ?? '-' }}</h5>
                    </div>

                    @if($skripsiSeminar->description)
                        <div class="mb-4">
                            <label class="text-muted mb-1">Deskripsi / Abstrak</label>
                            <p class="text-justify">{{ $skripsiSeminar->description }}</p>
                        </div>
                    @endif

                    @if($skripsiSeminar->notes)
                        <div class="mb-4">
                            <label class="text-muted mb-1">Catatan</label>
                            <p>{{ $skripsiSeminar->notes }}</p>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">Tanggal Dibuat</label>
                            <p class="font-weight-semibold">
                                <i class="far fa-calendar"></i> {{ $skripsiSeminar->created_at->format('d M Y H:i') }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">Terakhir Diupdate</label>
                            <p class="font-weight-semibold">
                                <i class="far fa-calendar"></i> {{ $skripsiSeminar->updated_at->format('d M Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents -->
            <div class="card-modern">
                <div class="card-modern-body">
                    <h4 class="font-weight-bold mb-3">Dokumen</h4>
                    
                    <div class="row">
                        @if($skripsiSeminar->proposal_document)
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 border">
                                    <div class="card-body text-center">
                                        <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                        <h6 class="mb-2">Dokumen Proposal</h6>
                                        <small class="text-muted d-block mb-3">{{ $skripsiSeminar->proposal_document->file_name }}</small>
                                        <div class="btn-group btn-group-sm flex-wrap justify-content-center">
                                            <a href="{{ $skripsiSeminar->proposal_document->getUrl() }}" target="_blank" class="btn btn-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <button type="button" class="btn btn-info preview-doc"
                                                    data-url="{{ $skripsiSeminar->proposal_document->getUrl() }}"
                                                    data-type="pdf">
                                                <i class="fas fa-expand"></i> Preview
                                            </button>
                                            <a href="{{ $skripsiSeminar->proposal_document->getUrl() }}" download class="btn btn-success">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($skripsiSeminar->approval_document)
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 border">
                                    <div class="card-body text-center">
                                        <i class="fas fa-file-pdf fa-3x text-success mb-3"></i>
                                        <h6 class="mb-2">Persetujuan Pembimbing</h6>
                                        <small class="text-muted d-block mb-3">{{ $skripsiSeminar->approval_document->file_name }}</small>
                                        <div class="btn-group btn-group-sm flex-wrap justify-content-center">
                                            <a href="{{ $skripsiSeminar->approval_document->getUrl() }}" target="_blank" class="btn btn-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <button type="button" class="btn btn-info preview-doc"
                                                    data-url="{{ $skripsiSeminar->approval_document->getUrl() }}"
                                                    data-type="pdf">
                                                <i class="fas fa-expand"></i> Preview
                                            </button>
                                            <a href="{{ $skripsiSeminar->approval_document->getUrl() }}" download class="btn btn-success">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($skripsiSeminar->plagiarism_document)
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 border">
                                    <div class="card-body text-center">
                                        <i class="fas fa-file-pdf fa-3x text-info mb-3"></i>
                                        <h6 class="mb-2">Plagiarism Check</h6>
                                        <small class="text-muted d-block mb-3">{{ $skripsiSeminar->plagiarism_document->file_name }}</small>
                                        <div class="btn-group btn-group-sm flex-wrap justify-content-center">
                                            <a href="{{ $skripsiSeminar->plagiarism_document->getUrl() }}" target="_blank" class="btn btn-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <button type="button" class="btn btn-info preview-doc"
                                                    data-url="{{ $skripsiSeminar->plagiarism_document->getUrl() }}"
                                                    data-type="pdf">
                                                <i class="fas fa-expand"></i> Preview
                                            </button>
                                            <a href="{{ $skripsiSeminar->plagiarism_document->getUrl() }}" download class="btn btn-success">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(!$skripsiSeminar->proposal_document && !$skripsiSeminar->approval_document && !$skripsiSeminar->plagiarism_document)
                            <div class="col-12 text-center text-muted py-4">
                                <i class="fas fa-folder-open fa-3x mb-3"></i>
                                <p>Tidak ada dokumen terlampir</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Status -->
            <div class="card-modern mb-4">
                <div class="card-modern-body">
                    <h5 class="font-weight-bold mb-3">Status</h5>
                    
                    @if($skripsiSeminar->application)
                        @if(!$skripsiSeminar->admin_validated_at && $skripsiSeminar->application->status == 'submitted')
                            <div class="alert alert-warning">
                                <i class="fas fa-clock"></i> <strong>Menunggu Validasi Admin</strong>
                                <p class="mb-0 mt-2 small">Pengajuan Anda sedang direview admin</p>
                            </div>
                        @elseif($skripsiSeminar->admin_validated_at && $skripsiSeminar->application->status == 'submitted')
                            <div class="alert alert-info">
                                <i class="fas fa-user-check"></i> <strong>Menunggu Reviewer</strong>
                                <p class="mb-0 mt-2 small">Reviewer ditugaskan — menunggu respons dan feedback dosen</p>
                            </div>
                        @elseif($skripsiSeminar->application->status == 'approved')
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> <strong>Review Selesai</strong>
                                <p class="mb-0 mt-2 small">Kedua reviewer telah mengirim feedback. Silakan kirim laporan hasil review.</p>
                            </div>
                        @elseif($skripsiSeminar->application->status == 'revision')
                            <div class="alert alert-warning">
                                <i class="fas fa-edit"></i> <strong>Revisi</strong>
                                <p class="mb-0 mt-2 small">Silakan lakukan revisi sesuai catatan</p>
                            </div>
                        @elseif($skripsiSeminar->application->status == 'rejected')
                            <div class="alert alert-danger">
                                <i class="fas fa-times-circle"></i> <strong>Ditolak</strong>
                                <p class="mb-0 mt-2 small">Pendaftaran Anda ditolak</p>
                            </div>
                        @elseif($skripsiSeminar->application->status == 'done')
                            <div class="alert alert-secondary">
                                <i class="fas fa-flag-checkered"></i> <strong>Selesai</strong>
                                <p class="mb-0 mt-2 small">Seminar telah selesai dilaksanakan</p>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-secondary">
                            <i class="fas fa-info-circle"></i> Status tidak tersedia
                        </div>
                    @endif
                </div>
            </div>

            <!-- Application Info -->
            @if($skripsiSeminar->application)
                <div class="card-modern">
                    <div class="card-modern-body">
                        <h5 class="font-weight-bold mb-3">Informasi Aplikasi</h5>
                        
                        <div class="mb-3">
                            <label class="text-muted mb-1">Tipe</label>
                            <p class="font-weight-semibold text-uppercase">{{ $skripsiSeminar->application->type }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted mb-1">Tahap</label>
                            <p class="font-weight-semibold text-capitalize">{{ $skripsiSeminar->application->stage }}</p>
                        </div>

                        @if($skripsiSeminar->application->submitted_at)
                            <div class="mb-3">
                                <label class="text-muted mb-1">Tanggal Submit</label>
                                <p class="font-weight-semibold">
                                    {{ \Carbon\Carbon::parse($skripsiSeminar->application->submitted_at)->format('d M Y H:i') }}
                                </p>
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
            <a href="{{ route('frontend.skripsi-seminars.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>
    </div>
</div>

<!-- Document Preview Modal -->
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
