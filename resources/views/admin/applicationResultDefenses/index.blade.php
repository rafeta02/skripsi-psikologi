@extends('layouts.admin')
@section('content')
@can('application_result_defense_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.application-result-defenses.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.applicationResultDefense.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.applicationResultDefense.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-ApplicationResultDefense">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.applicationResultDefense.fields.application') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationResultDefense.fields.result') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationResultDefense.fields.revision_deadline') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationResultDefense.fields.invitation_document') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationResultDefense.fields.feedback_document') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationResultDefense.fields.minutes_document') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationResultDefense.fields.latest_script') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationResultDefense.fields.approval_page') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationResultDefense.fields.report_document') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationResultDefense.fields.revision_approval_sheet') }}
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
      { data: 'placeholder', name: 'placeholder' },
{ data: 'application_status', name: 'application.status' },
{ data: 'result', name: 'result' },
{ data: 'revision_deadline', name: 'revision_deadline' },
{ data: 'invitation_document', name: 'invitation_document', sortable: false, searchable: false },
{ data: 'feedback_document', name: 'feedback_document', sortable: false, searchable: false },
{ data: 'minutes_document', name: 'minutes_document', sortable: false, searchable: false },
{ data: 'latest_script', name: 'latest_script', sortable: false, searchable: false },
{ data: 'approval_page', name: 'approval_page', sortable: false, searchable: false },
{ data: 'report_document', name: 'report_document', sortable: false, searchable: false },
{ data: 'revision_approval_sheet', name: 'revision_approval_sheet', sortable: false, searchable: false },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 50,
  };
  let table = $('.datatable-ApplicationResultDefense').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection