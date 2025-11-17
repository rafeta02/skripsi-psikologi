@extends('layouts.admin')
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <!-- Header with Action Buttons -->
<div class="card">
                <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <div>
                        <h3 class="mb-0"><i class="fas fa-graduation-cap mr-2"></i>Detail Pendaftaran Sidang Skripsi</h3>
                        <small>Review dan validasi dokumen sidang skripsi mahasiswa</small>
                    </div>
                    <div>
                        @if($skripsiDefense->status === 'pending')
                            <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#acceptModal">
                                <i class="fas fa-check-circle mr-1"></i> Terima
                            </button>
                            <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#rejectModal">
                                <i class="fas fa-times-circle mr-1"></i> Tolak
                            </button>
                        @elseif($skripsiDefense->status === 'accepted')
                            <span class="badge badge-success" style="font-size: 1.2rem; padding: 10px 20px;">
                                <i class="fas fa-check-circle mr-1"></i> Diterima
                            </span>
                        @else
                            <span class="badge badge-danger" style="font-size: 1.2rem; padding: 10px 20px;">
                                <i class="fas fa-times-circle mr-1"></i> Ditolak
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Informasi Mahasiswa -->
            @if($skripsiDefense->application && $skripsiDefense->application->mahasiswa)
            <div class="card mt-3">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-user-graduate mr-2"></i>Informasi Mahasiswa</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <p><strong>Nama:</strong> {{ $skripsiDefense->application->mahasiswa->user->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>NIM:</strong> {{ $skripsiDefense->application->mahasiswa->nim ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Email:</strong> {{ $skripsiDefense->application->mahasiswa->user->email ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <p><strong>Status Aplikasi:</strong> 
                                <span class="badge badge-primary">{{ ucfirst($skripsiDefense->application->type ?? 'N/A') }}</span>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Stage:</strong> 
                                <span class="badge badge-info">{{ ucfirst($skripsiDefense->application->stage ?? 'N/A') }}</span>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Status:</strong> 
                                <span class="badge badge-success">{{ ucfirst($skripsiDefense->application->status ?? 'N/A') }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Status Validasi -->
            <div class="card mt-3">
                <div class="card-header {{ $skripsiDefense->status === 'accepted' ? 'bg-success' : ($skripsiDefense->status === 'rejected' ? 'bg-danger' : 'bg-warning') }} text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Status Validasi</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Status:</strong> 
                                @if($skripsiDefense->status === 'pending')
                                    <span class="badge badge-warning badge-lg">Menunggu Validasi</span>
                                @elseif($skripsiDefense->status === 'accepted')
                                    <span class="badge badge-success badge-lg">Diterima</span>
                                @else
                                    <span class="badge badge-danger badge-lg">Ditolak</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Tanggal Dibuat:</strong> {{ $skripsiDefense->created_at ? $skripsiDefense->created_at->format('d M Y H:i') : 'N/A' }}</p>
                        </div>
                    </div>
                    @if($skripsiDefense->admin_note)
                    <div class="alert alert-info mt-3">
                        <h6><i class="fas fa-sticky-note mr-2"></i>Catatan Admin:</h6>
                        <p class="mb-0">{{ $skripsiDefense->admin_note }}</p>
                    </div>
                    @endif
                </div>
    </div>

            <!-- Informasi Skripsi -->
            <div class="card mt-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-book mr-2"></i>Informasi Skripsi</h5>
                </div>
    <div class="card-body">
        <div class="form-group">
                        <label><strong>Judul Skripsi:</strong></label>
                        <p class="form-control-plaintext border p-2 bg-light">{{ $skripsiDefense->title ?? 'N/A' }}</p>
                    </div>
            <div class="form-group">
                        <label><strong>Abstrak:</strong></label>
                        <p class="form-control-plaintext border p-2 bg-light" style="white-space: pre-wrap;">{{ $skripsiDefense->abstract ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Dokumen Sidang Utama -->
            <div class="card mt-3">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-file-pdf mr-2"></i>Dokumen Sidang Utama</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="document-item mb-3 p-3 border rounded {{ $skripsiDefense->defence_document ? 'border-success bg-light' : 'border-danger' }}">
                                <strong><i class="fas fa-file-alt mr-2"></i>{{ trans('cruds.skripsiDefense.fields.defence_document') }}</strong>
                            @if($skripsiDefense->defence_document)
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-primary preview-doc" 
                                                data-url="{{ $skripsiDefense->defence_document->getUrl() }}">
                                            <i class="fas fa-eye mr-1"></i> Lihat Dokumen
                                        </button>
                                        <a href="{{ $skripsiDefense->defence_document->getUrl() }}" download class="btn btn-sm btn-success">
                                            <i class="fas fa-download mr-1"></i> Download
                                        </a>
                                        <small class="d-block mt-2 text-muted">
                                            Size: {{ number_format($skripsiDefense->defence_document->size / 1024, 2) }} KB
                                        </small>
                                    </div>
                                @else
                                    <span class="badge badge-danger ml-2">Tidak ada file</span>
                            @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="document-item mb-3 p-3 border rounded {{ $skripsiDefense->plagiarism_report ? 'border-success bg-light' : 'border-danger' }}">
                                <strong><i class="fas fa-file-alt mr-2"></i>{{ trans('cruds.skripsiDefense.fields.plagiarism_report') }}</strong>
                            @if($skripsiDefense->plagiarism_report)
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-primary preview-doc" 
                                                data-url="{{ $skripsiDefense->plagiarism_report->getUrl() }}">
                                            <i class="fas fa-eye mr-1"></i> Lihat Dokumen
                                        </button>
                                        <a href="{{ $skripsiDefense->plagiarism_report->getUrl() }}" download class="btn btn-sm btn-success">
                                            <i class="fas fa-download mr-1"></i> Download
                                        </a>
                                        <small class="d-block mt-2 text-muted">
                                            Size: {{ number_format($skripsiDefense->plagiarism_report->size / 1024, 2) }} KB
                                        </small>
                                    </div>
                                @else
                                    <span class="badge badge-danger ml-2">Tidak ada file</span>
                            @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dokumen Etika & Penelitian -->
            <div class="card mt-3">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-file-contract mr-2"></i>Dokumen Etika & Penelitian</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="document-item mb-3 p-3 border rounded {{ count($skripsiDefense->ethics_statement) > 0 ? 'border-success bg-light' : 'border-danger' }}">
                                <strong><i class="fas fa-file-alt mr-2"></i>{{ trans('cruds.skripsiDefense.fields.ethics_statement') }}</strong>
                                @if(count($skripsiDefense->ethics_statement) > 0)
                                    <div class="mt-2">
                                        @foreach($skripsiDefense->ethics_statement as $index => $media)
                                            <div class="mb-2">
                                                <button type="button" class="btn btn-sm btn-primary preview-doc" 
                                                        data-url="{{ $media->getUrl() }}">
                                                    <i class="fas fa-eye mr-1"></i> File {{ $index + 1 }}
                                                </button>
                                                <a href="{{ $media->getUrl() }}" download class="btn btn-sm btn-success">
                                                    <i class="fas fa-download mr-1"></i> Download
                                                </a>
                                                <small class="text-muted ml-2">{{ number_format($media->size / 1024, 2) }} KB</small>
                                            </div>
                            @endforeach
                                    </div>
                                @else
                                    <span class="badge badge-danger ml-2">Tidak ada file</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="document-item mb-3 p-3 border rounded {{ count($skripsiDefense->research_instruments) > 0 ? 'border-success bg-light' : 'border-danger' }}">
                                <strong><i class="fas fa-file-alt mr-2"></i>{{ trans('cruds.skripsiDefense.fields.research_instruments') }}</strong>
                                @if(count($skripsiDefense->research_instruments) > 0)
                                    <div class="mt-2">
                                        @foreach($skripsiDefense->research_instruments as $index => $media)
                                            <div class="mb-2">
                                                <button type="button" class="btn btn-sm btn-primary preview-doc" 
                                                        data-url="{{ $media->getUrl() }}">
                                                    <i class="fas fa-eye mr-1"></i> File {{ $index + 1 }}
                                                </button>
                                                <a href="{{ $media->getUrl() }}" download class="btn btn-sm btn-success">
                                                    <i class="fas fa-download mr-1"></i> Download
                                                </a>
                                                <small class="text-muted ml-2">{{ number_format($media->size / 1024, 2) }} KB</small>
                                            </div>
                            @endforeach
                                    </div>
                                @else
                                    <span class="badge badge-danger ml-2">Tidak ada file</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="document-item mb-3 p-3 border rounded {{ count($skripsiDefense->data_collection_letter) > 0 ? 'border-success bg-light' : 'border-danger' }}">
                                <strong><i class="fas fa-file-alt mr-2"></i>{{ trans('cruds.skripsiDefense.fields.data_collection_letter') }}</strong>
                                @if(count($skripsiDefense->data_collection_letter) > 0)
                                    <div class="mt-2">
                                        @foreach($skripsiDefense->data_collection_letter as $index => $media)
                                            <div class="mb-2">
                                                <button type="button" class="btn btn-sm btn-primary preview-doc" 
                                                        data-url="{{ $media->getUrl() }}">
                                                    <i class="fas fa-eye mr-1"></i> File {{ $index + 1 }}
                                                </button>
                                                <a href="{{ $media->getUrl() }}" download class="btn btn-sm btn-success">
                                                    <i class="fas fa-download mr-1"></i> Download
                                                </a>
                                                <small class="text-muted ml-2">{{ number_format($media->size / 1024, 2) }} KB</small>
                                            </div>
                            @endforeach
                                    </div>
                                @else
                                    <span class="badge badge-danger ml-2">Tidak ada file</span>
                            @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="document-item mb-3 p-3 border rounded {{ count($skripsiDefense->research_module) > 0 ? 'border-success bg-light' : 'border-danger' }}">
                                <strong><i class="fas fa-file-alt mr-2"></i>{{ trans('cruds.skripsiDefense.fields.research_module') }}</strong>
                                @if(count($skripsiDefense->research_module) > 0)
                                    <div class="mt-2">
                                        @foreach($skripsiDefense->research_module as $index => $media)
                                            <div class="mb-2">
                                                <button type="button" class="btn btn-sm btn-primary preview-doc" 
                                                        data-url="{{ $media->getUrl() }}">
                                                    <i class="fas fa-eye mr-1"></i> File {{ $index + 1 }}
                                                </button>
                                                <a href="{{ $media->getUrl() }}" download class="btn btn-sm btn-success">
                                                    <i class="fas fa-download mr-1"></i> Download
                                                </a>
                                                <small class="text-muted ml-2">{{ number_format($media->size / 1024, 2) }} KB</small>
                                            </div>
                            @endforeach
                                    </div>
                                @else
                                    <span class="badge badge-danger ml-2">Tidak ada file</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dokumen Akademik -->
            <div class="card mt-3">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-university mr-2"></i>Dokumen Akademik</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="document-item mb-3 p-3 border rounded {{ $skripsiDefense->spp_receipt ? 'border-success bg-light' : 'border-danger' }}">
                                <strong><i class="fas fa-file-alt mr-2"></i>{{ trans('cruds.skripsiDefense.fields.spp_receipt') }}</strong>
                            @if($skripsiDefense->spp_receipt)
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-primary preview-doc" 
                                                data-url="{{ $skripsiDefense->spp_receipt->getUrl() }}">
                                            <i class="fas fa-eye mr-1"></i> Lihat Dokumen
                                        </button>
                                        <a href="{{ $skripsiDefense->spp_receipt->getUrl() }}" download class="btn btn-sm btn-success">
                                            <i class="fas fa-download mr-1"></i> Download
                                        </a>
                                        <small class="d-block mt-2 text-muted">
                                            Size: {{ number_format($skripsiDefense->spp_receipt->size / 1024, 2) }} KB
                                        </small>
                                    </div>
                                @else
                                    <span class="badge badge-danger ml-2">Tidak ada file</span>
                            @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="document-item mb-3 p-3 border rounded {{ $skripsiDefense->krs_latest ? 'border-success bg-light' : 'border-danger' }}">
                                <strong><i class="fas fa-file-alt mr-2"></i>{{ trans('cruds.skripsiDefense.fields.krs_latest') }}</strong>
                            @if($skripsiDefense->krs_latest)
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-primary preview-doc" 
                                                data-url="{{ $skripsiDefense->krs_latest->getUrl() }}">
                                            <i class="fas fa-eye mr-1"></i> Lihat Dokumen
                                        </button>
                                        <a href="{{ $skripsiDefense->krs_latest->getUrl() }}" download class="btn btn-sm btn-success">
                                            <i class="fas fa-download mr-1"></i> Download
                                        </a>
                                        <small class="d-block mt-2 text-muted">
                                            Size: {{ number_format($skripsiDefense->krs_latest->size / 1024, 2) }} KB
                                        </small>
                                    </div>
                                @else
                                    <span class="badge badge-danger ml-2">Tidak ada file</span>
                            @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="document-item mb-3 p-3 border rounded {{ $skripsiDefense->eap_certificate ? 'border-success bg-light' : 'border-danger' }}">
                                <strong><i class="fas fa-file-alt mr-2"></i>{{ trans('cruds.skripsiDefense.fields.eap_certificate') }}</strong>
                            @if($skripsiDefense->eap_certificate)
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-primary preview-doc" 
                                                data-url="{{ $skripsiDefense->eap_certificate->getUrl() }}">
                                            <i class="fas fa-eye mr-1"></i> Lihat Dokumen
                                        </button>
                                        <a href="{{ $skripsiDefense->eap_certificate->getUrl() }}" download class="btn btn-sm btn-success">
                                            <i class="fas fa-download mr-1"></i> Download
                                        </a>
                                        <small class="d-block mt-2 text-muted">
                                            Size: {{ number_format($skripsiDefense->eap_certificate->size / 1024, 2) }} KB
                                        </small>
                                    </div>
                                @else
                                    <span class="badge badge-danger ml-2">Tidak ada file</span>
                            @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="document-item mb-3 p-3 border rounded {{ $skripsiDefense->transcript ? 'border-success bg-light' : 'border-danger' }}">
                                <strong><i class="fas fa-file-alt mr-2"></i>{{ trans('cruds.skripsiDefense.fields.transcript') }}</strong>
                            @if($skripsiDefense->transcript)
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-primary preview-doc" 
                                                data-url="{{ $skripsiDefense->transcript->getUrl() }}">
                                            <i class="fas fa-eye mr-1"></i> Lihat Dokumen
                                        </button>
                                        <a href="{{ $skripsiDefense->transcript->getUrl() }}" download class="btn btn-sm btn-success">
                                            <i class="fas fa-download mr-1"></i> Download
                                        </a>
                                        <small class="d-block mt-2 text-muted">
                                            Size: {{ number_format($skripsiDefense->transcript->size / 1024, 2) }} KB
                                        </small>
                                    </div>
                                @else
                                    <span class="badge badge-danger ml-2">Tidak ada file</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="document-item mb-3 p-3 border rounded {{ $skripsiDefense->siakad_supervisor_screenshot ? 'border-success bg-light' : 'border-danger' }}">
                                <strong><i class="fas fa-file-alt mr-2"></i>{{ trans('cruds.skripsiDefense.fields.siakad_supervisor_screenshot') }}</strong>
                                @if($skripsiDefense->siakad_supervisor_screenshot)
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-primary preview-doc" 
                                                data-url="{{ $skripsiDefense->siakad_supervisor_screenshot->getUrl() }}">
                                            <i class="fas fa-eye mr-1"></i> Lihat Dokumen
                                        </button>
                                        <a href="{{ $skripsiDefense->siakad_supervisor_screenshot->getUrl() }}" download class="btn btn-sm btn-success">
                                            <i class="fas fa-download mr-1"></i> Download
                                        </a>
                                        <small class="d-block mt-2 text-muted">
                                            Size: {{ number_format($skripsiDefense->siakad_supervisor_screenshot->size / 1024, 2) }} KB
                                        </small>
                                    </div>
                                @else
                                    <span class="badge badge-danger ml-2">Tidak ada file</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dokumen Persetujuan & Pernyataan -->
            <div class="card mt-3">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-stamp mr-2"></i>Dokumen Persetujuan & Pernyataan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="document-item mb-3 p-3 border rounded {{ $skripsiDefense->publication_statement ? 'border-success bg-light' : 'border-danger' }}">
                                <strong><i class="fas fa-file-alt mr-2"></i>{{ trans('cruds.skripsiDefense.fields.publication_statement') }}</strong>
                                @if($skripsiDefense->publication_statement)
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-primary preview-doc" 
                                                data-url="{{ $skripsiDefense->publication_statement->getUrl() }}">
                                            <i class="fas fa-eye mr-1"></i> Lihat Dokumen
                                        </button>
                                        <a href="{{ $skripsiDefense->publication_statement->getUrl() }}" download class="btn btn-sm btn-success">
                                            <i class="fas fa-download mr-1"></i> Download
                                        </a>
                                        <small class="d-block mt-2 text-muted">
                                            Size: {{ number_format($skripsiDefense->publication_statement->size / 1024, 2) }} KB
                                        </small>
                                    </div>
                                @else
                                    <span class="badge badge-danger ml-2">Tidak ada file</span>
                            @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="document-item mb-3 p-3 border rounded {{ count($skripsiDefense->defense_approval_page) > 0 ? 'border-success bg-light' : 'border-danger' }}">
                                <strong><i class="fas fa-file-alt mr-2"></i>{{ trans('cruds.skripsiDefense.fields.defense_approval_page') }}</strong>
                                @if(count($skripsiDefense->defense_approval_page) > 0)
                                    <div class="mt-2">
                                        @foreach($skripsiDefense->defense_approval_page as $index => $media)
                                            <div class="mb-2">
                                                <button type="button" class="btn btn-sm btn-primary preview-doc" 
                                                        data-url="{{ $media->getUrl() }}">
                                                    <i class="fas fa-eye mr-1"></i> File {{ $index + 1 }}
                                                </button>
                                                <a href="{{ $media->getUrl() }}" download class="btn btn-sm btn-success">
                                                    <i class="fas fa-download mr-1"></i> Download
                                                </a>
                                                <small class="text-muted ml-2">{{ number_format($media->size / 1024, 2) }} KB</small>
                                            </div>
                            @endforeach
                                    </div>
                                @else
                                    <span class="badge badge-danger ml-2">Tidak ada file</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dokumen MBKM (Opsional) -->
            @if($skripsiDefense->mbkm_recommendation_letter || count($skripsiDefense->mbkm_report) > 0)
            <div class="card mt-3">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-graduation-cap mr-2"></i>Dokumen MBKM (Opsional)</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($skripsiDefense->mbkm_recommendation_letter)
                        <div class="col-md-6">
                            <div class="document-item mb-3 p-3 border rounded border-success bg-light">
                                <strong><i class="fas fa-file-alt mr-2"></i>{{ trans('cruds.skripsiDefense.fields.mbkm_recommendation_letter') }}</strong>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-primary preview-doc" 
                                            data-url="{{ $skripsiDefense->mbkm_recommendation_letter->getUrl() }}">
                                        <i class="fas fa-eye mr-1"></i> Lihat Dokumen
                                    </button>
                                    <a href="{{ $skripsiDefense->mbkm_recommendation_letter->getUrl() }}" download class="btn btn-sm btn-success">
                                        <i class="fas fa-download mr-1"></i> Download
                                    </a>
                                    <small class="d-block mt-2 text-muted">
                                        Size: {{ number_format($skripsiDefense->mbkm_recommendation_letter->size / 1024, 2) }} KB
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(count($skripsiDefense->mbkm_report) > 0)
                        <div class="col-md-6">
                            <div class="document-item mb-3 p-3 border rounded border-success bg-light">
                                <strong><i class="fas fa-file-alt mr-2"></i>{{ trans('cruds.skripsiDefense.fields.mbkm_report') }}</strong>
                                <div class="mt-2">
                                    @foreach($skripsiDefense->mbkm_report as $index => $media)
                                        <div class="mb-2">
                                            <button type="button" class="btn btn-sm btn-primary preview-doc" 
                                                    data-url="{{ $media->getUrl() }}">
                                                <i class="fas fa-eye mr-1"></i> File {{ $index + 1 }}
                                            </button>
                                            <a href="{{ $media->getUrl() }}" download class="btn btn-sm btn-success">
                                                <i class="fas fa-download mr-1"></i> Download
                                            </a>
                                            <small class="text-muted ml-2">{{ number_format($media->size / 1024, 2) }} KB</small>
                                        </div>
                            @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Dokumen Pendukung Lainnya -->
            <div class="card mt-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-folder-open mr-2"></i>Dokumen Pendukung Lainnya</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="document-item mb-3 p-3 border rounded {{ count($skripsiDefense->research_poster) > 0 ? 'border-success bg-light' : 'border-danger' }}">
                                <strong><i class="fas fa-file-alt mr-2"></i>{{ trans('cruds.skripsiDefense.fields.research_poster') }}</strong>
                                @if(count($skripsiDefense->research_poster) > 0)
                                    <div class="mt-2">
                                        @foreach($skripsiDefense->research_poster as $index => $media)
                                            <div class="mb-2">
                                                <button type="button" class="btn btn-sm btn-primary preview-doc" 
                                                        data-url="{{ $media->getUrl() }}">
                                                    <i class="fas fa-eye mr-1"></i> File {{ $index + 1 }}
                                                </button>
                                                <a href="{{ $media->getUrl() }}" download class="btn btn-sm btn-success">
                                                    <i class="fas fa-download mr-1"></i> Download
                                                </a>
                                                <small class="text-muted ml-2">{{ number_format($media->size / 1024, 2) }} KB</small>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="badge badge-danger ml-2">Tidak ada file</span>
                            @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="document-item mb-3 p-3 border rounded {{ count($skripsiDefense->supervision_logbook) > 0 ? 'border-success bg-light' : 'border-danger' }}">
                                <strong><i class="fas fa-file-alt mr-2"></i>{{ trans('cruds.skripsiDefense.fields.supervision_logbook') }}</strong>
                                @if(count($skripsiDefense->supervision_logbook) > 0)
                                    <div class="mt-2">
                                        @foreach($skripsiDefense->supervision_logbook as $index => $media)
                                            <div class="mb-2">
                                                <button type="button" class="btn btn-sm btn-primary preview-doc" 
                                                        data-url="{{ $media->getUrl() }}">
                                                    <i class="fas fa-eye mr-1"></i> File {{ $index + 1 }}
                                                </button>
                                                <a href="{{ $media->getUrl() }}" download class="btn btn-sm btn-success">
                                                    <i class="fas fa-download mr-1"></i> Download
                                                </a>
                                                <small class="text-muted ml-2">{{ number_format($media->size / 1024, 2) }} KB</small>
                                            </div>
                            @endforeach
                                    </div>
                                @else
                                    <span class="badge badge-danger ml-2">Tidak ada file</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="card mt-3 mb-4">
                <div class="card-body">
                    <a class="btn btn-secondary" href="{{ route('admin.skripsi-defenses.index') }}">
                        <i class="fas fa-arrow-left mr-1"></i> {{ trans('global.back_to_list') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Accept Modal -->
<div class="modal fade" id="acceptModal" tabindex="-1" role="dialog" aria-labelledby="acceptModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.skripsi-defenses.accept', $skripsiDefense->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="acceptModalLabel">
                        <i class="fas fa-check-circle mr-2"></i>Terima Pendaftaran Sidang
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin <strong>menerima</strong> pendaftaran sidang skripsi dari:</p>
                    <div class="alert alert-info">
                        <strong>Nama:</strong> {{ $skripsiDefense->application->mahasiswa->user->name ?? 'N/A' }}<br>
                        <strong>NIM:</strong> {{ $skripsiDefense->application->mahasiswa->nim ?? 'N/A' }}<br>
                        <strong>Judul:</strong> {{ $skripsiDefense->title ?? 'N/A' }}
                    </div>
                    <div class="form-group">
                        <label for="accept_note">Catatan (Opsional)</label>
                        <textarea class="form-control" name="admin_note" id="accept_note" rows="3" placeholder="Tambahkan catatan untuk mahasiswa (opsional)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check-circle mr-1"></i> Ya, Terima
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.skripsi-defenses.reject', $skripsiDefense->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="rejectModalLabel">
                        <i class="fas fa-times-circle mr-2"></i>Tolak Pendaftaran Sidang
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin <strong>menolak</strong> pendaftaran sidang skripsi dari:</p>
                    <div class="alert alert-warning">
                        <strong>Nama:</strong> {{ $skripsiDefense->application->mahasiswa->user->name ?? 'N/A' }}<br>
                        <strong>NIM:</strong> {{ $skripsiDefense->application->mahasiswa->nim ?? 'N/A' }}<br>
                        <strong>Judul:</strong> {{ $skripsiDefense->title ?? 'N/A' }}
                    </div>
                    <div class="form-group">
                        <label for="reject_note">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="admin_note" id="reject_note" rows="3" placeholder="Jelaskan alasan penolakan untuk mahasiswa" required></textarea>
                        <small class="form-text text-muted">Catatan ini akan dikirimkan kepada mahasiswa sebagai feedback.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times-circle mr-1"></i> Ya, Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Document Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">
                    <i class="fas fa-file-pdf mr-2"></i>Preview Dokumen
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="height: 80vh;">
                <iframe id="pdfViewer" style="width: 100%; height: 100%; border: none;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.badge-lg {
    font-size: 1.1rem;
    padding: 8px 16px;
}

.document-item {
    transition: all 0.3s ease;
}

.document-item:hover {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
</style>

@endsection

@section('scripts')
@parent
<script>
// Document Preview Handler
$(document).ready(function() {
    $('.preview-doc').on('click', function() {
        const url = $(this).data('url');
        $('#pdfViewer').attr('src', url);
        $('#previewModal').modal('show');
    });

    // Clear iframe when modal is closed to stop loading
    $('#previewModal').on('hidden.bs.modal', function () {
        $('#pdfViewer').attr('src', '');
    });
});
</script>
@endsection
