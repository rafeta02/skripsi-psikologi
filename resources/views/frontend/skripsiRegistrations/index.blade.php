@extends('layouts.frontend')
@section('content')
<style>
    .page-header {
        background: linear-gradient(135deg, #22004C 0%, #4A0080 100%);
        color: white;
        padding: 2.5rem 0;
        margin-bottom: 2rem;
        border-radius: 12px;
    }
    
    .page-header h1 {
        margin: 0;
        font-size: 2rem;
        font-weight: 600;
    }
    
    .page-header p {
        margin: 0.5rem 0 0;
        opacity: 0.9;
        font-size: 1rem;
    }
    
    .list-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    
    .card-header-custom {
        padding: 1.5rem 2rem;
        background: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #2d3748;
        margin: 0;
    }
    
    .btn-add {
        padding: 0.65rem 1.5rem;
        background: linear-gradient(135deg, #22004C 0%, #4A0080 100%);
        color: white;
        border-radius: 8px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.95rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }
    
    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(34, 0, 76, 0.4);
        color: white;
        text-decoration: none;
    }
    
    .btn-add i {
        margin-right: 0.5rem;
    }
    
    .table-wrapper {
        padding: 2rem;
    }
    
    .table {
        margin-bottom: 0;
    }
    
    .table thead th {
        background: #f7fafc;
        color: #4a5568;
        font-weight: 600;
        font-size: 0.9rem;
        border-bottom: 2px solid #e2e8f0;
        padding: 1rem;
        white-space: nowrap;
    }
    
    .table tbody td {
        padding: 1rem;
        vertical-align: middle;
        color: #2d3748;
        font-size: 0.9rem;
        border-bottom: 1px solid #f7fafc;
    }
    
    .table tbody tr:hover {
        background: #f7fafc;
    }
    
    .status-badge {
        display: inline-block;
        padding: 0.35rem 0.85rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        white-space: nowrap;
    }
    
    .status-submitted {
        background: #fef3c7;
        color: #92400e;
    }
    
    .status-approved {
        background: #d1fae5;
        color: #065f46;
    }
    
    .status-rejected {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .status-revision {
        background: #fef3c7;
        color: #92400e;
    }
    
    .btn-reason {
        padding: 0.25rem 0.6rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
        border: 1px solid #991b1b;
        background: white;
        color: #991b1b;
        cursor: pointer;
        transition: all 0.2s;
        margin-top: 0.25rem;
    }
    
    .btn-reason:hover {
        background: #991b1b;
        color: white;
    }
    
    .status-scheduled {
        background: #dbeafe;
        color: #1e40af;
    }
    
    .status-done {
        background: #e0e7ff;
        color: #3730a3;
    }
    
    .action-btn {
        padding: 0.4rem 0.85rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        margin-right: 0.35rem;
        margin-bottom: 0.35rem;
    }
    
    .action-btn i {
        margin-right: 0.35rem;
    }
    
    .btn-view {
        background: #e0e7ff;
        color: #4338ca;
    }
    
    .btn-view:hover {
        background: #c7d2fe;
        color: #4338ca;
        text-decoration: none;
    }
    
    .btn-edit {
        background: #dbeafe;
        color: #1e40af;
    }
    
    .btn-edit:hover {
        background: #bfdbfe;
        color: #1e40af;
        text-decoration: none;
    }
    
    .btn-delete {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .btn-delete:hover {
        background: #fecaca;
        color: #991b1b;
    }
    
    .text-truncate-custom {
        max-width: 300px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .doc-link {
        color: #22004C;
        text-decoration: none;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
    }
    
    .doc-link:hover {
        color: #4A0080;
        text-decoration: underline;
    }
    
    .doc-link i {
        margin-right: 0.35rem;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #a0aec0;
    }
    
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .empty-state p {
        font-size: 1.1rem;
        margin: 0;
    }
    
    .info-banner {
        background: #ebf8ff;
        border-left: 4px solid #4299e1;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }
    
    .info-banner-title {
        font-weight: 600;
        color: #2c5282;
        margin-bottom: 0.25rem;
        font-size: 0.95rem;
    }
    
    .info-banner-text {
        font-size: 0.9rem;
        color: #2d3748;
        margin: 0;
    }
</style>

<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="page-header">
                <div class="container">
                    <h1>Pendaftaran Skripsi Reguler</h1>
                    <p>Kelola pendaftaran topik skripsi Anda</p>
                </div>
            </div>

            <!-- Info Banner -->
            <div class="info-banner">
                <div class="info-banner-title">Tahap: Pendaftaran Topik Skripsi (Langkah 2 dari Alur Skripsi Reguler)</div>
                <div class="info-banner-text">
                    Setelah mendaftar, admin akan memverifikasi berkas Anda. Jika disetujui, admin akan menugaskan dosen pembimbing sesuai preferensi Anda.
                </div>
            </div>

            <!-- List Card -->
            <div class="list-card">
                <div class="card-header-custom">
                    <h2 class="card-title">Daftar Pendaftaran</h2>
                    @can('skripsi_registration_create')
                        <a class="btn-add" href="{{ route('frontend.skripsi-registrations.create') }}">
                            <i class="fas fa-plus"></i> Daftar Baru
                        </a>
                    @endcan
                </div>

                <div class="table-wrapper">
                    <div class="table-responsive">
                        <table class="table table-hover datatable datatable-SkripsiRegistration">
                            <thead>
                                <tr>
                                    <th width="120">Status</th>
                                    <th>Tema</th>
                                    <th>Judul Skripsi</th>
                                    <th>Dosen TPS</th>
                                    <th>Preferensi Pembimbing</th>
                                    <th>Dokumen</th>
                                    <th width="200" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($skripsiRegistrations as $key => $skripsiRegistration)
                                    <tr data-entry-id="{{ $skripsiRegistration->id }}">
                                        <td>
                                            @if($skripsiRegistration->application)
                                                <span class="status-badge status-{{ $skripsiRegistration->application->status }}">
                                                    {{ ucfirst($skripsiRegistration->application->status) }}
                                                </span>
                                                @if($skripsiRegistration->application->status == 'rejected' && $skripsiRegistration->rejection_reason)
                                                    <br>
                                                    <button type="button" class="btn-reason" data-toggle="modal" data-target="#rejectionModalSkripsi{{ $skripsiRegistration->id }}" title="Lihat Alasan">
                                                        <i class="fas fa-info-circle"></i> Lihat Alasan
                                                    </button>
                                                @endif
                                            @else
                                                <span class="status-badge status-submitted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $skripsiRegistration->theme->name ?? '-' }}</strong>
                                        </td>
                                        <td>
                                            <div class="text-truncate-custom" title="{{ $skripsiRegistration->title }}">
                                                {{ $skripsiRegistration->title ?? '-' }}
                                            </div>
                                        </td>
                                        <td>
                                            {{ $skripsiRegistration->tps_lecturer->nama ?? '-' }}
                                        </td>
                                        <td>
                                            {{ $skripsiRegistration->preference_supervision->nama ?? '-' }}
                                        </td>
                                        <td>
                                            <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                                @if($skripsiRegistration->khs_all && count($skripsiRegistration->khs_all) > 0)
                                                    <a href="{{ $skripsiRegistration->khs_all[0]->getUrl() }}" target="_blank" class="doc-link">
                                                        <i class="fas fa-file-pdf"></i> KHS ({{ count($skripsiRegistration->khs_all) }})
                                                    </a>
                                                @endif
                                                @if($skripsiRegistration->krs_latest)
                                                    <a href="{{ $skripsiRegistration->krs_latest->getUrl() }}" target="_blank" class="doc-link">
                                                        <i class="fas fa-file-pdf"></i> KRS
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @can('skripsi_registration_show')
                                                <a class="action-btn btn-view" href="{{ route('frontend.skripsi-registrations.show', $skripsiRegistration->id) }}">
                                                    <i class="fas fa-eye"></i> Lihat
                                                </a>
                                            @endcan

                                            @can('skripsi_registration_edit')
                                                <a class="action-btn btn-edit" href="{{ route('frontend.skripsi-registrations.edit', $skripsiRegistration->id) }}">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                            @endcan

                                            @can('skripsi_registration_delete')
                                                <form action="{{ route('frontend.skripsi-registrations.destroy', $skripsiRegistration->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pendaftaran ini?');" style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <button type="submit" class="action-btn btn-delete">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">
                                            <div class="empty-state">
                                                <i class="fas fa-inbox"></i>
                                                <p>Belum ada pendaftaran skripsi</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rejection Reason Modals -->
    @foreach($skripsiRegistrations as $skripsiRegistration)
        @if($skripsiRegistration->application && $skripsiRegistration->application->status == 'rejected' && $skripsiRegistration->rejection_reason)
            <div class="modal fade" id="rejectionModalSkripsi{{ $skripsiRegistration->id }}" tabindex="-1" role="dialog" aria-labelledby="rejectionModalSkripsiLabel{{ $skripsiRegistration->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="rejectionModalSkripsiLabel{{ $skripsiRegistration->id }}">
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
                                {{ $skripsiRegistration->application->mahasiswa->nama ?? '-' }} ({{ $skripsiRegistration->application->mahasiswa->nim ?? '-' }})
                            </div>
                            <div class="mb-3">
                                <strong>Judul Skripsi:</strong><br>
                                {{ $skripsiRegistration->title ?? '-' }}
                            </div>
                            <hr>
                            <div class="alert alert-danger mb-0">
                                <strong><i class="fas fa-exclamation-triangle mr-2"></i>Alasan Penolakan:</strong>
                                <p class="mt-2 mb-0">{{ $skripsiRegistration->rejection_reason }}</p>
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
@can('skripsi_registration_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.skripsi-registrations.massDestroy') }}",
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
    order: [[ 0, 'desc' ]],
    pageLength: 25,
  });
  let table = $('.datatable-SkripsiRegistration:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection