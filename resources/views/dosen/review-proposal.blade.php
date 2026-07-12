@extends('layouts.dosen')

@section('content')
@include('partials.dosen.page-header', [
    'title' => 'Tinjau Proposal',
    'subtitle' => $assignment->application?->type === 'mbkm'
        ? 'Pendaftaran Skripsi MBKM — berikan keputusan dan feedback'
        : 'Pendaftaran Skripsi Reguler — berikan keputusan dan feedback',
])

<div class="mb-3">
    <a href="{{ route('dosen.task-assignments') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Kembali ke Penugasan
    </a>
</div>

    <!-- Application Info -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="mhs-card">
                <div class="mhs-card-body">
                    @php
                        $app = $assignment->application;
                        $mhs = $app?->mahasiswa;
                    @endphp

                    <h5 class="font-weight-bold mb-3">Informasi Mahasiswa & Proposal</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">Nama Mahasiswa</label>
                            <p class="font-weight-semibold mb-0">{{ $mhs->nama ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">NIM</label>
                            <p class="font-weight-semibold mb-0">{{ $mhs->nim ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">Program Studi</label>
                            <p class="font-weight-semibold mb-0">{{ $mhs->prodi->name ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">Jenjang</label>
                            <p class="font-weight-semibold mb-0">{{ $mhs->jenjang->name ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">Jalur / Tipe</label>
                            <p class="mb-0">
                                @if($app?->type === 'mbkm')
                                    <span class="badge badge-success">Skripsi MBKM</span>
                                @elseif($app?->type === 'skripsi')
                                    <span class="badge badge-primary">Skripsi Reguler</span>
                                @else
                                    <span class="badge badge-secondary">{{ $app->type ?? '-' }}</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">Tahap & Status Aplikasi</label>
                            <p class="mb-0">
                                <span class="badge badge-info text-capitalize">{{ $app->stage ?? '-' }}</span>
                                <span class="badge badge-secondary text-capitalize">{{ $app->status ?? '-' }}</span>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">Peran Anda</label>
                            <p class="font-weight-semibold mb-0 text-capitalize">{{ $assignment->role ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">Tanggal Penugasan</label>
                            <p class="font-weight-semibold mb-0">
                                {{ $assignment->assigned_at ? \Carbon\Carbon::parse($assignment->assigned_at)->format('d M Y H:i') : '-' }}
                            </p>
                        </div>
                    </div>

                    <hr class="my-3">

                    @if($app && $app->type === 'skripsi')
                        @php $registration = $app->skripsiRegistration; @endphp
                        @if($registration)
                            <h6 class="font-weight-bold text-primary mb-3"><i class="fas fa-file-alt mr-1"></i> Data Pendaftaran Skripsi Reguler</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted mb-1">Bidang Keilmuan</label>
                                    <p class="font-weight-semibold mb-0">{{ $registration->theme->name ?? '-' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted mb-1">Preferensi Dosen Pembimbing</label>
                                    <p class="font-weight-semibold mb-0">{{ $registration->preference_supervision->nama ?? '-' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted mb-1">Dosen TPS</label>
                                    <p class="font-weight-semibold mb-0">{{ $registration->tps_lecturer->nama ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted mb-1">Judul Skripsi</label>
                                <h6 class="font-weight-semibold mb-0">{{ $registration->title ?? '-' }}</h6>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted mb-1">Abstrak</label>
                                <div class="p-3 rounded" style="background: #f8f9fa; white-space: pre-wrap;">{{ $registration->abstract ?? '-' }}</div>
                            </div>
                        @else
                            <div class="alert alert-warning mb-0">Data pendaftaran skripsi belum tersedia.</div>
                        @endif
                    @elseif($app && $app->type === 'mbkm')
                        @php $registration = $app->mbkmRegistration; @endphp
                        @if($registration)
                            <h6 class="font-weight-bold text-success mb-3"><i class="fas fa-users mr-1"></i> Data Pendaftaran Skripsi MBKM</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted mb-1">Research Group</label>
                                    <p class="font-weight-semibold mb-0">{{ $registration->research_group->name ?? '-' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted mb-1">Tema Riset</label>
                                    <p class="font-weight-semibold mb-0">{{ $registration->themes_label }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted mb-1">Preferensi Dosen Pembimbing</label>
                                    <p class="font-weight-semibold mb-0">{{ $registration->preference_supervision->nama ?? '-' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted mb-1">Total SKS Diambil</label>
                                    <p class="font-weight-semibold mb-0">{{ $registration->total_sks_taken ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted mb-1">Judul Kegiatan MBKM</label>
                                    <h6 class="font-weight-semibold mb-0">{{ $registration->title_mbkm ?? '-' }}</h6>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted mb-1">Judul Skripsi</label>
                                    <h6 class="font-weight-semibold mb-0">{{ $registration->title ?? '-' }}</h6>
                                </div>
                            </div>
                            @if($registration->note)
                                <div class="mb-3">
                                    <label class="text-muted mb-1">Catatan Mahasiswa</label>
                                    <div class="p-3 rounded" style="background: #f8f9fa; white-space: pre-wrap;">{{ $registration->note }}</div>
                                </div>
                            @endif
                            @php
                                $nilaiRows = array_filter([
                                    'MK Kuantitatif' => $registration->nilai_mk_kuantitatif,
                                    'MK Kualitatif' => $registration->nilai_mk_kualitatif,
                                    'Statistika Dasar' => $registration->nilai_mk_statistika_dasar,
                                    'Statistika Lanjutan' => $registration->nilai_mk_statistika_lanjutan,
                                    'Konstruksi Tes' => $registration->nilai_mk_konstruksi_tes,
                                    'TPS' => $registration->nilai_mk_tps,
                                ], fn ($v) => $v !== null && $v !== '');
                            @endphp
                            @if(count($nilaiRows) > 0)
                                <div class="mb-3">
                                    <label class="text-muted mb-2 d-block">Nilai Mata Kuliah</label>
                                    <div class="row">
                                        @foreach($nilaiRows as $label => $nilai)
                                            <div class="col-md-4 col-6 mb-2">
                                                <span class="text-muted small">{{ $label }}</span>
                                                <p class="font-weight-semibold mb-0">{{ $nilai }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @if($registration->groupMembers && $registration->groupMembers->count() > 0)
                                <div class="mb-3">
                                    <label class="text-muted mb-2 d-block">Anggota Kelompok MBKM</label>
                                    <ul class="list-group list-group-flush border rounded">
                                        @foreach($registration->groupMembers as $member)
                                            <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                                <span>
                                                    <strong>{{ $member->mahasiswa->nama ?? '-' }}</strong>
                                                    <small class="text-muted ml-2">{{ $member->mahasiswa->nim ?? '' }}</small>
                                                </span>
                                                <span class="badge badge-light text-capitalize">{{ $member->role ?? 'anggota' }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        @else
                            <div class="alert alert-warning mb-0">Data pendaftaran MBKM belum tersedia.</div>
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
