@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, #e67e22 0%, #d35400 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <h2 class="mb-1 text-white font-weight-bold">
                        <i class="fas fa-flag mr-2"></i> Buat Laporan Masalah
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

                            <!-- Title -->
                            <div class="form-group">
                                <label class="form-label-modern required">Judul Laporan</label>
                                <input type="text" name="title" class="form-control-modern @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                                <small class="form-text text-muted">Ringkasan singkat masalah Anda</small>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Priority -->
                            <div class="form-group">
                                <label class="form-label-modern required">Prioritas</label>
                                <select name="priority" class="form-control-modern @error('priority') is-invalid @enderror" required>
                                    <option value="">-- Pilih Prioritas --</option>
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Rendah</option>
                                    <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Sedang</option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Tinggi</option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="form-group">
                                <label class="form-label-modern required">Deskripsi Masalah</label>
                                <textarea name="description" class="form-control-modern @error('description') is-invalid @enderror" rows="6" required>{{ old('description') }}</textarea>
                                <small class="form-text text-muted">Jelaskan masalah Anda secara detail</small>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Evidence Documents -->
                            <div class="form-group">
                                <label class="form-label-modern">Bukti Pendukung (PDF/Gambar)</label>
                                <div class="custom-file">
                                    <input type="file" name="evidence_document[]" class="custom-file-input @error('evidence_document') is-invalid @enderror" id="evidenceDocument" accept=".pdf,.jpg,.jpeg,.png" multiple>
                                    <label class="custom-file-label" for="evidenceDocument">Pilih file...</label>
                                </div>
                                <small class="form-text text-muted">Upload bukti pendukung jika ada (Max: 10MB per file, multiple files allowed)</small>
                                @error('evidence_document')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
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
        if (fileCount > 1) fileName = fileCount + ' files selected';
        $(this).next('.custom-file-label').html(fileName || 'Pilih file...');
    });
});
</script>
@endpush
@endsection
