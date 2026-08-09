<div class="form-group">
    <label class="form-label-modern required" for="eap_grade">Nilai EAP</label>
    <select name="eap_grade" id="eap_grade" class="form-control-modern @error('eap_grade') is-invalid @enderror" required>
        <option value="">-- Pilih Nilai EAP --</option>
        @foreach(\App\Models\SkripsiDefense::EAP_GRADE_SELECT as $value => $label)
            <option value="{{ $value }}" {{ old('eap_grade', $selected ?? '') == $value ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
    <small class="form-text text-muted">Nilai huruf EAP sesuai sertifikat (A, A-, B+, dst.)</small>
    @error('eap_grade')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>
