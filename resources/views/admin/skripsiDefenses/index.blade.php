@extends('layouts.admin')
@section('content')
@can('skripsi_defense_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.skripsi-defenses.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.skripsiDefense.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.skripsiDefense.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-SkripsiDefense">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.skripsiDefense.fields.application') }}
                    </th>
                    <th>
                        {{ trans('cruds.skripsiDefense.fields.title') }}
                    </th>
                    <th>
                        {{ trans('cruds.skripsiDefense.fields.abstract') }}
                    </th>
                    <th>
                        {{ trans('cruds.skripsiDefense.fields.defence_document') }}
                    </th>
                    <th>
                        {{ trans('cruds.skripsiDefense.fields.plagiarism_report') }}
                    </th>
                    <th>
                        {{ trans('cruds.skripsiDefense.fields.ethics_statement') }}
                    </th>
                    <th>
                        {{ trans('cruds.skripsiDefense.fields.research_instruments') }}
                    </th>
                    <th>
                        {{ trans('cruds.skripsiDefense.fields.data_collection_letter') }}
                    </th>
                    <th>
                        {{ trans('cruds.skripsiDefense.fields.research_module') }}
                    </th>
                    <th>
                        {{ trans('cruds.skripsiDefense.fields.mbkm_recommendation_letter') }}
                    </th>
                    <th>
                        {{ trans('cruds.skripsiDefense.fields.publication_statement') }}
                    </th>
                    <th>
                        {{ trans('cruds.skripsiDefense.fields.defense_approval_page') }}
                    </th>
                    <th>
                        {{ trans('cruds.skripsiDefense.fields.spp_receipt') }}
                    </th>
                    <th>
                        {{ trans('cruds.skripsiDefense.fields.krs_latest') }}
                    </th>
                    <th>
                        {{ trans('cruds.skripsiDefense.fields.eap_certificate') }}
                    </th>
                    <th>
                        {{ trans('cruds.skripsiDefense.fields.transcript') }}
                    </th>
                    <th>
                        {{ trans('cruds.skripsiDefense.fields.mbkm_report') }}
                    </th>
                    <th>
                        {{ trans('cruds.skripsiDefense.fields.research_poster') }}
                    </th>
                    <th>
                        {{ trans('cruds.skripsiDefense.fields.siakad_supervisor_screenshot') }}
                    </th>
                    <th>
                        {{ trans('cruds.skripsiDefense.fields.supervision_logbook') }}
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
@can('skripsi_defense_delete')
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
@endcan

  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.skripsi-defenses.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'application_status', name: 'application.status' },
{ data: 'title', name: 'title' },
{ data: 'abstract', name: 'abstract' },
{ data: 'defence_document', name: 'defence_document', sortable: false, searchable: false },
{ data: 'plagiarism_report', name: 'plagiarism_report', sortable: false, searchable: false },
{ data: 'ethics_statement', name: 'ethics_statement', sortable: false, searchable: false },
{ data: 'research_instruments', name: 'research_instruments', sortable: false, searchable: false },
{ data: 'data_collection_letter', name: 'data_collection_letter', sortable: false, searchable: false },
{ data: 'research_module', name: 'research_module', sortable: false, searchable: false },
{ data: 'mbkm_recommendation_letter', name: 'mbkm_recommendation_letter', sortable: false, searchable: false },
{ data: 'publication_statement', name: 'publication_statement', sortable: false, searchable: false },
{ data: 'defense_approval_page', name: 'defense_approval_page', sortable: false, searchable: false },
{ data: 'spp_receipt', name: 'spp_receipt', sortable: false, searchable: false },
{ data: 'krs_latest', name: 'krs_latest', sortable: false, searchable: false },
{ data: 'eap_certificate', name: 'eap_certificate', sortable: false, searchable: false },
{ data: 'transcript', name: 'transcript', sortable: false, searchable: false },
{ data: 'mbkm_report', name: 'mbkm_report', sortable: false, searchable: false },
{ data: 'research_poster', name: 'research_poster', sortable: false, searchable: false },
{ data: 'siakad_supervisor_screenshot', name: 'siakad_supervisor_screenshot', sortable: false, searchable: false },
{ data: 'supervision_logbook', name: 'supervision_logbook', sortable: false, searchable: false },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 50,
  };
  let table = $('.datatable-SkripsiDefense').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection