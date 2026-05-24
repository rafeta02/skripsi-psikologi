@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, #16a085 0%, #27ae60 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <h2 class="mb-1 text-white font-weight-bold">
                        <i class="fas fa-edit mr-2"></i> Edit Pendaftaran Sidang
                    </h2>
                    <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                        Update informasi pendaftaran sidang skripsi/MBKM
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
                    <form action="{{ route('frontend.skripsi-defenses.update', $skripsiDefense->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <input type="hidden" name="application_id" value="{{ $skripsiDefense->application_id ?? '' }}">

                        <!-- Title -->
                        <div class="form-group">
                            <label class="form-label-modern required">Judul Skripsi/MBKM</label>
                            <input type="text" name="title" class="form-control-modern @error('title') is-invalid @enderror" value="{{ old('title', $skripsiDefense->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Abstract -->
                        <div class="form-group">
                            <label class="form-label-modern required">Abstrak</label>
                            <textarea name="abstract" class="form-control-modern @error('abstract') is-invalid @enderror" rows="5" required>{{ old('abstract', $skripsiDefense->abstract) }}</textarea>
                            <small class="form-text text-muted">Ringkasan penelitian skripsi/MBKM Anda</small>
                            @error('abstract')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Defense Document -->
                        <div class="form-group">
                            <label class="form-label-modern">Naskah Skripsi/MBKM Final (PDF)</label>
                            @if($skripsiDefense->defence_document)
                                <div class="mb-2">
                                    <a href="{{ $skripsiDefense->defence_document->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-file-pdf"></i> Lihat Dokumen Saat Ini
                                    </a>
                                </div>
                            @endif
                            <div class="custom-file">
                                <input type="file" name="defence_document" class="custom-file-input @error('defence_document') is-invalid @enderror" id="defenceDocument" accept=".pdf">
                                <label class="custom-file-label" for="defenceDocument">{{ $skripsiDefense->defence_document ? 'Ganti file...' : 'Pilih file...' }}</label>
                            </div>
                            <small class="form-text text-muted">Upload naskah baru jika ingin mengganti (Max: 20MB)</small>
                            @error('defence_document')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Approval Document -->
                        <div class="form-group">
                            <label class="form-label-modern">Surat Persetujuan Pembimbing (PDF)</label>
                            @if($skripsiDefense->approval_document)
                                <div class="mb-2">
                                    <a href="{{ $skripsiDefense->approval_document->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-file-pdf"></i> Lihat Dokumen Saat Ini
                                    </a>
                                </div>
                            @endif
                            <div class="custom-file">
                                <input type="file" name="approval_document" class="custom-file-input @error('approval_document') is-invalid @enderror" id="approvalDocument" accept=".pdf">
                                <label class="custom-file-label" for="approvalDocument">{{ $skripsiDefense->approval_document ? 'Ganti file...' : 'Pilih file...' }}</label>
                            </div>
                            <small class="form-text text-muted">Upload surat persetujuan baru (opsional)</small>
                            @error('approval_document')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Plagiarism Document -->
                        <div class="form-group">
                            <label class="form-label-modern">Hasil Plagiarism Check Final (PDF)</label>
                            @if($skripsiDefense->plagiarism_document)
                                <div class="mb-2">
                                    <a href="{{ $skripsiDefense->plagiarism_document->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-file-pdf"></i> Lihat Dokumen Saat Ini
                                    </a>
                                </div>
                            @endif
                            <div class="custom-file">
                                <input type="file" name="plagiarism_document" class="custom-file-input @error('plagiarism_document') is-invalid @enderror" id="plagiarismDocument" accept=".pdf">
                                <label class="custom-file-label" for="plagiarismDocument">{{ $skripsiDefense->plagiarism_document ? 'Ganti file...' : 'Pilih file...' }}</label>
                            </div>
                            <small class="form-text text-muted">Upload hasil plagiarism check baru (opsional)</small>
                            @error('plagiarism_document')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Revision Document (Optional) -->
                        <div class="form-group">
                            <label class="form-label-modern">Bukti Revisi Seminar (PDF)</label>
                            @if($skripsiDefense->revision_document)
                                <div class="mb-2">
                                    <a href="{{ $skripsiDefense->revision_document->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-file-pdf"></i> Lihat Dokumen Saat Ini
                                    </a>
                                </div>
                            @endif
                            <div class="custom-file">
                                <input type="file" name="revision_document" class="custom-file-input @error('revision_document') is-invalid @enderror" id="revisionDocument" accept=".pdf">
                                <label class="custom-file-label" for="revisionDocument">{{ $skripsiDefense->revision_document ? 'Ganti file...' : 'Pilih file...' }}</label>
                            </div>
                            <small class="form-text text-muted">Upload bukti revisi baru (opsional)</small>
                            @error('revision_document')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="form-group">
                            <label class="form-label-modern">Catatan Tambahan</label>
                            <textarea name="notes" class="form-control-modern @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $skripsiDefense->notes) }}</textarea>
                            <small class="form-text text-muted">Informasi tambahan (opsional)</small>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('frontend.skripsi-defenses.show', $skripsiDefense->id) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-success btn-lg">
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
    // Update file input labels
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName || 'Pilih file...');
    });
});
</script>
@endpush
@endsection
