@extends('layouts.dosen')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, var(--dosen-primary) 0%, var(--dosen-secondary) 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <h2 class="mb-1 text-white font-weight-bold">
                        <i class="fas fa-clipboard-check mr-2"></i> Review Proposal
                    </h2>
                    <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                        Tinjau pendaftaran skripsi dan berikan keputusan beserta feedback
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Application Info -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern">
                <div class="card-modern-body">
                    <h5 class="font-weight-bold mb-3">Informasi Mahasiswa & Proposal</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">Nama Mahasiswa</label>
                            <p class="font-weight-semibold">
                                @if($assignment->application && $assignment->application->mahasiswa)
                                    {{ $assignment->application->mahasiswa->nama }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">NIM</label>
                            <p class="font-weight-semibold">
                                @if($assignment->application && $assignment->application->mahasiswa)
                                    {{ $assignment->application->mahasiswa->nim }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">Tipe</label>
                            <p class="font-weight-semibold text-uppercase">
                                @if($assignment->application)
                                    <span class="badge badge-primary">{{ $assignment->application->type }}</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">Tahap</label>
                            <p class="font-weight-semibold text-capitalize">
                                @if($assignment->application)
                                    <span class="badge badge-info">{{ $assignment->application->stage }}</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($assignment->application && $assignment->application->type == 'skripsi')
                        @php
                            $registration = $assignment->application->skripsiRegistration;
                        @endphp
                        @if($registration)
                            <div class="mb-3">
                                <label class="text-muted mb-1">Tema Keilmuan</label>
                                <p class="font-weight-semibold mb-0">{{ $registration->theme->name ?? '-' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted mb-1">Judul Skripsi</label>
                                <h6 class="font-weight-semibold mb-0">{{ $registration->title ?? '-' }}</h6>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted mb-1">Abstrak</label>
                                <div class="p-3 rounded" style="background: #f8f9fa; white-space: pre-wrap;">{{ $registration->abstract ?? '-' }}</div>
                            </div>
                        @endif
                    @elseif($assignment->application && $assignment->application->type == 'mbkm')
                        @php
                            $registration = $assignment->application->mbkmRegistration;
                        @endphp
                        @if($registration)
                            <div class="mb-3">
                                <label class="text-muted mb-1">Judul MBKM</label>
                                <h6 class="font-weight-semibold">{{ $registration->mbkm_title ?? '-' }}</h6>
                            </div>
                            @if($registration->mbkm_description)
                                <div class="mb-3">
                                    <label class="text-muted mb-1">Deskripsi</label>
                                    <p>{{ Str::limit($registration->mbkm_description, 300) }}</p>
                                </div>
                            @endif
                        @endif
                    @endif

                    <!-- Download Documents -->
                    <div class="mt-3">
                        <h6 class="font-weight-semibold mb-2">Dokumen Mahasiswa:</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @if($assignment->application && $assignment->application->type == 'skripsi' && $assignment->application->skripsiRegistration)
                                @php $skripsiReg = $assignment->application->skripsiRegistration; @endphp
                                @if($skripsiReg->khs_all && count($skripsiReg->khs_all) > 0)
                                    @foreach($skripsiReg->khs_all as $file)
                                        <a href="{{ $file->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary mr-1 mb-1">
                                            <i class="fas fa-file-pdf"></i> KHS{{ count($skripsiReg->khs_all) > 1 ? ' (' . $loop->iteration . ')' : '' }}
                                        </a>
                                    @endforeach
                                @endif
                                @if($skripsiReg->krs_latest)
                                    <a href="{{ $skripsiReg->krs_latest->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary mr-1 mb-1">
                                        <i class="fas fa-file-pdf"></i> KRS
                                    </a>
                                @endif
                            @elseif($assignment->application && $assignment->application->type == 'mbkm' && $assignment->application->mbkmRegistration)
                                @php $mbkmReg = $assignment->application->mbkmRegistration; @endphp
                                @if($mbkmReg->khs_all && count($mbkmReg->khs_all) > 0)
                                    @foreach($mbkmReg->khs_all as $file)
                                        <a href="{{ $file->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary mr-1 mb-1">
                                            <i class="fas fa-file-pdf"></i> KHS{{ count($mbkmReg->khs_all) > 1 ? ' (' . $loop->iteration . ')' : '' }}
                                        </a>
                                    @endforeach
                                @endif
                                @if($mbkmReg->krs_latest)
                                    <a href="{{ $mbkmReg->krs_latest->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary mr-1 mb-1">
                                        <i class="fas fa-file-pdf"></i> KRS
                                    </a>
                                @endif
                                @if($mbkmReg->spp)
                                    <a href="{{ $mbkmReg->spp->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary mr-1 mb-1">
                                        <i class="fas fa-file-pdf"></i> SPP
                                    </a>
                                @endif
                                @if($mbkmReg->proposal_mbkm)
                                    <a href="{{ $mbkmReg->proposal_mbkm->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary mr-1 mb-1">
                                        <i class="fas fa-file-pdf"></i> Proposal MBKM
                                    </a>
                                @endif
                                @if($mbkmReg->recognition_form)
                                    <a href="{{ $mbkmReg->recognition_form->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary mr-1 mb-1">
                                        <i class="fas fa-file-pdf"></i> Form Pengakuan
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Form -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-modern">
                <div class="card-modern-body">
                    <h5 class="font-weight-bold mb-4">Form Keputusan & Feedback</h5>
                    
                    <form action="{{ route('dosen.task-assignments.respond', $assignment->id) }}" method="POST">
                        @csrf
                        
                        <!-- Decision -->
                        <div class="form-group">
                            <label class="form-label-modern required">Keputusan Review</label>
                            <select name="review_decision" class="form-control-modern @error('review_decision') is-invalid @enderror" required>
                                <option value="">-- Pilih Keputusan --</option>
                                <option value="approved" {{ old('review_decision') == 'approved' ? 'selected' : '' }}>✅ Setuju (Approved)</option>
                                <option value="revision" {{ old('review_decision') == 'revision' ? 'selected' : '' }}>📝 Perlu Revisi</option>
                                <option value="rejected" {{ old('review_decision') == 'rejected' ? 'selected' : '' }}>❌ Ditolak (Rejected)</option>
                            </select>
                            @error('review_decision')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Feedback/Comments -->
                        <div class="form-group">
                            <label class="form-label-modern required">Feedback & Komentar</label>
                            <textarea name="feedback" class="form-control-modern @error('feedback') is-invalid @enderror" rows="6" required>{{ old('feedback') }}</textarea>
                            <small class="form-text text-muted">Berikan feedback konstruktif untuk mahasiswa</small>
                            @error('feedback')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Revision Notes (conditional) -->
                        <div class="form-group" id="revisionNotesField" style="display: none;">
                            <label class="form-label-modern">Catatan Revisi</label>
                            <textarea name="revision_notes" class="form-control-modern @error('revision_notes') is-invalid @enderror" rows="4">{{ old('revision_notes') }}</textarea>
                            <small class="form-text text-muted">Jelaskan secara spesifik apa yang perlu direvisi</small>
                            @error('revision_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('dosen.task-assignments') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane"></i> Kirim Review
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
    // Show/hide revision notes based on decision
    $('select[name="review_decision"]').on('change', function() {
        if ($(this).val() === 'revision') {
            $('#revisionNotesField').slideDown();
            $('#revisionNotesField textarea').prop('required', true);
        } else {
            $('#revisionNotesField').slideUp();
            $('#revisionNotesField textarea').prop('required', false);
        }
    });
    
    // Trigger on page load
    $('select[name="review_decision"]').trigger('change');
});
</script>
@endpush
@endsection
