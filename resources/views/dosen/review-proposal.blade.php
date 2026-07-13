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

@php
    $app = $assignment->application;
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

                    @php $registration = $app?->skripsiRegistration; @endphp
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

{{-- Review Form --}}
<div class="row mt-2">
    <div class="col-lg-12">
        <div class="card-modern">
            <div class="card-modern-body">
                <h5 class="font-weight-bold mb-4">Form Keputusan & Feedback</h5>

                <form action="{{ route('dosen.task-assignments.respond', $assignment->id) }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="form-label-modern required">Keputusan</label>
                        <select name="review_decision" class="form-control-modern @error('review_decision') is-invalid @enderror" required>
                            <option value="">-- Pilih Keputusan --</option>
                            <option value="approved" {{ old('review_decision') == 'approved' ? 'selected' : '' }}>Terima</option>
                            <option value="rejected" {{ old('review_decision') == 'rejected' ? 'selected' : '' }}>Tolak</option>
                        </select>
                        @error('review_decision')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label-modern required">Feedback & Komentar</label>
                        <textarea name="feedback" class="form-control-modern @error('feedback') is-invalid @enderror" rows="6" required>{{ old('feedback') }}</textarea>
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
