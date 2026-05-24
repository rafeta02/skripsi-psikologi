@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <h2 class="mb-1 text-white font-weight-bold">
                        <i class="fas fa-award mr-2"></i> Laporan Hasil Sidang Final
                    </h2>
                    <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                        Laporkan hasil sidang skripsi/MBKM Anda
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
                        <form action="{{ route('frontend.application-result-defenses.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <input type="hidden" name="application_id" value="{{ $activeApplication->id }}">

                            <!-- Application Info -->
                            <div class="alert alert-info mb-4">
                                <h6 class="font-weight-bold mb-2">Informasi Aplikasi:</h6>
                                <p class="mb-1"><strong>Tipe:</strong> {{ strtoupper($activeApplication->type) }}</p>
                                <p class="mb-0"><strong>Tahap:</strong> {{ ucfirst($activeApplication->stage) }}</p>
                            </div>

                            <!-- Result Status -->
                            <div class="form-group">
                                <label class="form-label-modern required">Hasil Sidang</label>
                                <select name="result" class="form-control-modern @error('result') is-invalid @enderror" required>
                                    <option value="">-- Pilih Hasil Sidang --</option>
                                    <option value="passed_with_honor" {{ old('result') == 'passed_with_honor' ? 'selected' : '' }}>🏆 Lulus Cumlaude</option>
                                    <option value="passed" {{ old('result') == 'passed' ? 'selected' : '' }}>✅ Lulus</option>
                                    <option value="passed_with_revision" {{ old('result') == 'passed_with_revision' ? 'selected' : '' }}>📝 Lulus dengan Revisi</option>
                                    <option value="failed" {{ old('result') == 'failed' ? 'selected' : '' }}>❌ Tidak Lulus</option>
                                </select>
                                @error('result')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Grade (Score) -->
                            <div class="form-group">
                                <label class="form-label-modern">Nilai Akhir</label>
                                <input type="number" name="grade" class="form-control-modern @error('grade') is-invalid @enderror" value="{{ old('grade') }}" min="0" max="100" step="0.01">
                                <small class="form-text text-muted">Nilai akhir sidang (0-100)</small>
                                @error('grade')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Revision Deadline (conditional) -->
                            <div class="form-group" id="revisionDeadlineField" style="display: none;">
                                <label class="form-label-modern">Tenggat Waktu Revisi</label>
                                <input type="date" name="revision_deadline" class="form-control-modern @error('revision_deadline') is-invalid @enderror" value="{{ old('revision_deadline') }}" min="{{ date('Y-m-d') }}">
                                <small class="form-text text-muted">Batas waktu untuk menyelesaikan revisi</small>
                                @error('revision_deadline')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div class="form-group">
                                <label class="form-label-modern">Catatan Penguji</label>
                                <textarea name="notes" class="form-control-modern @error('notes') is-invalid @enderror" rows="4">{{ old('notes') }}</textarea>
                                <small class="form-text text-muted">Komentar atau saran dari tim penguji</small>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Report Documents -->
                            <div class="form-group">
                                <label class="form-label-modern">Berita Acara Sidang (PDF)</label>
                                <div class="custom-file">
                                    <input type="file" name="report_document[]" class="custom-file-input @error('report_document') is-invalid @enderror" id="reportDocument" accept=".pdf" multiple>
                                    <label class="custom-file-label" for="reportDocument">Pilih file...</label>
                                </div>
                                <small class="form-text text-muted">Upload berita acara sidang (Max: 10MB per file, multiple files allowed)</small>
                                @error('report_document')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Attendance Document -->
                            <div class="form-group">
                                <label class="form-label-modern">Daftar Hadir Sidang (PDF)</label>
                                <div class="custom-file">
                                    <input type="file" name="attendance_document" class="custom-file-input @error('attendance_document') is-invalid @enderror" id="attendanceDocument" accept=".pdf">
                                    <label class="custom-file-label" for="attendanceDocument">Pilih file...</label>
                                </div>
                                <small class="form-text text-muted">Upload daftar hadir (opsional, Max: 10MB)</small>
                                @error('attendance_document')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Form Documents -->
                            <div class="form-group">
                                <label class="form-label-modern">Form Penilaian Penguji (PDF)</label>
                                <div class="custom-file">
                                    <input type="file" name="form_document[]" class="custom-file-input @error('form_document') is-invalid @enderror" id="formDocument" accept=".pdf" multiple>
                                    <label class="custom-file-label" for="formDocument">Pilih file...</label>
                                </div>
                                <small class="form-text text-muted">Upload form penilaian dari penguji (Max: 10MB per file, multiple files allowed)</small>
                                @error('form_document')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-secondary">
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
$(document).ready(function() {
    // Update file input labels
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        let fileCount = $(this)[0].files.length;
        
        if (fileCount > 1) {
            fileName = fileCount + ' files selected';
        }
        
        $(this).next('.custom-file-label').html(fileName || 'Pilih file...');
    });
    
    // Show/hide revision deadline based on result
    $('select[name="result"]').on('change', function() {
        if ($(this).val() === 'passed_with_revision') {
            $('#revisionDeadlineField').show().find('input').prop('required', true);
        } else {
            $('#revisionDeadlineField').hide().find('input').prop('required', false);
        }
    });
    
    // Trigger on page load
    $('select[name="result"]').trigger('change');
});
</script>
@endpush
@endsection
