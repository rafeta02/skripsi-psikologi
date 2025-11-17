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
                        $mahasiswa = $skripsiRegistration->application->mahasiswa ?? null;
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

            <!-- Thesis Information Card -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-book mr-2"></i>
                        Informasi Skripsi
                    </h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Tema Keilmuan:</label>
                        <p>{{ $skripsiRegistration->theme->name ?? 'N/A' }}</p>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Judul Skripsi:</label>
                        <p class="text-justify">{{ $skripsiRegistration->title ?? 'N/A' }}</p>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Abstrak:</label>
                        <p class="text-justify">{{ $skripsiRegistration->abstract ?? 'Tidak ada abstrak' }}</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Dosen TPS:</label>
                                <p>{{ $skripsiRegistration->tps_lecturer->nama ?? 'Tidak ada' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Preferensi Dosen Pembimbing:</label>
                                <p>{{ $skripsiRegistration->preference_supervision->nama ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    @if($skripsiRegistration->assigned_supervisor)
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle mr-2"></i>
                            <strong>Dosen Pembimbing Ditugaskan:</strong> {{ $skripsiRegistration->assigned_supervisor->nama }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Documents Card -->
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-file-pdf mr-2"></i>
                        Dokumen Persyaratan
                    </h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="font-weight-bold">KHS (Kartu Hasil Studi):</label>
                        @if($skripsiRegistration->khs_all->count() > 0)
                            <div class="list-group">
                                @foreach($skripsiRegistration->khs_all as $key => $media)
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

                    <div class="form-group">
                        <label class="font-weight-bold">KRS Semester Terakhir:</label>
                        @if($skripsiRegistration->krs_latest)
                            <div class="list-group">
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-file-pdf text-danger mr-2"></i>
                                        <span>KRS Latest</span>
                                        <br>
                                        <small class="text-muted">{{ $skripsiRegistration->krs_latest->file_name }}</small>
                                    </div>
                                    <div class="btn-group">
                                        <a href="{{ $skripsiRegistration->krs_latest->getUrl() }}" target="_blank" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <button type="button" class="btn btn-sm btn-info preview-doc" 
                                                data-url="{{ $skripsiRegistration->krs_latest->getUrl() }}" 
                                                data-type="pdf">
                                            <i class="fas fa-expand"></i> Preview
                                        </button>
                                        <a href="{{ $skripsiRegistration->krs_latest->getUrl() }}" download class="btn btn-sm btn-success">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p class="text-muted">Tidak ada file KRS</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action History Card -->
            @if($skripsiRegistration->application && $skripsiRegistration->application->actions->count() > 0)
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-history mr-2"></i>
                        Riwayat Aksi
                    </h3>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach($skripsiRegistration->application->actions->sortByDesc('created_at') as $action)
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
                        $status = $skripsiRegistration->application->status ?? 'unknown';
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

                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>ID Pendaftaran</th>
                            <td>: #{{ $skripsiRegistration->id }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Daftar</th>
                            <td>: {{ $skripsiRegistration->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        @if($skripsiRegistration->approval_date)
                        <tr>
                            <th>Tanggal Disetujui</th>
                            <td>: {{ \Carbon\Carbon::parse($skripsiRegistration->approval_date)->format('d M Y H:i') }}</td>
                        </tr>
                        @endif
                    </table>

                    @if($skripsiRegistration->rejection_reason)
                        <div class="alert alert-danger mt-3">
                            <strong><i class="fas fa-exclamation-triangle mr-2"></i>Alasan Penolakan:</strong>
                            <p class="mb-0 mt-2">{{ $skripsiRegistration->rejection_reason }}</p>
                        </div>
                    @endif

                    @if($skripsiRegistration->revision_notes)
                        <div class="alert alert-warning mt-3">
                            <strong><i class="fas fa-edit mr-2"></i>Catatan Revisi:</strong>
                            <p class="mb-0 mt-2">{{ $skripsiRegistration->revision_notes }}</p>
                        </div>
                    @endif

                    @php
                        $supervisorAssignment = \App\Models\ApplicationAssignment::where('application_id', $skripsiRegistration->application_id)
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
            @if($skripsiRegistration->application && $skripsiRegistration->application->status === 'submitted')
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
                                onclick="showApproveModal({{ $skripsiRegistration->id }})">
                            <i class="fas fa-check-circle mr-2"></i> Setujui Pendaftaran
                        </button>
                        
                        <button type="button" class="btn btn-warning btn-lg btn-block mb-2" 
                                onclick="showRevisionModal({{ $skripsiRegistration->id }})">
                            <i class="fas fa-edit mr-2"></i> Minta Revisi
                        </button>
                        
                        <button type="button" class="btn btn-danger btn-lg btn-block" 
                                onclick="showRejectModal({{ $skripsiRegistration->id }})">
                            <i class="fas fa-times-circle mr-2"></i> Tolak Pendaftaran
                        </button>
                    </div>
                </div>
            </div>
            @endif

            <!-- Navigation Card -->
            <div class="card">
                <div class="card-body">
                    <a class="btn btn-default btn-block" href="{{ route('admin.skripsi-registrations.index') }}">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                    </a>
                    @can('skripsi_registration_edit')
                        <a class="btn btn-info btn-block" href="{{ route('admin.skripsi-registrations.edit', $skripsiRegistration->id) }}">
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
                    Setujui Pendaftaran
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="approveForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="supervisor_id">Pilih Dosen Pembimbing <span class="text-danger">*</span></label>
                        <select class="form-control" id="supervisor_id" name="supervisor_id" required>
                            <option value="">-- Pilih Dosen --</option>
                            @foreach(\App\Models\Dosen::orderBy('nama')->get() as $dosen)
                                <option value="{{ $dosen->id }}" {{ $skripsiRegistration->preference_supervision_id == $dosen->id ? 'selected' : '' }}>
                                    {{ $dosen->nama }}
                                </option>
                            @endforeach
                        </select>
                        @if($skripsiRegistration->preference_supervision)
                            <small class="form-text text-info">
                                <i class="fas fa-info-circle mr-1"></i>
                                Preferensi mahasiswa: {{ $skripsiRegistration->preference_supervision->nama }}
                            </small>
                        @endif
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
                    Tolak Pendaftaran
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
let currentRegistrationId = {{ $skripsiRegistration->id }};

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
        url: `/admin/skripsi-registrations/${currentRegistrationId}/approve`,
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
                text: response.message,
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
        url: `/admin/skripsi-registrations/${currentRegistrationId}/reject`,
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
                text: response.message,
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
        url: `/admin/skripsi-registrations/${currentRegistrationId}/request-revision`,
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
                text: response.message,
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
