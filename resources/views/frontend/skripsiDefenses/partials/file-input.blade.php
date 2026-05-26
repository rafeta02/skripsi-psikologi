@php
    $inputName = ($multiple ?? false) ? $name . '[]' : $name;
    $inputId = str_replace(['[', ']'], '_', $name);
    $acceptAttr = $accept ?? '.pdf';
@endphp
<div class="form-group">
    <label class="form-label-modern {{ ($required ?? false) ? 'required' : '' }}" for="{{ $inputId }}">{{ $label }}</label>
    <div class="custom-file">
        <input
            type="file"
            name="{{ $inputName }}"
            id="{{ $inputId }}"
            class="custom-file-input @error($name) is-invalid @enderror @error($name . '.*') is-invalid @enderror"
            accept="{{ $acceptAttr }}"
            {{ ($required ?? false) ? 'required' : '' }}
            {{ ($multiple ?? false) ? 'multiple' : '' }}
        >
        <label class="custom-file-label" for="{{ $inputId }}">Pilih file...</label>
    </div>
    <small class="form-text text-muted">{{ $hint ?? 'PDF, maks. 10MB' }}{{ ($multiple ?? false) ? ' — dapat memilih lebih dari satu file' : '' }}</small>
    @error($name)
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
    @error($name . '.*')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>
