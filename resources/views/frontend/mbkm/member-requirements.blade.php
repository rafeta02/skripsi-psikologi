@extends('layouts.mahasiswa')

@section('title', 'Syarat Individu MBKM')

@section('content')
<div class="container py-4">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h3 class="mb-1">Syarat Individu MBKM</h3>
                    <p class="text-muted mb-0">Lengkapi judul skripsi, nilai, dan dokumen pribadi Anda.</p>
                </div>
                <span class="badge badge-{{ ($member->requirements_status ?? 'incomplete') === 'complete' ? 'success' : 'warning' }} p-2">
                    {{ ($member->requirements_status ?? 'incomplete') === 'complete' ? 'Lengkap' : 'Belum lengkap' }}
                </span>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="alert alert-info">
                <strong>Kelompok:</strong> {{ $registration->title_mbkm ?? '-' }}<br>
                <strong>Ketua:</strong> {{ $registration->application->mahasiswa->nama ?? '-' }}<br>
                <strong>Status kelompok:</strong>
                {{ ($registration->group_status ?? 'draft') === 'submitted' ? 'Sudah diajukan ke admin' : 'Draft — menunggu semua anggota lengkap' }}
            </div>

            @if($locked)
                <div class="alert alert-warning">Pengajuan kelompok sudah dikirim. Data individu terkunci.</div>
            @endif

            <form method="POST" action="{{ route('frontend.mbkm.member-requirements.update') }}" enctype="multipart/form-data">
                @csrf

                <h5 class="mt-3 mb-3">Judul Skripsi (Individu)</h5>
                <div class="form-group">
                    <label>Judul Skripsi <span class="text-danger">*</span></label>
                    <textarea name="title" class="form-control" rows="3" {{ $locked ? 'readonly' : 'required' }}>{{ old('title', $member->title) }}</textarea>
                </div>
                <div class="form-group">
                    <label>Judul Skripsi (English)</label>
                    <textarea name="title_en" class="form-control" rows="2" {{ $locked ? 'readonly' : '' }}>{{ old('title_en', $member->title_en) }}</textarea>
                </div>

                <h5 class="mt-4 mb-3">Nilai (Individu)</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Total SKS <span class="text-danger">*</span></label>
                            <input type="number" name="total_sks_taken" class="form-control" min="0" value="{{ old('total_sks_taken', $member->total_sks_taken) }}" {{ $locked ? 'readonly' : 'required' }}>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>SKS MKP <span class="text-danger">*</span></label>
                            <input type="number" name="sks_mkp_taken" class="form-control" min="0" value="{{ old('sks_mkp_taken', $member->sks_mkp_taken) }}" {{ $locked ? 'readonly' : 'required' }}>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach([
                        'nilai_mk_kuantitatif' => 'Nilai MK Kuantitatif',
                        'nilai_mk_kualitatif' => 'Nilai MK Kualitatif',
                        'nilai_mk_statistika_dasar' => 'Nilai MK Statistika Dasar',
                        'nilai_mk_statistika_lanjutan' => 'Nilai MK Statistika Lanjutan',
                        'nilai_mk_konstruksi_tes' => 'Nilai MK Konstruksi Tes',
                        'nilai_mk_tps' => 'Nilai MK TPS',
                    ] as $field => $label)
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ $label }} <span class="text-danger">*</span></label>
                                <input type="text" name="{{ $field }}" class="form-control" maxlength="10" value="{{ old($field, $member->{$field}) }}" {{ $locked ? 'readonly' : 'required' }}>
                            </div>
                        </div>
                    @endforeach
                </div>

                <h5 class="mt-4 mb-3">Dokumen (Individu)</h5>
                <div class="form-group">
                    <label>KHS Seluruh Semester <span class="text-danger">*</span></label>
                    @if($member->khs_all && count($member->khs_all))
                        <div class="mb-2">
                            @foreach($member->khs_all as $media)
                                <a href="{{ $media->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-secondary mr-1">{{ $media->file_name }}</a>
                            @endforeach
                        </div>
                    @endif
                    @unless($locked)
                        <input type="file" name="khs_all[]" class="form-control-file" multiple accept=".pdf" {{ $member->hasCompleteDocuments() ? '' : 'required' }}>
                    @endunless
                </div>
                <div class="form-group">
                    <label>KRS Semester Terbaru <span class="text-danger">*</span></label>
                    @if($member->krs_latest)
                        <div class="mb-2"><a href="{{ $member->krs_latest->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-secondary">{{ $member->krs_latest->file_name }}</a></div>
                    @endif
                    @unless($locked)
                        <input type="file" name="krs_latest" class="form-control-file" accept=".pdf" {{ $member->krs_latest ? '' : 'required' }}>
                    @endunless
                </div>
                <div class="form-group">
                    <label>Bukti SPP <span class="text-danger">*</span></label>
                    @if($member->spp)
                        <div class="mb-2"><a href="{{ $member->spp->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-secondary">{{ $member->spp->file_name }}</a></div>
                    @endif
                    @unless($locked)
                        <input type="file" name="spp" class="form-control-file" accept=".pdf" {{ $member->spp ? '' : 'required' }}>
                    @endunless
                </div>
                <div class="form-group">
                    <label>Form Rekognisi (Opsional)</label>
                    @if($member->recognition_form)
                        <div class="mb-2"><a href="{{ $member->recognition_form->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-secondary">{{ $member->recognition_form->file_name }}</a></div>
                    @endif
                    @unless($locked)
                        <input type="file" name="recognition_form" class="form-control-file" accept=".pdf">
                    @endunless
                </div>

                @unless($locked)
                    <button type="submit" class="btn btn-primary mt-3">
                        <i class="fas fa-save mr-1"></i> Simpan Syarat Individu
                    </button>
                @endunless
                <a href="{{ route('frontend.mbkm.show', $registration->application_id) }}" class="btn btn-secondary mt-3">Lihat Kelompok</a>
            </form>
        </div>
    </div>
</div>
@endsection
