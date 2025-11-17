@extends('layouts.frontend')
@section('content')
<link rel="stylesheet" href="{{ asset('css/modern-form.css') }}">
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="detail-card">
                <div class="detail-header">
                    <div>
                        <h2>Detail Hasil Sidang</h2>
                        <p>Informasi lengkap hasil sidang skripsi</p>
                    </div>
                    <div>
                        @if($applicationResultDefense->result == 'passed')
                            <span class="status-badge approved large">
                                <i class="fas fa-check-circle mr-2"></i> Lulus
                            </span>
                        @elseif($applicationResultDefense->result == 'revision')
                            <span class="status-badge pending large">
                                <i class="fas fa-edit mr-2"></i> Revisi
                            </span>
                        @elseif($applicationResultDefense->result == 'failed')
                            <span class="status-badge rejected large">
                                <i class="fas fa-times-circle mr-2"></i> Tidak Lulus
                            </span>
                        @endif
                    </div>
                </div>

                <div class="detail-body">
                    <div class="detail-section">
                        <h3 class="section-title">
                            <i class="fas fa-file-alt mr-2"></i> Informasi Aplikasi
                        </h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Mahasiswa:</span>
                                    <span class="info-value">{{ $applicationResultDefense->application->mahasiswa->nama ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">NIM:</span>
                                    <span class="info-value">{{ $applicationResultDefense->application->mahasiswa->nim ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h3 class="section-title">
                            <i class="fas fa-clipboard-check mr-2"></i> Hasil Sidang
                        </h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Hasil:</span>
                                    <span class="info-value">
                                        {{ App\Models\ApplicationResultDefense::RESULT_SELECT[$applicationResultDefense->result] ?? '-' }}
                                    </span>
                                </div>
                            </div>
                            @if($applicationResultDefense->revision_deadline)
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="info-label">Batas Revisi:</span>
                                        <span class="info-value">
                                            <i class="fas fa-calendar-alt mr-1 text-muted"></i>
                                            {{ \Carbon\Carbon::parse($applicationResultDefense->revision_deadline)->format('d F Y') }}
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($applicationResultDefense->note)
                        <div class="detail-section">
                            <h3 class="section-title">
                                <i class="fas fa-sticky-note mr-2"></i> Catatan & Saran Perbaikan
                            </h3>
                            <div class="note-box">
                                {!! nl2br(e($applicationResultDefense->note)) !!}
                            </div>
                        </div>
                    @endif

                    <div class="detail-section">
                        <h3 class="section-title">
                            <i class="fas fa-folder-open mr-2"></i> Dokumen
                        </h3>

                        @if($applicationResultDefense->report_document && $applicationResultDefense->report_document->isNotEmpty())
                            <h4 class="document-category-title">Berita Acara Sidang</h4>
                            <div class="document-list">
                                @foreach($applicationResultDefense->report_document as $key => $media)
                                    <div class="document-item">
                                        <div class="document-icon">
                                            <i class="fas fa-file-pdf"></i>
                                        </div>
                                        <div class="document-info">
                                            <div class="document-name">{{ $media->file_name ?? 'Berita Acara ' . ($key + 1) }}</div>
                                            <div class="document-meta">
                                                <span><i class="fas fa-hdd mr-1"></i>{{ number_format(($media->size ?? 0) / 1024, 2) }} KB</span>
                                            </div>
                                        </div>
                                        <div class="document-actions">
                                            <button type="button" class="btn-icon preview-doc" data-url="{{ $media->getUrl() }}" title="Preview">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="{{ $media->getUrl() }}" class="btn-icon" download title="Download">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if($applicationResultDefense->attendance_document)
                            <h4 class="document-category-title">Daftar Hadir</h4>
                            <div class="document-list">
                                <div class="document-item">
                                    <div class="document-icon">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>
                                    <div class="document-info">
                                        <div class="document-name">{{ $applicationResultDefense->attendance_document->file_name ?? 'Daftar Hadir' }}</div>
                                        <div class="document-meta">
                                            <span><i class="fas fa-hdd mr-1"></i>{{ number_format(($applicationResultDefense->attendance_document->size ?? 0) / 1024, 2) }} KB</span>
                                        </div>
                                    </div>
                                    <div class="document-actions">
                                        <button type="button" class="btn-icon preview-doc" data-url="{{ $applicationResultDefense->attendance_document->getUrl() }}" title="Preview">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ $applicationResultDefense->attendance_document->getUrl() }}" class="btn-icon" download title="Download">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($applicationResultDefense->latest_script)
                            <h4 class="document-category-title">Naskah Skripsi Final</h4>
                            <div class="document-list">
                                <div class="document-item">
                                    <div class="document-icon">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>
                                    <div class="document-info">
                                        <div class="document-name">{{ $applicationResultDefense->latest_script->file_name ?? 'Naskah Skripsi' }}</div>
                                        <div class="document-meta">
                                            <span><i class="fas fa-hdd mr-1"></i>{{ number_format(($applicationResultDefense->latest_script->size ?? 0) / 1024, 2) }} KB</span>
                                        </div>
                                    </div>
                                    <div class="document-actions">
                                        <button type="button" class="btn-icon preview-doc" data-url="{{ $applicationResultDefense->latest_script->getUrl() }}" title="Preview">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ $applicationResultDefense->latest_script->getUrl() }}" class="btn-icon" download title="Download">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($applicationResultDefense->certificate_document)
                            <h4 class="document-category-title">Lembar Pengesahan</h4>
                            <div class="document-list">
                                <div class="document-item">
                                    <div class="document-icon">
                                        <i class="fas fa-certificate"></i>
                                    </div>
                                    <div class="document-info">
                                        <div class="document-name">{{ $applicationResultDefense->certificate_document->file_name ?? 'Lembar Pengesahan' }}</div>
                                        <div class="document-meta">
                                            <span><i class="fas fa-hdd mr-1"></i>{{ number_format(($applicationResultDefense->certificate_document->size ?? 0) / 1024, 2) }} KB</span>
                                        </div>
                                    </div>
                                    <div class="document-actions">
                                        <button type="button" class="btn-icon preview-doc" data-url="{{ $applicationResultDefense->certificate_document->getUrl() }}" title="Preview">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ $applicationResultDefense->certificate_document->getUrl() }}" class="btn-icon" download title="Download">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($applicationResultDefense->publication_document)
                            <h4 class="document-category-title">Bukti Publikasi</h4>
                            <div class="document-list">
                                <div class="document-item">
                                    <div class="document-icon">
                                        <i class="fas fa-newspaper"></i>
                                    </div>
                                    <div class="document-info">
                                        <div class="document-name">{{ $applicationResultDefense->publication_document->file_name ?? 'Bukti Publikasi' }}</div>
                                        <div class="document-meta">
                                            <span><i class="fas fa-hdd mr-1"></i>{{ number_format(($applicationResultDefense->publication_document->size ?? 0) / 1024, 2) }} KB</span>
                                        </div>
                                    </div>
                                    <div class="document-actions">
                                        <button type="button" class="btn-icon preview-doc" data-url="{{ $applicationResultDefense->publication_document->getUrl() }}" title="Preview">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ $applicationResultDefense->publication_document->getUrl() }}" class="btn-icon" download title="Download">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="detail-actions">
                    <a href="{{ route('frontend.application-result-defenses.index') }}" class="btn-back">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    @can('application_result_defense_edit')
                        <a href="{{ route('frontend.application-result-defenses.edit', $applicationResultDefense->id) }}" class="btn-primary-custom">
                            <i class="fas fa-edit mr-2"></i> Edit Data
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Document Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">
                    <i class="fas fa-file-pdf mr-2"></i>Preview Dokumen
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="height: 80vh;">
                <iframe id="pdfViewer" style="width: 100%; height: 100%; border: none;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
$(document).ready(function() {
    $('.preview-doc').on('click', function() {
        const url = $(this).data('url');
        $('#pdfViewer').attr('src', url);
        $('#previewModal').modal('show');
    });

    $('#previewModal').on('hidden.bs.modal', function () {
        $('#pdfViewer').attr('src', '');
    });
});
</script>
@endsection