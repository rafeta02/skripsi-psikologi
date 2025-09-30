@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('skripsi_defense_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.skripsi-defenses.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.skripsiDefense.title_singular') }}
                        </a>
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.skripsiDefense.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-SkripsiDefense">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.skripsiDefense.fields.application') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.skripsiDefense.fields.title') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.skripsiDefense.fields.abstract') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.skripsiDefense.fields.defence_document') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.skripsiDefense.fields.plagiarism_report') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.skripsiDefense.fields.ethics_statement') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.skripsiDefense.fields.research_instruments') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.skripsiDefense.fields.data_collection_letter') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.skripsiDefense.fields.research_module') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.skripsiDefense.fields.mbkm_recommendation_letter') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.skripsiDefense.fields.publication_statement') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.skripsiDefense.fields.defense_approval_page') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.skripsiDefense.fields.spp_receipt') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.skripsiDefense.fields.krs_latest') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.skripsiDefense.fields.eap_certificate') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.skripsiDefense.fields.transcript') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.skripsiDefense.fields.mbkm_report') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.skripsiDefense.fields.research_poster') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.skripsiDefense.fields.siakad_supervisor_screenshot') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.skripsiDefense.fields.supervision_logbook') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($skripsiDefenses as $key => $skripsiDefense)
                                    <tr data-entry-id="{{ $skripsiDefense->id }}">
                                        <td>
                                            {{ $skripsiDefense->application->status ?? '' }}
                                        </td>
                                        <td>
                                            {{ $skripsiDefense->title ?? '' }}
                                        </td>
                                        <td>
                                            {{ $skripsiDefense->abstract ?? '' }}
                                        </td>
                                        <td>
                                            @if($skripsiDefense->defence_document)
                                                <a href="{{ $skripsiDefense->defence_document->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @if($skripsiDefense->plagiarism_report)
                                                <a href="{{ $skripsiDefense->plagiarism_report->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @foreach($skripsiDefense->ethics_statement as $key => $media)
                                                <a href="{{ $media->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($skripsiDefense->research_instruments as $key => $media)
                                                <a href="{{ $media->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($skripsiDefense->data_collection_letter as $key => $media)
                                                <a href="{{ $media->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($skripsiDefense->research_module as $key => $media)
                                                <a href="{{ $media->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if($skripsiDefense->mbkm_recommendation_letter)
                                                <a href="{{ $skripsiDefense->mbkm_recommendation_letter->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @if($skripsiDefense->publication_statement)
                                                <a href="{{ $skripsiDefense->publication_statement->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @foreach($skripsiDefense->defense_approval_page as $key => $media)
                                                <a href="{{ $media->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if($skripsiDefense->spp_receipt)
                                                <a href="{{ $skripsiDefense->spp_receipt->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @if($skripsiDefense->krs_latest)
                                                <a href="{{ $skripsiDefense->krs_latest->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @if($skripsiDefense->eap_certificate)
                                                <a href="{{ $skripsiDefense->eap_certificate->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @if($skripsiDefense->transcript)
                                                <a href="{{ $skripsiDefense->transcript->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @foreach($skripsiDefense->mbkm_report as $key => $media)
                                                <a href="{{ $media->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($skripsiDefense->research_poster as $key => $media)
                                                <a href="{{ $media->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if($skripsiDefense->siakad_supervisor_screenshot)
                                                <a href="{{ $skripsiDefense->siakad_supervisor_screenshot->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @foreach($skripsiDefense->supervision_logbook as $key => $media)
                                                <a href="{{ $media->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endforeach
                                        </td>
                                        <td>
                                            @can('skripsi_defense_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.skripsi-defenses.show', $skripsiDefense->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('skripsi_defense_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.skripsi-defenses.edit', $skripsiDefense->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('skripsi_defense_delete')
                                                <form action="{{ route('frontend.skripsi-defenses.destroy', $skripsiDefense->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('skripsi_defense_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.skripsi-defenses.massDestroy') }}",
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
  let table = $('.datatable-SkripsiDefense:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection