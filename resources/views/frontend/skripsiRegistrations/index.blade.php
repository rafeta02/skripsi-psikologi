@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('skripsi_registration_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.skripsi-registrations.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.skripsiRegistration.title_singular') }}
                        </a>
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.skripsiRegistration.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-SkripsiRegistration">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.skripsiRegistration.fields.application') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.skripsiRegistration.fields.theme') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.skripsiRegistration.fields.title') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.skripsiRegistration.fields.abstract') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.skripsiRegistration.fields.tps_lecturer') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.skripsiRegistration.fields.preference_supervision') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.skripsiRegistration.fields.khs_all') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.skripsiRegistration.fields.krs_latest') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($skripsiRegistrations as $key => $skripsiRegistration)
                                    <tr data-entry-id="{{ $skripsiRegistration->id }}">
                                        <td>
                                            {{ $skripsiRegistration->application->status ?? '' }}
                                        </td>
                                        <td>
                                            {{ $skripsiRegistration->theme->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $skripsiRegistration->title ?? '' }}
                                        </td>
                                        <td>
                                            {{ $skripsiRegistration->abstract ?? '' }}
                                        </td>
                                        <td>
                                            {{ $skripsiRegistration->tps_lecturer->nama ?? '' }}
                                        </td>
                                        <td>
                                            {{ $skripsiRegistration->preference_supervision->nama ?? '' }}
                                        </td>
                                        <td>
                                            @foreach($skripsiRegistration->khs_all as $key => $media)
                                                <a href="{{ $media->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if($skripsiRegistration->krs_latest)
                                                <a href="{{ $skripsiRegistration->krs_latest->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @can('skripsi_registration_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.skripsi-registrations.show', $skripsiRegistration->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('skripsi_registration_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.skripsi-registrations.edit', $skripsiRegistration->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('skripsi_registration_delete')
                                                <form action="{{ route('frontend.skripsi-registrations.destroy', $skripsiRegistration->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('skripsi_registration_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.skripsi-registrations.massDestroy') }}",
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
  let table = $('.datatable-SkripsiRegistration:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection