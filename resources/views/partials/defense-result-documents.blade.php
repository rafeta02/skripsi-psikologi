@php
    $collections = [
        ['label' => 'Berita Acara Sidang', 'collection' => 'report_document'],
        ['label' => 'Daftar Hadir', 'collection' => 'attendance_document'],
        ['label' => 'Lembar Persetujuan Revisi', 'collection' => 'revision_approval_sheet'],
        ['label' => 'Naskah Skripsi Final', 'collection' => 'latest_script'],
        ['label' => 'Form Penilaian Penguji', 'collection' => 'form_document'],
        ['label' => 'Dokumentasi Sidang', 'collection' => 'documentation'],
        ['label' => 'Lembar Pengesahan / Sertifikat', 'collection' => 'certificate_document'],
        ['label' => 'Bukti Publikasi', 'collection' => 'publication_document'],
    ];
    $hasAny = false;
@endphp

@foreach($collections as $col)
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
                                <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
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
