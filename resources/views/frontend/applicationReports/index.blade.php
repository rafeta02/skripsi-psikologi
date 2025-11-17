@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('application_report_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.application-reports.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.applicationReport.title_singular') }}
                        </a>
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.applicationReport.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-ApplicationReport">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationReport.fields.application') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.applicationReport.fields.period') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.applicationReport.fields.status') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.applicationReport.fields.note') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($applicationReports as $key => $applicationReport)
                                    <tr data-entry-id="{{ $applicationReport->id }}">
                                        <td>
                                            {{ $applicationReport->application->type ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationReport->period ?? '' }}
                                        </td>
                                        <td>
                                            @if($applicationReport->status == 'submitted')
                                                <span class="badge badge-warning">Submitted</span>
                                            @else
                                                <span class="badge badge-success">Reviewed</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($applicationReport->note)
                                                <span class="badge badge-info" data-toggle="tooltip" title="{{ $applicationReport->note }}">
                                                    <i class="fas fa-comment"></i> Ada Catatan
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @can('application_report_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.application-reports.show', $applicationReport->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('application_report_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.application-reports.edit', $applicationReport->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('application_report_delete')
                                                <form action="{{ route('frontend.application-reports.destroy', $applicationReport->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('application_report_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.application-reports.massDestroy') }}",
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
  let table = $('.datatable-ApplicationReport:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
  // Initialize tooltips
  $('[data-toggle="tooltip"]').tooltip();
  
})

</script>
@endsection