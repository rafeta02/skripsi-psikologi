<div class="form-group">
    <label class="form-label-modern required" for="eap_grade">{{ trans('cruds.skripsiDefense.fields.eap_grade') }}</label>
    <select name="eap_grade" id="eap_grade" class="form-control-modern @error('eap_grade') is-invalid @enderror" required>
        <option value="">-- Pilih Nilai EAP --</option>
        @foreach(\App\Models\SkripsiDefense::EAP_GRADE_SELECT as $value => $label)
            <option value="{{ $value }}" {{ old('eap_grade', $selected ?? '') == $value ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
    <small class="form-text text-muted">{{ trans('cruds.skripsiDefense.fields.eap_grade_helper') }}</small>
    @error('eap_grade')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label class="form-label-modern required" for="eap_score">{{ trans('cruds.skripsiDefense.fields.eap_score') }}</label>
    <input type="number" name="eap_score" id="eap_score" min="1" max="100" step="1"
        class="form-control-modern @error('eap_score') is-invalid @enderror"
        value="{{ old('eap_score', $score ?? '') }}" required>
    <small class="form-text text-muted">{{ trans('cruds.skripsiDefense.fields.eap_score_helper') }}</small>
    @error('eap_score')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>
