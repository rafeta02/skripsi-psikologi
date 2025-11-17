@extends('layouts.frontend')
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

            <!-- MBKM Registration Information Card -->
            @php
                $mbkmRegistration = \App\Models\MbkmRegistration::where('application_id', $mbkmSeminar->application_id)->first();
            @endphp
            @if($mbkmRegistration)
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
                        <label class="font-weight-bold">Judul Skripsi (dari Registrasi):</label>
                        <p class="text-justify">{{ $mbkmRegistration->title ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Seminar Proposal Information Card -->
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-presentation mr-2"></i>
                        Informasi Seminar Proposal
                    </h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Judul Proposal:</label>
                        <p class="text-justify">{{ $mbkmSeminar->title ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Dosen Assignments -->
            @php
                $assignments = \App\Models\ApplicationAssignment::where('application_id', $mbkmSeminar->application_id)
                    ->with('lecturer')
                    ->get();
            @endphp
            @if($assignments && $assignments->count() > 0)
            <div class="card">
                <div class="card-header bg-purple text-white" style="background-color: #6f42c1;">
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
                                    <th width="10%">No</th>
                                    <th width="25%">Peran</th>
                                    <th width="40%">Nama Dosen</th>
                                    <th width="25%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignments as $index => $assignment)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($assignment->role == 'supervisor')
                                            <i class="fas fa-chalkboard-teacher mr-1"></i> Pembimbing
                                        @elseif($assignment->role == 'examiner_1')
                                            <i class="fas fa-user-check mr-1"></i> Penguji 1
                                        @elseif($assignment->role == 'examiner_2')
                                            <i class="fas fa-user-check mr-1"></i> Penguji 2
                                        @else
                                            {{ ucfirst($assignment->role) }}
                                        @endif
                                    </td>
                                    <td>{{ $assignment->lecturer->nama ?? 'N/A' }}</td>
                                    <td>
                                        @if($assignment->status == 'assigned')
                                            <span class="badge badge-warning">
                                                <i class="fas fa-hourglass-half mr-1"></i> Menunggu
                                            </span>
                                        @elseif($assignment->status == 'accepted')
                                            <span class="badge badge-success">
                                                <i class="fas fa-check-circle mr-1"></i> Diterima
                                            </span>
                                        @elseif($assignment->status == 'rejected')
                                            <span class="badge badge-danger">
                                                <i class="fas fa-times-circle mr-1"></i> Ditolak
                                            </span>
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

            <!-- Schedule Information -->
            @php
                $schedule = \App\Models\Schedule::where('application_id', $mbkmSeminar->application_id)
                    ->where('schedule_type', 'seminar')
                    ->with('ruang')
                    ->first();
            @endphp
            @if($schedule)
            <div class="card">
                <div class="card-header" style="background-color: #17a2b8; color: white;">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Jadwal Seminar
                    </h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="font-weight-bold">
                                        <i class="fas fa-clock mr-1"></i> Waktu:
                                    </label>
                                    <p class="mb-0">{{ $schedule->waktu ? \Carbon\Carbon::parse($schedule->waktu)->format('d F Y, H:i') : 'Belum ditentukan' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="font-weight-bold">
                                        <i class="fas fa-map-marker-alt mr-1"></i> Tempat:
                                    </label>
                                    <p class="mb-0">
                                        @if($schedule->ruang)
                                            {{ $schedule->ruang->name }}
                                        @elseif($schedule->custom_place)
                                            {{ $schedule->custom_place }}
                                        @else
                                            Belum ditentukan
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        @if($schedule->online_meeting)
                            <div class="mt-2">
                                <label class="font-weight-bold">
                                    <i class="fas fa-video mr-1"></i> Link Meeting Online:
                                </label>
                                <p class="mb-0">
                                    <a href="{{ $schedule->online_meeting }}" target="_blank" class="btn btn-sm btn-primary">
                                        <i class="fas fa-external-link-alt mr-1"></i> Buka Link Meeting
                                    </a>
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Documents Card -->
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-file-pdf mr-2"></i>
                        Dokumen Seminar Proposal
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Proposal Document -->
                    <div class="form-group">
                        <label class="font-weight-bold">Dokumen Proposal:</label>
                        @if($mbkmSeminar->proposal_document)
                            <div class="list-group">
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-file-pdf text-danger mr-2"></i>
                                        <span>Dokumen Proposal</span>
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
                            <p class="text-muted">Tidak ada dokumen proposal</p>
                        @endif
                    </div>

                    <!-- Approval Document -->
                    <div class="form-group">
                        <label class="font-weight-bold">Form Persetujuan Pembimbing:</label>
                        @if($mbkmSeminar->approval_document)
                            <div class="list-group">
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-file-signature text-success mr-2"></i>
                                        <span>Form Persetujuan</span>
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
                            <p class="text-muted">Tidak ada form persetujuan</p>
                        @endif
                    </div>

                    <!-- Plagiarism Document -->
                    <div class="form-group">
                        <label class="font-weight-bold">Hasil Cek Plagiarisme:</label>
                        @if($mbkmSeminar->plagiarism_document)
                            <div class="list-group">
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-shield-alt text-info mr-2"></i>
                                        <span>Hasil Cek Plagiarisme</span>
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
                            <p class="text-muted">Tidak ada hasil cek plagiarisme</p>
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
                            'scheduled' => ['badge' => 'primary', 'icon' => 'calendar-check', 'text' => 'Terjadwal'],
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
                        <strong>Seminar Proposal MBKM</strong>
                    </div>

                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>ID Seminar</th>
                            <td>: #{{ $mbkmSeminar->id }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Daftar</th>
                            <td>: {{ $mbkmSeminar->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Tahap</th>
                            <td>: {{ ucfirst($mbkmSeminar->application->stage ?? 'N/A') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Navigation Card -->
            <div class="card">
                <div class="card-body">
                    <a class="btn btn-default btn-block" href="{{ route('frontend.mbkm-seminars.index') }}">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                    </a>
                    @if($mbkmSeminar->application->status != 'approved' && $mbkmSeminar->application->status != 'done')
                        <a class="btn btn-info btn-block" href="{{ route('frontend.mbkm-seminars.edit', $mbkmSeminar->id) }}">
                            <i class="fas fa-edit mr-2"></i> Edit Seminar
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

@endsection

@section('scripts')
<script>
// Document Preview
$('.preview-doc').on('click', function() {
    const url = $(this).data('url');
    $('#pdfViewer').attr('src', url);
    $('#previewModal').modal('show');
});

// Clear iframe when modal is closed
$('#previewModal').on('hidden.bs.modal', function() {
    $('#pdfViewer').attr('src', '');
});
</script>
@endsection