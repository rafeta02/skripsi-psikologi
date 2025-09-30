@extends('layouts.admin')
@section('content')
@can('application_schedule_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.application-schedules.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.applicationSchedule.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.applicationSchedule.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-ApplicationSchedule">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.applicationSchedule.fields.application') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationSchedule.fields.schedule_type') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationSchedule.fields.waktu') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationSchedule.fields.ruang') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationSchedule.fields.online_meeting') }}
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
@can('application_schedule_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.application-schedules.massDestroy') }}",
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
    ajax: "{{ route('admin.application-schedules.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'application_status', name: 'application.status' },
{ data: 'schedule_type', name: 'schedule_type' },
{ data: 'waktu', name: 'waktu' },
{ data: 'ruang_name', name: 'ruang.name' },
{ data: 'online_meeting', name: 'online_meeting' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 50,
  };
  let table = $('.datatable-ApplicationSchedule').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection