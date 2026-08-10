@props(['skripsiDefense'])

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
