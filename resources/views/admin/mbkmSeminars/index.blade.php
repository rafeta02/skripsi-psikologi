@extends('layouts.admin')
@section('content')
@can('mbkm_seminar_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.mbkm-seminars.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.mbkmSeminar.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.mbkmSeminar.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-MbkmSeminar text-center">
            <thead>
                <tr>
                    <th width="10" class="text-center"></th>
                    <th class="text-center">{{ trans('cruds.mbkmSeminar.fields.kelompok') }}</th>
                    <th class="text-center">{{ trans('cruds.mbkmSeminar.fields.title') }}</th>
                    <th class="text-center">{{ trans('cruds.mbkmSeminar.fields.pembimbing') }}</th>
                    <th class="text-center">&nbsp;</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<style>
    .datatable-MbkmSeminar th,
    .datatable-MbkmSeminar td {
        text-align: center !important;
        vertical-align: middle !important;
    }
    .datatable-MbkmSeminar td ul.list-unstyled {
        display: inline-block;
        text-align: left;
        margin: 0 auto;
    }
</style>

@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('mbkm_seminar_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.mbkm-seminars.massDestroy') }}",
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
    ajax: "{{ route('admin.mbkm-seminars.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder', className: 'text-center' },
      { data: 'kelompok', name: 'kelompok', searchable: false, orderable: false, className: 'text-center' },
      { data: 'title', name: 'title', className: 'text-center' },
      { data: 'pembimbing', name: 'pembimbing', searchable: false, orderable: false, className: 'text-center' },
      { data: 'actions', name: '{{ trans('global.actions') }}', className: 'text-center' }
    ],
    orderCellsTop: true,
    order: [[ 2, 'desc' ]],
    pageLength: 50,
  };
  let table = $('.datatable-MbkmSeminar').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection
