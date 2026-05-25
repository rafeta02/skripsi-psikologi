@extends('layouts.mahasiswa')

@section('title', 'Detail Aplikasi Skripsi')

@section('content')
<div class="container py-4">
    <div class="card-modern">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">
                <i class="fas fa-file-alt mr-2"></i> Detail Aplikasi Skripsi Reguler
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
                            <td>: <span class="badge badge-primary">Skripsi Reguler</span></td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>:
                                @php $regStatus = $application->getRegistrationStatusForMahasiswa(); @endphp
                                <span class="badge badge-{{ $regStatus['badge'] }}">
                                    {{ $regStatus['label'] }}
                                </span>
                                <br>
                                <small class="text-muted">{{ $regStatus['detail'] }}</small>
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
                    @if($application->skripsiRegistration)
                    <h5 class="text-primary mb-3">Detail Pendaftaran</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td width="150"><strong>Bidang Keilmuan</strong></td>
                            <td>: {{ $application->skripsiRegistration->theme->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Judul</strong></td>
                            <td>: {{ $application->skripsiRegistration->title ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Dosen TPS</strong></td>
                            <td>: {{ $application->skripsiRegistration->tps_lecturer->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Preferensi Pembimbing</strong></td>
                            <td>: {{ $application->skripsiRegistration->preference_supervision->nama ?? '-' }}</td>
                        </tr>
                    </table>
                    @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Pendaftaran belum dilengkapi. 
                        <a href="{{ route('frontend.skripsi.create', $application->id) }}" class="alert-link">Lengkapi sekarang</a>
                    </div>
                    @endif
                </div>
            </div>

            @if($application->skripsiRegistration)
            <!-- Abstract -->
            <div class="mb-4">
                <h5 class="text-primary mb-3">Abstrak / Ringkasan</h5>
                <div class="p-3 bg-light rounded">
                    {{ $application->skripsiRegistration->abstract ?? '-' }}
                </div>
            </div>

            <!-- Documents -->
            <div class="mb-4">
                <h5 class="text-primary mb-3">Dokumen Persyaratan</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6><i class="fas fa-file-pdf text-danger mr-2"></i> KHS (Semua Semester)</h6>
                                @if($application->skripsiRegistration->khs_all && count($application->skripsiRegistration->khs_all) > 0)
                                    <ul class="list-unstyled">
                                        @foreach($application->skripsiRegistration->khs_all as $file)
                                        <li>
                                            <a href="{{ $file->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-download"></i> {{ $file->file_name }}
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted">Belum ada file</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6><i class="fas fa-file-pdf text-danger mr-2"></i> KRS (Semester Terbaru)</h6>
                                @if($application->skripsiRegistration->krs_latest)
                                    <a href="{{ $application->skripsiRegistration->krs_latest->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download"></i> {{ $application->skripsiRegistration->krs_latest->file_name }}
                                    </a>
                                @else
                                    <p class="text-muted">Belum ada file</p>
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
                <a href="{{ route('frontend.skripsi.edit', $application->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit mr-2"></i> Edit Pendaftaran
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
