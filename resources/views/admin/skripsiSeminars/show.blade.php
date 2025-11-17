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
                        $mahasiswa = $skripsiSeminar->application->mahasiswa ?? null;
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

            <!-- Seminar Proposal Information Card -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-presentation mr-2"></i>
                        Informasi Seminar Proposal
                    </h3>
                </div>
    <div class="card-body">
        <div class="form-group">
                        <label class="font-weight-bold">Judul Proposal:</label>
                        <p class="text-justify">{{ $skripsiSeminar->title ?? 'N/A' }}</p>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Tanggal Pengajuan:</label>
                                <p>{{ $skripsiSeminar->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Stage:</label>
                                <p><span class="badge badge-primary">{{ ucfirst($skripsiSeminar->application->stage ?? 'N/A') }}</span></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Status:</label>
                                <p>
                                    @php
                                        $status = $skripsiSeminar->application->status ?? 'unknown';
                                        $badgeClass = [
                                            'submitted' => 'info',
                                            'approved' => 'success',
                                            'rejected' => 'danger',
                                        ][$status] ?? 'secondary';
                                    @endphp
                                    <span class="badge badge-{{ $badgeClass }}">{{ ucfirst($status) }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($skripsiSeminar->reviewer1 || $skripsiSeminar->reviewer2)
                    <div class="alert alert-info mt-3">
                        <h5 class="alert-heading"><i class="fas fa-users mr-2"></i>Reviewer yang Ditugaskan</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Reviewer 1:</label>
                                    <p>{{ $skripsiSeminar->reviewer1->nama ?? 'Belum ditugaskan' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
            <div class="form-group">
                                    <label class="font-weight-bold">Reviewer 2:</label>
                                    <p>{{ $skripsiSeminar->reviewer2->nama ?? 'Belum ditugaskan' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Documents Card -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-file-pdf mr-2"></i>
                        Dokumen Seminar
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Proposal Document -->
                        <div class="col-md-4">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <strong>Dokumen Proposal</strong>
                                </div>
                                <div class="card-body text-center">
                            @if($skripsiSeminar->proposal_document)
                                        <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                        <div class="btn-group-vertical w-100">
                                            <button type="button" class="btn btn-sm btn-primary preview-doc mb-2" 
                                                    data-url="{{ $skripsiSeminar->proposal_document->getUrl() }}">
                                                <i class="fas fa-eye mr-1"></i> Preview
                                            </button>
                                            <a href="{{ $skripsiSeminar->proposal_document->getUrl() }}" 
                                               class="btn btn-sm btn-success" target="_blank" download>
                                                <i class="fas fa-download mr-1"></i> Download
                                            </a>
                                        </div>
                                        <small class="text-muted d-block mt-2">
                                            {{ number_format($skripsiSeminar->proposal_document->size / 1024 / 1024, 2) }} MB
                                        </small>
                                    @else
                                        <i class="fas fa-file-pdf fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Tidak ada file</p>
                            @endif
                                </div>
                            </div>
                        </div>

                        <!-- Approval Document -->
                        <div class="col-md-4">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <strong>Form Persetujuan</strong>
                                </div>
                                <div class="card-body text-center">
                            @if($skripsiSeminar->approval_document)
                                        <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                        <div class="btn-group-vertical w-100">
                                            <button type="button" class="btn btn-sm btn-primary preview-doc mb-2" 
                                                    data-url="{{ $skripsiSeminar->approval_document->getUrl() }}">
                                                <i class="fas fa-eye mr-1"></i> Preview
                                            </button>
                                            <a href="{{ $skripsiSeminar->approval_document->getUrl() }}" 
                                               class="btn btn-sm btn-success" target="_blank" download>
                                                <i class="fas fa-download mr-1"></i> Download
                                            </a>
                                        </div>
                                        <small class="text-muted d-block mt-2">
                                            {{ number_format($skripsiSeminar->approval_document->size / 1024 / 1024, 2) }} MB
                                        </small>
                                    @else
                                        <i class="fas fa-file-pdf fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Tidak ada file</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Plagiarism Document -->
                        <div class="col-md-4">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <strong>Cek Plagiarisme</strong>
                                </div>
                                <div class="card-body text-center">
                                    @if($skripsiSeminar->plagiarism_document)
                                        <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                        <div class="btn-group-vertical w-100">
                                            <button type="button" class="btn btn-sm btn-primary preview-doc mb-2" 
                                                    data-url="{{ $skripsiSeminar->plagiarism_document->getUrl() }}">
                                                <i class="fas fa-eye mr-1"></i> Preview
                                            </button>
                                            <a href="{{ $skripsiSeminar->plagiarism_document->getUrl() }}" 
                                               class="btn btn-sm btn-success" target="_blank" download>
                                                <i class="fas fa-download mr-1"></i> Download
                                            </a>
                                        </div>
                                        <small class="text-muted d-block mt-2">
                                            {{ number_format($skripsiSeminar->plagiarism_document->size / 1024 / 1024, 2) }} MB
                                        </small>
                                    @else
                                        <i class="fas fa-file-pdf fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Tidak ada file</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action History -->
            @if($skripsiSeminar->application && $skripsiSeminar->application->actions->count() > 0)
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-history mr-2"></i>
                        Riwayat Aksi
                    </h3>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach($skripsiSeminar->application->actions->sortByDesc('created_at') as $action)
                            <div class="time-label">
                                <span class="bg-{{ str_contains($action->action_type, 'approved') ? 'success' : (str_contains($action->action_type, 'rejected') ? 'danger' : 'info') }}">
                                    {{ $action->created_at->format('d M Y H:i') }}
                                </span>
                            </div>
                            <div>
                                <i class="fas fa-{{ str_contains($action->action_type, 'approved') ? 'check' : (str_contains($action->action_type, 'rejected') ? 'times' : 'info-circle') }} bg-{{ str_contains($action->action_type, 'approved') ? 'success' : (str_contains($action->action_type, 'rejected') ? 'danger' : 'info') }}"></i>
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
                        $status = $skripsiSeminar->application->status ?? 'unknown';
                        $statusConfig = [
                            'submitted' => ['badge' => 'info', 'icon' => 'clock', 'text' => 'Menunggu Review'],
                            'approved' => ['badge' => 'success', 'icon' => 'check-circle', 'text' => 'Disetujui'],
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
                            <th>ID Seminar</th>
                            <td>: #{{ $skripsiSeminar->id }}</td>
                    </tr>
                    <tr>
                            <th>Tanggal Daftar</th>
                            <td>: {{ $skripsiSeminar->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        @if($skripsiSeminar->application && $skripsiSeminar->application->submitted_at)
                        <tr>
                            <th>Submitted At</th>
                            <td>: {{ \Carbon\Carbon::parse($skripsiSeminar->application->submitted_at)->format('d M Y H:i') }}</td>
                        </tr>
                            @endif
            </table>

                    @if($skripsiSeminar->application && $skripsiSeminar->application->notes)
                        <div class="alert alert-{{ $status === 'rejected' ? 'danger' : 'info' }} mt-3">
                            <strong><i class="fas fa-{{ $status === 'rejected' ? 'exclamation-triangle' : 'info-circle' }} mr-2"></i>Catatan:</strong>
                            <p class="mb-0 mt-2">{{ $skripsiSeminar->application->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons Card -->
            @if($skripsiSeminar->application && $skripsiSeminar->application->status === 'submitted')
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
                                onclick="showApproveModal({{ $skripsiSeminar->id }})">
                            <i class="fas fa-check-circle mr-2"></i> Setujui Seminar
                        </button>
                        
                        <button type="button" class="btn btn-danger btn-lg btn-block" 
                                onclick="showRejectModal({{ $skripsiSeminar->id }})">
                            <i class="fas fa-times-circle mr-2"></i> Tolak Seminar
                        </button>
                    </div>
                </div>
            </div>
            @endif

            <!-- Navigation Card -->
            <div class="card">
                <div class="card-body">
                    <a class="btn btn-default btn-block" href="{{ route('admin.skripsi-seminars.index') }}">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                    </a>
                    @can('skripsi_seminar_edit')
                        <a class="btn btn-info btn-block" href="{{ route('admin.skripsi-seminars.edit', $skripsiSeminar->id) }}">
                            <i class="fas fa-edit mr-2"></i> Edit Seminar
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
                    Setujui Seminar Proposal
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="approveForm">
                <div class="modal-body">
                    <div class="alert alert-success">
                        <i class="fas fa-info-circle mr-2"></i>
                        Seminar proposal akan disetujui dan mahasiswa dapat melanjutkan ke tahap berikutnya.
                    </div>

                    <div class="form-group">
                        <label for="reviewer_1_id">Reviewer 1 <span class="text-danger">*</span></label>
                        <select class="form-control" id="reviewer_1_id" name="reviewer_1_id" required>
                            <option value="">-- Pilih Reviewer 1 --</option>
                            @foreach(\App\Models\Dosen::orderBy('nama')->get() as $dosen)
                                <option value="{{ $dosen->id }}">{{ $dosen->nama }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Pilih dosen pertama sebagai reviewer</small>
                    </div>

                    <div class="form-group">
                        <label for="reviewer_2_id">Reviewer 2 <span class="text-danger">*</span></label>
                        <select class="form-control" id="reviewer_2_id" name="reviewer_2_id" required>
                            <option value="">-- Pilih Reviewer 2 --</option>
                            @foreach(\App\Models\Dosen::orderBy('nama')->get() as $dosen)
                                <option value="{{ $dosen->id }}">{{ $dosen->nama }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Pilih dosen kedua sebagai reviewer (harus berbeda dari reviewer 1)</small>
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
                        <i class="fas fa-check mr-1"></i> Setujui & Tugaskan Reviewer
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
                    Tolak Seminar Proposal
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
let currentSeminarId = {{ $skripsiSeminar->id }};

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
    
    const reviewer1Id = $('#reviewer_1_id').val();
    const reviewer2Id = $('#reviewer_2_id').val();
    
    // Validate reviewers are selected and different
    if (!reviewer1Id || !reviewer2Id) {
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian',
            text: 'Silakan pilih kedua reviewer'
        });
        return;
    }
    
    if (reviewer1Id === reviewer2Id) {
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian',
            text: 'Reviewer 1 dan Reviewer 2 harus berbeda'
        });
        return;
    }
    
    const formData = {
        reviewer_1_id: reviewer1Id,
        reviewer_2_id: reviewer2Id,
        notes: $('#approve_notes').val(),
        _token: '{{ csrf_token() }}'
    };
    
    $.ajax({
        url: `/admin/skripsi-seminars/${currentSeminarId}/approve`,
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
            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                // Display validation errors
                const errors = xhr.responseJSON.errors;
                message = Object.values(errors).flat().join('\n');
            }
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: message
            });
            $('#approveForm button[type="submit"]').prop('disabled', false).html('<i class="fas fa-check mr-1"></i> Setujui & Tugaskan Reviewer');
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
        url: `/admin/skripsi-seminars/${currentSeminarId}/reject`,
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
