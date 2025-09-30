@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('dosen_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.dosens.create') }}">
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
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-Dosen">
                            <thead>
                                <tr>
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
                            <tbody>
                                @foreach($dosens as $key => $dosen)
                                    <tr data-entry-id="{{ $dosen->id }}">
                                        <td>
                                            {{ $dosen->nip ?? '' }}
                                        </td>
                                        <td>
                                            {{ $dosen->nidn ?? '' }}
                                        </td>
                                        <td>
                                            {{ $dosen->nama ?? '' }}
                                        </td>
                                        <td>
                                            {{ $dosen->tempat_lahir ?? '' }}
                                        </td>
                                        <td>
                                            {{ $dosen->tanggal_lahir ?? '' }}
                                        </td>
                                        <td>
                                            {{ App\Models\Dosen::GENDER_SELECT[$dosen->gender] ?? '' }}
                                        </td>
                                        <td>
                                            {{ $dosen->prodi->name ?? '' }}
                                        </td>
                                        <td>
                                            @foreach($dosen->keilmuans as $key => $item)
                                                <span>{{ $item->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            {{ $dosen->riset_grup->name ?? '' }}
                                        </td>
                                        <td>
                                            @can('dosen_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.dosens.show', $dosen->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('dosen_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.dosens.edit', $dosen->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('dosen_delete')
                                                <form action="{{ route('frontend.dosens.destroy', $dosen->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                                </form>
                                            @endcan

                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('dosen_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.dosens.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
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

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 50,
  });
  let table = $('.datatable-Dosen:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection