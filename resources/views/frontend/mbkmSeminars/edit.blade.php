@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, #3498db 0%, #2ecc71 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <h2 class="mb-1 text-white font-weight-bold">
                        <i class="fas fa-edit mr-2"></i> Edit Pendaftaran Seminar MBKM
                    </h2>
                    <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                        Update informasi pendaftaran seminar MBKM
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
                    <form action="{{ route('frontend.mbkm-seminars.update', $mbkmSeminar->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <input type="hidden" name="application_id" value="{{ $mbkmSeminar->application_id ?? '' }}">

                        <!-- Title -->
                        <div class="form-group">
                            <label class="form-label-modern required">Judul MBKM</label>
                            <input type="text" name="title" class="form-control-modern @error('title') is-invalid @enderror" value="{{ old('title', $mbkmSeminar->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label class="form-label-modern">Deskripsi / Abstrak</label>
                            <textarea name="description" class="form-control-modern @error('description') is-invalid @enderror" rows="5">{{ old('description', $mbkmSeminar->description) }}</textarea>
                            <small class="form-text text-muted">Jelaskan secara singkat tentang MBKM Anda</small>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Proposal Document -->
                        <div class="form-group">
                            <label class="form-label-modern">Dokumen Proposal MBKM (PDF)</label>
                            @if($mbkmSeminar->proposal_document)
                                <div class="mb-2">
                                    <a href="{{ $mbkmSeminar->proposal_document->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-file-pdf"></i> Lihat Dokumen Saat Ini
                                    </a>
                                </div>
                            @endif
                            <div class="custom-file">
                                <input type="file" name="proposal_document" class="custom-file-input @error('proposal_document') is-invalid @enderror" id="proposalDocument" accept=".pdf">
                                <label class="custom-file-label" for="proposalDocument">{{ $mbkmSeminar->proposal_document ? 'Ganti file...' : 'Pilih file...' }}</label>
                            </div>
                            <small class="form-text text-muted">Upload dokumen proposal baru jika ingin mengganti (Max: 10MB)</small>
                            @error('proposal_document')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Approval Document -->
                        <div class="form-group">
                            <label class="form-label-modern">Dokumen Persetujuan Pembimbing (PDF)</label>
                            @if($mbkmSeminar->approval_document)
                                <div class="mb-2">
                                    <a href="{{ $mbkmSeminar->approval_document->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-file-pdf"></i> Lihat Dokumen Saat Ini
                                    </a>
                                </div>
                            @endif
                            <div class="custom-file">
                                <input type="file" name="approval_document" class="custom-file-input @error('approval_document') is-invalid @enderror" id="approvalDocument" accept=".pdf">
                                <label class="custom-file-label" for="approvalDocument">{{ $mbkmSeminar->approval_document ? 'Ganti file...' : 'Pilih file...' }}</label>
                            </div>
                            <small class="form-text text-muted">Upload surat persetujuan dari pembimbing (opsional)</small>
                            @error('approval_document')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Plagiarism Check Document -->
                        <div class="form-group">
                            <label class="form-label-modern">Hasil Plagiarism Check (PDF)</label>
                            @if($mbkmSeminar->plagiarism_document)
                                <div class="mb-2">
                                    <a href="{{ $mbkmSeminar->plagiarism_document->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-file-pdf"></i> Lihat Dokumen Saat Ini
                                    </a>
                                </div>
                            @endif
                            <div class="custom-file">
                                <input type="file" name="plagiarism_document" class="custom-file-input @error('plagiarism_document') is-invalid @enderror" id="plagiarismDocument" accept=".pdf">
                                <label class="custom-file-label" for="plagiarismDocument">{{ $mbkmSeminar->plagiarism_document ? 'Ganti file...' : 'Pilih file...' }}</label>
                            </div>
                            <small class="form-text text-muted">Upload hasil pengecekan plagiarisme (opsional)</small>
                            @error('plagiarism_document')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="form-group">
                            <label class="form-label-modern">Catatan Tambahan</label>
                            <textarea name="notes" class="form-control-modern @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $mbkmSeminar->notes) }}</textarea>
                            <small class="form-text text-muted">Catatan atau informasi tambahan (opsional)</small>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('frontend.mbkm-seminars.show', $mbkmSeminar->id) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Simpan Perubahan
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
    // Update file input label when file selected
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName || 'Pilih file...');
    });
});
</script>
@endpush
@endsection
