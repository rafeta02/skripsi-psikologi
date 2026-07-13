@extends('layouts.admin')
@section('content')
@can('mbkm_registration_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.mbkm-registrations.create') }}">
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
        <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-MbkmRegistration text-center">
            <thead>
                <tr>
                    <th width="10" class="text-center"></th>
                    <th class="text-center">Ketua & Anggota Kelompok</th>
                    <th class="text-center">{{ trans('cruds.mbkmRegistration.fields.research_group') }}</th>
                    <th class="text-center">{{ trans('cruds.mbkmRegistration.fields.preference_supervision') }}</th>
                    <th class="text-center">{{ trans('cruds.mbkmRegistration.fields.theme') }}</th>
                    <th class="text-center">{{ trans('cruds.mbkmRegistration.fields.title_mbkm') }}</th>
                    <th class="text-center">Status Kelompok</th>
                    <th class="text-center">Syarat Anggota</th>
                    <th class="text-center">&nbsp;</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<style>
    .datatable-MbkmRegistration th,
    .datatable-MbkmRegistration td {
        text-align: center !important;
        vertical-align: middle !important;
    }
    .datatable-MbkmRegistration td ul.list-unstyled {
        display: inline-block;
        text-align: left;
        margin: 0 auto;
    }
</style>

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
    url: "{{ route('admin.mbkm-registrations.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
          return entry.id
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

  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.mbkm-registrations.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder', className: 'text-center' },
      { data: 'kelompok_anggota', name: 'kelompok_anggota', searchable: false, orderable: false, className: 'text-center' },
      { data: 'research_group_name', name: 'research_group.name', className: 'text-center' },
      { data: 'preference_supervision_nip', name: 'preference_supervision.nama', className: 'text-center' },
      { data: 'theme_name', name: 'theme.name', className: 'text-center' },
      { data: 'title_mbkm', name: 'title_mbkm', className: 'text-center' },
      { data: 'group_status_label', name: 'group_status', className: 'text-center' },
      { data: 'members_count', name: 'members_count', searchable: false, orderable: false, className: 'text-center' },
      { data: 'actions', name: '{{ trans('global.actions') }}', className: 'text-center' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 50,
    columnDefs: [
      { className: 'text-center', targets: '_all' }
    ],
  };
  let table = $('.datatable-MbkmRegistration').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection
