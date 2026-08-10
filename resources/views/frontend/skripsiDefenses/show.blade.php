@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, #16a085 0%, #27ae60 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1 text-white font-weight-bold">
                                <i class="fas fa-graduation-cap mr-2"></i> Detail Sidang Skripsi
                            </h2>
                            <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                                Informasi lengkap pendaftaran sidang
                            </p>
                        </div>
                        <div>
                            @can('skripsi_defense_edit')
                                @if($skripsiDefense->application && in_array($skripsiDefense->application->status, ['revision', 'submitted']))
                                    <a href="{{ route('frontend.skripsi-defenses.edit', $skripsiDefense->id) }}" class="btn btn-light">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                @endif
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($skripsiDefense->isAccepted())
        @include('partials.siakad-upload-warning')
    @endif

    <!-- Detail Content -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card-modern mb-4">
                <div class="card-modern-body">
                    <h4 class="font-weight-bold mb-3">Informasi Sidang</h4>
                    
                    <div class="mb-4">
                        <label class="text-muted mb-1">Judul</label>
                        <h5 class="font-weight-semibold">{{ $skripsiDefense->title ?? '-' }}</h5>
                    </div>

                    @if($skripsiDefense->abstract)
                        <div class="mb-4">
                            <label class="text-muted mb-1">Abstrak</label>
                            <p class="text-justify">{{ $skripsiDefense->abstract }}</p>
                        </div>
                    @endif

                    @if($skripsiDefense->eap_grade || $skripsiDefense->eap_score)
                        <div class="mb-4">
                            <label class="text-muted mb-1">EAP</label>
                            <p class="font-weight-semibold mb-0">
                                @if($skripsiDefense->eap_grade)
                                    <span class="badge badge-primary badge-lg px-3 py-2 mr-1">{{ trans('cruds.skripsiDefense.fields.eap_grade') }}: {{ $skripsiDefense->eapGradeLabel() }}</span>
                                @endif
                                @if($skripsiDefense->eap_score)
                                    <span class="badge badge-info badge-lg px-3 py-2">{{ trans('cruds.skripsiDefense.fields.eap_score') }}: {{ $skripsiDefense->eap_score }}</span>
                                @endif
                            </p>
                        </div>
                    @endif

                    @if($skripsiDefense->notes)
                        <div class="mb-4">
                            <label class="text-muted mb-1">Catatan</label>
                            <div class="alert alert-light">
                                {{ $skripsiDefense->notes }}
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">Tanggal Dibuat</label>
                            <p class="font-weight-semibold">
                                {{ $skripsiDefense->created_at->format('d M Y H:i') }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">Terakhir Diupdate</label>
                            <p class="font-weight-semibold">
                                {{ $skripsiDefense->updated_at->format('d M Y H:i') }}
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
                        @if($skripsiDefense->defence_document)
                            @include('frontend.skripsiDefenses.partials.document-card', [
                                'media' => $skripsiDefense->defence_document,
                                'label' => 'Naskah Skripsi Final',
                                'icon' => 'fa-file-pdf',
                                'iconColor' => 'text-danger',
                            ])
                        @endif

                        @if($skripsiDefense->plagiarism_report)
                            @include('frontend.skripsiDefenses.partials.document-card', [
                                'media' => $skripsiDefense->plagiarism_report,
                                'label' => trans('cruds.skripsiDefense.fields.plagiarism_report'),
                                'icon' => 'fa-file-pdf',
                                'iconColor' => 'text-info',
                            ])
                        @endif

                        @if($skripsiDefense->signed_scientific_publication_statement)
                            @include('frontend.skripsiDefenses.partials.document-card', [
                                'media' => $skripsiDefense->signed_scientific_publication_statement,
                                'label' => trans('cruds.skripsiDefense.fields.signed_scientific_publication_statement'),
                                'icon' => 'fa-file-signature',
                                'iconColor' => 'text-success',
                            ])
                        @endif

                        @include('frontend.skripsiDefenses.partials.document-collection-card', [
                            'items' => $skripsiDefense->ethics_statement,
                            'label' => trans('cruds.skripsiDefense.fields.ethics_statement'),
                            'icon' => 'fa-balance-scale',
                            'iconColor' => 'text-success',
                        ])

                        @include('frontend.skripsiDefenses.partials.document-collection-card', [
                            'items' => $skripsiDefense->research_instruments,
                            'label' => trans('cruds.skripsiDefense.fields.research_instruments'),
                            'icon' => 'fa-clipboard-list',
                            'iconColor' => 'text-primary',
                        ])

                        @include('frontend.skripsiDefenses.partials.document-collection-card', [
                            'items' => $skripsiDefense->data_collection_letter,
                            'label' => trans('cruds.skripsiDefense.fields.data_collection_letter'),
                            'icon' => 'fa-envelope-open-text',
                            'iconColor' => 'text-info',
                        ])

                        @include('frontend.skripsiDefenses.partials.document-collection-card', [
                            'items' => $skripsiDefense->research_module,
                            'label' => trans('cruds.skripsiDefense.fields.research_module'),
                            'icon' => 'fa-book-open',
                            'iconColor' => 'text-secondary',
                        ])

                        @include('frontend.skripsiDefenses.partials.document-collection-card', [
                            'items' => $skripsiDefense->defense_approval_page,
                            'label' => trans('cruds.skripsiDefense.fields.defense_approval_page'),
                            'icon' => 'fa-stamp',
                            'iconColor' => 'text-warning',
                        ])

                        @include('frontend.skripsiDefenses.partials.document-collection-card', [
                            'items' => $skripsiDefense->research_poster,
                            'label' => trans('cruds.skripsiDefense.fields.research_poster'),
                            'icon' => 'fa-image',
                            'iconColor' => 'text-danger',
                        ])

                        @include('frontend.skripsiDefenses.partials.document-collection-card', [
                            'items' => $skripsiDefense->supervision_logbook,
                            'label' => trans('cruds.skripsiDefense.fields.supervision_logbook'),
                            'icon' => 'fa-book',
                            'iconColor' => 'text-dark',
                        ])

                        @if($skripsiDefense->spp_receipt)
                            @include('frontend.skripsiDefenses.partials.document-card', [
                                'media' => $skripsiDefense->spp_receipt,
                                'label' => trans('cruds.skripsiDefense.fields.spp_receipt'),
                                'icon' => 'fa-file-invoice-dollar',
                                'iconColor' => 'text-warning',
                            ])
                        @endif

                        @if($skripsiDefense->krs_latest)
                            @include('frontend.skripsiDefenses.partials.document-card', [
                                'media' => $skripsiDefense->krs_latest,
                                'label' => trans('cruds.skripsiDefense.fields.krs_latest'),
                                'icon' => 'fa-file-alt',
                                'iconColor' => 'text-primary',
                            ])
                        @endif

                        @if($skripsiDefense->eap_certificate)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 border shadow-sm">
                                    <div class="card-body text-center d-flex flex-column">
                                        <i class="fas fa-certificate fa-3x text-success mb-3"></i>
                                        <h6 class="mb-2">{{ trans('cruds.skripsiDefense.fields.eap_certificate') }}</h6>
                                        @if($skripsiDefense->eap_grade || $skripsiDefense->eap_score)
                                            <p class="mb-2">
                                                @if($skripsiDefense->eap_grade)
                                                    <span class="badge badge-primary">{{ trans('cruds.skripsiDefense.fields.eap_grade') }}: {{ $skripsiDefense->eapGradeLabel() }}</span>
                                                @endif
                                                @if($skripsiDefense->eap_score)
                                                    <span class="badge badge-info ml-1">{{ trans('cruds.skripsiDefense.fields.eap_score') }}: {{ $skripsiDefense->eap_score }}</span>
                                                @endif
                                            </p>
                                        @endif
                                        <small class="text-muted d-block mb-2 text-truncate px-2" title="{{ $skripsiDefense->eap_certificate->file_name }}">
                                            {{ $skripsiDefense->eap_certificate->file_name }}
                                        </small>
                                        <div class="mt-auto">
                                            @include('frontend.skripsiDefenses.partials.media-actions', ['media' => $skripsiDefense->eap_certificate])
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($skripsiDefense->transcript)
                            @include('frontend.skripsiDefenses.partials.document-card', [
                                'media' => $skripsiDefense->transcript,
                                'label' => trans('cruds.skripsiDefense.fields.transcript'),
                                'icon' => 'fa-file-alt',
                                'iconColor' => 'text-secondary',
                            ])
                        @endif

                        @if($skripsiDefense->siakad_supervisor_screenshot)
                            @include('frontend.skripsiDefenses.partials.document-card', [
                                'media' => $skripsiDefense->siakad_supervisor_screenshot,
                                'label' => trans('cruds.skripsiDefense.fields.siakad_supervisor_screenshot'),
                                'icon' => 'fa-desktop',
                                'iconColor' => 'text-info',
                            ])
                        @endif

                        @if(($skripsiDefense->application->type ?? null) === 'mbkm')
                            @if($skripsiDefense->mbkm_recommendation_letter)
                                @include('frontend.skripsiDefenses.partials.document-card', [
                                    'media' => $skripsiDefense->mbkm_recommendation_letter,
                                    'label' => trans('cruds.skripsiDefense.fields.mbkm_recommendation_letter'),
                                    'icon' => 'fa-file-pdf',
                                    'iconColor' => 'text-primary',
                                ])
                            @endif

                            @include('frontend.skripsiDefenses.partials.document-collection-card', [
                                'items' => $skripsiDefense->mbkm_report,
                                'label' => trans('cruds.skripsiDefense.fields.mbkm_report'),
                                'icon' => 'fa-globe',
                                'iconColor' => 'text-info',
                            ])
                        @endif

                        @php
                            $hasAnyDocument = $skripsiDefense->defence_document
                                || $skripsiDefense->plagiarism_report
                                || $skripsiDefense->signed_scientific_publication_statement
                                || count($skripsiDefense->ethics_statement) > 0
                                || count($skripsiDefense->research_instruments) > 0
                                || count($skripsiDefense->data_collection_letter) > 0
                                || count($skripsiDefense->research_module) > 0
                                || count($skripsiDefense->defense_approval_page) > 0
                                || count($skripsiDefense->research_poster) > 0
                                || count($skripsiDefense->supervision_logbook) > 0
                                || $skripsiDefense->spp_receipt
                                || $skripsiDefense->krs_latest
                                || $skripsiDefense->eap_certificate
                                || $skripsiDefense->transcript
                                || $skripsiDefense->siakad_supervisor_screenshot
                                || $skripsiDefense->mbkm_recommendation_letter
                                || count($skripsiDefense->mbkm_report) > 0;
                        @endphp

                        @if(!$hasAnyDocument)
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
                    
                    @php
                        $defenseStatus = $skripsiDefense->validationStatus();
                    @endphp
                    @if($defenseStatus === 'pending')
                        <div class="alert alert-warning">
                            <i class="fas fa-clock"></i> <strong>Menunggu Validasi Admin</strong>
                            <p class="mb-0 mt-2 small">Pendaftaran sidang sedang ditinjau oleh admin.</p>
                        </div>
                    @elseif($defenseStatus === 'accepted')
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> <strong>Diterima</strong>
                            <p class="mb-0 mt-2 small">Pendaftaran sidang diterima. Lanjutkan ke penjadwalan sidang.</p>
                        </div>
                        @if($skripsiDefense->examiner1?->dosen || $skripsiDefense->examiner2?->dosen)
                            <div class="mt-3 small text-muted">
                                <strong>Penguji:</strong>
                                <ul class="mb-0 pl-3">
                                    @if($skripsiDefense->examiner1?->dosen)
                                        <li>Penguji 1: {{ $skripsiDefense->examiner1->dosen->nama }}</li>
                                    @endif
                                    @if($skripsiDefense->examiner2?->dosen)
                                        <li>Penguji 2: {{ $skripsiDefense->examiner2->dosen->nama }}</li>
                                    @endif
                                </ul>
                            </div>
                        @endif
                        <div class="mt-3">
                            @if($existingSchedule ?? null)
                                <a href="{{ route('frontend.application-schedules.show', $existingSchedule->id) }}" class="btn btn-sm btn-info btn-block">
                                    <i class="fas fa-calendar-check"></i> Lihat Jadwal Sidang
                                </a>
                            @elseif(($scheduleAccess['allowed'] ?? false))
                                @can('application_schedule_create')
                                    <a href="{{ route('frontend.application-schedules.create') }}" class="btn btn-sm btn-primary btn-block">
                                        <i class="fas fa-calendar-plus"></i> Ajukan Jadwal Sidang
                                    </a>
                                @endcan
                            @elseif(($scheduleAccess['message'] ?? null))
                                <p class="small text-muted mb-0">{{ $scheduleAccess['message'] }}</p>
                            @endif
                        </div>
                    @elseif($defenseStatus === 'rejected')
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle"></i> <strong>Ditolak</strong>
                            <p class="mb-0 mt-2 small">
                                @if($skripsiDefense->admin_note)
                                    {{ $skripsiDefense->admin_note }}
                                @else
                                    Pendaftaran sidang ditolak. Silakan perbaiki dan ajukan kembali.
                                @endif
                            </p>
                        </div>
                    @elseif($skripsiDefense->application?->status == 'scheduled')
                        <div class="alert alert-info">
                            <i class="fas fa-calendar-check"></i> <strong>Terjadwal</strong>
                            <p class="mb-0 mt-2 small">Sidang telah dijadwalkan</p>
                        </div>
                    @elseif($skripsiDefense->application?->status == 'done')
                        <div class="alert alert-secondary">
                            <i class="fas fa-flag-checkered"></i> <strong>Selesai</strong>
                            <p class="mb-0 mt-2 small">Sidang telah selesai dilaksanakan</p>
                        </div>
                    @else
                        <div class="alert alert-secondary">
                            <i class="fas fa-info-circle"></i> Status: {{ ucfirst($skripsiDefense->application->status ?? '-') }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Application Info -->
            @if($skripsiDefense->application)
                <div class="card-modern">
                    <div class="card-modern-body">
                        <h5 class="font-weight-bold mb-3">Informasi Aplikasi</h5>
                        
                        <div class="mb-3">
                            <label class="text-muted mb-1">Tipe</label>
                            <p class="font-weight-semibold text-uppercase">
                                <span class="badge badge-{{ $skripsiDefense->application->type == 'mbkm' ? 'primary' : 'success' }}">
                                    {{ $skripsiDefense->application->type }}
                                </span>
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted mb-1">Tahap</label>
                            <p class="font-weight-semibold text-capitalize">
                                <span class="badge badge-info">{{ $skripsiDefense->application->stage }}</span>
                            </p>
                        </div>

                        @if($skripsiDefense->application->submitted_at)
                            <div class="mb-3">
                                <label class="text-muted mb-1">Tanggal Submit</label>
                                <p class="font-weight-semibold">
                                    {{ \Carbon\Carbon::parse($skripsiDefense->application->submitted_at)->format('d M Y H:i') }}
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
            <a href="{{ route('frontend.skripsi-defenses.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
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
