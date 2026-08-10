@php
    $singleCollections = [
        ['label' => 'Form Penggantian Judul Skripsi', 'collection' => 'title_change_form', 'icon' => 'fa-file-pdf text-danger'],
        ['label' => 'Berita Acara dan Lampirannya', 'collection' => 'minutes_document', 'icon' => 'fa-file-pdf text-danger'],
        ['label' => 'Naskah Skripsi yang Telah Direvisi dan Disahkan', 'collection' => 'latest_script', 'icon' => 'fa-file-pdf text-danger'],
        ['label' => 'Lembar Pengesahan', 'collection' => 'approval_page', 'icon' => 'fa-file-pdf text-danger'],
        ['label' => 'Lembar Persetujuan Hasil Revisi', 'collection' => 'revision_approval_sheet', 'icon' => 'fa-file-pdf text-danger'],
    ];
    $multipleCollections = [
        ['label' => 'Dokumentasi Sidang', 'collection' => 'documentation', 'image' => true, 'icon' => 'fa-file-image text-info'],
        ['label' => 'Berkas Undangan Sidang', 'collection' => 'invitation_document', 'icon' => 'fa-file-pdf text-danger'],
        ['label' => 'Umpan Balik Sidang Pembimbing dan Penguji', 'collection' => 'feedback_document', 'icon' => 'fa-file-pdf text-danger'],
    ];
    $hasAny = false;
@endphp

@if(!empty($record->final_title))
    <div class="mb-4">
        <h6 class="font-weight-semibold mb-2">Judul Akhir Skripsi</h6>
        <p class="mb-0">{{ $record->final_title }}</p>
    </div>
    @php $hasAny = true; @endphp
@endif

@foreach($singleCollections as $col)
    @php $document = $record->getMedia($col['collection'])->last(); @endphp
    @if($document)
        @php
            $hasAny = true;
            $docType = str_starts_with($document->mime_type ?? '', 'image/') ? 'image' : 'pdf';
        @endphp
        <div class="mb-4">
            <h6 class="font-weight-semibold mb-2">{{ $col['label'] }}</h6>
            <div class="list-group list-group-flush">
                <div class="list-group-item px-0 d-flex justify-content-between align-items-center flex-wrap">
                    <div class="mr-3 mb-2 mb-md-0">
                        <i class="fas {{ $col['icon'] }} mr-2"></i>
                        <span class="text-muted small">{{ $document->file_name }}</span>
                    </div>
                    <div class="btn-group flex-shrink-0">
                        <a href="{{ $document->getUrl() }}" target="_blank" class="btn btn-sm btn-primary" title="View">
                            <i class="fas fa-eye mr-1"></i> View
                        </a>
                        <button type="button" class="btn btn-sm btn-info preview-doc"
                                data-url="{{ $document->getUrl() }}"
                                data-type="{{ $docType }}"
                                data-name="{{ $col['label'] }}"
                                title="Preview">
                            <i class="fas fa-expand mr-1"></i> Preview
                        </button>
                        <a href="{{ $document->getUrl() }}" download class="btn btn-sm btn-success" title="Download">
                            <i class="fas fa-download mr-1"></i> Download
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach

@foreach($multipleCollections as $col)
    @php
        $items = $record->getMedia($col['collection'])->filter(function ($item) {
            return $item instanceof \Spatie\MediaLibrary\MediaCollections\Models\Media;
        });
    @endphp
    @if($items->count() > 0)
        @php $hasAny = true; @endphp
        <div class="mb-4">
            <h6 class="font-weight-semibold mb-2">{{ $col['label'] }}</h6>
            <div class="list-group list-group-flush">
                @foreach($items as $document)
                    @php
                        $isImage = !empty($col['image']) && str_starts_with($document->mime_type ?? '', 'image/');
                        $docType = $isImage ? 'image' : 'pdf';
                        $icon = $isImage ? 'fa-file-image text-info' : 'fa-file-pdf text-danger';
                    @endphp
                    <div class="list-group-item px-0 d-flex justify-content-between align-items-center flex-wrap">
                        <div class="mr-3 mb-2 mb-md-0">
                            <i class="fas {{ $icon }} mr-2"></i>
                            <span class="text-muted small">{{ $document->file_name }}</span>
                        </div>
                        <div class="btn-group flex-shrink-0">
                            <a href="{{ $document->getUrl() }}" target="_blank" class="btn btn-sm btn-primary" title="View">
                                <i class="fas fa-eye mr-1"></i> View
                            </a>
                            <button type="button" class="btn btn-sm btn-info preview-doc"
                                    data-url="{{ $document->getUrl() }}"
                                    data-type="{{ $docType }}"
                                    data-name="{{ $col['label'] }}"
                                    title="Preview">
                                <i class="fas fa-expand mr-1"></i> Preview
                            </button>
                            <a href="{{ $document->getUrl() }}" download class="btn btn-sm btn-success" title="Download">
                                <i class="fas fa-download mr-1"></i> Download
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endforeach

@if(!$hasAny)
    <div class="text-center text-muted py-4">
        <i class="fas fa-folder-open fa-3x mb-3"></i>
        <p class="mb-0">Tidak ada dokumen terlampir</p>
    </div>
@endif
