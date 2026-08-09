@extends('layouts.admin')
@section('content')
@can('dosen_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.dosens.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.dosen.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'Dosen', 'route' => 'admin.dosens.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.dosen.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Dosen">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.dosen.fields.nip') }}
                    </th>
                    <th>
                        {{ trans('cruds.dosen.fields.nidn') }}
                    </th>
                    <th>
                        {{ trans('cruds.dosen.fields.nama') }}
                    </th>
                    <th>
                        {{ trans('cruds.dosen.fields.tempat_lahir') }}
                    </th>
                    <th>
                        {{ trans('cruds.dosen.fields.tanggal_lahir') }}
                    </th>
                    <th>
                        {{ trans('cruds.dosen.fields.gender') }}
                    </th>
                    <th>
                        {{ trans('cruds.dosen.fields.prodi') }}
                    </th>
                    <th>
                        {{ trans('cruds.dosen.fields.mbkm_availability') }}
                    </th>
                    <th>
                        {{ trans('cruds.dosen.fields.riset_grup') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
            </thead>
        </table>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('dosen_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.dosens.massDestroy') }}",
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
    ajax: "{{ route('admin.dosens.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'nip', name: 'nip' },
{ data: 'nidn', name: 'nidn' },
{ data: 'nama', name: 'nama' },
{ data: 'tempat_lahir', name: 'tempat_lahir' },
{ data: 'tanggal_lahir', name: 'tanggal_lahir' },
{ data: 'gender', name: 'gender' },
{ data: 'prodi_name', name: 'prodi.name' },
{ data: 'mbkm_availability', name: 'mbkm_availability', orderable: false, searchable: false },
{ data: 'riset_grup_name', name: 'riset_grup.name' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 50,
  };
  let table = $('.datatable-Dosen').DataTable(dtOverrideGlobals);

  function syncMbkmToggleButton($btn, enabled) {
    $btn
      .data('enabled', enabled ? '1' : '0')
      .toggleClass('btn-success', !enabled)
      .toggleClass('btn-warning', enabled)
      .text(enabled ? 'MBKM Off' : 'MBKM On')
      .attr('title', enabled ? 'Nonaktifkan MBKM' : 'Aktifkan MBKM');
  }

  function toggleMbkmAvailability(url, $checkbox, $button) {
    $.ajax({
      headers: {'x-csrf-token': _token},
      method: 'POST',
      url: url,
    }).done(function (response) {
      const enabled = !!response.mbkm_availability;

      if ($checkbox && $checkbox.length) {
        $checkbox.prop('checked', enabled);
      }

      if ($button && $button.length) {
        syncMbkmToggleButton($button, enabled);
      }

      $('.datatable-Dosen').find('.toggle-mbkm-availability[data-url="' + url + '"]').prop('checked', enabled);
      $('.datatable-Dosen').find('.toggle-mbkm-availability-btn[data-url="' + url + '"]').each(function () {
        syncMbkmToggleButton($(this), enabled);
      });
    }).fail(function () {
      if ($checkbox && $checkbox.length) {
        $checkbox.prop('checked', !$checkbox.prop('checked'));
      }

      alert('Gagal memperbarui status MBKM.');
    });
  }

  $('.datatable-Dosen').on('change', '.toggle-mbkm-availability', function () {
    const $checkbox = $(this);
    const $row = $checkbox.closest('tr');
    const $button = $row.find('.toggle-mbkm-availability-btn');

    toggleMbkmAvailability($checkbox.data('url'), $checkbox, $button);
  });

  $('.datatable-Dosen').on('click', '.toggle-mbkm-availability-btn', function () {
    const $button = $(this);
    const $row = $button.closest('tr');
    const $checkbox = $row.find('.toggle-mbkm-availability');

    toggleMbkmAvailability($button.data('url'), $checkbox, $button);
  });

  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection