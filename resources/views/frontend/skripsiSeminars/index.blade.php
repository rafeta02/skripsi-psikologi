@extends('layouts.frontend')
@section('content')
<link rel="stylesheet" href="{{ asset('css/modern-form.css') }}">
<div class="container py-4">
    <div class="page-header">
        <div>
            <h1 class="page-title">Seminar Proposal Skripsi</h1>
            <p class="page-subtitle">Kelola pendaftaran seminar proposal skripsi reguler</p>
        </div>
        @can('skripsi_seminar_create')
            <a href="{{ route('frontend.skripsi-seminars.create') }}" class="btn-primary-custom">
                <i class="fas fa-plus-circle mr-2"></i> Daftar Seminar
            </a>
        @endcan
    </div>

    @if(session('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="info-box info mb-4">
        <div class="info-box-title">Informasi Seminar</div>
        <div class="info-box-text">
            <ul class="mb-0">
                <li>Pastikan proposal sudah disetujui dosen pembimbing sebelum mendaftar</li>
                <li>Admin akan mengecek kelengkapan dokumen dan mengassign dosen reviewer</li>
                <li>Setelah disetujui, Anda dapat mengatur jadwal seminar</li>
            </ul>
        </div>
    </div>

    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable datatable-SkripsiSeminar">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>Mahasiswa</th>
                        <th>Judul</th>
                        <th>Tanggal Daftar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($skripsiSeminars as $key => $skripsiSeminar)
                        <tr data-entry-id="{{ $skripsiSeminar->id }}">
                            <td></td>
                            <td>
                                <div class="font-weight-bold">{{ $skripsiSeminar->application->mahasiswa->nama ?? '' }}</div>
                                <small class="text-muted">{{ $skripsiSeminar->application->mahasiswa->nim ?? '' }}</small>
                            </td>
                            <td>
                                <div class="text-truncate" style="max-width: 300px;" title="{{ $skripsiSeminar->title }}">
                                    {{ $skripsiSeminar->title ?? '-' }}
                                </div>
                            </td>
                            <td>
                                <i class="fas fa-calendar-alt mr-1 text-muted"></i>
                                {{ $skripsiSeminar->created_at ? $skripsiSeminar->created_at->format('d M Y') : '-' }}
                            </td>
                            <td>
                                @if($skripsiSeminar->application->status == 'submitted')
                                    <span class="status-badge pending">
                                        <i class="fas fa-clock mr-1"></i> Menunggu
                                    </span>
                                @elseif($skripsiSeminar->application->status == 'approved')
                                    <span class="status-badge approved">
                                        <i class="fas fa-check-circle mr-1"></i> Disetujui
                                    </span>
                                @elseif($skripsiSeminar->application->status == 'rejected')
                                    <span class="status-badge rejected">
                                        <i class="fas fa-times-circle mr-1"></i> Ditolak
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    @can('skripsi_seminar_show')
                                        <a class="btn btn-xs btn-primary" href="{{ route('frontend.skripsi-seminars.show', $skripsiSeminar->id) }}" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endcan

                                    @can('skripsi_seminar_edit')
                                        <a class="btn btn-xs btn-info" href="{{ route('frontend.skripsi-seminars.edit', $skripsiSeminar->id) }}" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan

                                    @can('skripsi_seminar_delete')
                                        <form action="{{ route('frontend.skripsi-seminars.destroy', $skripsiSeminar->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('skripsi_seminar_delete')
  let deleteButtonTrans = 'Hapus yang dipilih'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.skripsi-seminars.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('Tidak ada data yang dipilih')
        return
      }

      if (confirm('Yakin ingin menghapus ' + ids.length + ' data?')) {
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
    order: [[ 3, 'desc' ]],
    pageLength: 25,
  });
  let table = $('.datatable-SkripsiSeminar:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection