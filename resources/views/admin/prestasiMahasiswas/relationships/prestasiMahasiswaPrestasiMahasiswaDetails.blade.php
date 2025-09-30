<div class="m-3">

    <div class="card">
        <div class="card-header">
            {{ trans('cruds.prestasiMahasiswaDetail.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-prestasiMahasiswaPrestasiMahasiswaDetails">
                    <thead>
                        <tr>
                            <th width="10">

                            </th>
                            <th>
                                {{ trans('cruds.prestasiMahasiswaDetail.fields.nim') }}
                            </th>
                            <th>
                                {{ trans('cruds.prestasiMahasiswaDetail.fields.nama') }}
                            </th>
                            <th>
                                {{ trans('cruds.prestasiMahasiswaDetail.fields.prestasi_mahasiswa') }}
                            </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($prestasiMahasiswaDetails as $key => $prestasiMahasiswaDetail)
                            <tr data-entry-id="{{ $prestasiMahasiswaDetail->id }}">
                                <td>

                                </td>
                                <td>
                                    {{ $prestasiMahasiswaDetail->nim ?? '' }}
                                </td>
                                <td>
                                    {{ $prestasiMahasiswaDetail->nama ?? '' }}
                                </td>
                                <td>
                                    {{ $prestasiMahasiswaDetail->prestasi_mahasiswa->nama_kegiatan ?? '' }}
                                </td>
                                <td>



                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
  
  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 50,
  });
  let table = $('.datatable-prestasiMahasiswaPrestasiMahasiswaDetails:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection