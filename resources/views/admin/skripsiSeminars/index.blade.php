@extends('layouts.admin')
@section('content')
@can('skripsi_seminar_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.skripsi-seminars.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.skripsiSeminar.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.skripsiSeminar.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-SkripsiSeminar text-center">
            <thead>
                <tr>
                    <th width="10" class="text-center"></th>
                    <th class="text-center">Mahasiswa</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">{{ trans('cruds.skripsiSeminar.fields.title') }}</th>
                    <th class="text-center">{{ trans('cruds.skripsiSeminar.fields.proposal_document') }}</th>
                    <th class="text-center">{{ trans('cruds.skripsiSeminar.fields.approval_document') }}</th>
                    <th class="text-center">{{ trans('cruds.skripsiSeminar.fields.plagiarism_document') }}</th>
                    <th class="text-center">&nbsp;</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<style>
    .datatable-SkripsiSeminar th,
    .datatable-SkripsiSeminar td {
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
@can('skripsi_seminar_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.skripsi-seminars.massDestroy') }}",
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
    ajax: "{{ route('admin.skripsi-seminars.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder', className: 'text-center' },
      { data: 'mahasiswa_name', name: 'application.mahasiswa.nama', className: 'text-center' },
      { data: 'status_badge', name: 'application.status', className: 'text-center', orderable: true, searchable: true },
      { data: 'title', name: 'title', className: 'text-center' },
      { data: 'proposal_document', name: 'proposal_document', sortable: false, searchable: false, className: 'text-center' },
      { data: 'approval_document', name: 'approval_document', sortable: false, searchable: false, className: 'text-center' },
      { data: 'plagiarism_document', name: 'plagiarism_document', sortable: false, searchable: false, className: 'text-center' },
      { data: 'actions', name: '{{ trans('global.actions') }}', className: 'text-center' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 50,
    columnDefs: [
      { className: 'text-center', targets: '_all' }
    ],
  };
  let table = $('.datatable-SkripsiSeminar').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection
