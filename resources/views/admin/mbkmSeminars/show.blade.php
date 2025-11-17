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
                        $mahasiswa = $mbkmSeminar->application->mahasiswa ?? null;
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

            <!-- MBKM Seminar Information Card -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-presentation mr-2"></i>
                        Informasi Seminar MBKM
                    </h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Judul Skripsi:</label>
                        <p class="text-justify">{{ $mbkmSeminar->title ?? 'N/A' }}</p>
                    </div>

                    @php
                        $mbkmRegistration = \App\Models\MbkmRegistration::where('application_id', $mbkmSeminar->application_id)->first();
                    @endphp

                    @if($mbkmRegistration)
                        <hr>
                        <h5 class="font-weight-bold mb-3">Informasi MBKM & Pendaftaran</h5>
                        
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
                    @endif

                    <div class="form-group">
                        <label class="font-weight-bold">Tanggal Pengajuan Seminar:</label>
                        <p>{{ $mbkmSeminar->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Dosen Pembimbing & Penguji -->
            @php
                $assignments = \App\Models\ApplicationAssignment::where('application_id', $mbkmSeminar->application_id)
                    ->with('lecturer')
                    ->get();
            @endphp

            @if($assignments->count() > 0)
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-user-tie mr-2"></i>
                        Dosen Pembimbing & Penguji
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="25%">Peran</th>
                                    <th width="40%">Nama Dosen</th>
                                    <th width="15%">Status</th>
                                    <th width="15%">Respons</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignments as $index => $assignment)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($assignment->role == 'supervisor')
                                            <span class="badge badge-primary">Pembimbing</span>
                                        @elseif($assignment->role == 'examiner_1')
                                            <span class="badge badge-info">Penguji 1</span>
                                        @elseif($assignment->role == 'examiner_2')
                                            <span class="badge badge-info">Penguji 2</span>
                                        @else
                                            <span class="badge badge-secondary">{{ ucfirst($assignment->role) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $assignment->lecturer->nama ?? 'N/A' }}</td>
                                    <td>
                                        @if($assignment->status == 'assigned')
                                            <span class="badge badge-warning">Menunggu</span>
                                        @elseif($assignment->status == 'accepted')
                                            <span class="badge badge-success">Diterima</span>
                                        @elseif($assignment->status == 'rejected')
                                            <span class="badge badge-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($assignment->responded_at)
                                            <small>{{ \Carbon\Carbon::parse($assignment->responded_at)->format('d M Y') }}</small>
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                </tr>
                                @if($assignment->note)
                                <tr>
                                    <td colspan="5">
                                        <small><strong>Catatan:</strong> {{ $assignment->note }}</small>
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Schedule Card -->
            @php
                $schedule = \App\Models\ApplicationSchedule::where('application_id', $mbkmSeminar->application_id)
                    ->where('schedule_type', 'seminar')
                    ->with('ruang')
                    ->first();
            @endphp

            @if($schedule)
            <div class="card">
                <div class="card-header text-white" style="background-color: #6f42c1;">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Jadwal Seminar
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold"><i class="far fa-clock mr-2"></i>Waktu:</label>
                                <p>{{ $schedule->waktu }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold"><i class="fas fa-map-marker-alt mr-2"></i>Tempat:</label>
                                <p>{{ $schedule->ruang->name ?? $schedule->custom_place ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    @if($schedule->online_meeting)
                        <div class="form-group">
                            <label class="font-weight-bold"><i class="fas fa-video mr-2"></i>Link Meeting:</label>
                            <p><a href="{{ $schedule->online_meeting }}" target="_blank" class="btn btn-sm btn-primary">
                                <i class="fas fa-external-link-alt mr-1"></i> Buka Link Meeting
                            </a></p>
                        </div>
                    @endif
                    @if($schedule->note)
                        <div class="alert alert-info mb-0">
                            <strong><i class="fas fa-info-circle mr-2"></i>Catatan:</strong>
                            <p class="mb-0">{{ $schedule->note }}</p>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Seminar Result Card -->
            @php
                $result = \App\Models\ApplicationResultSeminar::where('application_id', $mbkmSeminar->application_id)
                    ->first();
            @endphp

            @if($result)
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-clipboard-check mr-2"></i>
                        Hasil Seminar
                    </h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Status Hasil:</label>
                        <div>
                            @if($result->result == 'passed')
                                <span class="badge badge-success p-2" style="font-size: 1rem;">
                                    <i class="fas fa-check-circle mr-1"></i> Lulus
                                </span>
                            @elseif($result->result == 'revision')
                                <span class="badge badge-warning p-2" style="font-size: 1rem;">
                                    <i class="fas fa-edit mr-1"></i> Revisi
                                </span>
                            @elseif($result->result == 'failed')
                                <span class="badge badge-danger p-2" style="font-size: 1rem;">
                                    <i class="fas fa-times-circle mr-1"></i> Tidak Lulus
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($result->note)
                        <div class="form-group">
                            <label class="font-weight-bold">Catatan:</label>
                            <div class="alert alert-info">
                                {{ $result->note }}
                            </div>
                        </div>
                    @endif

                    @if($result->revision_deadline)
                        <div class="form-group">
                            <label class="font-weight-bold">Deadline Revisi:</label>
                            <p><span class="badge badge-warning">{{ $result->revision_deadline }}</span></p>
                        </div>
                    @endif

                    <!-- Result Documents -->
                    <hr>
                    <h5 class="font-weight-bold mb-3">Dokumen Hasil Seminar</h5>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Berita Acara:</label>
                                @if($result->report_document)
                                    <div class="btn-group-vertical d-block">
                                        <a href="{{ $result->report_document->getUrl() }}" target="_blank" class="btn btn-sm btn-primary mb-1">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="{{ $result->report_document->getUrl() }}" download class="btn btn-sm btn-success">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                @else
                                    <p class="text-muted">Belum ada</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Daftar Hadir:</label>
                                @if($result->attendance_document)
                                    <div class="btn-group-vertical d-block">
                                        <a href="{{ $result->attendance_document->getUrl() }}" target="_blank" class="btn btn-sm btn-primary mb-1">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="{{ $result->attendance_document->getUrl() }}" download class="btn btn-sm btn-success">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                @else
                                    <p class="text-muted">Belum ada</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Documents Card -->
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-file-pdf mr-2"></i>
                        Dokumen Seminar
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Proposal Document -->
                    <div class="form-group">
                        <label class="font-weight-bold">Proposal Skripsi:</label>
                        @if($mbkmSeminar->proposal_document)
                            <div class="list-group">
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-file-pdf text-danger mr-2"></i>
                                        <span>Proposal Document</span>
                                        <br>
                                        <small class="text-muted">{{ $mbkmSeminar->proposal_document->file_name }}</small>
                                    </div>
                                    <div class="btn-group">
                                        <a href="{{ $mbkmSeminar->proposal_document->getUrl() }}" target="_blank" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <button type="button" class="btn btn-sm btn-info preview-doc" 
                                                data-url="{{ $mbkmSeminar->proposal_document->getUrl() }}" 
                                                data-type="pdf">
                                            <i class="fas fa-expand"></i> Preview
                                        </button>
                                        <a href="{{ $mbkmSeminar->proposal_document->getUrl() }}" download class="btn btn-sm btn-success">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p class="text-muted">Tidak ada file proposal</p>
                        @endif
                    </div>

                    <!-- Approval Document -->
                    <div class="form-group">
                        <label class="font-weight-bold">Surat Persetujuan Pembimbing:</label>
                        @if($mbkmSeminar->approval_document)
                            <div class="list-group">
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-file-signature text-success mr-2"></i>
                                        <span>Approval Document</span>
                                        <br>
                                        <small class="text-muted">{{ $mbkmSeminar->approval_document->file_name }}</small>
                                    </div>
                                    <div class="btn-group">
                                        <a href="{{ $mbkmSeminar->approval_document->getUrl() }}" target="_blank" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <button type="button" class="btn btn-sm btn-info preview-doc" 
                                                data-url="{{ $mbkmSeminar->approval_document->getUrl() }}" 
                                                data-type="pdf">
                                            <i class="fas fa-expand"></i> Preview
                                        </button>
                                        <a href="{{ $mbkmSeminar->approval_document->getUrl() }}" download class="btn btn-sm btn-success">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p class="text-muted">Tidak ada file persetujuan</p>
                        @endif
                    </div>

                    <!-- Plagiarism Document -->
                    <div class="form-group">
                        <label class="font-weight-bold">Hasil Cek Plagiasi:</label>
                        @if($mbkmSeminar->plagiarism_document)
                            <div class="list-group">
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-file-alt text-info mr-2"></i>
                                        <span>Plagiarism Check</span>
                                        <br>
                                        <small class="text-muted">{{ $mbkmSeminar->plagiarism_document->file_name }}</small>
                                    </div>
                                    <div class="btn-group">
                                        <a href="{{ $mbkmSeminar->plagiarism_document->getUrl() }}" target="_blank" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <button type="button" class="btn btn-sm btn-info preview-doc" 
                                                data-url="{{ $mbkmSeminar->plagiarism_document->getUrl() }}" 
                                                data-type="pdf">
                                            <i class="fas fa-expand"></i> Preview
                                        </button>
                                        <a href="{{ $mbkmSeminar->plagiarism_document->getUrl() }}" download class="btn btn-sm btn-success">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p class="text-muted">Tidak ada hasil cek plagiasi</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action History Card -->
            @if($mbkmSeminar->application && $mbkmSeminar->application->actions->count() > 0)
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-history mr-2"></i>
                        Riwayat Aksi
                    </h3>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach($mbkmSeminar->application->actions->sortByDesc('created_at') as $action)
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
                        Status Seminar
                    </h3>
                </div>
                <div class="card-body">
                    @php
                        $status = $mbkmSeminar->application->status ?? 'unknown';
                        $statusConfig = [
                            'submitted' => ['badge' => 'info', 'icon' => 'clock', 'text' => 'Menunggu Review'],
                            'approved' => ['badge' => 'success', 'icon' => 'check-circle', 'text' => 'Disetujui'],
                            'rejected' => ['badge' => 'danger', 'icon' => 'times-circle', 'text' => 'Ditolak'],
                            'revision' => ['badge' => 'warning', 'icon' => 'edit', 'text' => 'Perlu Revisi'],
                            'scheduled' => ['badge' => 'primary', 'icon' => 'calendar', 'text' => 'Terjadwal'],
                            'done' => ['badge' => 'success', 'icon' => 'check-double', 'text' => 'Selesai'],
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
                        <strong>Jalur MBKM - Seminar Proposal</strong>
                    </div>

                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>ID Seminar</th>
                            <td>: #{{ $mbkmSeminar->id }}</td>
                        </tr>
                        <tr>
                            <th>ID Aplikasi</th>
                            <td>: #{{ $mbkmSeminar->application_id }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Dibuat</th>
                            <td>: {{ $mbkmSeminar->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        @if($mbkmSeminar->updated_at != $mbkmSeminar->created_at)
                        <tr>
                            <th>Terakhir Update</th>
                            <td>: {{ $mbkmSeminar->updated_at->format('d M Y H:i') }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Quick Stats Card -->
            <div class="card">
                <div class="card-header" style="background-color: #6f42c1; color: white;">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-chart-pie mr-2"></i>
                        Statistik
                    </h3>
                </div>
                <div class="card-body">
                    <div class="info-box bg-light">
                        <span class="info-box-icon bg-primary"><i class="fas fa-file-alt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Dokumen Upload</span>
                            <span class="info-box-number">
                                {{ collect([
                                    $mbkmSeminar->proposal_document,
                                    $mbkmSeminar->approval_document,
                                    $mbkmSeminar->plagiarism_document
                                ])->filter()->count() }} / 3
                            </span>
                        </div>
                    </div>

                    <div class="info-box bg-light">
                        <span class="info-box-icon bg-success"><i class="fas fa-user-tie"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Dosen Assigned</span>
                            <span class="info-box-number">{{ $assignments->count() }}</span>
                        </div>
                    </div>

                    <div class="info-box bg-light">
                        <span class="info-box-icon bg-warning"><i class="fas fa-calendar-check"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Jadwal</span>
                            <span class="info-box-number">
                                {{ $schedule ? 'Sudah Dijadwalkan' : 'Belum Dijadwalkan' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons Card -->
            @if($mbkmSeminar->application && $mbkmSeminar->application->status === 'submitted')
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
                                onclick="showApproveModal({{ $mbkmSeminar->id }})">
                            <i class="fas fa-check-circle mr-2"></i> Setujui Seminar
                        </button>
                        
                        <button type="button" class="btn btn-danger btn-lg btn-block" 
                                onclick="showRejectModal({{ $mbkmSeminar->id }})">
                            <i class="fas fa-times-circle mr-2"></i> Tolak Seminar
                        </button>
                    </div>
                </div>
            </div>
            @endif

            <!-- Navigation Card -->
            <div class="card">
                <div class="card-body">
                    <a class="btn btn-default btn-block mb-2" href="{{ route('admin.mbkm-seminars.index') }}">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                    </a>
                    @can('mbkm_seminar_edit')
                        <a class="btn btn-info btn-block mb-2" href="{{ route('admin.mbkm-seminars.edit', $mbkmSeminar->id) }}">
                            <i class="fas fa-edit mr-2"></i> Edit Seminar
                        </a>
                    @endcan
                    @if($mbkmSeminar->application)
                        <a class="btn btn-primary btn-block" href="{{ route('admin.applications.show', $mbkmSeminar->application_id) }}">
                            <i class="fas fa-eye mr-2"></i> Lihat Detail Aplikasi
                        </a>
                    @endif
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
                    Setujui Seminar MBKM
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="approveForm">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        Seminar MBKM akan disetujui dan mahasiswa dapat melanjutkan ke tahap berikutnya.
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
                    Tolak Seminar MBKM
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

@endsection

@section('scripts')
<script>
let currentSeminarId = {{ $mbkmSeminar->id }};

// Document Preview
$('.preview-doc').on('click', function() {
    const url = $(this).data('url');
    $('#pdfViewer').attr('src', url);
    $('#previewModal').modal('show');
});

// Modal Functions
function showApproveModal(id) {
    currentSeminarId = id;
    $('#approveModal').modal('show');
}

function showRejectModal(id) {
    currentSeminarId = id;
    $('#rejectModal').modal('show');
}

// Approve Form Submit
$('#approveForm').on('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = $(this).find('button[type="submit"]');
    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...');
    
    $.ajax({
        url: `/admin/mbkm-seminars/${currentSeminarId}/approve`,
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
                text: response.message || 'Seminar MBKM berhasil disetujui',
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
        url: `/admin/mbkm-seminars/${currentSeminarId}/reject`,
        method: 'POST',
        data: $(this).serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $('#rejectModal').modal('hide');
            Swal.fire({
                icon: 'success',
                title: 'Seminar Ditolak',
                text: response.message || 'Seminar MBKM berhasil ditolak',
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

// Clear forms on modal close
$('.modal').on('hidden.bs.modal', function() {
    $(this).find('form')[0].reset();
});
</script>
@endsection
