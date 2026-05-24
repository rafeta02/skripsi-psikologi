@extends('layouts.dosen')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, var(--dosen-primary) 0%, var(--dosen-secondary) 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <h2 class="mb-1 text-white font-weight-bold">
                        <i class="fas fa-star mr-2"></i> Penilaian Sidang
                    </h2>
                    <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                        Berikan penilaian final untuk sidang mahasiswa
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Mahasiswa Info -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern">
                <div class="card-modern-body">
                    <h5 class="font-weight-bold mb-3">Informasi Mahasiswa & Sidang</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">Nama Mahasiswa</label>
                            <p class="font-weight-semibold">
                                @if($application->mahasiswa)
                                    {{ $application->mahasiswa->nama }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">NIM</label>
                            <p class="font-weight-semibold">
                                @if($application->mahasiswa)
                                    {{ $application->mahasiswa->nim }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($application->skripsiDefense)
                        <div class="mb-3">
                            <label class="text-muted mb-1">Judul</label>
                            <h6 class="font-weight-semibold">{{ $application->skripsiDefense->title ?? '-' }}</h6>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">Tipe</label>
                            <span class="badge badge-primary">{{ strtoupper($application->type) }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">Tahap</label>
                            <span class="badge badge-info">{{ ucfirst($application->stage) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scoring Form -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-modern">
                <div class="card-modern-body">
                    <h5 class="font-weight-bold mb-4">Form Penilaian Sidang</h5>
                    
                    <form action="{{ route('dosen.scoring.store', $application->id) }}" method="POST">
                        @csrf
                        
                        <!-- Overall Score -->
                        <div class="form-group">
                            <label class="form-label-modern required">Nilai Keseluruhan (0-100)</label>
                            <input type="number" name="overall_score" class="form-control-modern @error('overall_score') is-invalid @enderror" value="{{ old('overall_score') }}" min="0" max="100" step="0.5" required>
                            <small class="form-text text-muted">Nilai akhir sidang</small>
                            @error('overall_score')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Detail Scores (Optional) -->
                        <h6 class="font-weight-semibold mb-3 mt-4">Komponen Penilaian (Opsional)</h6>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label-modern">Nilai Presentasi</label>
                                    <input type="number" name="presentation_score" class="form-control-modern" value="{{ old('presentation_score') }}" min="0" max="100" step="0.5">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label-modern">Nilai Penguasaan Materi</label>
                                    <input type="number" name="content_score" class="form-control-modern" value="{{ old('content_score') }}" min="0" max="100" step="0.5">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label-modern">Nilai Metodologi</label>
                                    <input type="number" name="methodology_score" class="form-control-modern" value="{{ old('methodology_score') }}" min="0" max="100" step="0.5">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label-modern">Nilai Q&A</label>
                                    <input type="number" name="qa_score" class="form-control-modern" value="{{ old('qa_score') }}" min="0" max="100" step="0.5">
                                </div>
                            </div>
                        </div>

                        <!-- Grade/Letter -->
                        <div class="form-group">
                            <label class="form-label-modern">Nilai Huruf</label>
                            <select name="grade_letter" class="form-control-modern">
                                <option value="">-- Pilih Nilai Huruf --</option>
                                <option value="A" {{ old('grade_letter') == 'A' ? 'selected' : '' }}>A (Excellent)</option>
                                <option value="B+" {{ old('grade_letter') == 'B+' ? 'selected' : '' }}>B+ (Very Good)</option>
                                <option value="B" {{ old('grade_letter') == 'B' ? 'selected' : '' }}>B (Good)</option>
                                <option value="C+" {{ old('grade_letter') == 'C+' ? 'selected' : '' }}>C+ (Satisfactory)</option>
                                <option value="C" {{ old('grade_letter') == 'C' ? 'selected' : '' }}>C (Pass)</option>
                                <option value="D" {{ old('grade_letter') == 'D' ? 'selected' : '' }}>D (Fail)</option>
                            </select>
                        </div>

                        <!-- Comments -->
                        <div class="form-group">
                            <label class="form-label-modern required">Komentar & Saran</label>
                            <textarea name="comments" class="form-control-modern @error('comments') is-invalid @enderror" rows="5" required>{{ old('comments') }}</textarea>
                            <small class="form-text text-muted">Berikan feedback untuk mahasiswa</small>
                            @error('comments')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Recommendation -->
                        <div class="form-group">
                            <label class="form-label-modern required">Rekomendasi</label>
                            <select name="recommendation" class="form-control-modern @error('recommendation') is-invalid @enderror" required>
                                <option value="">-- Pilih Rekomendasi --</option>
                                <option value="passed" {{ old('recommendation') == 'passed' ? 'selected' : '' }}>✅ Lulus</option>
                                <option value="passed_with_revision" {{ old('recommendation') == 'passed_with_revision' ? 'selected' : '' }}>📝 Lulus dengan Revisi</option>
                                <option value="failed" {{ old('recommendation') == 'failed' ? 'selected' : '' }}>❌ Tidak Lulus</option>
                            </select>
                            @error('recommendation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('dosen.scores') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Simpan Penilaian
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
