@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-calendar-check mr-2"></i>
            Persetujuan Jadwal Seminar/Sidang
        </h3>
    </div>

    <div class="card-body">
        <!-- Status Filter Tabs -->
        <ul class="nav nav-tabs mb-3" id="statusTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="pending-tab" data-toggle="tab" href="#pending" role="tab">
                    <i class="fas fa-clock mr-1"></i>
                    <span class="badge badge-warning">Menunggu Persetujuan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="approved-tab" data-toggle="tab" href="#approved" role="tab">
                    <i class="fas fa-check-circle mr-1"></i>
                    <span class="badge badge-success">Disetujui</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="rejected-tab" data-toggle="tab" href="#rejected" role="tab">
                    <i class="fas fa-times-circle mr-1"></i>
                    <span class="badge badge-danger">Ditolak</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="all-tab" data-toggle="tab" href="#all" role="tab">
                    <i class="fas fa-list mr-1"></i>
                    Semua Jadwal
                </a>
            </li>
        </ul>

        <div class="tab-content" id="statusTabContent">
            <!-- Pending Tab -->
            <div class="tab-pane fade show active" id="pending" role="tabpanel">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i>
                    Daftar jadwal yang menunggu persetujuan Anda. Klik tombol aksi untuk menyetujui atau menolak.
                </div>
                <table class="table table-bordered table-striped table-hover datatable datatable-pending">
                    <thead>
                        <tr>
                            <th width="10"></th>
                            <th>Mahasiswa</th>
                            <th>NIM</th>
                            <th>Tipe Jadwal</th>
                            <th>Waktu</th>
                            <th>Tempat/Ruangan</th>
                            <th>Diajukan</th>
                            <th width="200">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <!-- Approved Tab -->
            <div class="tab-pane fade" id="approved" role="tabpanel">
                <table class="table table-bordered table-striped table-hover datatable datatable-approved">
                    <thead>
                        <tr>
                            <th width="10"></th>
                            <th>Mahasiswa</th>
                            <th>NIM</th>
                            <th>Tipe Jadwal</th>
                            <th>Waktu</th>
                            <th>Tempat/Ruangan</th>
                            <th>Disetujui</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <!-- Rejected Tab -->
            <div class="tab-pane fade" id="rejected" role="tabpanel">
                <table class="table table-bordered table-striped table-hover datatable datatable-rejected">
                    <thead>
                        <tr>
                            <th width="10"></th>
                            <th>Mahasiswa</th>
                            <th>NIM</th>
                            <th>Tipe Jadwal</th>
                            <th>Waktu</th>
                            <th>Tempat/Ruangan</th>
                            <th>Alasan Penolakan</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <!-- All Tab -->
            <div class="tab-pane fade" id="all" role="tabpanel">
                <table class="table table-bordered table-striped table-hover datatable datatable-all">
                    <thead>
                        <tr>
                            <th width="10"></th>
                            <th>Mahasiswa</th>
                            <th>NIM</th>
                            <th>Tipe Jadwal</th>
                            <th>Waktu</th>
                            <th>Tempat/Ruangan</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Quick Approve Modal -->
<div class="modal fade" id="quickApproveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle mr-2"></i>
                    Setujui Jadwal
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="quickApproveForm">
                <div class="modal-body">
                    <div class="alert alert-success">
                        <i class="fas fa-info-circle mr-2"></i>
                        Jadwal akan disetujui dan mahasiswa dapat melanjutkan persiapan.
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

<!-- Quick Reject Modal -->
<div class="modal fade" id="quickRejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-times-circle mr-2"></i>
                    Tolak Jadwal
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="quickRejectForm">
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
@parent
<script>
let currentScheduleId = null;

