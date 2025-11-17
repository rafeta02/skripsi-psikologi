@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <h3 class="mb-0"><i class="fas fa-graduation-cap mr-2"></i>{{ trans('cruds.skripsiDefense.title') }} - Monitoring & Validasi</h3>
        <small>Daftar pendaftaran sidang skripsi untuk divalidasi</small>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-SkripsiDefense">
            <thead>
                <tr>
                    <th width="10"></th>
                    <th>Mahasiswa</th>
                    <th>NIM</th>
                    <th>{{ trans('cruds.skripsiDefense.fields.title') }}</th>
                    <th>{{ trans('cruds.skripsiDefense.fields.application') }}</th>
                    <th>Status Validasi</th>
                    <th>Tanggal Dibuat</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@endsection

@section('scripts')
@parent
<script>
$(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
  
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.skripsi-defenses.massDestroy') }}",
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

  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.skripsi-defenses.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
      { data: 'mahasiswa_name', name: 'application.mahasiswa.user.name', orderable: true, searchable: true },
      { data: 'mahasiswa_nim', name: 'application.mahasiswa.nim', orderable: true, searchable: true },
      { data: 'title', name: 'title', orderable: true, searchable: true },
      { data: 'application_status', name: 'application.status', orderable: true, searchable: true },
      { data: 'status', name: 'status', orderable: true, searchable: true },
      { data: 'created_at', name: 'created_at', orderable: true, searchable: true },
      { data: 'actions', name: '{{ trans('global.actions') }}', orderable: false, searchable: false }
    ],
    orderCellsTop: true,
    order: [[ 6, 'desc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-SkripsiDefense').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
         .columns.adjust();
  });
  
});

</script>
@endsection
