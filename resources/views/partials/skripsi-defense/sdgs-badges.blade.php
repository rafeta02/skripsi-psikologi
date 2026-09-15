@php
    $sdgIds = $sdgs ?? ($skripsiDefense->sdgs ?? []);
    $sdgGoals = config('sdgs.goals', []);
    $sdgColors = config('sdgs.colors', []);
    $label = $label ?? trans('cruds.skripsiDefense.fields.sdgs');
    $compact = $compact ?? false;
@endphp

@if(!empty($sdgIds))
    <div class="{{ $wrapperClass ?? 'mb-3' }}">
        @if($showLabel ?? true)
            <label class="{{ $labelClass ?? 'text-muted mb-2 d-block' }}">{{ $label }}</label>
        @endif
        <div class="d-flex flex-wrap">
            @foreach($sdgIds as $sdgId)
                @php
                    $sdgNumber = (int) $sdgId;
                    $sdgLabel = $sdgGoals[$sdgNumber] ?? null;
                    $sdgColor = $sdgColors[$sdgNumber] ?? '#6c757d';
                @endphp
                @if($sdgLabel)
                    @if($compact)
                        <span class="badge text-white mr-1 mb-1" style="background-color: {{ $sdgColor }};" title="{{ $sdgLabel }}">
                            {{ $sdgNumber }}
                        </span>
                    @else
                        <span class="badge text-white mr-1 mb-1 px-2 py-2" style="background-color: {{ $sdgColor }};" title="{{ $sdgLabel }}">
                            SDGs {{ $sdgNumber }}: {{ $sdgLabel }}
                        </span>
                    @endif
                @endif
            @endforeach
        </div>
    </div>
@elseif($showEmpty ?? false)
    <div class="{{ $wrapperClass ?? 'mb-3' }}">
        @if($showLabel ?? true)
            <label class="{{ $labelClass ?? 'text-muted mb-1 d-block' }}">{{ $label }}</label>
        @endif
        <span class="text-muted">-</span>
    </div>
@endif
