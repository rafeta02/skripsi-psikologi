@extends('layouts.admin')
@section('content')
@can('mbkm_registration_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.mbkm-registrations.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.mbkmRegistration.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.mbkmRegistration.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-MbkmRegistration">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.mbkmRegistration.fields.application') }}
                    </th>
                    <th>
                        {{ trans('cruds.mbkmRegistration.fields.research_group') }}
                    </th>
                    <th>
                        {{ trans('cruds.mbkmRegistration.fields.preference_supervision') }}
                    </th>
                    <th>
                        {{ trans('cruds.mbkmRegistration.fields.theme') }}
                    </th>
                    <th>
                        {{ trans('cruds.mbkmRegistration.fields.title_mbkm') }}
                    </th>
                    <th>
                        {{ trans('cruds.mbkmRegistration.fields.title') }}
                    </th>
                    <th>
                        {{ trans('cruds.mbkmRegistration.fields.total_sks_taken') }}
                    </th>
                    <th>
                        {{ trans('cruds.mbkmRegistration.fields.note') }}
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
@can('mbkm_registration_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.mbkm-registrations.massDestroy') }}",
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
    ajax: "{{ route('admin.mbkm-registrations.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'application_status', name: 'application.status' },
{ data: 'research_group_name', name: 'research_group.name' },
{ data: 'preference_supervision_nip', name: 'preference_supervision.nama' },
{ data: 'theme_name', name: 'theme.name' },
{ data: 'title_mbkm', name: 'title_mbkm' },
{ data: 'title', name: 'title' },
{ data: 'total_sks_taken', name: 'total_sks_taken' },
{ data: 'note', name: 'note' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 50,
  };
  let table = $('.datatable-MbkmRegistration').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection