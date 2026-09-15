@php
    $selectedSdgs = collect(old('sdgs', $selected ?? []))->map(fn ($id) => (int) $id)->all();
    $sdgGoals = config('sdgs.goals', []);
    $sdgColors = config('sdgs.colors', []);
    $labelClass = $labelClass ?? 'font-weight-bold';
@endphp

<div class="form-group">
    <label class="{{ $labelClass }} d-block">
        {{ trans('cruds.skripsiDefense.fields.sdgs') }} <span class="text-danger">*</span>
    </label>
    <small class="form-text text-muted d-block mb-3">
        {{ trans('cruds.skripsiDefense.fields.sdgs_helper') }}
        <a href="https://sdgs.bappenas.go.id/17-goals" target="_blank" rel="noopener">sdgs.bappenas.go.id/17-goals</a>
    </small>

    <div class="row">
        @foreach($sdgGoals as $number => $label)
            @php
                $color = $sdgColors[$number] ?? '#6c757d';
                $inputId = 'sdg_' . $number . '_' . uniqid();
                $isChecked = in_array((int) $number, $selectedSdgs, true);
            @endphp
            <div class="col-md-6 col-lg-4 mb-2">
                <div class="custom-control custom-checkbox sdg-checkbox-item p-2 border rounded h-100 {{ $isChecked ? 'border-primary bg-light' : '' }}">
                    <input
                        type="checkbox"
                        class="custom-control-input"
                        name="sdgs[]"
                        id="{{ $inputId }}"
                        value="{{ $number }}"
                        {{ $isChecked ? 'checked' : '' }}
                    >
                    <label class="custom-control-label w-100" for="{{ $inputId }}">
                        <span class="badge badge-pill text-white mr-1" style="background-color: {{ $color }};">{{ $number }}</span>
                        <span class="small">{{ $label }}</span>
                    </label>
                </div>
            </div>
        @endforeach
    </div>

    @error('sdgs')
        <span class="text-danger d-block">{{ $message }}</span>
    @enderror
    @error('sdgs.*')
        <span class="text-danger d-block">{{ $message }}</span>
    @enderror
</div>

@once
    @push('styles')
    <style>
        .sdg-checkbox-item .custom-control-label {
            cursor: pointer;
            line-height: 1.4;
        }
        .sdg-checkbox-item:hover {
            background-color: #f8f9fa;
        }
    </style>
    @endpush
@endonce
