@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, #2980b9 0%, #1a5276 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <h2 class="mb-1 text-white font-weight-bold">
                        <i class="fas fa-clipboard-check mr-2"></i> Laporan Hasil Review Proposal
                    </h2>
                    <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                        Skripsi Reguler — laporkan hasil review kelayakan proposal
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
                <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                </a>
            @else
                <div class="card-modern">
                    <div class="card-modern-body">
                        <form action="{{ route('frontend.application-result-reviews.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="application_id" value="{{ $activeApplication->id }}">

                            <div class="alert alert-info mb-4">
                                <h6 class="font-weight-bold mb-2">Informasi Aplikasi:</h6>
                                <p class="mb-1"><strong>Tipe:</strong> {{ strtoupper($activeApplication->type) }}</p>
                                <p class="mb-0"><strong>Tahap:</strong> {{ ucfirst($activeApplication->stage) }}</p>
                            </div>

                            <div class="form-group">
                                <label class="form-label-modern required">Hasil Review</label>
                                <select name="result" class="form-control-modern @error('result') is-invalid @enderror" required>
                                    <option value="">-- Pilih Hasil Review --</option>
                                    @foreach(\App\Models\ApplicationResultReview::RESULT_SELECT as $value => $label)
                                        <option value="{{ $value }}" {{ old('result') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('result')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <hr class="my-4">
                            <h5 class="font-weight-bold mb-3">Dokumen Wajib</h5>

                            <div class="form-group">
                                <label class="form-label-modern required">Form Umpan Balik Dari 2 Reviewer (PDF)</label>
                                <div class="mb-3">
                                    <label class="small font-weight-semibold text-muted" for="reviewerFeedbackForm1">Reviewer 1</label>
                                    <div class="custom-file">
                                        <input type="file" name="reviewer_feedback_form_1" class="custom-file-input @error('reviewer_feedback_form_1') is-invalid @enderror" id="reviewerFeedbackForm1" accept=".pdf,application/pdf" required>
                                        <label class="custom-file-label" for="reviewerFeedbackForm1">Pilih file reviewer 1...</label>
                                    </div>
                                    @error('reviewer_feedback_form_1')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div>
                                    <label class="small font-weight-semibold text-muted" for="reviewerFeedbackForm2">Reviewer 2</label>
                                    <div class="custom-file">
                                        <input type="file" name="reviewer_feedback_form_2" class="custom-file-input @error('reviewer_feedback_form_2') is-invalid @enderror" id="reviewerFeedbackForm2" accept=".pdf,application/pdf" required>
                                        <label class="custom-file-label" for="reviewerFeedbackForm2">Pilih file reviewer 2...</label>
                                    </div>
                                    @error('reviewer_feedback_form_2')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <small class="form-text text-muted">Unggah form umpan balik masing-masing reviewer (Max: 10MB per file)</small>
                            </div>

                            <div class="form-group">
                                <label class="form-label-modern required">Surat Permohonan Review Proposal (PDF)</label>
                                <div class="custom-file">
                                    <input type="file" name="application_letter" class="custom-file-input @error('application_letter') is-invalid @enderror" id="applicationLetter" accept=".pdf,application/pdf" required>
                                    <label class="custom-file-label" for="applicationLetter">Pilih file...</label>
                                </div>
                                @error('application_letter')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label-modern required">Berita Acara Review Proposal (PDF)</label>
                                <div class="custom-file">
                                    <input type="file" name="minutes_document" class="custom-file-input @error('minutes_document') is-invalid @enderror" id="minutesDocument" accept=".pdf,application/pdf" required>
                                    <label class="custom-file-label" for="minutesDocument">Pilih file...</label>
                                </div>
                                @error('minutes_document')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label-modern required">Naskah Proposal (PDF)</label>
                                <div class="custom-file">
                                    <input type="file" name="proposal_manuscript" class="custom-file-input @error('proposal_manuscript') is-invalid @enderror" id="proposalManuscript" accept=".pdf,application/pdf" required>
                                    <label class="custom-file-label" for="proposalManuscript">Pilih file...</label>
                                </div>
                                @error('proposal_manuscript')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label-modern required">Lembar Etika Penelitian (PDF)</label>
                                <div class="custom-file">
                                    <input type="file" name="research_ethics_form" class="custom-file-input @error('research_ethics_form') is-invalid @enderror" id="researchEthicsForm" accept=".pdf,application/pdf" required>
                                    <label class="custom-file-label" for="researchEthicsForm">Pilih file...</label>
                                </div>
                                @error('research_ethics_form')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('frontend.application-result-reviews.index') }}" class="btn btn-secondary">
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
        let fileCount = $(this)[0].files.length;
        let fileName = fileCount > 1 ? fileCount + ' file dipilih' : $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName || 'Pilih file...');
    });
});
</script>
@endpush
@endsection
