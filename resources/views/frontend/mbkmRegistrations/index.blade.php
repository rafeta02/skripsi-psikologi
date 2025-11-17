@extends('layouts.frontend')
@section('content')
<link rel="stylesheet" href="{{ asset('css/modern-form.css') }}">
<div class="container py-4">
    <div class="page-header">
        <div>
            <h1 class="page-title">Pendaftaran Skripsi MBKM</h1>
            <p class="page-subtitle">Kelola pendaftaran skripsi program MBKM</p>
        </div>
        @can('mbkm_registration_create')
            <a href="{{ route('frontend.mbkm-registrations.create') }}" class="btn-primary-custom">
                <i class="fas fa-plus-circle mr-2"></i> Daftar MBKM
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

    @php
        $hasRevisionRequest = $mbkmRegistrations->contains(function($reg) {
            return $reg->application->status == 'revision';
        });
    @endphp

    @if($hasRevisionRequest)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <h5 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Revisi Diperlukan!</h5>
            <p class="mb-3">Admin telah meminta Anda untuk merevisi pendaftaran MBKM Anda. Anda memiliki 2 pilihan:</p>
            <ol class="mb-3">
                <li><strong>Revisi Pendaftaran MBKM:</strong> Klik tombol <span class="badge badge-warning">Revisi</span> di bawah untuk memperbaiki pendaftaran MBKM Anda sesuai catatan admin.</li>
                <li><strong>Batalkan dan Daftar Skripsi Reguler:</strong> Jika Anda memutuskan tidak melanjutkan jalur MBKM, silakan hapus pendaftaran MBKM ini dan daftar melalui <a href="{{ route('frontend.skripsi-registrations.create') }}" class="alert-link"><strong>Skripsi Reguler</strong></a>.</li>
            </ol>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="info-box info mb-4">
        <div class="info-box-title">Informasi MBKM</div>
        <div class="info-box-text">
            <ul class="mb-0">
                <li>Pastikan semua dokumen MBKM sudah lengkap sebelum mendaftar</li>
                <li>Dosen pembimbing akan memverifikasi pendaftaran Anda</li>
                <li>Jika ditolak, Anda dapat merevisi dan mendaftar kembali</li>
            </ul>
        </div>
    </div>

    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable datatable-MbkmRegistration">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>Mahasiswa</th>
                        <th>Judul MBKM</th>
                        <th>Judul Skripsi</th>
                        <th>Dosen Pembimbing</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mbkmRegistrations as $key => $mbkmRegistration)
                        <tr data-entry-id="{{ $mbkmRegistration->id }}">
                            <td></td>
                            <td>
                                <div class="font-weight-bold">{{ $mbkmRegistration->application->mahasiswa->nama ?? '' }}</div>
                                <small class="text-muted">{{ $mbkmRegistration->application->mahasiswa->nim ?? '' }}</small>
                            </td>
                            <td>
                                <div class="text-truncate" style="max-width: 200px;" title="{{ $mbkmRegistration->title_mbkm }}">
                                    {{ $mbkmRegistration->title_mbkm ?? '-' }}
                                </div>
                            </td>
                            <td>
                                <div class="text-truncate" style="max-width: 200px;" title="{{ $mbkmRegistration->title }}">
                                    {{ $mbkmRegistration->title ?? '-' }}
                                </div>
                            </td>
                            <td>
                                {{ $mbkmRegistration->preference_supervision->nama ?? '-' }}
                            </td>
                            <td>
                                @if($mbkmRegistration->application->status == 'submitted')
                                    <span class="status-badge pending">
                                        <i class="fas fa-clock mr-1"></i> Menunggu
                                    </span>
                                @elseif($mbkmRegistration->application->status == 'approved')
                                    <span class="status-badge approved">
                                        <i class="fas fa-check-circle mr-1"></i> Disetujui
                                    </span>
                                @elseif($mbkmRegistration->application->status == 'rejected')
                                    <span class="status-badge rejected">
                                        <i class="fas fa-times-circle mr-1"></i> Ditolak
                                    </span>
                                    @if($mbkmRegistration->rejection_reason)
                                        <br>
                                        <button type="button" class="btn btn-xs btn-outline-danger mt-1" data-toggle="modal" data-target="#rejectionModal{{ $mbkmRegistration->id }}" title="Lihat Alasan">
                                            <i class="fas fa-info-circle mr-1"></i> Lihat Alasan
                                        </button>
                                    @endif
                                @elseif($mbkmRegistration->application->status == 'revision')
                                    <span class="badge badge-warning">
                                        <i class="fas fa-edit mr-1"></i> Perlu Revisi
                                    </span>
                                    @if($mbkmRegistration->revision_notes)
                                        <br><small class="text-muted">{{ Str::limit($mbkmRegistration->revision_notes, 50) }}</small>
                                    @endif
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    @can('mbkm_registration_show')
                                        <a class="btn btn-xs btn-primary" href="{{ route('frontend.mbkm-registrations.show', $mbkmRegistration->id) }}" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endcan

                                    @can('mbkm_registration_edit')
                                        @if($mbkmRegistration->application->status == 'revision')
                                            <a class="btn btn-xs btn-warning" href="{{ route('frontend.mbkm-registrations.edit', $mbkmRegistration->id) }}" title="Revisi Sekarang" style="font-weight: bold;">
                                                <i class="fas fa-edit"></i> Revisi
                                            </a>
                                        @else
                                            <a class="btn btn-xs btn-info" href="{{ route('frontend.mbkm-registrations.edit', $mbkmRegistration->id) }}" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                    @endcan

                                    @can('mbkm_registration_delete')
                                        <form action="{{ route('frontend.mbkm-registrations.destroy', $mbkmRegistration->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');" style="display: inline-block;">
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

    <!-- Rejection Reason Modals -->
    @foreach($mbkmRegistrations as $mbkmRegistration)
        @if($mbkmRegistration->application->status == 'rejected' && $mbkmRegistration->rejection_reason)
            <div class="modal fade" id="rejectionModal{{ $mbkmRegistration->id }}" tabindex="-1" role="dialog" aria-labelledby="rejectionModalLabel{{ $mbkmRegistration->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="rejectionModalLabel{{ $mbkmRegistration->id }}">
                                <i class="fas fa-times-circle mr-2"></i>
                                Alasan Penolakan
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <strong>Mahasiswa:</strong><br>
                                {{ $mbkmRegistration->application->mahasiswa->nama ?? '-' }} ({{ $mbkmRegistration->application->mahasiswa->nim ?? '-' }})
                            </div>
                            <div class="mb-3">
                                <strong>Judul MBKM:</strong><br>
                                {{ $mbkmRegistration->title_mbkm ?? '-' }}
                            </div>
                            <hr>
                            <div class="alert alert-danger mb-0">
                                <strong><i class="fas fa-exclamation-triangle mr-2"></i>Alasan Penolakan:</strong>
                                <p class="mt-2 mb-0">{{ $mbkmRegistration->rejection_reason }}</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="fas fa-times mr-2"></i>Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</div>
@endsection

@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('mbkm_registration_delete')
  let deleteButtonTrans = 'Hapus yang dipilih'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.mbkm-registrations.massDestroy') }}",
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
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  });
  let table = $('.datatable-MbkmRegistration:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection