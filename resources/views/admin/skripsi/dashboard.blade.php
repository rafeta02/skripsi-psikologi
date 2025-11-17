@extends('layouts.admin')
@section('content')

<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard Skripsi
                </div>
                <div class="card-body">
                    
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $stats['total_registrations'] }}</h3>
                                    <p>Total Pendaftaran</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <a href="{{ route('admin.skripsi-registrations.index') }}" class="small-box-footer">
                                    Lihat Semua <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $stats['pending_approvals'] }}</h3>
                                    <p>Menunggu Persetujuan</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <a href="{{ route('admin.skripsi-registrations.index') }}?status=submitted" class="small-box-footer">
                                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $stats['approved'] }}</h3>
                                    <p>Disetujui</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <a href="{{ route('admin.skripsi-registrations.index') }}?status=approved" class="small-box-footer">
                                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $stats['rejected'] }}</h3>
                                    <p>Ditolak</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                                <a href="{{ route('admin.skripsi-registrations.index') }}?status=rejected" class="small-box-footer">
                                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Stage Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-chart-pie mr-2"></i>
                                        Mahasiswa Per Tahap
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="stageChart" style="height: 250px;"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-chart-line mr-2"></i>
                                        Tingkat Persetujuan
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                        <h2 class="display-4">{{ $approvalRate }}%</h2>
                                        <p class="text-muted">Dari total {{ $stats['total_registrations'] }} pendaftaran</p>
                                        <div class="progress" style="height: 25px;">
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                 style="width: {{ $approvalRate }}%" 
                                                 aria-valuenow="{{ $approvalRate }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                                {{ $approvalRate }}%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Submissions Table -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-list mr-2"></i>
                                Pendaftaran Terbaru
                            </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped table-hover datatable datatable-skripsi-dashboard" id="dashboard-table">
                                <thead>
                                    <tr>
                                        <th width="80">ID</th>
                                        <th>Mahasiswa</th>
                                        <th>Judul</th>
                                        <th>Tema</th>
                                        <th width="120">Status</th>
                                        <th width="150">Pembimbing</th>
                                        <th width="130">Tanggal</th>
                                        <th width="120">Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approval Modal -->
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
                                <option value="{{ $dosen->id }}">{{ $dosen->nama }}</option>
                            @endforeach
                        </select>
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

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
$(document).ready(function() {
    let currentRegistrationId = null;

    // DataTable
    const table = $('#dashboard-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.skripsi.dashboard.data") }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'student', name: 'student', orderable: false, searchable: false },
            { data: 'title', name: 'title' },
            { data: 'theme', name: 'theme' },
            { data: 'status', name: 'status', orderable: false },
            { data: 'supervisor', name: 'supervisor', orderable: false },
            { data: 'submitted_at', name: 'submitted_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[0, 'desc']],
        pageLength: 10,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
        }
    });

    // Stage Chart
    const stageData = @json($stageStats);
    new Chart(document.getElementById('stageChart'), {
        type: 'doughnut',
        data: {
            labels: ['Pendaftaran', 'Seminar', 'Sidang'],
            datasets: [{
                data: [stageData.registration, stageData.seminar, stageData.defense],
                backgroundColor: ['#007bff', '#ffc107', '#28a745'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Approve Button Click
    $(document).on('click', '.approve-btn', function() {
        currentRegistrationId = $(this).data('id');
        $('#approveModal').modal('show');
    });

    // Reject Button Click
    $(document).on('click', '.reject-btn', function() {
        currentRegistrationId = $(this).data('id');
        $('#rejectModal').modal('show');
    });

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
                });
                table.ajax.reload();
                $('#approveForm')[0].reset();
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: xhr.responseJSON?.message || 'Terjadi kesalahan'
                });
            },
            complete: function() {
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
                });
                table.ajax.reload();
                $('#rejectForm')[0].reset();
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: xhr.responseJSON?.message || 'Terjadi kesalahan'
                });
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('<i class="fas fa-times mr-1"></i> Tolak');
            }
        });
    });

    // Clear modal on close
    $('.modal').on('hidden.bs.modal', function() {
        $(this).find('form')[0].reset();
        currentRegistrationId = null;
    });
});
</script>
@endsection

