@extends('layouts.admin')
@section('content')
@can('mahasiswa_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.mahasiswas.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.mahasiswa.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'Mahasiswa', 'route' => 'admin.mahasiswas.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.mahasiswa.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Mahasiswa">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.mahasiswa.fields.nim') }}
                    </th>
                    <th>
                        {{ trans('cruds.mahasiswa.fields.nama') }}
                    </th>
                    <th>
                        {{ trans('cruds.mahasiswa.fields.tahun_masuk') }}
                    </th>
                    <th>
                        {{ trans('cruds.mahasiswa.fields.kelas') }}
                    </th>
                    <th>
                        {{ trans('cruds.mahasiswa.fields.prodi') }}
                    </th>
                    <th>
                        {{ trans('cruds.mahasiswa.fields.jenjang') }}
                    </th>
                    <th>
                        {{ trans('cruds.mahasiswa.fields.fakultas') }}
                    </th>
                    <th>
                        {{ trans('cruds.mahasiswa.fields.tanggal_lahir') }}
                    </th>
                    <th>
                        {{ trans('cruds.mahasiswa.fields.tempat_lahir') }}
                    </th>
                    <th>
                        {{ trans('cruds.mahasiswa.fields.gender') }}
                    </th>
                    <th>
                        {{ trans('cruds.mahasiswa.fields.alamat') }}
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
@can('mahasiswa_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.mahasiswas.massDestroy') }}",
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
    ajax: "{{ route('admin.mahasiswas.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'nim', name: 'nim' },
{ data: 'nama', name: 'nama' },
{ data: 'tahun_masuk', name: 'tahun_masuk' },
{ data: 'kelas', name: 'kelas' },
{ data: 'prodi_name', name: 'prodi.name' },
{ data: 'jenjang_name', name: 'jenjang.name' },
{ data: 'fakultas_name', name: 'fakultas.name' },
{ data: 'tanggal_lahir', name: 'tanggal_lahir' },
{ data: 'tempat_lahir', name: 'tempat_lahir' },
{ data: 'gender', name: 'gender' },
{ data: 'alamat', name: 'alamat' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 50,
  };
  let table = $('.datatable-Mahasiswa').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection