@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('application_assignment_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.application-assignments.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.applicationAssignment.title_singular') }}
                        </a>
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.applicationAssignment.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-ApplicationAssignment">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationAssignment.fields.application') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.applicationAssignment.fields.lecturer') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.applicationAssignment.fields.role') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.applicationAssignment.fields.status') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.applicationAssignment.fields.responded_at') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($applicationAssignments as $key => $applicationAssignment)
                                    <tr data-entry-id="{{ $applicationAssignment->id }}">
                                        <td>
                                            {{ $applicationAssignment->application->status ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationAssignment->lecturer->nidn ?? '' }}
                                        </td>
                                        <td>
                                            {{ App\Models\ApplicationAssignment::ROLE_SELECT[$applicationAssignment->role] ?? '' }}
                                        </td>
                                        <td>
                                            {{ App\Models\ApplicationAssignment::STATUS_SELECT[$applicationAssignment->status] ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationAssignment->responded_at ?? '' }}
                                        </td>
                                        <td>
                                            @can('application_assignment_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.application-assignments.show', $applicationAssignment->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('application_assignment_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.application-assignments.edit', $applicationAssignment->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('application_assignment_delete')
                                                <form action="{{ route('frontend.application-assignments.destroy', $applicationAssignment->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('application_assignment_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.application-assignments.massDestroy') }}",
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
  let table = $('.datatable-ApplicationAssignment:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection