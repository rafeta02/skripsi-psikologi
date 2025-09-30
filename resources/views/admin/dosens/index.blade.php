@extends('layouts.admin')
@section('content')
@can('dosen_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.dosens.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.dosen.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'Dosen', 'route' => 'admin.dosens.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.dosen.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Dosen">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.dosen.fields.nip') }}
                    </th>
                    <th>
                        {{ trans('cruds.dosen.fields.nidn') }}
                    </th>
                    <th>
                        {{ trans('cruds.dosen.fields.nama') }}
                    </th>
                    <th>
                        {{ trans('cruds.dosen.fields.tempat_lahir') }}
                    </th>
                    <th>
                        {{ trans('cruds.dosen.fields.tanggal_lahir') }}
                    </th>
                    <th>
                        {{ trans('cruds.dosen.fields.gender') }}
                    </th>
                    <th>
                        {{ trans('cruds.dosen.fields.prodi') }}
                    </th>
                    <th>
                        {{ trans('cruds.dosen.fields.keilmuan') }}
                    </th>
                    <th>
                        {{ trans('cruds.dosen.fields.riset_grup') }}
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
@can('dosen_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.dosens.massDestroy') }}",
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
    ajax: "{{ route('admin.dosens.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'nip', name: 'nip' },
{ data: 'nidn', name: 'nidn' },
{ data: 'nama', name: 'nama' },
{ data: 'tempat_lahir', name: 'tempat_lahir' },
{ data: 'tanggal_lahir', name: 'tanggal_lahir' },
{ data: 'gender', name: 'gender' },
{ data: 'prodi_name', name: 'prodi.name' },
{ data: 'keilmuan', name: 'keilmuans.name' },
{ data: 'riset_grup_name', name: 'riset_grup.name' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 50,
  };
  let table = $('.datatable-Dosen').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection