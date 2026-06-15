@php
    $scoreComponentLabels = \App\Models\ApplicationScore::scoreComponentLabels();
    $scoreRecord = $scoreRecord ?? null;
    $colClass = $colClass ?? 'col-12 mb-3';
    $inputClass = $inputClass ?? 'form-control score-input';
    $useAdminLabel = $useAdminLabel ?? false;
@endphp

@foreach($scoreComponentLabels as $name => $label)
    <div class="{{ $colClass }}">
        @if($useAdminLabel)
            <label for="{{ $name }}">{{ $label }}</label>
        @else
            <label class="form-label-modern required" for="{{ $name }}">{{ $label }}</label>
        @endif
        <input type="number"
               name="{{ $name }}"
               id="{{ $name }}"
               class="{{ $inputClass }} @error($name) is-invalid @enderror"
               value="{{ old($name, $scoreRecord?->$name) }}"
               min="0"
               max="100"
               @if(!$useAdminLabel) required @endif>
        @error($name)
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        @if($useAdminLabel && $errors->has($name))
            <span class="text-danger">{{ $errors->first($name) }}</span>
        @endif
    </div>
@endforeach
