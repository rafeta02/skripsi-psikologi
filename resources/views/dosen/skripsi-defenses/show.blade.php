@extends('layouts.dosen')

@section('content')
@php
    $mahasiswa = $skripsiDefense->application?->mahasiswa;
@endphp

@include('partials.dosen.page-header', [
    'title' => 'Naskah Sidang Skripsi',
    'subtitle' => 'Baca naskah mahasiswa bimbingan/pengujian sebelum pelaksanaan sidang',
])

<div class="mb-3">
    <a href="{{ route('dosen.skripsi-defenses.index') }}" class="btn btn-sm btn-outline-secondary">
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
                        <label class="text-muted mb-0">Jenjang</label>
                        <p class="font-weight-semibold mb-0">{{ $mahasiswa->jenjang->name ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mhs-card mb-3">
            <div class="mhs-card-body">
                <h5 class="font-weight-bold mb-3">
                    <i class="fas fa-book text-success mr-2"></i> Judul & Abstrak
                </h5>
                <div class="mb-3">
                    <label class="text-muted mb-1">Judul</label>
                    <p class="font-weight-semibold mb-0">{{ $skripsiDefense->title ?? '-' }}</p>
                </div>
                @if($skripsiDefense->title_en)
                    <div class="mb-3">
                        <label class="text-muted mb-1">Judul (English)</label>
                        <p class="font-weight-semibold mb-0">{{ $skripsiDefense->title_en }}</p>
                    </div>
                @endif
                @if($skripsiDefense->abstract)
                    <div class="mb-0">
                        <label class="text-muted mb-1">Abstrak</label>
                        <p class="text-justify mb-0">{{ $skripsiDefense->abstract }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="mhs-card">
            <div class="mhs-card-body">
                <h5 class="font-weight-bold mb-3">
                    <i class="fas fa-folder-open text-warning mr-2"></i> Dokumen Sidang
                </h5>
                <div class="alert alert-info py-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    Silakan baca naskah skripsi final dan dokumen pendukung sebelum pelaksanaan sidang.
                </div>
                @include('dosen.skripsi-defenses.partials.documents', ['skripsiDefense' => $skripsiDefense])
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="mhs-card mb-3">
            <div class="mhs-card-body">
                <h5 class="font-weight-bold mb-3">Peran & Status</h5>
                @if($dosenRole)
                    <p class="mb-2">
                        <span class="badge badge-primary badge-lg">{{ $dosenRole }}</span>
                    </p>
                @endif
                <p class="mb-0">
                    <span class="badge badge-success">Pendaftaran sidang disetujui admin</span>
                </p>
            </div>
        </div>

        <div class="mhs-card mb-3">
            <div class="mhs-card-body">
                <h5 class="font-weight-bold mb-3">Dewan Penguji</h5>
                <ul class="list-unstyled mb-0">
                    @if($skripsiDefense->examiner1?->dosen)
                        <li class="mb-2">
                            <strong>Penguji 1:</strong><br>
                            {{ $skripsiDefense->examiner1->dosen->nama }}
                        </li>
                    @endif
                    @if($skripsiDefense->examiner2?->dosen)
                        <li class="mb-0">
                            <strong>Penguji 2:</strong><br>
                            {{ $skripsiDefense->examiner2->dosen->nama }}
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        @if($schedule)
            <div class="mhs-card mb-3">
                <div class="mhs-card-body">
                    <h5 class="font-weight-bold mb-3">
                        <i class="fas fa-calendar-alt text-info mr-2"></i> Jadwal Sidang
                    </h5>
                    @if($scheduleVerified)
                        <div class="mb-2">
                            <label class="text-muted mb-0">Waktu</label>
                            <p class="font-weight-semibold mb-0">{{ $schedule->waktu ?? '-' }}</p>
                        </div>
                        <div class="mb-2">
                            <label class="text-muted mb-0">Ruang</label>
                            <p class="font-weight-semibold mb-0">{{ $schedule->ruang->name ?? '-' }}</p>
                        </div>
                        <span class="badge badge-success">Jadwal diverifikasi admin</span>
                    @else
                        <p class="text-muted mb-0 small">Jadwal sidang belum diverifikasi admin.</p>
                    @endif
                </div>
            </div>
        @endif

        <div class="mhs-card">
            <div class="mhs-card-body">
                <h5 class="font-weight-bold mb-3">Jalur</h5>
                @if(($skripsiDefense->application->type ?? null) === 'mbkm')
                    <span class="badge badge-info badge-lg">MBKM</span>
                @else
                    <span class="badge badge-success badge-lg">Reguler</span>
                @endif
            </div>
        </div>

        @if($defenseHeld ?? false)
            <div class="mhs-card mt-3">
                <div class="mhs-card-body">
                    <h5 class="font-weight-bold mb-3">
                        <i class="fas fa-star text-warning mr-2"></i> Penilaian Sidang
                    </h5>
                    @if($canScore && $scoreAssignment)
                        @if($scoreAssignment->isComplete())
                            <p class="mb-2 small text-muted">
                                Nilai Anda: <strong>{{ number_format($scoreAssignment->score, 2) }}</strong>
                            </p>
                            <a href="{{ route('dosen.application-scores.edit', $scoreAssignment) }}" class="btn btn-sm btn-outline-primary btn-block">
                                <i class="fas fa-edit"></i> Ubah Penilaian
                            </a>
                        @else
                            <p class="mb-2 small text-muted">Sidang sudah dilaksanakan. Silakan isi penilaian Anda.</p>
                            <a href="{{ route('dosen.application-scores.edit', $scoreAssignment) }}" class="btn btn-sm btn-warning btn-block">
                                <i class="fas fa-star"></i> Isi Nilai Sidang
                            </a>
                        @endif
                    @elseif($canScore)
                        <a href="{{ route('dosen.scores') }}" class="btn btn-sm btn-warning btn-block">
                            <i class="fas fa-star"></i> Buka Penilaian Sidang
                        </a>
                    @else
                        <p class="mb-0 small text-muted">Penilaian tidak tersedia untuk sidang ini.</p>
                    @endif
                </div>
            </div>
        @endif

        @if($canViewResultReport ?? false)
            <div class="mhs-card mt-3">
                <div class="mhs-card-body">
                    <h5 class="font-weight-bold mb-3">
                        <i class="fas fa-file-alt text-primary mr-2"></i> Laporan Hasil Sidang
                    </h5>
                    <p class="mb-2 small text-muted">Mahasiswa sudah mengirim laporan hasil sidang.</p>
                    <a href="{{ route('dosen.application-result-defenses.show', $resultDefense->id) }}" class="btn btn-sm btn-primary btn-block">
                        <i class="fas fa-eye"></i> Lihat Laporan Hasil Sidang
                    </a>
                </div>
            </div>
        @endif
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

@section('scripts')
@parent
<script>
$(function () {
    $('.preview-doc').on('click', function () {
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
