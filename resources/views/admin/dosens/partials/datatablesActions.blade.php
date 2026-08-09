@can($viewGate)
    <a class="btn btn-xs btn-primary" href="{{ route('admin.' . $crudRoutePart . '.show', $row->id) }}">
        {{ trans('global.view') }}
    </a>
@endcan
@can($editGate)
    <a class="btn btn-xs btn-info" href="{{ route('admin.' . $crudRoutePart . '.edit', $row->id) }}">
        {{ trans('global.edit') }}
    </a>
    <button type="button"
            class="btn btn-xs {{ $row->mbkm_availability ? 'btn-warning' : 'btn-success' }} toggle-mbkm-availability-btn"
            data-url="{{ route('admin.dosens.toggleMbkmAvailability', $row->id) }}"
            data-enabled="{{ $row->mbkm_availability ? '1' : '0' }}"
            title="{{ $row->mbkm_availability ? 'Nonaktifkan MBKM' : 'Aktifkan MBKM' }}">
        {{ $row->mbkm_availability ? 'MBKM Off' : 'MBKM On' }}
    </button>
@endcan
@can($deleteGate)
    <form action="{{ route('admin.' . $crudRoutePart . '.destroy', $row->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
    </form>
@endcan
