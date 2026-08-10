@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        <i class="fas fa-clipboard-check mr-2"></i> Laporan Hasil Sidang Skripsi
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-ApplicationResultDefense text-center">
            <thead>
                <tr>
                    <th width="10" class="text-center"></th>
                    <th class="text-center">Mahasiswa</th>
                    <th class="text-center">Result</th>
                    <th class="text-center">Judul</th>
                    <th class="text-center">Dosen Pembimbing</th>
                    <th class="text-center">Dosen Penguji</th>
                    <th width="120" class="text-center">Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<style>
    .datatable-ApplicationResultDefense th,
    .datatable-ApplicationResultDefense td {
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
@can('application_result_defense_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.application-result-defenses.massDestroy') }}",
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
    ajax: "{{ route('admin.application-result-defenses.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder', className: 'text-center' },
      { data: 'id', name: 'id', visible: false },
      { data: 'mahasiswa_name', name: 'application.mahasiswa.nama', orderable: true, searchable: true, className: 'text-center' },
      { data: 'result_badge', name: 'result', orderable: true, searchable: true, className: 'text-center' },
      { data: 'final_title', name: 'final_title', orderable: true, searchable: true, className: 'text-center' },
      { data: 'dosen_pembimbing', name: 'dosen_pembimbing', orderable: false, searchable: false, className: 'text-center' },
      { data: 'dosen_penguji', name: 'dosen_penguji', orderable: false, searchable: false, className: 'text-center' },
      { data: 'actions', name: '{{ trans('global.actions') }}', orderable: false, searchable: false, className: 'text-center' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 50,
    columnDefs: [
      { className: 'text-center', targets: '_all' }
    ],
  };
  let table = $('.datatable-ApplicationResultDefense').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection
