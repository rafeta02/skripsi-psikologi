@php
    $collections = [
        ['label' => 'Berita Acara Sidang', 'media' => $record->report_document, 'multi' => true],
        ['label' => 'Daftar Hadir', 'media' => $record->attendance_document, 'multi' => false],
        ['label' => 'Lembar Persetujuan Revisi', 'media' => $record->revision_approval_sheet, 'multi' => true],
        ['label' => 'Naskah Skripsi Final', 'media' => $record->latest_script, 'multi' => false],
        ['label' => 'Form Penilaian Penguji', 'media' => $record->form_document, 'multi' => true],
        ['label' => 'Dokumentasi Sidang', 'media' => $record->documentation, 'multi' => true],
        ['label' => 'Lembar Pengesahan / Sertifikat', 'media' => $record->certificate_document, 'multi' => false],
        ['label' => 'Bukti Publikasi', 'media' => $record->publication_document, 'multi' => false],
    ];
    $hasAny = false;
@endphp

@foreach($collections as $col)
    @php
        $items = $col['multi']
            ? ($col['media'] && $col['media']->count() > 0 ? $col['media'] : collect())
            : ($col['media'] ? collect([$col['media']]) : collect());
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
