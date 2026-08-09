@extends('layouts.mahasiswa')

@php
    $isMbkm = ($activeApplication->type ?? '') === 'mbkm';
@endphp

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, #16a085 0%, #27ae60 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <h2 class="mb-1 text-white font-weight-bold">
                        <i class="fas fa-graduation-cap mr-2"></i> Pendaftaran Sidang Skripsi
                    </h2>
                    <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                        Lengkapi seluruh dokumen persyaratan sidang (format PDF, kecuali disebutkan lain)
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card-modern">
                <div class="card-modern-body">
                    <form action="{{ route('frontend.skripsi-defenses.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="application_id" value="{{ $activeApplication->id ?? '' }}">

                        <h5 class="font-weight-bold mb-3 text-primary"><i class="fas fa-book mr-2"></i>Data Skripsi</h5>

                        <div class="form-group">
                            <label class="form-label-modern required" for="title">Judul Skripsi</label>
                            <input type="text" name="title" id="title" class="form-control-modern @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                            @error('title')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label-modern required" for="abstract">Abstrak</label>
                            <textarea name="abstract" id="abstract" class="form-control-modern @error('abstract') is-invalid @enderror" rows="5" required>{{ old('abstract') }}</textarea>
                            @error('abstract')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <hr class="my-4">

                        <h5 class="font-weight-bold mb-3 text-primary"><i class="fas fa-file-pdf mr-2"></i>Dokumen Utama</h5>

                        @include('frontend.skripsiDefenses.partials.file-input', [
                            'name' => 'defence_document',
                            'label' => 'Naskah Skripsi Final',
                            'required' => true,
                            'hint' => 'PDF, maks. 20MB',
                        ])

                        @include('frontend.skripsiDefenses.partials.file-input', [
                            'name' => 'plagiarism_report',
                            'label' => 'Laporan Plagiarisme Maksimal 20%',
                            'required' => true,
                        ])

                        @include('frontend.skripsiDefenses.partials.file-input', [
                            'name' => 'signed_scientific_publication_statement',
                            'label' => 'Surat Pernyataan Publikasi Ilmiah sudah ditanda tangani',
                            'required' => true,
                        ])

                        <hr class="my-4">

                        <h5 class="font-weight-bold mb-3 text-primary"><i class="fas fa-folder-open mr-2"></i>Dokumen Penelitian (boleh lebih dari 1 file)</h5>

                        @foreach([
                            'ethics_statement' => 'Pernyataan Etika Penelitian / Ethical clearance',
                            'research_instruments' => 'Instrumen Penelitian yang telah terisi, verbatim/ guideline (minimal 10 pcs)',
                            'data_collection_letter' => 'Surat Keterangan Telah Melaksanakan Penelitian / Data Responden / Informed consent',
                            'defense_approval_page' => 'Halaman Persetujuan Sidang',
                            'research_poster' => 'Poster Penelitian',
                            'supervision_logbook' => 'Tangkapan Layar Panel Konsultasi Siakad yang sudah divalidasi dosen pembimbing Minimal 12 Kali',
                        ] as $field => $label)
                            @include('frontend.skripsiDefenses.partials.file-input', [
                                'name' => $field,
                                'label' => $label,
                                'required' => true,
                                'multiple' => true,
                            ])
                        @endforeach

                        @include('frontend.skripsiDefenses.partials.file-input', [
                            'name' => 'research_module',
                            'label' => 'Modul Penelitian (Opsional untuk penelitian eksperimen dan terpisah dari naskah)',
                            'required' => false,
                            'multiple' => true,
                        ])

                        <hr class="my-4">

                        <h5 class="font-weight-bold mb-3 text-primary"><i class="fas fa-university mr-2"></i>Dokumen Administrasi</h5>

                        @include('frontend.skripsiDefenses.partials.file-input', [
                            'name' => 'spp_receipt',
                            'label' => 'Bukti Pembayaran SPP',
                            'required' => true,
                        ])

                        @include('frontend.skripsiDefenses.partials.file-input', [
                            'name' => 'krs_latest',
                            'label' => 'KRS Terbaru',
                            'required' => true,
                        ])

                        @include('frontend.skripsiDefenses.partials.eap-grade-select')

                        @include('frontend.skripsiDefenses.partials.file-input', [
                            'name' => 'eap_certificate',
                            'label' => 'Sertifikat EAP yang sudah dilegalisir',
                            'required' => true,
                        ])

                        @include('frontend.skripsiDefenses.partials.file-input', [
                            'name' => 'transcript',
                            'label' => 'Transkrip Nilai Sementara (tanpa nilai skripsi minimal 138 SKS)',
                            'required' => true,
                        ])

                        @include('frontend.skripsiDefenses.partials.file-input', [
                            'name' => 'siakad_supervisor_screenshot',
                            'label' => 'Screenshot Pembimbing Skripsi dari SIAKAD',
                            'required' => true,
                            'accept' => '.pdf,.jpg,.jpeg,.png',
                            'hint' => 'PDF atau gambar, maks. 10MB',
                        ])

                        @if($isMbkm)
                            <hr class="my-4">
                            <h5 class="font-weight-bold mb-3 text-info"><i class="fas fa-globe mr-2"></i>Dokumen MBKM (opsional)</h5>

                            @include('frontend.skripsiDefenses.partials.file-input', [
                                'name' => 'mbkm_recommendation_letter',
                                'label' => trans('cruds.skripsiDefense.fields.mbkm_recommendation_letter'),
                                'required' => false,
                            ])

                            @include('frontend.skripsiDefenses.partials.file-input', [
                                'name' => 'mbkm_report',
                                'label' => trans('cruds.skripsiDefense.fields.mbkm_report'),
                                'required' => false,
                                'multiple' => true,
                            ])
                        @endif

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('frontend.skripsi-defenses.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-paper-plane"></i> Kirim Pendaftaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('.custom-file-input').on('change', function() {
        let fileCount = this.files ? this.files.length : 0;
        let fileName = fileCount > 1 ? fileCount + ' file dipilih' : ($(this).val().split('\\').pop() || 'Pilih file...');
        $(this).next('.custom-file-label').html(fileName);
    });
});
</script>
@endpush
@endsection
