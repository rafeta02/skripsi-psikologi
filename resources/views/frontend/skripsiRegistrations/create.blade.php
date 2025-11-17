@extends('layouts.frontend')
@section('content')
<style>
    .registration-wizard {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    
    .wizard-header {
        background: linear-gradient(135deg, #22004C 0%, #4A0080 100%);
        color: white;
        padding: 2rem;
        text-align: center;
    }
    
    .wizard-header h2 {
        margin: 0;
        font-size: 1.75rem;
        font-weight: 600;
    }
    
    .wizard-header p {
        margin: 0.5rem 0 0;
        opacity: 0.9;
        font-size: 0.95rem;
    }
    
    .wizard-steps {
        display: flex;
        justify-content: space-between;
        padding: 2rem 3rem;
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
    }
    
    .wizard-step {
        flex: 1;
        text-align: center;
        position: relative;
    }
    
    .wizard-step:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 20px;
        left: 50%;
        width: 100%;
        height: 2px;
        background: #dee2e6;
        z-index: 0;
    }
    
    .wizard-step.active:not(:last-child)::after {
        background: #22004C;
    }
    
    .step-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #dee2e6;
        color: #6c757d;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
        transition: all 0.3s;
    }
    
    .wizard-step.active .step-number {
        background: #22004C;
        color: white;
        transform: scale(1.1);
    }
    
    .wizard-step.completed .step-number {
        background: #28a745;
        color: white;
    }
    
    .step-label {
        font-size: 0.85rem;
        color: #6c757d;
        font-weight: 500;
    }
    
    .wizard-step.active .step-label {
        color: #22004C;
        font-weight: 600;
    }
    
    .wizard-body {
        padding: 2.5rem 3rem;
    }
    
    .form-section {
        display: none;
    }
    
    .form-section.active {
        display: block;
        animation: fadeIn 0.4s;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }
    
    .section-description {
        color: #718096;
        margin-bottom: 2rem;
        font-size: 0.95rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-group label {
        font-weight: 500;
        color: #4a5568;
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .form-group label .required {
        color: #e53e3e;
        margin-left: 2px;
    }
    
    .form-control, .select2-container--default .select2-selection--single {
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.65rem 1rem;
        font-size: 0.95rem;
        transition: all 0.2s;
    }
    
    .form-control:focus {
        border-color: #22004C;
        box-shadow: 0 0 0 3px rgba(34, 0, 76, 0.1);
        outline: none;
    }
    
    .help-block {
        font-size: 0.85rem;
        color: #a0aec0;
        margin-top: 0.25rem;
        display: block;
    }
    
    .dropzone {
        border: 2px dashed #cbd5e0;
        border-radius: 8px;
        background: #f7fafc;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .dropzone:hover {
        border-color: #22004C;
        background: #edf2f7;
    }
    
    .dropzone .dz-message {
        font-size: 0.95rem;
        color: #718096;
    }
    
    .wizard-actions {
        display: flex;
        justify-content: space-between;
        padding: 2rem 3rem;
        background: #f8f9fa;
        border-top: 1px solid #e9ecef;
    }
    
    .btn-wizard {
        padding: 0.65rem 2rem;
        border-radius: 8px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.95rem;
    }
    
    .btn-prev {
        background: #e2e8f0;
        color: #4a5568;
    }
    
    .btn-prev:hover {
        background: #cbd5e0;
    }
    
    .btn-next, .btn-submit {
        background: linear-gradient(135deg, #22004C 0%, #4A0080 100%);
        color: white;
    }
    
    .btn-next:hover, .btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(34, 0, 76, 0.4);
    }
    
    .btn-wizard:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .info-box {
        background: #ebf8ff;
        border-left: 4px solid #4299e1;
        padding: 1rem 1.25rem;
        border-radius: 6px;
        margin-bottom: 1.5rem;
    }
    
    .info-box-title {
        font-weight: 600;
        color: #2c5282;
        margin-bottom: 0.25rem;
    }
    
    .info-box-text {
        font-size: 0.9rem;
        color: #2d3748;
        margin-left: 15px;
        margin: 0;
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="registration-wizard">
                <!-- Header -->
                <div class="wizard-header">
                    <h2>Pendaftaran Topik Skripsi Reguler</h2>
                    <p>Lengkapi formulir berikut untuk mendaftar topik skripsi Anda</p>
                </div>

                <!-- Progress Steps -->
                <div class="wizard-steps">
                    <div class="wizard-step active" data-step="1">
                        <div class="step-number">1</div>
                        <div class="step-label">Informasi & Topik Skripsi</div>
                    </div>
                    <div class="wizard-step" data-step="2">
                        <div class="step-number">2</div>
                        <div class="step-label">Dokumen Persyaratan</div>
                    </div>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route("frontend.skripsi-registrations.store") }}" enctype="multipart/form-data" id="registrationForm">
                        @method('POST')
                        @csrf
                    
                    <div class="wizard-body">
                        <!-- Step 1: Information & Topic -->
                        <div class="form-section active" data-section="1">
                            <div class="section-title">Informasi & Topik Skripsi</div>
                            <div class="section-description">Lengkapi informasi dasar, topik skripsi, dan preferensi dosen pembimbing</div>

                            <div class="info-box">
                                <div class="info-box-title">Tahap: Pendaftaran Topik Skripsi</div>
                                <div class="info-box-text">Ini adalah langkah pertama dalam alur Skripsi Reguler. Setelah disetujui admin, Anda akan mendapatkan dosen pembimbing.</div>
                            </div>

                        <div class="form-group">
                                <label for="theme_id">
                                    Tema Keilmuan <span class="required">*</span>
                                </label>
                                <select class="form-control select2" name="theme_id" id="theme_id" required>
                                    <option value="">-- Pilih Tema Keilmuan --</option>
                                @foreach($themes as $id => $entry)
                                    <option value="{{ $id }}" {{ old('theme_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('theme'))
                                    <span class="text-danger small">{{ $errors->first('theme') }}</span>
                                @endif
                                <span class="help-block">Pilih bidang keilmuan yang sesuai dengan topik skripsi Anda</span>
                                </div>

                        <div class="form-group">
                                <label for="title">
                                    Judul Skripsi <span class="required">*</span>
                                </label>
                                <input class="form-control" type="text" name="title" id="title" value="{{ old('title', '') }}" placeholder="Masukkan judul skripsi Anda" required>
                            @if($errors->has('title'))
                                    <span class="text-danger small">{{ $errors->first('title') }}</span>
                            @endif
                                <span class="help-block">Tulis judul yang jelas dan spesifik</span>
                        </div>

                        <div class="form-group">
                                <label for="abstract">
                                    Abstrak / Ringkasan
                                </label>
                                <textarea class="form-control" name="abstract" id="abstract" rows="5" placeholder="Jelaskan ringkasan penelitian Anda...">{{ old('abstract') }}</textarea>
                            @if($errors->has('abstract'))
                                    <span class="text-danger small">{{ $errors->first('abstract') }}</span>
                            @endif
                                <span class="help-block">Ringkasan singkat tentang latar belakang, tujuan, dan metode penelitian</span>
                        </div>

                        <div class="form-group">
                                <label for="tps_lecturer_id">
                                    Dosen TPS (Tes Potensi Skripsi)
                                </label>
                            <select class="form-control select2" name="tps_lecturer_id" id="tps_lecturer_id">
                                    <option value="">-- Pilih Dosen TPS --</option>
                                @foreach($tps_lecturers as $id => $entry)
                                    <option value="{{ $id }}" {{ old('tps_lecturer_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('tps_lecturer'))
                                    <span class="text-danger small">{{ $errors->first('tps_lecturer') }}</span>
                            @endif
                                <span class="help-block">Dosen yang menguji TPS Anda</span>
                        </div>

                        <div class="form-group">
                                <label for="preference_supervision_id">
                                    Preferensi Dosen Pembimbing <span class="required">*</span>
                                </label>
                                <select class="form-control select2" name="preference_supervision_id" id="preference_supervision_id" required>
                                    <option value="">-- Pilih Dosen Pembimbing --</option>
                                @foreach($preference_supervisions as $id => $entry)
                                    <option value="{{ $id }}" {{ old('preference_supervision_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('preference_supervision'))
                                    <span class="text-danger small">{{ $errors->first('preference_supervision') }}</span>
                                @endif
                                <span class="help-block">Pilih dosen yang Anda inginkan sebagai pembimbing (akan diverifikasi oleh admin)</span>
                                </div>
                        </div>

                        <!-- Step 2: Documents -->
                        <div class="form-section" data-section="2">
                            <div class="section-title">Upload Dokumen Persyaratan</div>
                            <div class="section-description">Upload dokumen akademik yang diperlukan</div>

                            <div class="info-box">
                                <div class="info-box-title">Dokumen Wajib</div>
                                <div class="info-box-text">Pastikan semua dokumen dalam format PDF dan dapat dibaca dengan jelas. Ukuran maksimal per file: 10 MB</div>
                            </div>

                            <div class="form-group">
                                <label for="khs_all">
                                    Kartu Hasil Studi (KHS) Semua Semester <span class="required">*</span>
                                </label>
                                <div class="needsclick dropzone" id="khs_all-dropzone">
                                    <div class="dz-message">
                                        <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                        <p>Klik atau seret file KHS ke sini</p>
                                        <small>PDF, maksimal 10 MB (bisa multiple file)</small>
                                    </div>
                                </div>
                                @if($errors->has('khs_all'))
                                    <span class="text-danger small">{{ $errors->first('khs_all') }}</span>
                            @endif
                                <span class="help-block">Upload KHS dari semester 1 hingga semester terakhir</span>
                            </div>

                            <div class="form-group">
                                <label for="krs_latest">
                                    Kartu Rencana Studi (KRS) Semester Terakhir <span class="required">*</span>
                                </label>
                                <div class="needsclick dropzone" id="krs_latest-dropzone">
                                    <div class="dz-message">
                                        <i class="fas fa-file-pdf fa-2x mb-2" style="color: #cbd5e0;"></i>
                                        <p>Klik atau seret file KRS ke sini</p>
                                        <small>PDF, maksimal 10 MB (1 file)</small>
                                    </div>
                                </div>
                                @if($errors->has('krs_latest'))
                                    <span class="text-danger small">{{ $errors->first('krs_latest') }}</span>
                            @endif
                                <span class="help-block">Upload KRS semester yang sedang berjalan</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="wizard-actions">
                        <button type="button" class="btn-wizard btn-prev" id="prevBtn" style="display: none;">
                            <i class="fas fa-arrow-left mr-2"></i> Sebelumnya
                        </button>
                        <div></div>
                        <button type="button" class="btn-wizard btn-next" id="nextBtn">
                            Selanjutnya <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                        <button type="submit" class="btn-wizard btn-submit" id="submitBtn" style="display: none;">
                            <i class="fas fa-check mr-2"></i> Kirim Pendaftaran
                            </button>
                        </div>
                    </form>
                </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Wizard Navigation
let currentStep = 1;
const totalSteps = 2;

document.getElementById('nextBtn').addEventListener('click', function() {
    if (validateStep(currentStep)) {
        if (currentStep < totalSteps) {
            currentStep++;
            showStep(currentStep);
        }
    }
});

document.getElementById('prevBtn').addEventListener('click', function() {
    if (currentStep > 1) {
        currentStep--;
        showStep(currentStep);
    }
});

// Form submission validation
document.getElementById('registrationForm').addEventListener('submit', function(e) {
    // Validate all steps before submission
    let allValid = true;
    for (let i = 1; i <= totalSteps; i++) {
        if (!validateStep(i)) {
            allValid = false;
            // Go to first invalid step
            if (currentStep !== i) {
                currentStep = i;
                showStep(i);
            }
            break;
        }
    }
    
    if (!allValid) {
        e.preventDefault();
        return false;
    }
});

function showStep(step) {
    // Hide all sections
    document.querySelectorAll('.form-section').forEach(section => {
        section.classList.remove('active');
    });
    
    // Show current section
    document.querySelector(`.form-section[data-section="${step}"]`).classList.add('active');
    
    // Update wizard steps
    document.querySelectorAll('.wizard-step').forEach((wizardStep, index) => {
        wizardStep.classList.remove('active', 'completed');
        if (index + 1 < step) {
            wizardStep.classList.add('completed');
        } else if (index + 1 === step) {
            wizardStep.classList.add('active');
        }
    });
    
    // Update buttons
    document.getElementById('prevBtn').style.display = step === 1 ? 'none' : 'block';
    document.getElementById('nextBtn').style.display = step === totalSteps ? 'none' : 'block';
    document.getElementById('submitBtn').style.display = step === totalSteps ? 'block' : 'none';
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function validateStep(step) {
    const section = document.querySelector(`.form-section[data-section="${step}"]`);
    const requiredFields = section.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value || field.value === '') {
            isValid = false;
            field.style.borderColor = '#e53e3e';
            
            // Add error message if not exists
            if (!field.nextElementSibling || !field.nextElementSibling.classList.contains('validation-error')) {
                const errorMsg = document.createElement('span');
                errorMsg.className = 'text-danger small validation-error';
                errorMsg.textContent = 'Field ini wajib diisi';
                field.parentNode.insertBefore(errorMsg, field.nextSibling);
            }
        } else {
            field.style.borderColor = '#e2e8f0';
            // Remove error message
            const errorMsg = field.parentNode.querySelector('.validation-error');
            if (errorMsg) {
                errorMsg.remove();
            }
        }
    });
    
    // Validate documents on step 2
    if (step === 2) {
        // Check KHS files
        const khsFiles = document.querySelectorAll('input[name="khs_all[]"]');
        if (khsFiles.length === 0) {
            isValid = false;
            const khsDropzone = document.querySelector('#khs_all-dropzone');
            khsDropzone.style.borderColor = '#e53e3e';
            
            // Remove existing error first
            const existingError = khsDropzone.parentNode.querySelector('.doc-validation-error');
            if (existingError) existingError.remove();
            
            const errorMsg = document.createElement('div');
            errorMsg.className = 'text-danger small doc-validation-error mt-2';
            errorMsg.innerHTML = '<i class="fas fa-exclamation-circle"></i> KHS wajib diupload (minimal 1 file)';
            khsDropzone.parentNode.insertBefore(errorMsg, khsDropzone.nextSibling);
        } else {
            const khsDropzone = document.querySelector('#khs_all-dropzone');
            khsDropzone.style.borderColor = '#cbd5e0';
            const existingError = khsDropzone.parentNode.querySelector('.doc-validation-error');
            if (existingError) existingError.remove();
        }
        
        // Check KRS file
        const krsFile = document.querySelector('input[name="krs_latest"]');
        if (!krsFile) {
            isValid = false;
            const krsDropzone = document.querySelector('#krs_latest-dropzone');
            krsDropzone.style.borderColor = '#e53e3e';
            
            // Remove existing error first
            const existingError = krsDropzone.parentNode.querySelector('.doc-validation-error');
            if (existingError) existingError.remove();
            
            const errorMsg = document.createElement('div');
            errorMsg.className = 'text-danger small doc-validation-error mt-2';
            errorMsg.innerHTML = '<i class="fas fa-exclamation-circle"></i> KRS semester terakhir wajib diupload';
            krsDropzone.parentNode.insertBefore(errorMsg, krsDropzone.nextSibling);
        } else {
            const krsDropzone = document.querySelector('#krs_latest-dropzone');
            krsDropzone.style.borderColor = '#cbd5e0';
            const existingError = krsDropzone.parentNode.querySelector('.doc-validation-error');
            if (existingError) existingError.remove();
        }
    }
    
    if (!isValid) {
        alert('Mohon lengkapi semua field yang wajib diisi dan upload semua dokumen yang diperlukan');
    }
    
    return isValid;
}

// Dropzone Configuration
    var uploadedKhsAllMap = {}
Dropzone.options.khsAllDropzone = {
    url: '{{ route('frontend.skripsi-registrations.storeMedia') }}',
    maxFilesize: 10, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="khs_all[]" value="' + response.name + '">')
      uploadedKhsAllMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedKhsAllMap[file.name]
      }
      $('form').find('input[name="khs_all[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($skripsiRegistration) && $skripsiRegistration->khs_all)
          var files =
            {!! json_encode($skripsiRegistration->khs_all) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="khs_all[]" value="' + file.file_name + '">')
            }
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}
</script>
<script>
    Dropzone.options.krsLatestDropzone = {
    url: '{{ route('frontend.skripsi-registrations.storeMedia') }}',
    maxFilesize: 10, // MB
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10
    },
    success: function (file, response) {
      $('form').find('input[name="krs_latest"]').remove()
      $('form').append('<input type="hidden" name="krs_latest" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="krs_latest"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($skripsiRegistration) && $skripsiRegistration->krs_latest)
      var file = {!! json_encode($skripsiRegistration->krs_latest) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="krs_latest" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}
</script>
@endsection