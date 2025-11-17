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
                        $mahasiswa = $applicationSchedule->application->mahasiswa ?? null;
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

            <!-- Schedule Information Card -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-calendar-check mr-2"></i>
                        Detail Jadwal
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Tipe Jadwal:</label>
                                <p>
                                    <span class="badge badge-primary badge-lg">
                                        {{ App\Models\ApplicationSchedule::SCHEDULE_TYPE_SELECT[$applicationSchedule->schedule_type] ?? 'N/A' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Status Application:</label>
                                <p>
                                    @php
                                        $status = $applicationSchedule->application->status ?? 'unknown';
                                        $badgeClass = [
                                            'submitted' => 'info',
                                            'approved' => 'success',
                                            'scheduled' => 'success',
                                            'rejected' => 'danger',
                                        ][$status] ?? 'secondary';
                                    @endphp
                                    <span class="badge badge-{{ $badgeClass }} badge-lg">{{ ucfirst($status) }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-clock mr-2"></i>Waktu Pelaksanaan:</label>
                        <p class="h5 text-primary">{{ $applicationSchedule->waktu }}</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold"><i class="fas fa-map-marker-alt mr-2"></i>Ruangan:</label>
                                <p>{{ $applicationSchedule->ruang->name ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold"><i class="fas fa-building mr-2"></i>Tempat Lain:</label>
                                <p>{{ $applicationSchedule->custom_place ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    @if($applicationSchedule->online_meeting)
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-video mr-2"></i>Link Meeting Online:</label>
                        <p>
                            <a href="{{ $applicationSchedule->online_meeting }}" target="_blank" class="btn btn-sm btn-primary">
                                <i class="fas fa-external-link-alt mr-1"></i> Buka Meeting
                            </a>
                        </p>
                        <small class="text-muted">{{ $applicationSchedule->online_meeting }}</small>
                    </div>
                    @endif

                    @if($applicationSchedule->note)
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-sticky-note mr-2"></i>Catatan:</label>
                        <p class="text-justify">{{ $applicationSchedule->note }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Documents Card -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-file-pdf mr-2"></i>
                        Dokumen Persetujuan
                    </h3>
                </div>
                <div class="card-body">
                    @if($applicationSchedule->approval_form && count($applicationSchedule->approval_form) > 0)
                        <div class="row">
                            @foreach($applicationSchedule->approval_form as $index => $media)
                            <div class="col-md-4">
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <strong>Dokumen {{ $index + 1 }}</strong>
                                    </div>
                                    <div class="card-body text-center">
                                        <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                        <div class="btn-group-vertical w-100">
                                            <button type="button" class="btn btn-sm btn-primary preview-doc mb-2" 
                                                    data-url="{{ $media->getUrl() }}">
                                                <i class="fas fa-eye mr-1"></i> Preview
                                            </button>
                                            <a href="{{ $media->getUrl() }}" 
                                               class="btn btn-sm btn-success" target="_blank" download>
                                                <i class="fas fa-download mr-1"></i> Download
                                            </a>
                                        </div>
                                        <small class="text-muted d-block mt-2">
                                            {{ number_format($media->size / 1024 / 1024, 2) }} MB
                                        </small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Belum ada dokumen persetujuan yang diupload.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action History -->
            @if($applicationSchedule->application && $applicationSchedule->application->actions->count() > 0)
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-history mr-2"></i>
                        Riwayat Aksi
                    </h3>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach($applicationSchedule->application->actions->sortByDesc('created_at') as $action)
                            <div class="time-label">
                                <span class="bg-{{ str_contains($action->action_type, 'approved') || str_contains($action->action_type, 'scheduled') ? 'success' : (str_contains($action->action_type, 'rejected') ? 'danger' : 'info') }}">
                                    {{ $action->created_at->format('d M Y H:i') }}
                                </span>
                            </div>
                            <div>
                                <i class="fas fa-{{ str_contains($action->action_type, 'approved') || str_contains($action->action_type, 'scheduled') ? 'check' : (str_contains($action->action_type, 'rejected') ? 'times' : 'info-circle') }} bg-{{ str_contains($action->action_type, 'approved') || str_contains($action->action_type, 'scheduled') ? 'success' : (str_contains($action->action_type, 'rejected') ? 'danger' : 'info') }}"></i>
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
                        Status Jadwal
                    </h3>
                </div>
                <div class="card-body">
                    @php
                        $status = $applicationSchedule->application->status ?? 'unknown';
                        $statusConfig = [
                            'submitted' => ['badge' => 'info', 'icon' => 'clock', 'text' => 'Menunggu Review'],
                            'approved' => ['badge' => 'warning', 'icon' => 'calendar-plus', 'text' => 'Menunggu Jadwal'],
                            'scheduled' => ['badge' => 'success', 'icon' => 'check-circle', 'text' => 'Jadwal Disetujui'],
                            'rejected' => ['badge' => 'danger', 'icon' => 'times-circle', 'text' => 'Ditolak'],
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
                            <th>ID Jadwal</th>
                            <td>: #{{ $applicationSchedule->id }}</td>
                        </tr>
                        <tr>
                            <th>Dibuat</th>
                            <td>: {{ $applicationSchedule->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                            <th>Stage</th>
                            <td>: <span class="badge badge-primary">{{ ucfirst($applicationSchedule->application->stage ?? 'N/A') }}</span></td>
                    </tr>
            </table>

                    @if($applicationSchedule->application && $applicationSchedule->application->notes)
                        <div class="alert alert-{{ $status === 'rejected' ? 'danger' : 'info' }} mt-3">
                            <strong><i class="fas fa-{{ $status === 'rejected' ? 'exclamation-triangle' : 'info-circle' }} mr-2"></i>Catatan:</strong>
                            <p class="mb-0 mt-2">{{ $applicationSchedule->application->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons Card -->
            @if($applicationSchedule->application && $applicationSchedule->application->status !== 'scheduled' && $applicationSchedule->application->status !== 'rejected')
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
                                onclick="showApproveModal({{ $applicationSchedule->id }})">
                            <i class="fas fa-check-circle mr-2"></i> Setujui Jadwal
                        </button>
                        
                        <button type="button" class="btn btn-danger btn-lg btn-block" 
                                onclick="showRejectModal({{ $applicationSchedule->id }})">
                            <i class="fas fa-times-circle mr-2"></i> Tolak Jadwal
                        </button>
                    </div>
                </div>
            </div>
            @endif

            <!-- Navigation Card -->
            <div class="card">
                <div class="card-body">
                    <a class="btn btn-default btn-block" href="{{ route('admin.application-schedules.index') }}">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                    </a>
                    @can('application_schedule_edit')
                        <a class="btn btn-info btn-block" href="{{ route('admin.application-schedules.edit', $applicationSchedule->id) }}">
                            <i class="fas fa-edit mr-2"></i> Edit Jadwal
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
                    Setujui Jadwal Seminar
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="approveForm">
                <div class="modal-body">
                    <div class="alert alert-success">
                        <i class="fas fa-info-circle mr-2"></i>
                        Jadwal seminar akan disetujui dan mahasiswa dapat melanjutkan persiapan.
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
                    Tolak Jadwal Seminar
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
let currentScheduleId = {{ $applicationSchedule->id }};

// Document Preview
$('.preview-doc').on('click', function() {
    const url = $(this).data('url');
    $('#pdfViewer').attr('src', url);
    $('#previewModal').modal('show');
});

// Modal Functions
function showApproveModal(id) {
    currentScheduleId = id;
    $('#approveModal').modal('show');
}

function showRejectModal(id) {
    currentScheduleId = id;
    $('#rejectModal').modal('show');
}

// Approve Form Submit
$('#approveForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = {
        notes: $('#approve_notes').val(),
        _token: '{{ csrf_token() }}'
    };
    
    $.ajax({
        url: `/admin/application-schedules/${currentScheduleId}/approve`,
        method: 'POST',
        data: formData,
        beforeSend: function() {
            $('#approveForm button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Processing...');
        },
        success: function(response) {
            if (response.success) {
                $('#approveModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message,
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload();
                });
            }
        },
        error: function(xhr) {
            let message = 'Terjadi kesalahan';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: message
            });
            $('#approveForm button[type="submit"]').prop('disabled', false).html('<i class="fas fa-check mr-1"></i> Setujui');
        }
    });
});

// Reject Form Submit
$('#rejectForm').on('submit', function(e) {
    e.preventDefault();
    
    const reason = $('#reject_reason').val();
    if (reason.length < 10) {
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian',
            text: 'Alasan penolakan minimal 10 karakter'
        });
        return;
    }
    
    const formData = {
        reason: reason,
        _token: '{{ csrf_token() }}'
    };
    
    $.ajax({
        url: `/admin/application-schedules/${currentScheduleId}/reject`,
        method: 'POST',
        data: formData,
        beforeSend: function() {
            $('#rejectForm button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Processing...');
        },
        success: function(response) {
            if (response.success) {
                $('#rejectModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message,
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload();
                });
            }
        },
        error: function(xhr) {
            let message = 'Terjadi kesalahan';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: message
            });
            $('#rejectForm button[type="submit"]').prop('disabled', false).html('<i class="fas fa-times mr-1"></i> Tolak');
        }
    });
});
</script>
@endsection
