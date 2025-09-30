@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('mbkm_group_member_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.mbkm-group-members.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.mbkmGroupMember.title_singular') }}
                        </a>
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.mbkmGroupMember.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-MbkmGroupMember">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mbkmGroupMember.fields.mbkm_registration') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.mbkmGroupMember.fields.mahasiswa') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.mbkmGroupMember.fields.role') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mbkmGroupMembers as $key => $mbkmGroupMember)
                                    <tr data-entry-id="{{ $mbkmGroupMember->id }}">
                                        <td>
                                            {{ $mbkmGroupMember->mbkm_registration->title_mbkm ?? '' }}
                                        </td>
                                        <td>
                                            {{ $mbkmGroupMember->mahasiswa->nama ?? '' }}
                                        </td>
                                        <td>
                                            {{ App\Models\MbkmGroupMember::ROLE_SELECT[$mbkmGroupMember->role] ?? '' }}
                                        </td>
                                        <td>
                                            @can('mbkm_group_member_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.mbkm-group-members.show', $mbkmGroupMember->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('mbkm_group_member_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.mbkm-group-members.edit', $mbkmGroupMember->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('mbkm_group_member_delete')
                                                <form action="{{ route('frontend.mbkm-group-members.destroy', $mbkmGroupMember->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('mbkm_group_member_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.mbkm-group-members.massDestroy') }}",
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
  let table = $('.datatable-MbkmGroupMember:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection