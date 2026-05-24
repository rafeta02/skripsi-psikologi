@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, #16a085 0%, #27ae60 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <h2 class="mb-1 text-white font-weight-bold">
                        <i class="fas fa-graduation-cap mr-2"></i> Pendaftaran Sidang Skripsi
                    </h2>
                    <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                        Lengkapi form berikut untuk mendaftar sidang skripsi/MBKM
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-modern">
                <div class="card-modern-body">
                    <form action="{{ route('frontend.skripsi-defenses.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <input type="hidden" name="application_id" value="{{ $activeApplication->id ?? '' }}">

                        <!-- Application Info -->
                        @if($activeApplication)
                            <div class="alert alert-info mb-4">
                                <h6 class="font-weight-bold mb-2">Informasi Aplikasi:</h6>
                                <p class="mb-1"><strong>Tipe:</strong> {{ strtoupper($activeApplication->type) }}</p>
                                <p class="mb-0"><strong>Tahap Sebelumnya:</strong> {{ ucfirst($activeApplication->stage) }}</p>
                            </div>
                        @endif

                        <!-- Title -->
                        <div class="form-group">
                            <label class="form-label-modern required">Judul Skripsi/MBKM</label>
                            <input type="text" name="title" class="form-control-modern @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Abstract -->
                        <div class="form-group">
                            <label class="form-label-modern required">Abstrak</label>
                            <textarea name="abstract" class="form-control-modern @error('abstract') is-invalid @enderror" rows="5" required>{{ old('abstract') }}</textarea>
                            <small class="form-text text-muted">Ringkasan penelitian skripsi/MBKM Anda</small>
                            @error('abstract')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Defense Document -->
                        <div class="form-group">
                            <label class="form-label-modern required">Naskah Skripsi/MBKM Final (PDF)</label>
                            <div class="custom-file">
                                <input type="file" name="defence_document" class="custom-file-input @error('defence_document') is-invalid @enderror" id="defenceDocument" accept=".pdf" required>
                                <label class="custom-file-label" for="defenceDocument">Pilih file...</label>
                            </div>
                            <small class="form-text text-muted">Upload naskah skripsi/MBKM yang sudah diperbaiki (Max: 20MB)</small>
                            @error('defence_document')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Approval Document -->
                        <div class="form-group">
                            <label class="form-label-modern required">Surat Persetujuan Pembimbing (PDF)</label>
                            <div class="custom-file">
                                <input type="file" name="approval_document" class="custom-file-input @error('approval_document') is-invalid @enderror" id="approvalDocument" accept=".pdf" required>
                                <label class="custom-file-label" for="approvalDocument">Pilih file...</label>
                            </div>
                            <small class="form-text text-muted">Upload surat persetujuan untuk sidang (Max: 10MB)</small>
                            @error('approval_document')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Plagiarism Document -->
                        <div class="form-group">
                            <label class="form-label-modern required">Hasil Plagiarism Check Final (PDF)</label>
                            <div class="custom-file">
                                <input type="file" name="plagiarism_document" class="custom-file-input @error('plagiarism_document') is-invalid @enderror" id="plagiarismDocument" accept=".pdf" required>
                                <label class="custom-file-label" for="plagiarismDocument">Pilih file...</label>
                            </div>
                            <small class="form-text text-muted">Upload hasil pengecekan plagiarisme naskah final (Max: 10MB)</small>
                            @error('plagiarism_document')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Revision Document (Optional) -->
                        <div class="form-group">
                            <label class="form-label-modern">Bukti Revisi Seminar (PDF)</label>
                            <div class="custom-file">
                                <input type="file" name="revision_document" class="custom-file-input @error('revision_document') is-invalid @enderror" id="revisionDocument" accept=".pdf">
                                <label class="custom-file-label" for="revisionDocument">Pilih file...</label>
                            </div>
                            <small class="form-text text-muted">Upload bukti bahwa revisi dari seminar sudah selesai (opsional, Max: 10MB)</small>
                            @error('revision_document')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="form-group">
                            <label class="form-label-modern">Catatan Tambahan</label>
                            <textarea name="notes" class="form-control-modern @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                            <small class="form-text text-muted">Informasi tambahan (opsional)</small>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
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
    // Update file input labels
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName || 'Pilih file...');
    });
});
</script>
@endpush
@endsection
