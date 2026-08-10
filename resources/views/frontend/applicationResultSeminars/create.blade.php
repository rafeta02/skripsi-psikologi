@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <h2 class="mb-1 text-white font-weight-bold">
                        <i class="fas fa-clipboard-check mr-2"></i> Laporan Hasil Review Kelayakan Proposal
                    </h2>
                    <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                        Laporkan hasil Review Kelayakan Proposal setelah review dilaksanakan
                    </p>
                </div>
            </div>
        </div>
    </div>

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
                        <form action="{{ route('frontend.application-result-seminars.store') }}" method="POST" enctype="multipart/form-data" id="resultSeminarForm">
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
                                    @foreach(\App\Models\ApplicationResultSeminar::RESULT_SELECT as $value => $label)
                                        <option value="{{ $value }}" {{ old('result') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('result')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label-modern">Catatan dari Reviewer</label>
                                <textarea name="note" class="form-control-modern @error('note') is-invalid @enderror" rows="4" placeholder="Masukan, saran, atau komentar dari reviewer">{{ old('note') }}</textarea>
                                @error('note')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="my-4">
                            <h5 class="font-weight-bold mb-3">Dokumen</h5>

                            <div class="form-group">
                                <label class="form-label-modern required">Form Review Kelayakan Proposal MBKM Riset (PDF)</label>
                                <div class="custom-file">
                                    <input type="file" name="form_document[]" class="custom-file-input @error('form_document') is-invalid @enderror @error('form_document.*') is-invalid @enderror" id="formDocument" accept=".pdf,application/pdf" multiple required>
                                    <label class="custom-file-label" for="formDocument">Pilih file...</label>
                                </div>
                                <small class="form-text text-muted">Form penilaian reviewer (Max: 10MB per file, boleh lebih dari 1 file)</small>
                                @error('form_document')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                @error('form_document.*')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label-modern required">Presensi Peserta (PDF)</label>
                                <div class="custom-file">
                                    <input type="file" name="attendance_document" class="custom-file-input @error('attendance_document') is-invalid @enderror" id="attendanceDocument" accept=".pdf,application/pdf" required>
                                    <label class="custom-file-label" for="attendanceDocument">Pilih file...</label>
                                </div>
                                <small class="form-text text-muted">Upload presensi peserta (Max: 10MB)</small>
                                @error('attendance_document')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label-modern">KRS Semester Terbaru (PDF) <span class="text-muted">(opsional)</span></label>
                                <div class="custom-file">
                                    <input type="file" name="krs_latest" class="custom-file-input @error('krs_latest') is-invalid @enderror" id="krsLatest" accept=".pdf,application/pdf">
                                    <label class="custom-file-label" for="krsLatest">Pilih file...</label>
                                </div>
                                <small class="form-text text-muted">Max: 5MB</small>
                                @error('krs_latest')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label-modern required">Dokumentasi Seminar (Screenshot atau Foto)</label>
                                <div class="custom-file">
                                    <input type="file" name="documentation[]" class="custom-file-input @error('documentation') is-invalid @enderror @error('documentation.*') is-invalid @enderror" id="documentation" accept="image/*,.jpg,.jpeg,.png,.webp" multiple required>
                                    <label class="custom-file-label" for="documentation">Pilih file...</label>
                                </div>
                                <small class="form-text text-muted">Screenshot atau foto kegiatan (Max: 5MB per file, boleh lebih dari 1 file)</small>
                                @error('documentation')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                @error('documentation.*')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label-modern">Tautan Record Meeting <span class="text-muted">(opsional jika online)</span></label>
                                <input type="url" name="meeting_recording_link" class="form-control-modern @error('meeting_recording_link') is-invalid @enderror" value="{{ old('meeting_recording_link') }}" placeholder="https://drive.google.com/... atau link Zoom/Meet recording">
                                <small class="form-text text-muted">Wajib diisi jika pelaksanaan dilakukan secara online</small>
                                @error('meeting_recording_link')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label-modern required">Naskah Proposal MBKM (KKN dan Skripsi Hasil Revisi) (PDF)</label>
                                <div class="custom-file">
                                    <input type="file" name="latest_script" class="custom-file-input @error('latest_script') is-invalid @enderror" id="latestScript" accept=".pdf,application/pdf" required>
                                    <label class="custom-file-label" for="latestScript">Pilih file...</label>
                                </div>
                                <small class="form-text text-muted">Naskah proposal hasil revisi (Max: 10MB)</small>
                                @error('latest_script')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('frontend.application-result-seminars.index') }}" class="btn btn-secondary">
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

        if (fileCount > 1) {
            fileName = fileCount + ' files selected';
        }

        $(this).next('.custom-file-label').html(fileName || 'Pilih file...');
    });
});
</script>
@endpush
@endsection
