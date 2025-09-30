@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('application_score_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.application-scores.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.applicationScore.title_singular') }}
                        </a>
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.applicationScore.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-ApplicationScore">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationScore.fields.application_result_defence') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.applicationScore.fields.examiner') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.applicationScore.fields.score') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.applicationScore.fields.note') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($applicationScores as $key => $applicationScore)
                                    <tr data-entry-id="{{ $applicationScore->id }}">
                                        <td>
                                            {{ $applicationScore->application_result_defence->result ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationScore->examiner->nama ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationScore->score ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationScore->note ?? '' }}
                                        </td>
                                        <td>
                                            @can('application_score_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.application-scores.show', $applicationScore->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('application_score_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.application-scores.edit', $applicationScore->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('application_score_delete')
                                                <form action="{{ route('frontend.application-scores.destroy', $applicationScore->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('application_score_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.application-scores.massDestroy') }}",
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
  let table = $('.datatable-ApplicationScore:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection