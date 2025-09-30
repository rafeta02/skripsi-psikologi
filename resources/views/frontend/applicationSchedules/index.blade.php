@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('application_schedule_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.application-schedules.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.applicationSchedule.title_singular') }}
                        </a>
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.applicationSchedule.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-ApplicationSchedule">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationSchedule.fields.application') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.applicationSchedule.fields.schedule_type') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.applicationSchedule.fields.waktu') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.applicationSchedule.fields.ruang') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.applicationSchedule.fields.online_meeting') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($applicationSchedules as $key => $applicationSchedule)
                                    <tr data-entry-id="{{ $applicationSchedule->id }}">
                                        <td>
                                            {{ $applicationSchedule->application->status ?? '' }}
                                        </td>
                                        <td>
                                            {{ App\Models\ApplicationSchedule::SCHEDULE_TYPE_SELECT[$applicationSchedule->schedule_type] ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationSchedule->waktu ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationSchedule->ruang->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationSchedule->online_meeting ?? '' }}
                                        </td>
                                        <td>
                                            @can('application_schedule_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.application-schedules.show', $applicationSchedule->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('application_schedule_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.application-schedules.edit', $applicationSchedule->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('application_schedule_delete')
                                                <form action="{{ route('frontend.application-schedules.destroy', $applicationSchedule->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('application_schedule_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.application-schedules.massDestroy') }}",
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
  let table = $('.datatable-ApplicationSchedule:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection