@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, #3498db 0%, #2ecc71 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <h2 class="mb-1 text-white font-weight-bold">
                        <i class="fas fa-chalkboard-teacher mr-2"></i> Pendaftaran Review Kelayakan Proposal (MBKM)
                    </h2>
                    <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                        Form kelompok (ketua) — lanjutan dari MbkmRegistration. Anggota tidak perlu mengisi form ini.
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
                    <form action="{{ route('frontend.mbkm-seminars.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <input type="hidden" name="application_id" value="{{ $activeApplication->id ?? '' }}">

                        @if(!empty($registration))
                            <div class="alert alert-info mb-4">
                                <strong><i class="fas fa-users mr-1"></i> Form kelompok</strong>
                                — 1 pengajuan untuk seluruh anggota.
                                <ul class="mb-0 mt-2 small">
                                    <li>Judul kegiatan MBKM: <strong>{{ $registration->title_mbkm ?? '-' }}</strong></li>
                                    <li>Anggota:
                                        {{ $registration->groupMembers->map(fn ($m) => ($m->mahasiswa->nama ?? '-') . ' (' . ($m->role ?? 'anggota') . ')')->implode(', ') }}
                                    </li>
                                </ul>
                            </div>
                        @endif

                        <!-- Title -->
                        <div class="form-group">
                            <label class="form-label-modern required">Judul MBKM / Proposal</label>
                            <input type="text" name="title" class="form-control-modern @error('title') is-invalid @enderror"
                                   value="{{ old('title', $registration->title_mbkm ?? '') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label-modern">Lokasi KKN</label>
                            <input type="text" name="lokasi_kkn" class="form-control-modern @error('lokasi_kkn') is-invalid @enderror"
                                   value="{{ old('lokasi_kkn', $registration->lokasi_kkn ?? '') }}" maxlength="255"
                                   placeholder="Contoh: Desa X, Kecamatan Y">
                            @error('lokasi_kkn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label class="form-label-modern">Deskripsi / Abstrak</label>
                            <textarea name="description" class="form-control-modern @error('description') is-invalid @enderror" rows="5">{{ old('description') }}</textarea>
                            <small class="form-text text-muted">Jelaskan secara singkat tentang MBKM Anda</small>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Proposal Document -->
                        <div class="form-group">
                            <label class="form-label-modern required">Dokumen Proposal MBKM (PDF)</label>
                            <div class="custom-file">
                                <input type="file" name="proposal_document" class="custom-file-input @error('proposal_document') is-invalid @enderror" id="proposalDocument" accept=".pdf" required>
                                <label class="custom-file-label" for="proposalDocument">Pilih file...</label>
                            </div>
                            <small class="form-text text-muted">Upload dokumen proposal MBKM dalam format PDF (Max: 10MB)</small>
                            @error('proposal_document')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Approval Document -->
                        <div class="form-group">
                            <label class="form-label-modern required">Dokumen Persetujuan Pembimbing (PDF)</label>
                            <div class="custom-file">
                                <input type="file" name="approval_document" class="custom-file-input @error('approval_document') is-invalid @enderror" id="approvalDocument" accept=".pdf" required>
                                <label class="custom-file-label" for="approvalDocument">Pilih file...</label>
                            </div>
                            <small class="form-text text-muted">Upload surat persetujuan dari pembimbing (Max: 10MB)</small>
                            @error('approval_document')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Plagiarism Check Document -->
                        <div class="form-group">
                            <label class="form-label-modern required">Hasil Plagiarism Check (PDF)</label>
                            <div class="custom-file">
                                <input type="file" name="plagiarism_document" class="custom-file-input @error('plagiarism_document') is-invalid @enderror" id="plagiarismDocument" accept=".pdf" required>
                                <label class="custom-file-label" for="plagiarismDocument">Pilih file...</label>
                            </div>
                            <small class="form-text text-muted">Upload hasil pengecekan plagiarisme (Max: 10MB)</small>
                            @error('plagiarism_document')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="form-group">
                            <label class="form-label-modern">Catatan Tambahan</label>
                            <textarea name="notes" class="form-control-modern @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                            <small class="form-text text-muted">Catatan atau informasi tambahan (opsional)</small>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('frontend.mbkm-seminars.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
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
    // Update file input label when file selected
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName || 'Pilih file...');
    });
});
</script>
@endpush
@endsection
