@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, #e67e22 0%, #d35400 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <h2 class="mb-1 text-white font-weight-bold">
                        <i class="fas fa-flag mr-2"></i> Buat Laporan Kendala
                    </h2>
                    <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                        Laporkan kendala atau masalah terkait proses skripsi/MBKM
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="row">
        <div class="col-lg-12">
            @if(!$activeApplication)
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> <strong>Perhatian!</strong>
                    <p class="mb-0 mt-2">Tidak ada aplikasi aktif. Silakan daftar terlebih dahulu.</p>
                </div>
                <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                </a>
            @else
                <div class="card-modern">
                    <div class="card-modern-body">
                        <form action="{{ route('frontend.application-reports.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="application_id" value="{{ $activeApplication->id }}">

                            <div class="form-group">
                                <label class="form-label-modern">Aplikasi</label>
                                <input type="text" class="form-control-modern" value="{{ strtoupper($activeApplication->type) }} — {{ ucfirst($activeApplication->stage) }}" readonly>
                            </div>

                            <div class="form-group">
                                <label class="form-label-modern required" for="period">Periode Laporan</label>
                                <select name="period" id="period" class="form-control-modern @error('period') is-invalid @enderror">
                                    <option value="">-- Pilih periode (opsional) --</option>
                                    @foreach(App\Models\ApplicationReport::PERIOD_SELECT as $key => $label)
                                        <option value="{{ $key }}" {{ old('period') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('period')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label-modern required" for="report_text">Uraian Kendala</label>
                                <textarea name="report_text" id="report_text" rows="8" class="form-control-modern @error('report_text') is-invalid @enderror" required placeholder="Jelaskan kendala atau masalah yang Anda alami secara detail">{{ old('report_text') }}</textarea>
                                <small class="form-text text-muted">Wajib diisi — jelaskan masalah, dampak, dan bantuan yang dibutuhkan</small>
                                @error('report_text')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label-modern" for="report_document">Bukti Pendukung (PDF/Gambar)</label>
                                <div class="custom-file">
                                    <input type="file" name="report_document[]" class="custom-file-input @error('report_document') is-invalid @enderror @error('report_document.*') is-invalid @enderror" id="report_document" accept=".pdf,.jpg,.jpeg,.png" multiple>
                                    <label class="custom-file-label" for="report_document">Pilih file...</label>
                                </div>
                                <small class="form-text text-muted">Opsional. Maks. 10MB per file</small>
                                @error('report_document')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                @error('report_document.*')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('frontend.application-reports.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane"></i> Kirim Laporan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        let fileCount = $(this)[0].files.length;
        if (fileCount > 1) fileName = fileCount + ' file dipilih';
        $(this).next('.custom-file-label').html(fileName || 'Pilih file...');
    });
});
</script>
@endpush
@endsection
