@extends('layouts.frontend')
@section('content')
<link rel="stylesheet" href="{{ asset('css/modern-form.css') }}">
<div class="container py-4">
    <div class="page-header">
        <div>
            <h1 class="page-title">Jadwal Seminar & Sidang</h1>
            <p class="page-subtitle">Kelola jadwal seminar proposal dan sidang skripsi Anda</p>
        </div>
        @can('application_schedule_create')
            <a href="{{ route('frontend.application-schedules.create') }}" class="btn-primary-custom">
                <i class="fas fa-plus-circle mr-2"></i> Tambah Jadwal
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
        <div class="info-box-title">Informasi Jadwal</div>
        <div class="info-box-text">
            <ul class="mb-0">
                <li>Pastikan jadwal telah dikonfirmasi dengan dosen pembimbing dan penguji</li>
                <li>Upload form persetujuan jadwal yang telah ditandatangani</li>
                <li>Jadwal yang sudah disetujui admin tidak dapat diubah tanpa persetujuan</li>
            </ul>
        </div>
    </div>

    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable datatable-ApplicationSchedule">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>Aplikasi</th>
                        <th>Tipe</th>
                        <th>Waktu</th>
                        <th>Tempat</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applicationSchedules as $key => $applicationSchedule)
                        <tr data-entry-id="{{ $applicationSchedule->id }}">
                            <td></td>
                            <td>
                                <div class="font-weight-bold">{{ $applicationSchedule->application->mahasiswa->nama ?? '' }}</div>
                                <small class="text-muted">{{ $applicationSchedule->application->mahasiswa->nim ?? '' }}</small>
                            </td>
                            <td>
                                @if($applicationSchedule->schedule_type == 'seminar' || $applicationSchedule->schedule_type == 'skripsi_seminar')
                                    <span class="badge badge-info">
                                        <i class="fas fa-presentation mr-1"></i> Seminar
                                    </span>
                                @elseif($applicationSchedule->schedule_type == 'defense' || $applicationSchedule->schedule_type == 'skripsi_defense')
                                    <span class="badge badge-warning">
                                        <i class="fas fa-gavel mr-1"></i> Sidang
                                    </span>
                                @elseif($applicationSchedule->schedule_type == 'mbkm_seminar')
                                    <span class="badge badge-success">
                                        <i class="fas fa-users mr-1"></i> MBKM
                                    </span>
                                @else
                                    <span class="badge badge-secondary">
                                        <i class="fas fa-question mr-1"></i> {{ ucfirst($applicationSchedule->schedule_type) }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <i class="fas fa-calendar-alt mr-1 text-muted"></i>
                                {{ $applicationSchedule->waktu ? \Carbon\Carbon::parse($applicationSchedule->waktu)->format('d M Y, H:i') : '-' }}
                            </td>
                            <td>
                                @if($applicationSchedule->ruang)
                                    <i class="fas fa-door-open mr-1 text-muted"></i>
                                    {{ $applicationSchedule->ruang->nama ?? '' }}
                                @elseif($applicationSchedule->custom_place)
                                    <i class="fas fa-map-marker-alt mr-1 text-muted"></i>
                                    {{ $applicationSchedule->custom_place }}
                                @elseif($applicationSchedule->online_meeting)
                                    <i class="fas fa-video mr-1 text-muted"></i>
                                    Online Meeting
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($applicationSchedule->status == 'pending')
                                    <span class="status-badge pending">
                                        <i class="fas fa-clock mr-1"></i> Menunggu
                                    </span>
                                @elseif($applicationSchedule->status == 'approved')
                                    <span class="status-badge approved">
                                        <i class="fas fa-check-circle mr-1"></i> Disetujui
                                    </span>
                                @elseif($applicationSchedule->status == 'rejected')
                                    <span class="status-badge rejected">
                                        <i class="fas fa-times-circle mr-1"></i> Ditolak
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    @can('application_schedule_show')
                                        <a class="btn btn-xs btn-primary" href="{{ route('frontend.application-schedules.show', $applicationSchedule->id) }}" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endcan

                                    @can('application_schedule_edit')
                                        <a class="btn btn-xs btn-info" href="{{ route('frontend.application-schedules.edit', $applicationSchedule->id) }}" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan

                                    @can('application_schedule_delete')
                                        <form action="{{ route('frontend.application-schedules.destroy', $applicationSchedule->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');" style="display: inline-block;">
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
@can('application_schedule_delete')
  let deleteButtonTrans = 'Hapus yang dipilih'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.application-schedules.massDestroy') }}",
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
  let table = $('.datatable-ApplicationSchedule:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection