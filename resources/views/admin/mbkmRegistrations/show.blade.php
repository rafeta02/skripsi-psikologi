@extends('layouts.admin')
@section('content')

<div class="content">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Student Information Card -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-user-graduate mr-2"></i>
                        Informasi Mahasiswa
                    </h3>
                </div>
                <div class="card-body">
                    @php
                        $mahasiswa = $mbkmRegistration->application->mahasiswa ?? null;
                    @endphp
                    @if($mahasiswa)
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th width="40%">Nama</th>
                                        <td>: {{ $mahasiswa->nama }}</td>
                                    </tr>
                                    <tr>
                                        <th>NIM</th>
                                        <td>: {{ $mahasiswa->nim }}</td>
                                    </tr>
                                    <tr>
                                        <th>Prodi</th>
                                        <td>: {{ $mahasiswa->prodi->name ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th width="40%">Jenjang</th>
                                        <td>: {{ $mahasiswa->jenjang->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>: {{ $mahasiswa->email ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>No. Telepon</th>
                                        <td>: {{ $mahasiswa->nomor_telepon ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Data mahasiswa tidak tersedia.
                        </div>
                    @endif
                </div>
            </div>

            <!-- MBKM Information Card -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-briefcase mr-2"></i>
                        Informasi MBKM & Skripsi
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Kelompok Riset:</label>
                                <p>{{ $mbkmRegistration->research_group->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Tema Keilmuan:</label>
                                <p>{{ $mbkmRegistration->theme->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Judul Kegiatan MBKM:</label>
                        <p class="text-justify">{{ $mbkmRegistration->title_mbkm ?? 'N/A' }}</p>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Judul Skripsi:</label>
                        <p class="text-justify">{{ $mbkmRegistration->title ?? 'N/A' }}</p>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Preferensi Dosen Pembimbing:</label>
                        <p>{{ $mbkmRegistration->preference_supervision->nama ?? 'N/A' }}</p>
                    </div>

                    @if($mbkmRegistration->note)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Catatan:</strong> {{ $mbkmRegistration->note }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Academic Information Card -->
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-graduation-cap mr-2"></i>
                        Informasi Akademik
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Total SKS yang Telah Diambil:</label>
                                <p>{{ $mbkmRegistration->total_sks_taken ?? '-' }} SKS</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Jumlah SKS MKP:</label>
                                <p>{{ $mbkmRegistration->sks_mkp_taken ?? '-' }} SKS</p>
                            </div>
                        </div>
                    </div>

                    <h5 class="mt-3 mb-3"><strong>Nilai Mata Kuliah:</strong></h5>
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="60%">MK Kuantitatif:</th>
                                    <td><span class="badge badge-primary">{{ $mbkmRegistration->nilai_mk_kuantitatif ?? '-' }}</span></td>
                                </tr>
                                <tr>
                                    <th>MK Kualitatif:</th>
                                    <td><span class="badge badge-primary">{{ $mbkmRegistration->nilai_mk_kualitatif ?? '-' }}</span></td>
                                </tr>
                                <tr>
                                    <th>MK Statistika Dasar:</th>
                                    <td><span class="badge badge-primary">{{ $mbkmRegistration->nilai_mk_statistika_dasar ?? '-' }}</span></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="60%">MK Statistika Lanjutan:</th>
                                    <td><span class="badge badge-primary">{{ $mbkmRegistration->nilai_mk_statistika_lanjutan ?? '-' }}</span></td>
                                </tr>
                                <tr>
                                    <th>MK Konstruksi Tes:</th>
                                    <td><span class="badge badge-primary">{{ $mbkmRegistration->nilai_mk_konstruksi_tes ?? '-' }}</span></td>
                                </tr>
                                <tr>
                                    <th>MK TPS:</th>
                                    <td><span class="badge badge-primary">{{ $mbkmRegistration->nilai_mk_tps ?? '-' }}</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Group Members Card -->
            @if($mbkmRegistration->groupMembers && $mbkmRegistration->groupMembers->count() > 0)
            <div class="card">
                <div class="card-header bg-purple text-white" style="background-color: #6f42c1;">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-users mr-2"></i>
                        Anggota Kelompok MBKM
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="20%">NIM</th>
                                    <th width="50%">Nama</th>
                                    <th width="25%">Peran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mbkmRegistration->groupMembers as $index => $member)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $member->mahasiswa->nim ?? '-' }}</td>
                                    <td>{{ $member->mahasiswa->nama ?? '-' }}</td>
                                    <td>
                                        @if($member->role == 'ketua')
                                            <span class="badge badge-success">Ketua</span>
                                        @else
                                            <span class="badge badge-secondary">Anggota</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Documents Card -->
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-file-pdf mr-2"></i>
                        Dokumen Persyaratan
                    </h3>
                </div>
                <div class="card-body">
                    <!-- KHS Files -->
                    <div class="form-group">
                        <label class="font-weight-bold">KHS (Kartu Hasil Studi):</label>
                        @if($mbkmRegistration->khs_all->count() > 0)
                            <div class="list-group">
                                @foreach($mbkmRegistration->khs_all as $key => $media)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
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
                            <p class="text-muted">Tidak ada file KHS</p>
                        @endif
                    </div>

                    <!-- KRS File -->
                    <div class="form-group">
                        <label class="font-weight-bold">KRS Semester Terakhir:</label>
                        @if($mbkmRegistration->krs_latest)
                            <div class="list-group">
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-file-pdf text-danger mr-2"></i>
                                        <span>KRS Latest</span>
                                        <br>
                                        <small class="text-muted">{{ $mbkmRegistration->krs_latest->file_name }}</small>
                                    </div>
                                    <div class="btn-group">
                                        <a href="{{ $mbkmRegistration->krs_latest->getUrl() }}" target="_blank" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <button type="button" class="btn btn-sm btn-info preview-doc" 
                                                data-url="{{ $mbkmRegistration->krs_latest->getUrl() }}" 
                                                data-type="pdf">
                                            <i class="fas fa-expand"></i> Preview
                                        </button>
                                        <a href="{{ $mbkmRegistration->krs_latest->getUrl() }}" download class="btn btn-sm btn-success">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p class="text-muted">Tidak ada file KRS</p>
                        @endif
                    </div>

                    <!-- SPP File -->
                    <div class="form-group">
                        <label class="font-weight-bold">Bukti Pembayaran SPP:</label>
                        @if($mbkmRegistration->spp)
                            <div class="list-group">
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-receipt text-success mr-2"></i>
                                        <span>Bukti Pembayaran SPP</span>
                                        <br>
                                        <small class="text-muted">{{ $mbkmRegistration->spp->file_name }}</small>
                                    </div>
                                    <div class="btn-group">
                                        <a href="{{ $mbkmRegistration->spp->getUrl() }}" target="_blank" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <button type="button" class="btn btn-sm btn-info preview-doc" 
                                                data-url="{{ $mbkmRegistration->spp->getUrl() }}" 
                                                data-type="pdf">
                                            <i class="fas fa-expand"></i> Preview
                                        </button>
                                        <a href="{{ $mbkmRegistration->spp->getUrl() }}" download class="btn btn-sm btn-success">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p class="text-muted">Tidak ada bukti pembayaran SPP</p>
                        @endif
                    </div>

                    <!-- Proposal MBKM File -->
                    <div class="form-group">
                        <label class="font-weight-bold">Proposal MBKM:</label>
                        @if($mbkmRegistration->proposal_mbkm)
                            <div class="list-group">
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-file-alt text-primary mr-2"></i>
                                        <span>Proposal MBKM</span>
                                        <br>
                                        <small class="text-muted">{{ $mbkmRegistration->proposal_mbkm->file_name }}</small>
                                    </div>
                                    <div class="btn-group">
                                        <a href="{{ $mbkmRegistration->proposal_mbkm->getUrl() }}" target="_blank" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <button type="button" class="btn btn-sm btn-info preview-doc" 
                                                data-url="{{ $mbkmRegistration->proposal_mbkm->getUrl() }}" 
                                                data-type="pdf">
                                            <i class="fas fa-expand"></i> Preview
                                        </button>
                                        <a href="{{ $mbkmRegistration->proposal_mbkm->getUrl() }}" download class="btn btn-sm btn-success">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p class="text-muted">Tidak ada proposal MBKM</p>
                        @endif
                    </div>

                    <!-- Recognition Form File -->
                    <div class="form-group">
                        <label class="font-weight-bold">Form Konversi SKS:</label>
                        @if($mbkmRegistration->recognition_form)
                            <div class="list-group">
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-file-signature text-info mr-2"></i>
                                        <span>Form Konversi SKS</span>
                                        <br>
                                        <small class="text-muted">{{ $mbkmRegistration->recognition_form->file_name }}</small>
                                    </div>
                                    <div class="btn-group">
                                        <a href="{{ $mbkmRegistration->recognition_form->getUrl() }}" target="_blank" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <button type="button" class="btn btn-sm btn-info preview-doc" 
                                                data-url="{{ $mbkmRegistration->recognition_form->getUrl() }}" 
                                                data-type="pdf">
                                            <i class="fas fa-expand"></i> Preview
                                        </button>
                                        <a href="{{ $mbkmRegistration->recognition_form->getUrl() }}" download class="btn btn-sm btn-success">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p class="text-muted">Tidak ada form konversi SKS</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action History Card -->
            @if($mbkmRegistration->application && $mbkmRegistration->application->actions->count() > 0)
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-history mr-2"></i>
                        Riwayat Aksi
                    </h3>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach($mbkmRegistration->application->actions->sortByDesc('created_at') as $action)
                            <div class="time-label">
                                <span class="bg-{{ $action->action_type === 'approved' ? 'success' : ($action->action_type === 'rejected' ? 'danger' : 'warning') }}">
                                    {{ $action->created_at->format('d M Y H:i') }}
                                </span>
                            </div>
                            <div>
                                <i class="fas fa-{{ $action->action_type === 'approved' ? 'check' : ($action->action_type === 'rejected' ? 'times' : 'edit') }} bg-{{ $action->action_type === 'approved' ? 'success' : ($action->action_type === 'rejected' ? 'danger' : 'warning') }}"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fas fa-user mr-1"></i> {{ $action->actionBy->name ?? 'System' }}</span>
                                    <h3 class="timeline-header">{{ ucfirst(str_replace('_', ' ', $action->action_type)) }}</h3>
                                    @if($action->notes)
                                        <div class="timeline-body">
                                            {{ $action->notes }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-info-circle mr-2"></i>
                        Status Pendaftaran
                    </h3>
                </div>
                <div class="card-body">
                    @php
                        $status = $mbkmRegistration->application->status ?? 'unknown';
                        $statusConfig = [
                            'submitted' => ['badge' => 'info', 'icon' => 'clock', 'text' => 'Menunggu Review'],
                            'approved' => ['badge' => 'success', 'icon' => 'check-circle', 'text' => 'Disetujui'],
                            'rejected' => ['badge' => 'danger', 'icon' => 'times-circle', 'text' => 'Ditolak'],
                            'revision' => ['badge' => 'warning', 'icon' => 'edit', 'text' => 'Perlu Revisi'],
                        ];
                        $config = $statusConfig[$status] ?? ['badge' => 'secondary', 'icon' => 'question', 'text' => 'Unknown'];
                    @endphp
                    
                    <div class="text-center mb-3">
                        <span class="badge badge-{{ $config['badge'] }} p-3" style="font-size: 1.2rem;">
                            <i class="fas fa-{{ $config['icon'] }} mr-2"></i>
                            {{ $config['text'] }}
                        </span>
                    </div>

                    <div class="alert alert-primary">
                        <i class="fas fa-briefcase mr-2"></i>
                        <strong>Jalur MBKM</strong>
                    </div>

                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>ID Pendaftaran</th>
                            <td>: #{{ $mbkmRegistration->id }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Daftar</th>
                            <td>: {{ $mbkmRegistration->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        @if($mbkmRegistration->approval_date)
                        <tr>
                            <th>Tanggal Disetujui</th>
                            <td>: {{ \Carbon\Carbon::parse($mbkmRegistration->approval_date)->format('d M Y H:i') }}</td>
                        </tr>
                        @endif
                    </table>

                    @if($mbkmRegistration->rejection_reason)
                        <div class="alert alert-danger mt-3">
                            <strong><i class="fas fa-exclamation-triangle mr-2"></i>Alasan Penolakan:</strong>
                            <p class="mb-0 mt-2">{{ $mbkmRegistration->rejection_reason }}</p>
                        </div>
                    @endif

                    @if($mbkmRegistration->revision_notes)
                        <div class="alert alert-warning mt-3">
                            <strong><i class="fas fa-edit mr-2"></i>Catatan Revisi:</strong>
                            <p class="mb-0 mt-2">{{ $mbkmRegistration->revision_notes }}</p>
                        </div>
                    @endif

                    @php
                        $supervisorAssignment = \App\Models\ApplicationAssignment::where('application_id', $mbkmRegistration->application_id)
                            ->where('role', 'supervisor')
                            ->with('lecturer')
                            ->first();
                    @endphp

                    @if($supervisorAssignment)
                        <hr>
                        <h6 class="font-weight-bold mb-3">
                            <i class="fas fa-user-tie mr-2"></i>Status Persetujuan Dosen
                        </h6>
                        <div class="mb-2">
                            <strong>Dosen Pembimbing:</strong><br>
                            {{ $supervisorAssignment->lecturer->nama ?? 'N/A' }}
                        </div>
                        <div class="mb-2">
                            <strong>Status:</strong><br>
                            @if($supervisorAssignment->status == 'assigned')
                                <span class="badge badge-warning">
                                    <i class="fas fa-hourglass-half mr-1"></i> Menunggu Persetujuan Dosen
                                </span>
                            @elseif($supervisorAssignment->status == 'accepted')
                                <span class="badge badge-success">
                                    <i class="fas fa-check-circle mr-1"></i> Dosen Menyetujui
                                </span>
                            @elseif($supervisorAssignment->status == 'rejected')
                                <span class="badge badge-danger">
                                    <i class="fas fa-times-circle mr-1"></i> Dosen Menolak
                                </span>
                            @endif
                        </div>
                        @if($supervisorAssignment->responded_at)
                            <div class="mb-2">
                                <strong>Tanggal Respons:</strong><br>
                                <small>{{ \Carbon\Carbon::parse($supervisorAssignment->responded_at)->format('d M Y H:i') }}</small>
                            </div>
                        @endif
                        @if($supervisorAssignment->note)
                            <div class="alert alert-info mt-2 mb-0" style="font-size: 0.9rem;">
                                <strong><i class="fas fa-comment mr-1"></i>Catatan Dosen:</strong>
                                <p class="mb-0 mt-1">{{ $supervisorAssignment->note }}</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Action Buttons Card -->
            @if($mbkmRegistration->application && $mbkmRegistration->application->status === 'submitted')
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-tasks mr-2"></i>
                        Aksi Admin
                    </h3>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-success btn-lg btn-block mb-2" 
                                onclick="showApproveModal({{ $mbkmRegistration->id }})">
                            <i class="fas fa-check-circle mr-2"></i> Setujui Pendaftaran
                        </button>
                        
                        <button type="button" class="btn btn-warning btn-lg btn-block mb-2" 
                                onclick="showRevisionModal({{ $mbkmRegistration->id }})">
                            <i class="fas fa-edit mr-2"></i> Minta Revisi
                        </button>
                        
                        <button type="button" class="btn btn-danger btn-lg btn-block" 
                                onclick="showRejectModal({{ $mbkmRegistration->id }})">
                            <i class="fas fa-times-circle mr-2"></i> Tolak Pendaftaran
                        </button>
                    </div>
                </div>
            </div>
            @endif

            <!-- Navigation Card -->
            <div class="card">
                <div class="card-body">
                    <a class="btn btn-default btn-block" href="{{ route('admin.mbkm-registrations.index') }}">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                    </a>
                    @can('mbkm_registration_edit')
                        <a class="btn btn-info btn-block" href="{{ route('admin.mbkm-registrations.edit', $mbkmRegistration->id) }}">
                            <i class="fas fa-edit mr-2"></i> Edit Pendaftaran
                        </a>
                    @endcan
                </div>
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

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle mr-2"></i>
                    Setujui Pendaftaran MBKM
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="approveForm">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        Pendaftaran MBKM akan disetujui dan mahasiswa dapat melanjutkan ke tahap berikutnya.
                    </div>
                    <div class="form-group">
                        <label for="approve_notes">Catatan (Opsional)</label>
                        <textarea class="form-control" id="approve_notes" name="notes" rows="3" 
                                  placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check mr-1"></i> Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-times-circle mr-2"></i>
                    Tolak Pendaftaran MBKM
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="rejectForm">
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Pastikan Anda memberikan alasan yang jelas untuk penolakan.
                    </div>
                    <div class="form-group">
                        <label for="reject_reason">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="reject_reason" name="reason" rows="4" 
                                  placeholder="Jelaskan alasan penolakan..." required minlength="10"></textarea>
                        <small class="form-text text-muted">Minimal 10 karakter</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times mr-1"></i> Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Revision Modal -->
<div class="modal fade" id="revisionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="fas fa-edit mr-2"></i>
                    Minta Revisi
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="revisionForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="revision_notes">Catatan Revisi <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="revision_notes" name="notes" rows="4" 
                                  placeholder="Jelaskan revisi yang diperlukan..." required minlength="10"></textarea>
                        <small class="form-text text-muted">Minimal 10 karakter</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-edit mr-1"></i> Kirim Permintaan Revisi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let currentRegistrationId = {{ $mbkmRegistration->id }};

// Document Preview
$('.preview-doc').on('click', function() {
    const url = $(this).data('url');
    $('#pdfViewer').attr('src', url);
    $('#previewModal').modal('show');
});

// Modal Functions
function showApproveModal(id) {
    currentRegistrationId = id;
    $('#approveModal').modal('show');
}

function showRejectModal(id) {
    currentRegistrationId = id;
    $('#rejectModal').modal('show');
}

function showRevisionModal(id) {
    currentRegistrationId = id;
    $('#revisionModal').modal('show');
}

// Approve Form Submit
$('#approveForm').on('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = $(this).find('button[type="submit"]');
    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...');
    
    $.ajax({
        url: `/admin/mbkm-registrations/${currentRegistrationId}/approve`,
        method: 'POST',
        data: $(this).serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $('#approveModal').modal('hide');
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: response.message || 'Pendaftaran MBKM berhasil disetujui',
                timer: 2000
            }).then(() => {
                location.reload();
            });
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: xhr.responseJSON?.message || 'Terjadi kesalahan'
            });
            submitBtn.prop('disabled', false).html('<i class="fas fa-check mr-1"></i> Setujui');
        }
    });
});

// Reject Form Submit
$('#rejectForm').on('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = $(this).find('button[type="submit"]');
    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...');
    
    $.ajax({
        url: `/admin/mbkm-registrations/${currentRegistrationId}/reject`,
        method: 'POST',
        data: $(this).serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $('#rejectModal').modal('hide');
            Swal.fire({
                icon: 'success',
                title: 'Pendaftaran Ditolak',
                text: response.message || 'Pendaftaran MBKM berhasil ditolak',
                timer: 2000
            }).then(() => {
                location.reload();
            });
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: xhr.responseJSON?.message || 'Terjadi kesalahan'
            });
            submitBtn.prop('disabled', false).html('<i class="fas fa-times mr-1"></i> Tolak');
        }
    });
});

// Revision Form Submit
$('#revisionForm').on('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = $(this).find('button[type="submit"]');
    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...');
    
    $.ajax({
        url: `/admin/mbkm-registrations/${currentRegistrationId}/request-revision`,
        method: 'POST',
        data: $(this).serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $('#revisionModal').modal('hide');
            Swal.fire({
                icon: 'success',
                title: 'Revisi Diminta',
                text: response.message || 'Permintaan revisi berhasil dikirim',
                timer: 2000
            }).then(() => {
                location.reload();
            });
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: xhr.responseJSON?.message || 'Terjadi kesalahan'
            });
            submitBtn.prop('disabled', false).html('<i class="fas fa-edit mr-1"></i> Kirim Permintaan Revisi');
        }
    });
});

// Clear forms on modal close
$('.modal').on('hidden.bs.modal', function() {
    $(this).find('form')[0].reset();
});
</script>
@endsection
