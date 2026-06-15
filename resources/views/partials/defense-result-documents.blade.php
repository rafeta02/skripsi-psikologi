@php
    $singleCollections = [
        ['label' => 'Form Penggantian Judul Skripsi', 'collection' => 'title_change_form'],
        ['label' => 'Berita Acara dan Lampirannya', 'collection' => 'minutes_document'],
        ['label' => 'Naskah Skripsi yang Telah Direvisi dan Disahkan', 'collection' => 'latest_script'],
        ['label' => 'Lembar Pengesahan', 'collection' => 'approval_page'],
        ['label' => 'Lembar Persetujuan Hasil Revisi', 'collection' => 'revision_approval_sheet'],
    ];
    $multipleCollections = [
        ['label' => 'Dokumentasi Sidang', 'collection' => 'documentation', 'image' => true],
        ['label' => 'Berkas Undangan Sidang', 'collection' => 'invitation_document'],
        ['label' => 'Umpan Balik Sidang Pembimbing dan Penguji', 'collection' => 'feedback_document'],
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
        @php $hasAny = true; @endphp
        <div class="mb-4">
            <h6 class="font-weight-semibold mb-2">{{ $col['label'] }}</h6>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card h-100 border">
                        <div class="card-body text-center">
                            <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                            <h6 class="mb-2 text-truncate small" title="{{ $document->file_name }}">{{ $document->file_name }}</h6>
                            <a href="{{ $document->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download"></i> Download
                            </a>
                        </div>
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
            <div class="row">
                @foreach($items as $document)
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 border">
                            <div class="card-body text-center">
                                @if(!empty($col['image']) && str_starts_with($document->mime_type ?? '', 'image/'))
                                    <i class="fas fa-file-image fa-3x text-info mb-3"></i>
                                @else
                                    <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                @endif
                                <h6 class="mb-2 text-truncate small" title="{{ $document->file_name }}">{{ $document->file_name }}</h6>
                                <a href="{{ $document->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download"></i> Download
                                </a>
                            </div>
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
