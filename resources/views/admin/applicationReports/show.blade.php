@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h3><i class="fas fa-flag"></i> Detail Laporan Kendala</h3>
            @if($applicationReport->status == 'submitted')
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#reviewModal">
                    <i class="fas fa-check"></i> Mark as Reviewed
                </button>
            @else
                <span class="badge badge-success badge-lg">
                    <i class="fas fa-check-circle"></i> Reviewed
                </span>
            @endif
        </div>
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group mb-3">
                <a class="btn btn-default" href="{{ route('admin.application-reports.index') }}">
                    <i class="fas fa-arrow-left"></i> {{ trans('global.back_to_list') }}
                </a>
            </div>

            <!-- Mahasiswa Info -->
            <div class="alert alert-info">
                <h5><i class="fas fa-user"></i> Informasi Mahasiswa</h5>
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td width="150"><strong>Nama:</strong></td>
                        <td>{{ $applicationReport->application->mahasiswa->nama ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>NIM:</strong></td>
                        <td>{{ $applicationReport->application->mahasiswa->nim ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td>{{ $applicationReport->application->mahasiswa->email ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>

            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width="200">
                            {{ trans('cruds.applicationReport.fields.application') }}
                        </th>
                        <td>
                            <span class="badge badge-primary">{{ $applicationReport->application->type ?? '' }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Periode Laporan
                        </th>
                        <td>
                            {{ $applicationReport->period ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationReport.fields.status') }}
                        </th>
                        <td>
                            @if($applicationReport->status == 'submitted')
                                <span class="badge badge-warning">Submitted</span>
                            @else
                                <span class="badge badge-success">Reviewed</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationReport.fields.report_text') }}
                        </th>
                        <td>
                            <div class="border p-3 bg-light">
                                {!! $applicationReport->report_text !!}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationReport.fields.report_document') }}
                        </th>
                        <td>
                            @if($applicationReport->report_document->count() > 0)
                                @foreach($applicationReport->report_document as $key => $media)
                                    <a href="{{ $media->getUrl() }}" target="_blank" class="btn btn-sm btn-info mb-1">
                                        <i class="fas fa-file-download"></i> {{ trans('global.view_file') }} {{ $key + 1 }}
                                    </a>
                                @endforeach
                            @else
                                <span class="text-muted">Tidak ada dokumen</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationReport.fields.note') }}
                        </th>
                        <td>
                            {{ $applicationReport->note ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Tanggal Dibuat
                        </th>
                        <td>
                            {{ $applicationReport->created_at ? $applicationReport->created_at->format('d M Y H:i') : '-' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group mt-3">
                <a class="btn btn-default" href="{{ route('admin.application-reports.index') }}">
                    <i class="fas fa-arrow-left"></i> {{ trans('global.back_to_list') }}
                </a>
                @can('application_report_edit')
                <a class="btn btn-info" href="{{ route('admin.application-reports.edit', $applicationReport->id) }}">
                    <i class="fas fa-edit"></i> {{ trans('global.edit') }}
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>

<!-- Review Modal -->
@if($applicationReport->status == 'submitted')
<div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.application-reports.mark-reviewed', $applicationReport->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewModalLabel">
                        <i class="fas fa-check-circle"></i> Mark as Reviewed
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Anda akan menandai laporan ini sebagai <strong>Reviewed</strong>. 
                        Silakan tambahkan catatan jika diperlukan.
                    </div>
                    
                    <div class="form-group">
                        <label for="note">
                            <i class="fas fa-sticky-note"></i> Catatan Admin
                        </label>
                        <textarea name="note" id="note" class="form-control" rows="5" placeholder="Tambahkan catatan untuk mahasiswa (opsional)...">{{ $applicationReport->note }}</textarea>
                        <small class="form-text text-muted">
                            Catatan ini akan terlihat oleh mahasiswa dan dapat membantu mereka memahami tindak lanjut yang perlu dilakukan.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check"></i> Mark as Reviewed
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection