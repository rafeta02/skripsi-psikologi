@extends('layouts.mahasiswa')

@push('styles')
<style>
    .section-divider {
        border-top: 2px solid #e9ecef;
        margin: 2rem 0 1.25rem;
        padding-top: 1.25rem;
    }
    .section-title { font-weight: 700; margin-bottom: 1rem; }
    .section-title.required-docs { color: #c0392b; }
    .section-title.optional-docs { color: #6c757d; }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, #27ae60 0%, #1e8449 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <h2 class="mb-1 text-white font-weight-bold">
                        <i class="fas fa-award mr-2"></i> Laporan Hasil Sidang
                    </h2>
                    <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                        Unggah hasil dan dokumen pelaksanaan sidang skripsi
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            @if(!$activeApplication)
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> Tidak ada aplikasi aktif.
                </div>
                <a href="{{ route('frontend.application-result-defenses.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            @else
                <div class="card-modern">
                    <div class="card-modern-body">
                        <form action="{{ route('frontend.application-result-defenses.store') }}" method="POST" enctype="multipart/form-data" id="defenseResultForm">
                            @csrf
                            <input type="hidden" name="application_id" value="{{ $activeApplication->id }}">

                            <div class="alert alert-info mb-4">
                                <h6 class="font-weight-bold mb-2">Informasi Aplikasi</h6>
                                <p class="mb-1"><strong>Tipe:</strong> {{ strtoupper($activeApplication->type) }}</p>
                                <p class="mb-0"><strong>Tahap:</strong> {{ ucfirst($activeApplication->stage) }}</p>
                            </div>

                            <h5 class="section-title">1. Hasil Sidang <span class="text-danger">*</span></h5>
                            <div class="form-group">
                                <select name="result" id="resultSelect" class="form-control-modern @error('result') is-invalid @enderror" required>
                                    <option value="">-- Pilih Hasil Sidang --</option>
                                    <option value="passed" {{ old('result') == 'passed' ? 'selected' : '' }}>Lulus</option>
                                    <option value="revision" {{ old('result') == 'revision' ? 'selected' : '' }}>Revisi</option>
                                    <option value="failed" {{ old('result') == 'failed' ? 'selected' : '' }}>Tidak Lulus</option>
                                </select>
                                @error('result')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="section-divider">
                                <h5 class="section-title required-docs">2. Dokumen Wajib</h5>
                            </div>

                            <div class="form-group">
                                <label class="form-label-modern required">Berita Acara Sidang (PDF)</label>
                                <input type="file" name="report_document[]" class="form-control-file @error('report_document') is-invalid @enderror" accept=".pdf" multiple required>
                                <small class="text-muted">Bisa lebih dari satu file. Maks. 10 MB per file.</small>
                                @error('report_document')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label-modern required">Daftar Hadir Sidang (PDF)</label>
                                <input type="file" name="attendance_document" class="form-control-file @error('attendance_document') is-invalid @enderror" accept=".pdf" required>
                                <small class="text-muted">Maks. 10 MB.</small>
                                @error('attendance_document')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div id="revisionDocsSection" class="d-none">
                                <div class="form-group">
                                    <label class="form-label-modern required">Lembar Persetujuan Revisi (PDF)</label>
                                    <input type="file" name="revision_approval_sheet[]" id="revisionApprovalSheet" class="form-control-file @error('revision_approval_sheet') is-invalid @enderror" accept=".pdf" multiple>
                                    <small class="text-muted">Wajib jika hasil sidang Revisi.</small>
                                    @error('revision_approval_sheet')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="section-divider">
                                <h5 class="section-title optional-docs">3. Catatan &amp; Revisi</h5>
                            </div>

                            <div class="form-group">
                                <label class="form-label-modern">Catatan / Saran Penguji</label>
                                <textarea name="note" class="form-control-modern @error('note') is-invalid @enderror" rows="3">{{ old('note') }}</textarea>
                                @error('note')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group d-none" id="revisionDeadlineField">
                                <label class="form-label-modern required">Batas Waktu Revisi</label>
                                <input type="date" name="revision_deadline" class="form-control-modern @error('revision_deadline') is-invalid @enderror" value="{{ old('revision_deadline') }}" min="{{ date('Y-m-d') }}">
                                @error('revision_deadline')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="section-divider">
                                <h5 class="section-title optional-docs">4. Dokumen Tambahan (Opsional)</h5>
                            </div>

                            <div class="form-group">
                                <label class="form-label-modern">Naskah Skripsi Final (PDF)</label>
                                <input type="file" name="latest_script" class="form-control-file @error('latest_script') is-invalid @enderror" accept=".pdf">
                                <small class="text-muted">Naskah setelah sidang. Maks. 20 MB.</small>
                                @error('latest_script')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label-modern">Form Penilaian Penguji (PDF)</label>
                                <input type="file" name="form_document[]" class="form-control-file @error('form_document') is-invalid @enderror" accept=".pdf" multiple>
                                <small class="text-muted">Form penilaian dari masing-masing penguji.</small>
                                @error('form_document')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label-modern">Dokumentasi Sidang (PDF/Gambar)</label>
                                <input type="file" name="documentation[]" class="form-control-file @error('documentation') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png" multiple>
                                <small class="text-muted">Foto atau lampiran dokumentasi pelaksanaan sidang.</small>
                                @error('documentation')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label-modern">Lembar Pengesahan / Sertifikat (PDF)</label>
                                <input type="file" name="certificate_document" class="form-control-file @error('certificate_document') is-invalid @enderror" accept=".pdf">
                                @error('certificate_document')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label-modern">Bukti Publikasi / Pernyataan Publikasi (PDF)</label>
                                <input type="file" name="publication_document" class="form-control-file @error('publication_document') is-invalid @enderror" accept=".pdf">
                                @error('publication_document')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('frontend.application-result-defenses.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-success btn-lg">
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
$(function () {
    function toggleRevisionFields() {
        const isRevision = $('#resultSelect').val() === 'revision';
        $('#revisionDeadlineField').toggleClass('d-none', !isRevision).find('input').prop('required', isRevision);
        $('#revisionDocsSection').toggleClass('d-none', !isRevision);
        $('#revisionApprovalSheet').prop('required', isRevision);
    }
    $('#resultSelect').on('change', toggleRevisionFields);
    toggleRevisionFields();
});
</script>
@endpush
@endsection
