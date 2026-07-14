@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        <i class="fas fa-clipboard-check mr-2"></i> Laporan Hasil Review Kelayakan Proposal
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-ApplicationResultSeminar text-center">
            <thead>
                <tr>
                    <th width="10" class="text-center"></th>
                    <th width="80" class="text-center">ID</th>
                    <th class="text-center">Mahasiswa</th>
                    <th width="120" class="text-center">NIM</th>
                    <th class="text-center">Hasil Review</th>
                    <th width="160" class="text-center">Status Validasi</th>
                    <th width="140" class="text-center">Tenggat Revisi</th>
                    <th width="120" class="text-center">Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<style>
    .datatable-ApplicationResultSeminar th,
    .datatable-ApplicationResultSeminar td {
        text-align: center !important;
        vertical-align: middle !important;
    }
</style>

@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('application_result_seminar_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.application-result-seminars.massDestroy') }}",
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
    ajax: "{{ route('admin.application-result-seminars.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder', className: 'text-center' },
      { data: 'id', name: 'id', className: 'text-center' },
      { data: 'mahasiswa_name', name: 'mahasiswa_name', orderable: false, searchable: false, className: 'text-center' },
      { data: 'mahasiswa_nim', name: 'mahasiswa_nim', orderable: false, searchable: false, className: 'text-center' },
      { data: 'result', name: 'result', className: 'text-center' },
      { data: 'application_status', name: 'application_status', orderable: false, searchable: false, className: 'text-center' },
      { data: 'revision_deadline', name: 'revision_deadline', className: 'text-center' },
      { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-ApplicationResultSeminar').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection
