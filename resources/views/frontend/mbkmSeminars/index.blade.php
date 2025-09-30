@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('mbkm_seminar_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.mbkm-seminars.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.mbkmSeminar.title_singular') }}
                        </a>
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.mbkmSeminar.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-MbkmSeminar">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mbkmSeminar.fields.application') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.mbkmSeminar.fields.title') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.mbkmSeminar.fields.proposal_document') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.mbkmSeminar.fields.approval_document') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.mbkmSeminar.fields.plagiarism_document') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mbkmSeminars as $key => $mbkmSeminar)
                                    <tr data-entry-id="{{ $mbkmSeminar->id }}">
                                        <td>
                                            {{ $mbkmSeminar->application->status ?? '' }}
                                        </td>
                                        <td>
                                            {{ $mbkmSeminar->title ?? '' }}
                                        </td>
                                        <td>
                                            @if($mbkmSeminar->proposal_document)
                                                <a href="{{ $mbkmSeminar->proposal_document->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @if($mbkmSeminar->approval_document)
                                                <a href="{{ $mbkmSeminar->approval_document->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @if($mbkmSeminar->plagiarism_document)
                                                <a href="{{ $mbkmSeminar->plagiarism_document->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @can('mbkm_seminar_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.mbkm-seminars.show', $mbkmSeminar->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('mbkm_seminar_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.mbkm-seminars.edit', $mbkmSeminar->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('mbkm_seminar_delete')
                                                <form action="{{ route('frontend.mbkm-seminars.destroy', $mbkmSeminar->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('mbkm_seminar_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.mbkm-seminars.massDestroy') }}",
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
  let table = $('.datatable-MbkmSeminar:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection