@extends('layouts.mahasiswa')

@section('title', 'Detail Aplikasi MBKM')

@section('content')
<div class="container py-4">
    <div class="card-modern">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">
                <i class="fas fa-users mr-2"></i> Detail Aplikasi MBKM
            </h3>
        </div>
        <div class="card-body">
            <!-- Application Info -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="text-primary mb-3">Informasi Aplikasi</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td width="150"><strong>ID Aplikasi</strong></td>
                            <td>: #{{ $application->id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tipe</strong></td>
                            <td>: <span class="badge badge-success">Skripsi MBKM</span></td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>: 
                                <span class="badge 
                                    @if($application->status == 'approved') badge-success
                                    @elseif($application->status == 'rejected') badge-danger
                                    @elseif($application->status == 'submitted') badge-warning
                                    @else badge-info
                                    @endif">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Tahap</strong></td>
                            <td>: {{ ucfirst($application->stage) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Submit</strong></td>
                            <td>: {{ $application->created_at->format('d F Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    @if($application->mbkmRegistration)
                    <h5 class="text-primary mb-3">Detail Pendaftaran MBKM</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td width="150"><strong>Research Group</strong></td>
                            <td>: {{ $application->mbkmRegistration->research_group->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tema Riset</strong></td>
                            <td>: {{ $application->mbkmRegistration->themes_label }}</td>
                        </tr>
                        <tr>
                            <td><strong>Dosen Pembimbing</strong></td>
                            <td>: {{ $application->mbkmRegistration->preference_supervision->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Total SKS</strong></td>
                            <td>: {{ $application->mbkmRegistration->total_sks_taken ?? '-' }}</td>
                        </tr>
                    </table>

                    @if($application->mbkmRegistration->groupMembers && $application->mbkmRegistration->groupMembers->count() > 0)
                        <h6 class="text-primary mt-3 mb-2">Anggota Kelompok</h6>
                        <ul class="list-group list-group-flush border rounded mb-3">
                            @foreach($application->mbkmRegistration->groupMembers as $member)
                                <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                    <span>
                                        <strong>{{ $member->mahasiswa->nama ?? '-' }}</strong>
                                        <small class="text-muted ml-2">{{ $member->mahasiswa->nim ?? '' }}</small>
                                    </span>
                                    <span class="badge badge-{{ $member->role === 'ketua' ? 'success' : 'secondary' }} text-capitalize">
                                        {{ $member->role ?? 'anggota' }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    @if(!empty($isGroupFollower))
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-users mr-1"></i>
                            Anda adalah anggota kelompok. Form tahap MBKM diisi ketua; status Anda ikut terbarui hingga sebelum sidang.
                        </div>
                    @endif
                    @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Pendaftaran belum dilengkapi. 
                        <a href="{{ route('frontend.mbkm.create', $application->id) }}" class="alert-link">Lengkapi sekarang</a>
                    </div>
                    @endif
                </div>
            </div>

            @if($application->mbkmRegistration)
            <!-- Titles -->
            <div class="mb-4">
                <h5 class="text-primary mb-3">Judul</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="text-muted">Judul Kegiatan MBKM</h6>
                                <p>{{ $application->mbkmRegistration->title_mbkm ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="text-muted">Judul Skripsi</h6>
                                <p>{{ $application->mbkmRegistration->title ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grades -->
            <div class="mb-4">
                <h5 class="text-primary mb-3">Data Nilai</h5>
                <div class="row">
                    <div class="col-md-4">
                        <table class="table table-sm">
                            <tr>
                                <td>MK Kuantitatif</td>
                                <td><strong>{{ $application->mbkmRegistration->nilai_mk_kuantitatif }}</strong></td>
                            </tr>
                            <tr>
                                <td>MK Kualitatif</td>
                                <td><strong>{{ $application->mbkmRegistration->nilai_mk_kualitatif }}</strong></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <table class="table table-sm">
                            <tr>
                                <td>Statistika Dasar</td>
                                <td><strong>{{ $application->mbkmRegistration->nilai_mk_statistika_dasar }}</strong></td>
                            </tr>
                            <tr>
                                <td>Statistika Lanjutan</td>
                                <td><strong>{{ $application->mbkmRegistration->nilai_mk_statistika_lanjutan }}</strong></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <table class="table table-sm">
                            <tr>
                                <td>Konstruksi Tes</td>
                                <td><strong>{{ $application->mbkmRegistration->nilai_mk_konstruksi_tes }}</strong></td>
                            </tr>
                            <tr>
                                <td>TPS</td>
                                <td><strong>{{ $application->mbkmRegistration->nilai_mk_tps }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Documents -->
            <div class="mb-4">
                <h5 class="text-primary mb-3">Dokumen Persyaratan</h5>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6><i class="fas fa-file-pdf text-danger mr-2"></i> KHS</h6>
                                @if($application->mbkmRegistration->khs_all && count($application->mbkmRegistration->khs_all) > 0)
                                    @foreach($application->mbkmRegistration->khs_all as $file)
                                    <a href="{{ $file->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary btn-block">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                    @endforeach
                                @else
                                    <p class="text-muted small">Belum ada</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6><i class="fas fa-file-pdf text-danger mr-2"></i> KRS</h6>
                                @if($application->mbkmRegistration->krs_latest)
                                    <a href="{{ $application->mbkmRegistration->krs_latest->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary btn-block">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                @else
                                    <p class="text-muted small">Belum ada</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6><i class="fas fa-file-pdf text-danger mr-2"></i> SPP</h6>
                                @if($application->mbkmRegistration->spp)
                                    <a href="{{ $application->mbkmRegistration->spp->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary btn-block">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                @else
                                    <p class="text-muted small">Belum ada</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6><i class="fas fa-file-pdf text-danger mr-2"></i> Proposal MBKM</h6>
                                @if($application->mbkmRegistration->proposal_mbkm)
                                    <a href="{{ $application->mbkmRegistration->proposal_mbkm->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary btn-block">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                @else
                                    <p class="text-muted small">Belum ada</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6><i class="fas fa-file-pdf text-danger mr-2"></i> Form Rekognisi</h6>
                                @if($application->mbkmRegistration->recognition_form)
                                    <a href="{{ $application->mbkmRegistration->recognition_form->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary btn-block">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                @else
                                    <p class="text-muted small">Opsional</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="mt-4 pt-3 border-top">
                <a href="{{ route('mahasiswa.aplikasi') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
                
                @if(in_array($application->status, ['submitted', 'rejected']))
                <a href="{{ route('frontend.mbkm.edit', $application->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit mr-2"></i> Edit Pendaftaran
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