$(function () {
  // Common DataTable settings
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);

  // Function to create action buttons
  function createActionButtons(row, status) {
    let viewBtn = `<a class="btn btn-xs btn-primary" href="/admin/application-schedules/${row.id}" title="Lihat Detail">
                     <i class="fa fa-eye"></i>
                   </a>`;
    
    if (status === 'pending') {
      return `
        <div class="btn-group" role="group">
          ${viewBtn}
          <button type="button" class="btn btn-xs btn-success" onclick="quickApprove(${row.id})" title="Setujui">
            <i class="fa fa-check"></i>
          </button>
          <button type="button" class="btn btn-xs btn-danger" onclick="quickReject(${row.id})" title="Tolak">
            <i class="fa fa-times"></i>
          </button>
        </div>
      `;
    }
    
    let editBtn = '';
    @can('application_schedule_edit')
    editBtn = `<a class="btn btn-xs btn-info" href="/admin/application-schedules/${row.id}/edit" title="Edit">
                 <i class="fa fa-edit"></i>
               </a>`;
    @endcan
    
    let deleteBtn = '';
    @can('application_schedule_delete')
    deleteBtn = `<button type="button" class="btn btn-xs btn-danger" onclick="deleteSchedule(${row.id})" title="Hapus">
                   <i class="fa fa-trash"></i>
                 </button>`;
    @endcan
    
    return `<div class="btn-group" role="group">${viewBtn}${editBtn}${deleteBtn}</div>`;
  }

  // Pending Schedules DataTable
  let pendingTable = $('.datatable-pending').DataTable({
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: {
      url: "{{ route('admin.application-schedules.index') }}",
      data: function(d) {
        d.status_filter = 'pending';
      }
    },
    columns: [
      { data: 'placeholder', name: 'placeholder', orderable: false, searchable: false },
      { data: 'mahasiswa_name', name: 'application.mahasiswa.nama' },
      { data: 'mahasiswa_nim', name: 'application.mahasiswa.nim' },
      { data: 'schedule_type', name: 'schedule_type' },
      { data: 'waktu', name: 'waktu' },
      { data: 'ruang_name', name: 'ruang.name', orderable: false },
      { data: 'created_at', name: 'created_at' },
      { 
        data: null, 
        name: 'actions',
        orderable: false, 
        searchable: false,
        render: function(data, type, row) {
          return createActionButtons(row, 'pending');
        }
      }
    ],
    order: [[ 6, 'desc' ]],
    pageLength: 25,
  });

  // Approved Schedules DataTable
  let approvedTable = $('.datatable-approved').DataTable({
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: {
      url: "{{ route('admin.application-schedules.index') }}",
      data: function(d) {
        d.status_filter = 'approved';
      }
    },
    columns: [
      { data: 'placeholder', name: 'placeholder', orderable: false, searchable: false },
      { data: 'mahasiswa_name', name: 'application.mahasiswa.nama' },
      { data: 'mahasiswa_nim', name: 'application.mahasiswa.nim' },
      { data: 'schedule_type', name: 'schedule_type' },
      { data: 'waktu', name: 'waktu' },
      { data: 'ruang_name', name: 'ruang.name', orderable: false },
      { data: 'updated_at', name: 'updated_at' },
      { 
        data: null, 
        name: 'actions',
        orderable: false, 
        searchable: false,
        render: function(data, type, row) {
          return createActionButtons(row, 'approved');
        }
      }
    ],
    order: [[ 6, 'desc' ]],
    pageLength: 25,
  });

  // Rejected Schedules DataTable
  let rejectedTable = $('.datatable-rejected').DataTable({
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: {
      url: "{{ route('admin.application-schedules.index') }}",
      data: function(d) {
        d.status_filter = 'rejected';
      }
    },
    columns: [
      { data: 'placeholder', name: 'placeholder', orderable: false, searchable: false },
      { data: 'mahasiswa_name', name: 'application.mahasiswa.nama' },
      { data: 'mahasiswa_nim', name: 'application.mahasiswa.nim' },
      { data: 'schedule_type', name: 'schedule_type' },
      { data: 'waktu', name: 'waktu' },
      { data: 'ruang_name', name: 'ruang.name', orderable: false },
      { data: 'rejection_reason', name: 'application.notes', orderable: false },
      { 
        data: null, 
        name: 'actions',
        orderable: false, 
        searchable: false,
        render: function(data, type, row) {
          return createActionButtons(row, 'rejected');
        }
      }
    ],
    order: [[ 6, 'desc' ]],
    pageLength: 25,
  });

  // All Schedules DataTable
  let allTable = $('.datatable-all').DataTable({
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.application-schedules.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder', orderable: false, searchable: false },
      { data: 'mahasiswa_name', name: 'application.mahasiswa.nama' },
      { data: 'mahasiswa_nim', name: 'application.mahasiswa.nim' },
      { data: 'schedule_type', name: 'schedule_type' },
      { data: 'waktu', name: 'waktu' },
      { data: 'ruang_name', name: 'ruang.name', orderable: false },
      { data: 'status_badge', name: 'application.status', orderable: false, searchable: false },
      { 
        data: null, 
        name: 'actions',
        orderable: false, 
        searchable: false,
        render: function(data, type, row) {
          return createActionButtons(row, row.status);
        }
      }
    ],
    order: [[ 4, 'desc' ]],
    pageLength: 25,
  });

  // Adjust columns on tab change
  $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
  });
});

// Quick Approve Function
function quickApprove(id) {
  currentScheduleId = id;
  $('#approve_notes').val('');
  $('#quickApproveModal').modal('show');
}

// Quick Reject Function
function quickReject(id) {
  currentScheduleId = id;
  $('#reject_reason').val('');
  $('#quickRejectModal').modal('show');
}

// Approve Form Submit
$('#quickApproveForm').on('submit', function(e) {
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
      $('#quickApproveForm button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...');
    },
    success: function(response) {
      if (response.success) {
        $('#quickApproveModal').modal('hide');
        Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: response.message,
          timer: 2000,
          showConfirmButton: false
        }).then(() => {
          // Reload all datatables
          $('.datatable-pending').DataTable().ajax.reload();
          $('.datatable-approved').DataTable().ajax.reload();
          $('.datatable-all').DataTable().ajax.reload();
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
    },
    complete: function() {
      $('#quickApproveForm button[type="submit"]').prop('disabled', false).html('<i class="fas fa-check mr-1"></i> Setujui');
    }
  });
});

// Reject Form Submit
$('#quickRejectForm').on('submit', function(e) {
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
      $('#quickRejectForm button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...');
    },
    success: function(response) {
      if (response.success) {
        $('#quickRejectModal').modal('hide');
        Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: response.message,
          timer: 2000,
          showConfirmButton: false
        }).then(() => {
          // Reload all datatables
          $('.datatable-pending').DataTable().ajax.reload();
          $('.datatable-rejected').DataTable().ajax.reload();
          $('.datatable-all').DataTable().ajax.reload();
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
    },
    complete: function() {
      $('#quickRejectForm button[type="submit"]').prop('disabled', false).html('<i class="fas fa-times mr-1"></i> Tolak');
    }
  });
});

// Delete Schedule Function
function deleteSchedule(id) {
  Swal.fire({
    title: 'Apakah Anda yakin?',
    text: "Data jadwal akan dihapus permanen!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Ya, Hapus!',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: `/admin/application-schedules/${id}`,
        method: 'DELETE',
        data: {
          _token: '{{ csrf_token() }}'
        },
        success: function(response) {
          Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Jadwal berhasil dihapus',
            timer: 2000,
            showConfirmButton: false
          }).then(() => {
            // Reload all datatables
            $('.datatable').DataTable().ajax.reload();
          });
        },
        error: function(xhr) {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Gagal menghapus jadwal'
          });
        }
      });
    }
  });
}
</script>
@endsection
