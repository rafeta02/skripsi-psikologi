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
                            <td width="150"><strong>Tema Riset</strong></td>
                            <td>: {{ $application->skripsiRegistration->themes_label }}</td>
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
                @php $registration = $application->skripsiRegistration; @endphp

                <div class="form-group">
                    <label class="font-weight-bold">KHS (Kartu Hasil Studi):</label>
                    @if($registration->khs_all && count($registration->khs_all) > 0)
                        <div class="list-group">
                            @foreach($registration->khs_all as $key => $media)
                                <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <div class="mb-2 mb-md-0">
                                        <i class="fas fa-file-pdf text-danger mr-2"></i>
                                        <span>KHS File {{ $key + 1 }}</span>
                                        <br>
                                        <small class="text-muted">{{ $media->file_name }}</small>
                                    </div>
                                    <div class="btn-group">
                                        <a href="{{ $media->getUrl() }}" target="_blank" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <button type="button" class="btn btn-sm btn-info preview-doc"
                                                data-url="{{ $media->getUrl() }}"
                                                data-type="pdf">
                                            <i class="fas fa-expand"></i> Preview
                                        </button>
                                        <a href="{{ $media->getUrl() }}" download class="btn btn-sm btn-success">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">Belum ada file KHS</p>
                    @endif
                </div>

                <div class="form-group mb-0">
                    <label class="font-weight-bold">KRS Semester Terakhir:</label>
                    @if($registration->krs_latest)
                        <div class="list-group">
                            <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                <div class="mb-2 mb-md-0">
                                    <i class="fas fa-file-pdf text-danger mr-2"></i>
                                    <span>KRS Latest</span>
                                    <br>
                                    <small class="text-muted">{{ $registration->krs_latest->file_name }}</small>
                                </div>
                                <div class="btn-group">
                                    <a href="{{ $registration->krs_latest->getUrl() }}" target="_blank" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <button type="button" class="btn btn-sm btn-info preview-doc"
                                            data-url="{{ $registration->krs_latest->getUrl() }}"
                                            data-type="pdf">
                                        <i class="fas fa-expand"></i> Preview
                                    </button>
                                    <a href="{{ $registration->krs_latest->getUrl() }}" download class="btn btn-sm btn-success">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-muted mb-0">Belum ada file KRS</p>
                    @endif
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

<!-- Document Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-file-pdf mr-2"></i>
                    Preview Dokumen
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" style="height: 80vh;">
                <iframe id="pdfViewer" style="width: 100%; height: 100%; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.preview-doc').on('click', function() {
        const url = $(this).data('url');
        $('#pdfViewer').attr('src', url);
        $('#previewModal').modal('show');
    });

    $('#previewModal').on('hidden.bs.modal', function() {
        $('#pdfViewer').attr('src', '');
    });
});
</script>
@endpush
