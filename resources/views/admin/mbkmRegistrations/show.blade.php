@extends('layouts.admin')
@section('content')

<div class="content">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Student Information Card (Ketua) -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-user-graduate mr-2"></i>
                        Ketua Kelompok
                    </h3>
                </div>
                <div class="card-body">
                    @php
                        $mahasiswa = $mbkmRegistration->application->mahasiswa ?? null;
                    @endphp
                    @if($mahasiswa)
                        <div class="alert alert-info py-2">
                            <i class="fas fa-users mr-1"></i>
                            Sampai sebelum sidang, proses MBKM berkelompok. Data di bawah adalah ketua yang mengajukan.
                        </div>
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

            <!-- MBKM Information Card (kelompok) -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-briefcase mr-2"></i>
                        Informasi Kelompok MBKM
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
                                <label class="font-weight-bold">Tema Riset:</label>
                                <p>{{ $mbkmRegistration->themes_label }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Judul Kegiatan MBKM:</label>
                        <p class="text-justify">{{ $mbkmRegistration->title_mbkm ?? 'N/A' }}</p>
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

            @include('admin.partials.mbkm-group-context', [
                'mbkmGroupRegistration' => $mbkmRegistration,
                'mode' => 'full',
            ])

            <!-- Documents Card (kelompok) -->
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-file-pdf mr-2"></i>
                        Dokumen Kelompok
                    </h3>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        Dokumen individu (KHS, KRS, SPP, recognition) ada di detail tiap anggota di atas.
                    </p>

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
                        <strong>Jalur MBKM — Kelompok</strong>
                        <div class="small mt-1 mb-0">Hingga sebelum sidang, satu pengajuan untuk seluruh anggota.</div>
                    </div>

                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>ID Pendaftaran</th>
                            <td>: #{{ $mbkmRegistration->id }}</td>
                        </tr>
                        <tr>
                            <th>Status Kelompok</th>
                            <td>:
                                @if(($mbkmRegistration->group_status ?? 'draft') === 'submitted')
                                    <span class="badge badge-success">Diajukan</span>
                                @else
                                    <span class="badge badge-warning">Draft</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Syarat Anggota</th>
                            <td>:
                                @php
                                    $memberTotal = $mbkmRegistration->groupMembers->count();
                                    $memberComplete = $mbkmRegistration->groupMembers->where('requirements_status', 'complete')->count();
                                @endphp
                                {{ $memberComplete }}/{{ $memberTotal }}
                                @if($memberTotal > 0 && $memberComplete === $memberTotal)
                                    <span class="badge badge-success ml-1">Lengkap</span>
                                @endif
                            </td>
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
            @php
                $canApproveGroup = ($mbkmRegistration->group_status ?? 'draft') === 'submitted'
                    && $mbkmRegistration->allMembersRequirementsComplete();
            @endphp
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-tasks mr-2"></i>
                        Aksi Admin
                    </h3>
                </div>
                <div class="card-body">
                    @if(!$canApproveGroup)
                        <div class="alert alert-warning small">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Setujui hanya tersedia jika kelompok sudah diajukan ketua dan semua syarat individu lengkap.
                        </div>
                    @endif
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-success btn-lg btn-block mb-2" 
                                onclick="showApproveModal({{ $mbkmRegistration->id }})"
                                @if(!$canApproveGroup) disabled @endif>
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
                        Pengajuan <strong>kelompok</strong> MBKM akan disetujui. Status anggota ikut tersinkron sampai sebelum sidang.
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
