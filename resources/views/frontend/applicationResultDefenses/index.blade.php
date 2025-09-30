@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('application_result_defense_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.application-result-defenses.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.applicationResultDefense.title_singular') }}
                        </a>
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.applicationResultDefense.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-ApplicationResultDefense">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationResultDefense.fields.application') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.applicationResultDefense.fields.result') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.applicationResultDefense.fields.revision_deadline') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.applicationResultDefense.fields.invitation_document') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.applicationResultDefense.fields.feedback_document') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.applicationResultDefense.fields.minutes_document') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.applicationResultDefense.fields.latest_script') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.applicationResultDefense.fields.approval_page') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.applicationResultDefense.fields.report_document') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.applicationResultDefense.fields.revision_approval_sheet') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($applicationResultDefenses as $key => $applicationResultDefense)
                                    <tr data-entry-id="{{ $applicationResultDefense->id }}">
                                        <td>
                                            {{ $applicationResultDefense->application->status ?? '' }}
                                        </td>
                                        <td>
                                            {{ App\Models\ApplicationResultDefense::RESULT_SELECT[$applicationResultDefense->result] ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationResultDefense->revision_deadline ?? '' }}
                                        </td>
                                        <td>
                                            @foreach($applicationResultDefense->invitation_document as $key => $media)
                                                <a href="{{ $media->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($applicationResultDefense->feedback_document as $key => $media)
                                                <a href="{{ $media->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if($applicationResultDefense->minutes_document)
                                                <a href="{{ $applicationResultDefense->minutes_document->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @if($applicationResultDefense->latest_script)
                                                <a href="{{ $applicationResultDefense->latest_script->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @if($applicationResultDefense->approval_page)
                                                <a href="{{ $applicationResultDefense->approval_page->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @foreach($applicationResultDefense->report_document as $key => $media)
                                                <a href="{{ $media->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($applicationResultDefense->revision_approval_sheet as $key => $media)
                                                <a href="{{ $media->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endforeach
                                        </td>
                                        <td>
                                            @can('application_result_defense_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.application-result-defenses.show', $applicationResultDefense->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('application_result_defense_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.application-result-defenses.edit', $applicationResultDefense->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('application_result_defense_delete')
                                                <form action="{{ route('frontend.application-result-defenses.destroy', $applicationResultDefense->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('application_result_defense_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.application-result-defenses.massDestroy') }}",
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
  let table = $('.datatable-ApplicationResultDefense:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection