@extends('layouts.frontend')
@section('content')
<link rel="stylesheet" href="{{ asset('css/modern-form.css') }}">
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="form-card">
                <div class="form-header">
                    <h2>Upload Hasil Sidang Skripsi</h2>
                    <p>Upload dokumen hasil sidang skripsi Anda</p>
                </div>

                <form method="POST" action="{{ route("frontend.application-result-defenses.store") }}" enctype="multipart/form-data" id="defenseResultForm">
                    @method('POST')
                    @csrf
                    
                    <div class="form-body">
                        @if($activeApplication)
                            <input type="hidden" name="application_id" value="{{ $activeApplication->id }}">
                            
                            <div class="alert alert-success mb-4">
                                <h5 class="alert-heading"><i class="fas fa-info-circle mr-2"></i>Aplikasi Skripsi Anda</h5>
                                <p class="mb-1"><strong>Type:</strong> <span class="badge badge-primary">{{ ucfirst($activeApplication->type) }}</span></p>
                                <p class="mb-1"><strong>Stage:</strong> <span class="badge badge-info">{{ ucfirst($activeApplication->stage) }}</span></p>
                                <p class="mb-0"><strong>Status:</strong> <span class="badge badge-success">{{ ucfirst($activeApplication->status) }}</span></p>
                            </div>
                        @else
                            <div class="alert alert-warning mb-4">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Anda belum memiliki aplikasi skripsi yang aktif. Silakan buat aplikasi terlebih dahulu.
                            </div>
                        @endif

                        <div class="info-box info">
                            <div class="info-box-title">Informasi Penting</div>
                            <div class="info-box-text">
                                <ul class="mb-0">
                                    <li><span class="text-danger font-weight-bold">*</span> = Field wajib diisi</li>
                                    <li>Upload semua dokumen <strong>WAJIB</strong> terlebih dahulu</li>
                                    <li>Dokumen opsional dapat diupload kemudian melalui menu Edit</li>
                                    <li>Format file: PDF (maksimal 10 MB per file)</li>
                                </ul>
                            </div>
                        </div>

                        <!-- SECTION 1: REQUIRED - Informasi Hasil Sidang -->
                        <div class="section-divider">
                            <h4><i class="fas fa-clipboard-check mr-2"></i>Informasi Hasil Sidang <span class="text-danger">(WAJIB)</span></h4>
                        </div>

                        <div class="form-group">
                            <label for="result">Hasil Sidang <span class="required">*</span></label>
                            <select class="form-control @error('result') is-invalid @enderror" name="result" id="result" required>
                                <option value="">-- Pilih Hasil --</option>
                                @foreach(App\Models\ApplicationResultDefense::RESULT_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('result', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('result')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <span class="help-block">Hasil keputusan dari sidang skripsi</span>
                        </div>

                        <!-- SECTION 2: REQUIRED - Dokumen Wajib -->
                        <div class="section-divider">
                            <h4><i class="fas fa-file-upload mr-2"></i>Dokumen Wajib <span class="text-danger">*</span></h4>
                        </div>

                        <div class="form-group">
                            <label for="report_document">Berita Acara Sidang <span class="required">*</span></label>
                            <div class="needsclick dropzone @error('report_document') is-invalid @enderror" id="report_document-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>PDF berita acara sidang (maksimal 10 MB)</small>
                                </div>
                            </div>
                            @error('report_document')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                            <span class="help-block"><strong>WAJIB:</strong> Upload berita acara hasil sidang skripsi</span>
                        </div>

                        <div class="form-group">
                            <label for="attendance_document">Daftar Hadir Sidang <span class="required">*</span></label>
                            <div class="needsclick dropzone @error('attendance_document') is-invalid @enderror" id="attendance_document-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>PDF daftar hadir (maksimal 10 MB)</small>
                                </div>
                            </div>
                            @error('attendance_document')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                            <span class="help-block"><strong>WAJIB:</strong> Upload daftar hadir sidang skripsi</span>
                        </div>

                        <!-- SECTION 3: OPTIONAL - Catatan & Informasi Tambahan -->
                        <div class="section-divider">
                            <h4><i class="fas fa-info-circle mr-2"></i>Catatan & Informasi Tambahan <span class="text-muted">(Opsional)</span></h4>
                        </div>

                        <div class="form-group">
                            <label for="note">Catatan/Saran Perbaikan</label>
                            <textarea class="form-control @error('note') is-invalid @enderror" name="note" id="note" rows="4" placeholder="Catatan dan saran perbaikan dari dosen penguji..." maxlength="5000">{{ old('note') }}</textarea>
                            @error('note')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <span class="help-block">Catatan dan saran perbaikan dari dosen pembimbing/penguji (maksimal 5000 karakter)</span>
                        </div>

                        <div class="form-group">
                            <label for="revision_deadline">Batas Waktu Revisi</label>
                            <input class="form-control date @error('revision_deadline') is-invalid @enderror" type="text" name="revision_deadline" id="revision_deadline" value="{{ old('revision_deadline') }}" placeholder="Pilih tanggal">
                            @error('revision_deadline')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <span class="help-block">Batas waktu untuk menyelesaikan revisi (jika ada)</span>
                        </div>

                        <!-- SECTION 4: OPTIONAL - Dokumen Tambahan -->
                        <div class="section-divider">
                            <h4><i class="fas fa-folder-plus mr-2"></i>Dokumen Tambahan <span class="text-muted">(Opsional)</span></h4>
                            <p class="text-muted small mb-0">Dokumen-dokumen berikut dapat diupload sekarang atau kemudian melalui menu Edit</p>
                        </div>

                        <div class="form-group">
                            <label for="form_document">Form Penilaian</label>
                            <div class="needsclick dropzone" id="form_document-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>PDF form penilaian (maksimal 10 MB)</small>
                                </div>
                            </div>
                            @error('form_document')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                            <span class="help-block">Upload form penilaian dari dosen penguji</span>
                        </div>

                        <div class="form-group">
                            <label for="latest_script">Naskah Skripsi Final</label>
                            <div class="needsclick dropzone" id="latest_script-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>PDF naskah skripsi (maksimal 10 MB)</small>
                                </div>
                            </div>
                            @error('latest_script')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                            <span class="help-block">Upload naskah skripsi final setelah revisi</span>
                        </div>

                        <div class="form-group">
                            <label for="documentation">Dokumentasi Sidang</label>
                            <div class="needsclick dropzone" id="documentation-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>Foto dokumentasi sidang (maksimal 10 MB per file)</small>
                                </div>
                            </div>
                            @error('documentation')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                            <span class="help-block">Upload foto dokumentasi pelaksanaan sidang</span>
                        </div>

                        <div class="form-group">
                            <label for="certificate_document">Sertifikat/Lembar Pengesahan</label>
                            <div class="needsclick dropzone" id="certificate_document-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>PDF sertifikat (maksimal 10 MB)</small>
                                </div>
                            </div>
                            @error('certificate_document')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                            <span class="help-block">Upload lembar pengesahan yang sudah ditandatangani</span>
                        </div>

                        <div class="form-group">
                            <label for="publication_document">Bukti Publikasi/Jurnal</label>
                            <div class="needsclick dropzone" id="publication_document-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>PDF bukti publikasi (maksimal 10 MB)</small>
                                </div>
                            </div>
                            @error('publication_document')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                            <span class="help-block">Upload bukti publikasi artikel/jurnal (jika ada)</span>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('frontend.application-result-defenses.index') }}" class="btn-back">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                        <button type="submit" class="btn-submit" {{ !$activeApplication ? 'disabled' : '' }} id="submitBtn">
                            <i class="fas fa-save mr-2"></i> Simpan Data
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
// Validation flags
let hasReportDocument = false;
let hasAttendanceDocument = false;

// Form validation before submit
document.getElementById('defenseResultForm').addEventListener('submit', function(e) {
    const result = document.getElementById('result').value;
    
    if (!result) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Data Tidak Lengkap',
            text: 'Hasil sidang wajib dipilih!'
        });
        return false;
    }
    
    if (!hasReportDocument) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Dokumen Tidak Lengkap',
            text: 'Berita acara sidang wajib diupload!'
        });
        return false;
    }
    
    if (!hasAttendanceDocument) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Dokumen Tidak Lengkap',
            text: 'Daftar hadir sidang wajib diupload!'
        });
        return false;
    }
});

// Report Document Dropzone (REQUIRED - Multiple files)
var uploadedReportDocumentMap = {}
Dropzone.options.reportDocumentDropzone = {
    url: '{{ route('frontend.application-result-defenses.storeMedia') }}',
    maxFilesize: 10,
    acceptedFiles: '.pdf',
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10,
      collection_name: 'report_document'
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="report_document[]" value="' + response.name + '">')
      uploadedReportDocumentMap[file.name] = response.name
      hasReportDocument = true
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedReportDocumentMap[file.name]
      }
      $('form').find('input[name="report_document[]"][value="' + name + '"]').remove()
      
      // Check if still has files
      if ($('form').find('input[name="report_document[]"]').length === 0) {
        hasReportDocument = false
      }
    },
    init: function () {
@if(isset($applicationResultDefense) && $applicationResultDefense->report_document)
          var files = {!! json_encode($applicationResultDefense->report_document) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="report_document[]" value="' + file.file_name + '">')
              hasReportDocument = true
            }
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response
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

// Attendance Document Dropzone (REQUIRED - Single file)
Dropzone.options.attendanceDocumentDropzone = {
    url: '{{ route('frontend.application-result-defenses.storeMedia') }}',
    maxFilesize: 10,
    maxFiles: 1,
    acceptedFiles: '.pdf',
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10,
      collection_name: 'attendance_document'
    },
    success: function (file, response) {
      $('form').find('input[name="attendance_document"]').remove()
      $('form').append('<input type="hidden" name="attendance_document" value="' + response.name + '">')
      hasAttendanceDocument = true
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="attendance_document"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
        hasAttendanceDocument = false
      }
    },
    init: function () {
@if(isset($applicationResultDefense) && $applicationResultDefense->attendance_document)
      var file = {!! json_encode($applicationResultDefense->attendance_document) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="attendance_document" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
      hasAttendanceDocument = true
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response
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

// Form Document Dropzone (OPTIONAL - Multiple)
var uploadedFormDocumentMap = {}
Dropzone.options.formDocumentDropzone = {
    url: '{{ route('frontend.application-result-defenses.storeMedia') }}',
    maxFilesize: 10,
    acceptedFiles: '.pdf',
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10,
      collection_name: 'form_document'
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="form_document[]" value="' + response.name + '">')
      uploadedFormDocumentMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedFormDocumentMap[file.name]
      }
      $('form').find('input[name="form_document[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($applicationResultDefense) && $applicationResultDefense->form_document)
          var files = {!! json_encode($applicationResultDefense->form_document) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="form_document[]" value="' + file.file_name + '">')
            }
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response
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

// Latest Script Dropzone (OPTIONAL - Single)
Dropzone.options.latestScriptDropzone = {
    url: '{{ route('frontend.application-result-defenses.storeMedia') }}',
    maxFilesize: 10,
    maxFiles: 1,
    acceptedFiles: '.pdf',
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10,
      collection_name: 'latest_script'
    },
    success: function (file, response) {
      $('form').find('input[name="latest_script"]').remove()
      $('form').append('<input type="hidden" name="latest_script" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="latest_script"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($applicationResultDefense) && $applicationResultDefense->latest_script)
      var file = {!! json_encode($applicationResultDefense->latest_script) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="latest_script" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response
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

// Documentation Dropzone (OPTIONAL - Multiple)
var uploadedDocumentationMap = {}
Dropzone.options.documentationDropzone = {
    url: '{{ route('frontend.application-result-defenses.storeMedia') }}',
    maxFilesize: 10,
    acceptedFiles: '.pdf,.jpg,.jpeg,.png',
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10,
      collection_name: 'documentation'
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="documentation[]" value="' + response.name + '">')
      uploadedDocumentationMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedDocumentationMap[file.name]
      }
      $('form').find('input[name="documentation[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($applicationResultDefense) && $applicationResultDefense->documentation)
          var files = {!! json_encode($applicationResultDefense->documentation) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="documentation[]" value="' + file.file_name + '">')
            }
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response
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

// Certificate Document Dropzone (OPTIONAL - Single)
Dropzone.options.certificateDocumentDropzone = {
    url: '{{ route('frontend.application-result-defenses.storeMedia') }}',
    maxFilesize: 10,
    maxFiles: 1,
    acceptedFiles: '.pdf',
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10,
      collection_name: 'certificate_document'
    },
    success: function (file, response) {
      $('form').find('input[name="certificate_document"]').remove()
      $('form').append('<input type="hidden" name="certificate_document" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="certificate_document"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($applicationResultDefense) && $applicationResultDefense->certificate_document)
      var file = {!! json_encode($applicationResultDefense->certificate_document) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="certificate_document" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response
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

// Publication Document Dropzone (OPTIONAL - Single)
Dropzone.options.publicationDocumentDropzone = {
    url: '{{ route('frontend.application-result-defenses.storeMedia') }}',
    maxFilesize: 10,
    maxFiles: 1,
    acceptedFiles: '.pdf',
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10,
      collection_name: 'publication_document'
    },
    success: function (file, response) {
      $('form').find('input[name="publication_document"]').remove()
      $('form').append('<input type="hidden" name="publication_document" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="publication_document"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($applicationResultDefense) && $applicationResultDefense->publication_document)
      var file = {!! json_encode($applicationResultDefense->publication_document) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="publication_document" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response
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

<style>
.section-divider {
    margin: 30px 0 20px 0;
    padding-bottom: 10px;
    border-bottom: 2px solid #e2e8f0;
}

.section-divider h4 {
    color: #2d3748;
    font-weight: 600;
    margin: 0;
}

.form-group label .required {
    color: #e53e3e;
    font-weight: bold;
}

.is-invalid {
    border-color: #e53e3e !important;
}

.invalid-feedback {
    color: #e53e3e;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}
</style>
@endsection
