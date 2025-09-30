@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('mbkm_registration_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.mbkm-registrations.create') }}">
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
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-MbkmRegistration">
                            <thead>
                                <tr>
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
                            <tbody>
                                @foreach($mbkmRegistrations as $key => $mbkmRegistration)
                                    <tr data-entry-id="{{ $mbkmRegistration->id }}">
                                        <td>
                                            {{ $mbkmRegistration->application->status ?? '' }}
                                        </td>
                                        <td>
                                            {{ $mbkmRegistration->research_group->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $mbkmRegistration->preference_supervision->nip ?? '' }}
                                        </td>
                                        <td>
                                            {{ $mbkmRegistration->theme->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $mbkmRegistration->title_mbkm ?? '' }}
                                        </td>
                                        <td>
                                            {{ $mbkmRegistration->title ?? '' }}
                                        </td>
                                        <td>
                                            {{ $mbkmRegistration->total_sks_taken ?? '' }}
                                        </td>
                                        <td>
                                            {{ $mbkmRegistration->note ?? '' }}
                                        </td>
                                        <td>
                                            @can('mbkm_registration_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.mbkm-registrations.show', $mbkmRegistration->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('mbkm_registration_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.mbkm-registrations.edit', $mbkmRegistration->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('mbkm_registration_delete')
                                                <form action="{{ route('frontend.mbkm-registrations.destroy', $mbkmRegistration->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('mbkm_registration_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.mbkm-registrations.massDestroy') }}",
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
  let table = $('.datatable-MbkmRegistration:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection