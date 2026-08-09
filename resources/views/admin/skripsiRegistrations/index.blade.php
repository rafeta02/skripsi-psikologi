@extends('layouts.admin')
@section('content')
@can('skripsi_registration_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.skripsi-registrations.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.skripsiRegistration.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.skripsiRegistration.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-SkripsiRegistration text-center">
            <thead>
                <tr>
                    <th width="10" class="text-center"></th>
                    <th class="text-center">Mahasiswa</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Tema Riset</th>
                    <th class="text-center">{{ trans('cruds.skripsiRegistration.fields.title') }}</th>
                    <th class="text-center">{{ trans('cruds.skripsiRegistration.fields.preference_supervision') }}</th>
                    <th class="text-center">{{ trans('cruds.skripsiRegistration.fields.khs_all') }}</th>
                    <th class="text-center">{{ trans('cruds.skripsiRegistration.fields.krs_latest') }}</th>
                    <th class="text-center">&nbsp;</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<style>
    .datatable-SkripsiRegistration th,
    .datatable-SkripsiRegistration td {
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
@can('skripsi_registration_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.skripsi-registrations.massDestroy') }}",
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
    ajax: "{{ route('admin.skripsi-registrations.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder', className: 'text-center' },
      { data: 'mahasiswa_name', name: 'application.mahasiswa.nama', className: 'text-center' },
      { data: 'status_badge', name: 'application.status', className: 'text-center', orderable: true, searchable: true },
      { data: 'theme_name', name: 'theme.name', className: 'text-center', orderable: false, searchable: false },
      { data: 'title', name: 'title', className: 'text-center' },
      { data: 'preference_supervision_nama', name: 'preference_supervision.nama', className: 'text-center' },
      { data: 'khs_all', name: 'khs_all', sortable: false, searchable: false, className: 'text-center' },
      { data: 'krs_latest', name: 'krs_latest', sortable: false, searchable: false, className: 'text-center' },
      { data: 'actions', name: '{{ trans('global.actions') }}', className: 'text-center' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 50,
    columnDefs: [
      { className: 'text-center', targets: '_all' }
    ],
  };
  let table = $('.datatable-SkripsiRegistration').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection
