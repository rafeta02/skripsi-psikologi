@php
    $applicationId = $applicationId ?? null;
@endphp

@if($applicationId)
    <div class="alert alert-success {{ $class ?? 'mb-0' }}">
        <h6 class="font-weight-bold mb-2">
            <i class="fas fa-certificate mr-1"></i> Dokumen Akhir
        </h6>
        <p class="mb-2 small">
            Proses skripsi sudah <strong>selesai (finalisasi admin)</strong>. Unduh dokumen berikut:
        </p>
        <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-sm btn-success" href="{{ route('pdf.transkrip-nilai', $applicationId) }}" target="_blank">
                <i class="fas fa-file-pdf mr-1"></i> Rekap Nilai
            </a>
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('mahasiswa.dokumen') }}">
                <i class="fas fa-folder mr-1"></i> Semua Dokumen
            </a>
        </div>
        @if(isset($finalScore) && $finalScore !== null)
            <p class="mb-0 mt-2 small text-muted">
                Nilai akhir sidang: <strong>{{ number_format($finalScore, 2) }}</strong>
                @if(!empty($finalGradeLetter))
                    ({{ $finalGradeLetter }})
                @endif
            </p>
        @endif
    </div>
@endif
