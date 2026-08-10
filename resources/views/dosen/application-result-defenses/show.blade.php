@extends('layouts.dosen')

@section('content')
@php
    $mahasiswa = $resultDefense->application?->mahasiswa;
    $resultLabels = \App\Models\ApplicationResultDefense::RESULT_SELECT;
@endphp

@include('partials.dosen.page-header', [
    'title' => 'Laporan Hasil Sidang',
    'subtitle' => 'Dokumen laporan yang dikirim mahasiswa setelah sidang skripsi',
])

<div class="mb-3">
    <a href="{{ route('dosen.application-result-defenses.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="mhs-card mb-3">
            <div class="mhs-card-body">
                <h5 class="font-weight-bold mb-3">
                    <i class="fas fa-user-graduate text-primary mr-2"></i> Informasi Mahasiswa
                </h5>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="text-muted mb-0">Nama</label>
                        <p class="font-weight-semibold mb-0">{{ $mahasiswa->nama ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="text-muted mb-0">NIM</label>
                        <p class="font-weight-semibold mb-0">{{ $mahasiswa->nim ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="text-muted mb-0">Program Studi</label>
                        <p class="font-weight-semibold mb-0">{{ $mahasiswa->prodi->name ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="text-muted mb-0">Peran Anda</label>
                        <p class="font-weight-semibold mb-0">{{ $dosenRole ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mhs-card mb-3">
            <div class="mhs-card-body">
                <h5 class="font-weight-bold mb-3">
                    <i class="fas fa-award text-success mr-2"></i> Ringkasan Hasil Sidang
                </h5>
                <div class="mb-3">
                    <label class="text-muted mb-1">Hasil Sidang</label>
                    <p class="mb-0">
                        @php
                            $resultClass = match ($resultDefense->result) {
                                'passed' => 'success',
                                'revision' => 'warning',
                                'failed' => 'danger',
                                default => 'secondary',
                            };
                        @endphp
                        <span class="badge badge-{{ $resultClass }} badge-lg">
                            {{ $resultLabels[$resultDefense->result] ?? '-' }}
                        </span>
                    </p>
                </div>
                @if($resultDefense->final_title)
                    <div class="mb-3">
                        <label class="text-muted mb-1">Judul Akhir</label>
                        <p class="font-weight-semibold mb-0">{{ $resultDefense->final_title }}</p>
                    </div>
                @endif
                @if($resultDefense->final_title_en)
                    <div class="mb-3">
                        <label class="text-muted mb-1">Judul Akhir (English)</label>
                        <p class="font-weight-semibold mb-0">{{ $resultDefense->final_title_en }}</p>
                    </div>
                @endif
                @if($resultDefense->note)
                    <div class="mb-3">
                        <label class="text-muted mb-1">Catatan</label>
                        <p class="mb-0">{{ $resultDefense->note }}</p>
                    </div>
                @endif
                @if($resultDefense->result === 'revision' && $resultDefense->revision_deadline)
                    <div class="alert alert-info mb-0">
                        <strong>Batas revisi:</strong> {{ $resultDefense->revision_deadline }}
                    </div>
                @endif
            </div>
        </div>

        <div class="mhs-card">
            <div class="mhs-card-body">
                <h5 class="font-weight-bold mb-3">
                    <i class="fas fa-folder-open text-warning mr-2"></i> Dokumen Laporan
                </h5>
                @include('partials.defense-result-documents', ['record' => $resultDefense])
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="mhs-card mb-3">
            <div class="mhs-card-body">
                <h5 class="font-weight-bold mb-3">Status</h5>
                <div class="mb-2">{!! $resultDefense->adminValidationStatusHtml() !!}</div>
                <p class="mb-0 small text-muted">
                    Dikirim: {{ $resultDefense->created_at?->format('d M Y H:i') ?? '-' }}
                </p>
            </div>
        </div>

        @if($resultDefense->scores->isNotEmpty())
            <div class="mhs-card mb-3">
                <div class="mhs-card-body">
                    <h5 class="font-weight-bold mb-3">Rekap Nilai Sidang</h5>
                    <p class="mb-2">
                        Nilai akhir:
                        <strong>{{ number_format($resultDefense->final_score, 2) }}</strong>
                        <span class="badge badge-primary ml-1">{{ $resultDefense->final_grade_letter }}</span>
                    </p>
                    <ul class="list-unstyled mb-0 small">
                        @foreach($resultDefense->scores as $score)
                            <li class="mb-2">
                                <strong>{{ $score->examiner->nama ?? 'Dosen' }}:</strong>
                                @if($score->isComplete())
                                    {{ number_format($score->score, 2) }}
                                @else
                                    <span class="text-muted">Belum diisi</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @if(($resultDefense->application->type ?? null) === 'mbkm')
            <span class="badge badge-info badge-lg">MBKM</span>
        @else
            <span class="badge badge-success badge-lg">Reguler</span>
        @endif
    </div>
</div>

@include('partials.document-preview-modal')
@endsection

@section('scripts')
@parent
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
});
</script>
@endsection
