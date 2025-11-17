@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('skripsi_defense_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.skripsi-defenses.create') }}">
                            <i class="fas fa-plus mr-1"></i> {{ trans('global.add') }} {{ trans('cruds.skripsiDefense.title_singular') }}
                        </a>
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-graduation-cap mr-2"></i>{{ trans('cruds.skripsiDefense.title_singular') }} {{ trans('global.list') }}</h5>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover datatable datatable-SkripsiDefense">
                            <thead>
                                <tr>
                                    <th>{{ trans('cruds.skripsiDefense.fields.title') }}</th>
                                    <th>{{ trans('cruds.skripsiDefense.fields.application') }}</th>
                                    <th>Status Validasi</th>
                                    <th>Catatan Admin</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>{{ trans('global.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($skripsiDefenses as $key => $skripsiDefense)
                                    <tr data-entry-id="{{ $skripsiDefense->id }}">
                                        <td>{{ $skripsiDefense->title ?? '' }}</td>
                                        <td>
                                            @if($skripsiDefense->application)
                                                <span class="badge badge-info">{{ ucfirst($skripsiDefense->application->type) }}</span>
                                                <span class="badge badge-secondary">{{ ucfirst($skripsiDefense->application->status) }}</span>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            @if($skripsiDefense->status === 'accepted')
                                                <span class="badge badge-success badge-lg">
                                                    <i class="fas fa-check-circle"></i> Diterima
                                                </span>
                                            @elseif($skripsiDefense->status === 'rejected')
                                                <span class="badge badge-danger badge-lg">
                                                    <i class="fas fa-times-circle"></i> Ditolak
                                                </span>
                                            @else
                                                <span class="badge badge-warning badge-lg">
                                                    <i class="fas fa-clock"></i> Menunggu Validasi
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($skripsiDefense->admin_note)
                                                <button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" title="{{ $skripsiDefense->admin_note }}">
                                                    <i class="fas fa-sticky-note"></i> Lihat Catatan
                                                </button>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $skripsiDefense->created_at ? $skripsiDefense->created_at->format('d M Y H:i') : '' }}</td>
                                        <td>
                                            @can('skripsi_defense_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.skripsi-defenses.show', $skripsiDefense->id) }}">
                                                    <i class="fas fa-eye"></i> {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('skripsi_defense_edit')
                                                @if($skripsiDefense->status === 'pending' || $skripsiDefense->status === 'rejected')
                                                    <a class="btn btn-xs btn-info" href="{{ route('frontend.skripsi-defenses.edit', $skripsiDefense->id) }}">
                                                        <i class="fas fa-edit"></i> {{ trans('global.edit') }}
                                                    </a>
                                                @else
                                                    <button class="btn btn-xs btn-secondary" disabled title="Tidak dapat diedit setelah diterima">
                                                        <i class="fas fa-lock"></i> {{ trans('global.edit') }}
                                                    </button>
                                                @endif
                                            @endcan

                                            @can('skripsi_defense_delete')
                                                @if($skripsiDefense->status === 'pending' || $skripsiDefense->status === 'rejected')
                                                    <form action="{{ route('frontend.skripsi-defenses.destroy', $skripsiDefense->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                        <button type="submit" class="btn btn-xs btn-danger">
                                                            <i class="fas fa-trash"></i> {{ trans('global.delete') }}
                                                        </button>
                                                    </form>
                                                @else
                                                    <button class="btn btn-xs btn-secondary" disabled title="Tidak dapat dihapus setelah diterima">
                                                        <i class="fas fa-lock"></i> {{ trans('global.delete') }}
                                                    </button>
                                                @endif
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
$(document).ready(function() {
    $('.datatable-SkripsiDefense').DataTable({
        "pageLength": 25,
        "order": [[ 4, "desc" ]],
        "columnDefs": [
            { "orderable": false, "targets": 5 }
        ]
    });
    
    $('[data-toggle="tooltip"]').tooltip();
});
</script>

<style>
.badge-lg {
    font-size: 0.95rem;
    padding: 6px 12px;
}
</style>
@endsection
