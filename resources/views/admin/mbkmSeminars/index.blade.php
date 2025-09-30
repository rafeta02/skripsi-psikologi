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
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-MbkmSeminar">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.mbkmSeminar.fields.application') }}
                    </th>
                    <th>
                        {{ trans('cruds.mbkmSeminar.fields.title') }}
                    </th>
                    <th>
                        {{ trans('cruds.mbkmSeminar.fields.proposal_document') }}
                    </th>
                    <th>
                        {{ trans('cruds.mbkmSeminar.fields.approval_document') }}
                    </th>
                    <th>
                        {{ trans('cruds.mbkmSeminar.fields.plagiarism_document') }}
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
@can('mbkm_seminar_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
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
      { data: 'placeholder', name: 'placeholder' },
{ data: 'application_status', name: 'application.status' },
{ data: 'title', name: 'title' },
{ data: 'proposal_document', name: 'proposal_document', sortable: false, searchable: false },
{ data: 'approval_document', name: 'approval_document', sortable: false, searchable: false },
{ data: 'plagiarism_document', name: 'plagiarism_document', sortable: false, searchable: false },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
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