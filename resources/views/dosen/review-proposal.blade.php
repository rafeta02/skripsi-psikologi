@extends('layouts.dosen')

@section('content')
@php
    $app = $assignment->application;
    $reviewSubtitle = match (true) {
        $assignment->isSupervisorInformant() => 'Informasi Review Kelayakan Proposal (Reguler) mahasiswa bimbingan Anda — tidak perlu respons',
        $assignment->isSupervisorAssignment() => 'Persetujuan pembimbing — terima atau tolak permintaan bimbingan',
        ($app->type ?? null) === 'mbkm' && ($app->stage ?? null) === 'seminar' => 'Review Kelayakan Proposal (MBKM) — berikan keputusan dan feedback',
        ($app->type ?? null) === 'mbkm' => 'Pendaftaran Skripsi MBKM — berikan keputusan',
        ($app->stage ?? null) === 'seminar' => 'Review Kelayakan Proposal (Reguler) — berikan keputusan dan feedback',
        default => 'Pendaftaran Skripsi Reguler — persetujuan pembimbing',
    };
    $reviewTitle = match (true) {
        $assignment->isSupervisorInformant() => 'Informasi Review Kelayakan Proposal (Reguler)',
        ($app->stage ?? null) === 'seminar' && ($assignment->role ?? null) === 'reviewer'
            => 'Tinjau Review Kelayakan Proposal (' . (($app->type ?? null) === 'mbkm' ? 'MBKM' : 'Reguler') . ')',
        $assignment->isSupervisorAssignment() => 'Persetujuan Pembimbing',
        default => 'Tinjau Proposal',
    };
@endphp
@include('partials.dosen.page-header', [
    'title' => $reviewTitle,
    'subtitle' => $reviewSubtitle,
])

<div class="mb-3">
    <a href="{{ route('dosen.task-assignments') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Kembali ke Penugasan
    </a>
</div>

@php
    $mhs = $app?->mahasiswa;
@endphp

@if($app && $app->type === 'mbkm')
    @php $registration = $app->mbkmRegistration; @endphp

    @if(!$registration)
        <div class="alert alert-warning">Data pendaftaran MBKM belum tersedia.</div>
    @else
        <div class="row">
            <div class="col-lg-8">
                {{-- Ketua --}}
                <div class="mhs-card mb-3">
                    <div class="mhs-card-body">
                        <h5 class="font-weight-bold mb-2">
                            <i class="fas fa-user-graduate text-primary mr-2"></i> Ketua Kelompok
                        </h5>
                        <div class="alert alert-info py-2 mb-3">
                            <i class="fas fa-users mr-1"></i>
                            Sampai sebelum sidang, proses MBKM berkelompok. Penugasan ini untuk <strong>satu kelompok</strong>.
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label class="text-muted mb-0">Nama</label>
                                <p class="font-weight-semibold mb-0">{{ $mhs->nama ?? '-' }}</p>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="text-muted mb-0">NIM</label>
                                <p class="font-weight-semibold mb-0">{{ $mhs->nim ?? '-' }}</p>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="text-muted mb-0">Program Studi</label>
                                <p class="font-weight-semibold mb-0">{{ $mhs->prodi->name ?? '-' }}</p>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="text-muted mb-0">Jenjang</label>
                                <p class="font-weight-semibold mb-0">{{ $mhs->jenjang->name ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Info kelompok --}}
                <div class="mhs-card mb-3">
                    <div class="mhs-card-body">
                        <h5 class="font-weight-bold mb-3">
                            <i class="fas fa-briefcase text-info mr-2"></i> Informasi Kelompok MBKM
                            <span class="badge badge-info ml-1">Syarat kelompok</span>
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted mb-0">Research Group</label>
                                <p class="font-weight-semibold mb-0">{{ $registration->research_group->name ?? '-' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted mb-0">Tema Riset</label>
                                <p class="font-weight-semibold mb-0">{{ $registration->themes_label }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted mb-0">Preferensi Dosen Pembimbing</label>
                                <p class="font-weight-semibold mb-0">{{ $registration->preference_supervision->nama ?? '-' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted mb-0">Peran Anda</label>
                                <p class="font-weight-semibold mb-0 text-capitalize">{{ $assignment->role ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="text-muted mb-0">Judul Kegiatan MBKM</label>
                            <p class="font-weight-semibold mb-0">{{ $registration->title_mbkm ?? '-' }}</p>
                        </div>
                        @if($registration->note)
                            <div class="alert alert-secondary mb-0 mt-2">
                                <strong>Catatan:</strong> {{ $registration->note }}
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Anggota + syarat individu (reuse admin partial) --}}
                @include('admin.partials.mbkm-group-context', [
                    'mbkmGroupRegistration' => $registration,
                    'mode' => 'full',
                ])

                {{-- Dokumen kelompok --}}
                <div class="mhs-card mb-3">
                    <div class="mhs-card-body">
                        <h5 class="font-weight-bold mb-2">
                            <i class="fas fa-file-pdf text-warning mr-2"></i> Dokumen Kelompok
                            <span class="badge badge-warning text-dark ml-1">Syarat kelompok</span>
                        </h5>
                        <p class="text-muted small mb-3">
                            Dokumen individu (KHS, KRS, SPP, recognition) ada di detail tiap anggota di atas.
                        </p>
                        @if($registration->proposal_mbkm)
                            <div class="list-group">
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-file-alt text-primary mr-2"></i>
                                        <strong>Proposal MBKM</strong>
                                        <br>
                                        <small class="text-muted">{{ $registration->proposal_mbkm->file_name }}</small>
                                    </div>
                                    <div class="btn-group">
                                        <a href="{{ $registration->proposal_mbkm->getUrl() }}" target="_blank" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <button type="button" class="btn btn-sm btn-info preview-doc"
                                                data-url="{{ $registration->proposal_mbkm->getUrl() }}"
                                                data-type="pdf">
                                            <i class="fas fa-expand"></i> Preview
                                        </button>
                                        <a href="{{ $registration->proposal_mbkm->getUrl() }}" download class="btn btn-sm btn-success">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p class="text-muted mb-0">Tidak ada proposal MBKM</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="mhs-card mb-3">
                    <div class="mhs-card-body">
                        <h6 class="font-weight-bold mb-3">Status Penugasan</h6>
                        <p class="mb-2">
                            <span class="badge badge-success">Skripsi MBKM</span>
                            <span class="badge badge-info text-capitalize">{{ $app->stage ?? '-' }}</span>
                            <span class="badge badge-secondary text-capitalize">{{ $app->status ?? '-' }}</span>
                        </p>
                        @if(($registration->group_status ?? 'draft') === 'submitted')
                            <span class="badge badge-success p-2 mb-2 d-inline-block">Kelompok diajukan</span>
                        @else
                            <span class="badge badge-warning p-2 mb-2 d-inline-block">Draft kelompok</span>
                        @endif
                        <p class="small text-muted mb-0">
                            Ditugaskan:
                            {{ $assignment->assigned_at ? \Carbon\Carbon::parse($assignment->assigned_at)->format('d M Y H:i') : '-' }}
                        </p>
                    </div>
                </div>

                <div class="mhs-card mb-3">
                    <div class="mhs-card-body">
                        <h6 class="font-weight-bold mb-2">Ringkasan Syarat</h6>
                        <ul class="small pl-3 mb-0">
                            <li class="mb-1"><strong>Kelompok:</strong> research group, tema, judul MBKM, anggota, proposal</li>
                            <li><strong>Individu (per anggota):</strong> judul skripsi, nilai MK/SKS, KHS/KRS/SPP</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

@else
    {{-- Skripsi reguler (tetap) --}}
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="mhs-card">
                <div class="mhs-card-body">
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
                            <p class="mb-0"><span class="badge badge-primary">Skripsi Reguler</span></p>
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
                            <p class="font-weight-semibold mb-0">
                                @if($assignment->isSupervisorInformant())
                                    Pembimbing (Informasi)
                                @else
                                    <span class="text-capitalize">{{ $assignment->role ?? '-' }}</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted mb-1">Tanggal Penugasan</label>
                            <p class="font-weight-semibold mb-0">
                                {{ $assignment->assigned_at ? \Carbon\Carbon::parse($assignment->assigned_at)->format('d M Y H:i') : '-' }}
                            </p>
                        </div>
                    </div>

                    <hr class="my-3">

                    @php
                        $seminar = $assignment->skripsiSeminar ?? $app?->skripsiSeminar;
                        $registration = $app?->skripsiRegistration;
                        $isProposalReviewer = $assignment->isProposalReviewer();
                        $isSupervisorInformant = $assignment->isSupervisorInformant();
                        $showsProposalReview = $assignment->showsProposalReviewDocuments();
                    @endphp

                    @if($showsProposalReview && $seminar)
                        <h6 class="font-weight-bold text-primary mb-3">
                            <i class="fas fa-presentation mr-1"></i> Review Kelayakan Proposal (Reguler)
                        </h6>
                        <div class="mb-3">
                            <label class="text-muted mb-1">Judul Proposal</label>
                            <h6 class="font-weight-semibold mb-0">{{ $seminar->title ?? '-' }}</h6>
                        </div>
                        @if($isProposalReviewer)
                        <div class="mb-3">
                            <label class="text-muted mb-1">Slot Reviewer</label>
                            <p class="mb-0"><span class="badge badge-info">{{ str_replace('_', ' ', ucfirst($assignment->reviewer_slot ?? 'reviewer')) }}</span></p>
                        </div>
                        @elseif($isSupervisorInformant)
                        <div class="alert alert-info py-2 small mb-3">
                            <i class="fas fa-info-circle mr-1"></i>
                            Anda menerima informasi ini sebagai <strong>dosen pembimbing</strong>. Review proposal dilakukan oleh reviewer yang ditugaskan admin.
                        </div>
                        @endif
                        @if($isProposalReviewer && $assignment->getRawOriginal('response_deadline'))
                            <div class="alert alert-light border small">
                                Batas respons penugasan:
                                <strong>{{ \Carbon\Carbon::parse($assignment->getRawOriginal('response_deadline'))->format('d M Y H:i') }}</strong>
                                @if($assignment->getRawOriginal('feedback_deadline'))
                                    · Batas kirim feedback:
                                    <strong>{{ \Carbon\Carbon::parse($assignment->getRawOriginal('feedback_deadline'))->format('d M Y H:i') }}</strong>
                                @endif
                            </div>
                        @endif
                        <h6 class="font-weight-semibold mb-2">Dokumen Proposal:</h6>
                        <div class="list-group mb-0">
                            @include('partials.document-action-buttons', ['media' => $seminar->proposal_document, 'label' => 'Dokumen Proposal'])
                            @include('partials.document-action-buttons', ['media' => $seminar->approval_document, 'label' => 'Persetujuan Pembimbing'])
                            @include('partials.document-action-buttons', ['media' => $seminar->plagiarism_document, 'label' => 'Cek Plagiarisme'])
                        </div>
                    @elseif($registration)
                        <h6 class="font-weight-bold text-primary mb-3"><i class="fas fa-file-alt mr-1"></i> Data Pendaftaran Skripsi Reguler</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted mb-1">Tema Riset</label>
                                <p class="font-weight-semibold mb-0">{{ $registration->themes_label }}</p>
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
                        <div class="mt-3">
                            <h6 class="font-weight-semibold mb-2">Dokumen Mahasiswa:</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @if($registration->khs_all && count($registration->khs_all) > 0)
                                    @foreach($registration->khs_all as $file)
                                        <a href="{{ $file->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary mr-1 mb-1">
                                            <i class="fas fa-file-pdf"></i> KHS{{ count($registration->khs_all) > 1 ? ' (' . $loop->iteration . ')' : '' }}
                                        </a>
                                    @endforeach
                                @endif
                                @if($registration->krs_latest)
                                    <a href="{{ $registration->krs_latest->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary mr-1 mb-1">
                                        <i class="fas fa-file-pdf"></i> KRS
                                    </a>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning mb-0">Data pendaftaran skripsi belum tersedia.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif

{{-- Review / Response Forms --}}
<div class="row mt-2">
    <div class="col-lg-12">
        @php $isProposalReviewer = $assignment->isProposalReviewer(); @endphp

        @if($assignment->isSupervisorInformant())
            <div class="alert alert-secondary mb-0">
                <h5 class="alert-heading mb-2">Penugasan informasi</h5>
                <p class="mb-0">Tidak ada tindakan yang perlu Anda lakukan. Halaman ini hanya untuk melihat dokumen Review Kelayakan Proposal mahasiswa bimbingan Anda.</p>
            </div>

        @elseif($isProposalReviewer && $assignment->status === 'assigned')
            <div class="card-modern">
                <div class="card-modern-body">
                    <h5 class="font-weight-bold mb-3">Respons Penugasan Reviewer</h5>
                    <p class="text-muted small">Terima atau tolak penugasan review proposal dalam {{ config('thesis.reviewer_response_days', 5) }} hari sejak ditugaskan.</p>
                    <form action="{{ route('dosen.task-assignments.respond', $assignment->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="action" value="respond_assignment">
                        <div class="form-group">
                            <label class="form-label-modern required">Keputusan</label>
                            <select name="assignment_response" class="form-control-modern" required>
                                <option value="">-- Pilih --</option>
                                <option value="accepted">Terima — bersedia mereview proposal</option>
                                <option value="rejected">Tolak penugasan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label-modern">Alasan Penolakan</label>
                            <textarea name="rejection_reason" class="form-control-modern" rows="3" placeholder="Wajib diisi jika menolak penugasan"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Kirim Respons</button>
                    </form>
                </div>
            </div>

        @elseif($isProposalReviewer && $assignment->status === 'accepted')
            <div class="card-modern">
                <div class="card-modern-body">
                    <h5 class="font-weight-bold mb-3">Kirim Feedback Review Proposal</h5>
                    <p class="text-muted small">Unggah 1 dokumen umpan balik (PDF/Word) dan pilih status hasil review. Batas maksimal {{ config('thesis.reviewer_feedback_deadline_days', 14) }} hari sejak penugasan.</p>
                    <form action="{{ route('dosen.task-assignments.respond', $assignment->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="action" value="submit_feedback">
                        <div class="form-group">
                            <label class="form-label-modern required">Hasil Review</label>
                            <select name="feedback_result" class="form-control-modern" required>
                                <option value="">-- Pilih --</option>
                                @foreach(\App\Models\ApplicationAssignment::FEEDBACK_RESULT_SELECT as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label-modern required">Catatan / Umpan Balik</label>
                            <textarea name="feedback_note" class="form-control-modern" rows="5" required minlength="10" placeholder="Tulis umpan balik untuk mahasiswa..."></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label-modern required">Dokumen Umpan Balik (PDF / Word)</label>
                            <input type="file" name="feedback_document" class="form-control-file" accept=".pdf,.doc,.docx" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane"></i> Kirim Feedback
                        </button>
                    </form>
                </div>
            </div>

        @elseif($isProposalReviewer && $assignment->status === 'feedback_submitted')
            <div class="alert alert-success">
                <h5 class="alert-heading">Feedback telah dikirim</h5>
                <p class="mb-1"><strong>Hasil:</strong> {{ \App\Models\ApplicationAssignment::FEEDBACK_RESULT_SELECT[$assignment->feedback_result] ?? $assignment->feedback_result }}</p>
                @if($assignment->feedback_note)
                    <p class="mb-1"><strong>Catatan:</strong> {{ $assignment->feedback_note }}</p>
                @endif
                @if($assignment->feedback_document)
                    <a href="{{ $assignment->feedback_document->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                        <i class="fas fa-download"></i> Unduh Dokumen Feedback
                    </a>
                @endif
            </div>

        @elseif($assignment->isSupervisorAssignment() && $assignment->status === 'assigned')
            <div class="card-modern">
                <div class="card-modern-body">
                    <h5 class="font-weight-bold mb-3">Respons Permintaan Pembimbing</h5>
                    <p class="text-muted small">Terima atau tolak permintaan menjadi dosen pembimbing. Tidak perlu mengunggah dokumen feedback.</p>
                    <form action="{{ route('dosen.task-assignments.respond', $assignment->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label-modern required">Keputusan</label>
                            <select name="supervisor_response" class="form-control-modern" required>
                                <option value="">-- Pilih --</option>
                                <option value="accepted">Terima — bersedia membimbing mahasiswa</option>
                                <option value="rejected">Tolak permintaan pembimbingan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label-modern">Alasan Penolakan</label>
                            <textarea name="rejection_reason" class="form-control-modern" rows="3" placeholder="Wajib diisi jika menolak permintaan pembimbingan"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label-modern">Catatan (opsional)</label>
                            <textarea name="note" class="form-control-modern" rows="2" placeholder="Catatan tambahan untuk admin atau mahasiswa"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Kirim Keputusan</button>
                    </form>
                </div>
            </div>

        @elseif($assignment->isSupervisorAssignment() && $assignment->status === 'accepted')
            <div class="alert alert-success">
                <h5 class="alert-heading">Pembimbingan diterima</h5>
                <p class="mb-0">Anda telah menerima permintaan menjadi dosen pembimbing mahasiswa ini.</p>
            </div>

        @elseif($assignment->isSupervisorAssignment() && $assignment->status === 'rejected')
            <div class="alert alert-danger">
                <h5 class="alert-heading">Pembimbingan ditolak</h5>
                @if($assignment->rejection_reason)
                    <p class="mb-0"><strong>Alasan:</strong> {{ $assignment->rejection_reason }}</p>
                @endif
            </div>

        @elseif(!$isProposalReviewer)
        <div class="card-modern">
            <div class="card-modern-body">
                <h5 class="font-weight-bold mb-4">Form Keputusan & Feedback</h5>

                <form action="{{ route('dosen.task-assignments.respond', $assignment->id) }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="form-label-modern required">Keputusan</label>
                        <select name="review_decision" class="select2 form-control-modern @error('review_decision') is-invalid @enderror" required>
                            <option value="">-- Pilih Keputusan --</option>
                            <option value="approved" {{ old('review_decision') == 'approved' ? 'selected' : '' }}>✅ Setujui (Approved)</option>
                            <option value="rejected" {{ old('review_decision') == 'rejected' ? 'selected' : '' }}>❌ Tolak (Rejected)</option>
                        </select>
                        @error('review_decision')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label-modern required">Feedback & Komentar</label>
                        <textarea name="feedback" class="form-control-modern @error('feedback') is-invalid @enderror" rows="6" required placeholder="Tulis feedback untuk mahasiswa...">{{ old('feedback') }}</textarea>
                        <small class="form-text text-muted">Berikan feedback konstruktif untuk mahasiswa</small>
                        @error('feedback')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('dosen.task-assignments') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane"></i> Kirim Keputusan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Preview modal --}}
<div class="modal fade" id="docPreviewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Dokumen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0" style="min-height: 70vh;">
                <iframe id="docPreviewFrame" src="" style="width:100%; height:70vh; border:0;"></iframe>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $(document).on('click', '.preview-doc', function() {
        var url = $(this).data('url');
        $('#docPreviewFrame').attr('src', url);
        $('#docPreviewModal').modal('show');
    });

    $('#docPreviewModal').on('hidden.bs.modal', function() {
        $('#docPreviewFrame').attr('src', '');
    });
});
</script>
@endpush
@endsection
