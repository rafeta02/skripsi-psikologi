@extends('layouts.frontend')
@section('content')
<link rel="stylesheet" href="{{ asset('css/modern-form.css') }}">
<div class="container py-4">
    <div class="page-header">
        <div>
            <h1 class="page-title">Hasil Seminar Proposal</h1>
            <p class="page-subtitle">Kelola dokumen hasil seminar proposal skripsi</p>
        </div>
        @can('application_result_seminar_create')
            <a href="{{ route('frontend.application-result-seminars.create') }}" class="btn-primary-custom">
                <i class="fas fa-plus-circle mr-2"></i> Tambah Hasil Seminar
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

    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable datatable-ApplicationResultSeminar">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>Mahasiswa</th>
                        <th>Hasil</th>
                        <th>Batas Revisi</th>
                        <th>Dokumen</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applicationResultSeminars as $key => $applicationResultSeminar)
                        <tr data-entry-id="{{ $applicationResultSeminar->id }}">
                            <td></td>
                            <td>
                                <div class="font-weight-bold">{{ $applicationResultSeminar->application->mahasiswa->nama ?? '' }}</div>
                                <small class="text-muted">{{ $applicationResultSeminar->application->mahasiswa->nim ?? '' }}</small>
                            </td>
                            <td>
                                @if($applicationResultSeminar->result == 'passed')
                                    <span class="status-badge approved">
                                        <i class="fas fa-check-circle mr-1"></i> Lulus
                                    </span>
                                @elseif($applicationResultSeminar->result == 'revision')
                                    <span class="status-badge pending">
                                        <i class="fas fa-edit mr-1"></i> Revisi
                                    </span>
                                @elseif($applicationResultSeminar->result == 'failed')
                                    <span class="status-badge rejected">
                                        <i class="fas fa-times-circle mr-1"></i> Tidak Lulus
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($applicationResultSeminar->revision_deadline)
                                    <i class="fas fa-calendar-alt mr-1 text-muted"></i>
                                    {{ \Carbon\Carbon::parse($applicationResultSeminar->revision_deadline)->format('d M Y') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="document-quick-links">
                                    @if($applicationResultSeminar->report_document && count($applicationResultSeminar->report_document) > 0)
                                        <span class="badge badge-info" title="Berita Acara">
                                            <i class="fas fa-file-pdf mr-1"></i> {{ count($applicationResultSeminar->report_document) }}
                                        </span>
                                    @endif
                                    @if($applicationResultSeminar->attendance_document)
                                        <span class="badge badge-success" title="Daftar Hadir">
                                            <i class="fas fa-users mr-1"></i> 1
                                        </span>
                                    @endif
                                    @if($applicationResultSeminar->latest_script)
                                        <span class="badge badge-primary" title="Naskah">
                                            <i class="fas fa-book mr-1"></i> 1
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    @can('application_result_seminar_show')
                                        <a class="btn btn-xs btn-primary" href="{{ route('frontend.application-result-seminars.show', $applicationResultSeminar->id) }}" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endcan

                                    @can('application_result_seminar_edit')
                                        <a class="btn btn-xs btn-info" href="{{ route('frontend.application-result-seminars.edit', $applicationResultSeminar->id) }}" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan

                                    @can('application_result_seminar_delete')
                                        <form action="{{ route('frontend.application-result-seminars.destroy', $applicationResultSeminar->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');" style="display: inline-block;">
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
@can('application_result_seminar_delete')
  let deleteButtonTrans = 'Hapus yang dipilih'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.application-result-seminars.massDestroy') }}",
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
  let table = $('.datatable-ApplicationResultSeminar:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection