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
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-SkripsiRegistration">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.skripsiRegistration.fields.application') }}
                    </th>
                    <th>
                        {{ trans('cruds.skripsiRegistration.fields.theme') }}
                    </th>
                    <th>
                        {{ trans('cruds.skripsiRegistration.fields.title') }}
                    </th>
                    <th>
                        {{ trans('cruds.skripsiRegistration.fields.abstract') }}
                    </th>
                    <th>
                        {{ trans('cruds.skripsiRegistration.fields.tps_lecturer') }}
                    </th>
                    <th>
                        {{ trans('cruds.skripsiRegistration.fields.preference_supervision') }}
                    </th>
                    <th>
                        {{ trans('cruds.skripsiRegistration.fields.khs_all') }}
                    </th>
                    <th>
                        {{ trans('cruds.skripsiRegistration.fields.krs_latest') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
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
      { data: 'placeholder', name: 'placeholder' },
{ data: 'application_status', name: 'application.status' },
{ data: 'theme_name', name: 'theme.name' },
{ data: 'title', name: 'title' },
{ data: 'abstract', name: 'abstract' },
{ data: 'tps_lecturer_nama', name: 'tps_lecturer.nama' },
{ data: 'preference_supervision_nama', name: 'preference_supervision.nama' },
{ data: 'khs_all', name: 'khs_all', sortable: false, searchable: false },
{ data: 'krs_latest', name: 'krs_latest', sortable: false, searchable: false },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 50,
  };
  let table = $('.datatable-SkripsiRegistration').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection