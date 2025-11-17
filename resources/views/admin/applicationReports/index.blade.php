@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-flag"></i> Monitoring Laporan Kendala Mahasiswa</h3>
    </div>

    <div class="card-body">
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> 
            <strong>Informasi:</strong> Halaman ini menampilkan semua laporan kendala yang dilaporkan oleh mahasiswa. 
            Anda dapat menandai laporan yang sudah ditinjau dengan mengklik tombol "Mark as Reviewed".
        </div>

        <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-ApplicationReport">
            <thead>
                <tr>
                    <th width="10"></th>
                    <th>Mahasiswa</th>
                    <th>NIM</th>
                    <th>Aplikasi</th>
                    <th>Periode</th>
                    <th>Status</th>
                    <th>Catatan</th>
                    <th>Review Action</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="reviewForm" action="" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewModalLabel">
                        <i class="fas fa-check-circle"></i> Mark as Reviewed
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Anda akan menandai laporan ini sebagai <strong>Reviewed</strong>. 
                        Silakan tambahkan catatan jika diperlukan.
                    </div>
                    
                    <div class="form-group">
                        <label for="reviewNote">
                            <i class="fas fa-sticky-note"></i> Catatan Admin
                        </label>
                        <textarea name="note" id="reviewNote" class="form-control" rows="5" placeholder="Tambahkan catatan untuk mahasiswa (opsional)..."></textarea>
                        <small class="form-text text-muted">
                            Catatan ini akan terlihat oleh mahasiswa dan dapat membantu mereka memahami tindak lanjut yang perlu dilakukan.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check"></i> Mark as Reviewed
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
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('application_report_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.application-reports.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
          return entry.id
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.application-reports.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
      { data: 'mahasiswa_name', name: 'application.mahasiswa.nama' },
      { data: 'mahasiswa_nim', name: 'application.mahasiswa.nim' },
      { data: 'application_type', name: 'application.type' },
      { data: 'period', name: 'period' },
      { data: 'status', name: 'status' },
      { data: 'note', name: 'note' },
      { data: 'review_action', name: 'review_action', orderable: false, searchable: false },
      { data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 5, 'asc' ]], // Order by status (submitted first)
    pageLength: 25,
  };
  let table = $('.datatable-ApplicationReport').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

// Handle review modal
$(document).on('click', '.btn-review', function() {
    var reportId = $(this).data('id');
    var mahasiswaName = $(this).data('mahasiswa');
    var currentNote = $(this).data('note');
    
    // Update modal content
    $('#reviewModalLabel').html('<i class="fas fa-check-circle"></i> Mark as Reviewed - ' + mahasiswaName);
    $('#reviewForm').attr('action', '/admin/application-reports/' + reportId + '/mark-reviewed');
    $('#reviewNote').val(currentNote);
});

</script>
@endsection