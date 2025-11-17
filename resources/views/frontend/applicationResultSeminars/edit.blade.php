@extends('layouts.frontend')
@section('content')
<link rel="stylesheet" href="{{ asset('css/modern-form.css') }}">
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="form-card">
                <div class="form-header">
                    <h2>Edit Hasil Seminar Proposal</h2>
                    <p>Perbarui dokumen hasil seminar proposal skripsi Anda</p>
                </div>

                <form method="POST" action="{{ route("frontend.application-result-seminars.update", [$applicationResultSeminar->id]) }}" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    
                    <div class="form-body">
                        @if($applicationResultSeminar->application)
                            <input type="hidden" name="application_id" value="{{ $applicationResultSeminar->application->id }}">
                            
                            <div class="alert alert-info mb-4">
                                <h5 class="alert-heading"><i class="fas fa-info-circle mr-2"></i>Aplikasi Skripsi</h5>
                                <p class="mb-1"><strong>Stage:</strong> <span class="badge badge-primary">{{ ucfirst($applicationResultSeminar->application->stage) }}</span></p>
                                <p class="mb-0"><strong>Status:</strong> <span class="badge badge-success">{{ ucfirst($applicationResultSeminar->application->status) }}</span></p>
                            </div>
                        @endif

                        <div class="info-box warning">
                            <div class="info-box-title">Mode Edit</div>
                            <div class="info-box-text">
                                Pastikan semua perubahan dokumen sudah dikonfirmasi dengan dosen pembimbing sebelum menyimpan.
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="result">Hasil Seminar <span class="required">*</span></label>
                            <select class="form-control" name="result" id="result" required>
                                <option value="">-- Pilih Hasil --</option>
                                @foreach(App\Models\ApplicationResultSeminar::RESULT_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('result', $applicationResultSeminar->result) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('result'))
                                <span class="text-danger small d-block mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('result') }}</span>
                            @endif
                            <span class="help-block">Hasil keputusan dari seminar proposal</span>
                        </div>

                        <div class="form-group">
                            <label for="note">Catatan/Saran Perbaikan</label>
                            <textarea class="form-control" name="note" id="note" rows="4" placeholder="Catatan dan saran perbaikan dari dosen penguji...">{{ old('note', $applicationResultSeminar->note) }}</textarea>
                            @if($errors->has('note'))
                                <span class="text-danger small">{{ $errors->first('note') }}</span>
                            @endif
                            <span class="help-block">Catatan dan saran perbaikan dari dosen pembimbing/penguji</span>
                        </div>

                        <div class="form-group">
                            <label for="revision_deadline">Batas Waktu Revisi</label>
                            <input class="form-control date" type="text" name="revision_deadline" id="revision_deadline" value="{{ old('revision_deadline', $applicationResultSeminar->revision_deadline) }}" placeholder="Pilih tanggal">
                            @if($errors->has('revision_deadline'))
                                <span class="text-danger small">{{ $errors->first('revision_deadline') }}</span>
                            @endif
                            <span class="help-block">Batas waktu untuk menyelesaikan revisi (jika ada)</span>
                        </div>

                        <div class="form-group">
                            <label for="report_document">Berita Acara Seminar <span class="required">*</span></label>
                            <div class="needsclick dropzone" id="report_document-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>PDF berita acara seminar (maksimal 10 MB)</small>
                                </div>
                            </div>
                            @if($errors->has('report_document'))
                                <span class="text-danger small d-block mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('report_document') }}</span>
                            @endif
                            <span class="help-block">Upload berita acara hasil seminar proposal</span>
                        </div>

                        <div class="form-group">
                            <label for="attendance_document">Daftar Hadir <span class="required">*</span></label>
                            <div class="needsclick dropzone" id="attendance_document-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>PDF daftar hadir (maksimal 10 MB)</small>
                                </div>
                            </div>
                            @if($errors->has('attendance_document'))
                                <span class="text-danger small d-block mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('attendance_document') }}</span>
                            @endif
                            <span class="help-block">Upload daftar hadir seminar proposal</span>
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
                            @if($errors->has('form_document'))
                                <span class="text-danger small">{{ $errors->first('form_document') }}</span>
                            @endif
                            <span class="help-block">Upload form penilaian dari dosen penguji</span>
                        </div>

                        <div class="form-group">
                            <label for="latest_script">Naskah Proposal Terbaru</label>
                            <div class="needsclick dropzone" id="latest_script-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>PDF naskah proposal (maksimal 10 MB)</small>
                                </div>
                            </div>
                            @if($errors->has('latest_script'))
                                <span class="text-danger small">{{ $errors->first('latest_script') }}</span>
                            @endif
                            <span class="help-block">Upload naskah proposal terbaru setelah revisi</span>
                        </div>

                        <div class="form-group">
                            <label for="documentation">Dokumentasi Seminar</label>
                            <div class="needsclick dropzone" id="documentation-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>Foto dokumentasi seminar (maksimal 10 MB)</small>
                                </div>
                            </div>
                            @if($errors->has('documentation'))
                                <span class="text-danger small">{{ $errors->first('documentation') }}</span>
                            @endif
                            <span class="help-block">Upload foto dokumentasi pelaksanaan seminar</span>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('frontend.application-result-seminars.index') }}" class="btn-back">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
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
    var uploadedReportDocumentMap = {}
Dropzone.options.reportDocumentDropzone = {
    url: '{{ route('frontend.application-result-seminars.storeMedia') }}',
    maxFilesize: 10,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="report_document[]" value="' + response.name + '">')
      uploadedReportDocumentMap[file.name] = response.name
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
    },
    init: function () {
@if(isset($applicationResultSeminar) && $applicationResultSeminar->report_document)
          var files = {!! json_encode($applicationResultSeminar->report_document) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="report_document[]" value="' + file.file_name + '">')
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

Dropzone.options.attendanceDocumentDropzone = {
    url: '{{ route('frontend.application-result-seminars.storeMedia') }}',
    maxFilesize: 10,
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10
    },
    success: function (file, response) {
      $('form').find('input[name="attendance_document"]').remove()
      $('form').append('<input type="hidden" name="attendance_document" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="attendance_document"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($applicationResultSeminar) && $applicationResultSeminar->attendance_document)
      var file = {!! json_encode($applicationResultSeminar->attendance_document) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="attendance_document" value="' + file.file_name + '">')
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

var uploadedFormDocumentMap = {}
Dropzone.options.formDocumentDropzone = {
    url: '{{ route('frontend.application-result-seminars.storeMedia') }}',
    maxFilesize: 10,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10
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
@if(isset($applicationResultSeminar) && $applicationResultSeminar->form_document)
          var files = {!! json_encode($applicationResultSeminar->form_document) !!}
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

Dropzone.options.latestScriptDropzone = {
    url: '{{ route('frontend.application-result-seminars.storeMedia') }}',
    maxFilesize: 10,
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10
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
@if(isset($applicationResultSeminar) && $applicationResultSeminar->latest_script)
      var file = {!! json_encode($applicationResultSeminar->latest_script) !!}
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

var uploadedDocumentationMap = {}
Dropzone.options.documentationDropzone = {
    url: '{{ route('frontend.application-result-seminars.storeMedia') }}',
    maxFilesize: 10,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10
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
@if(isset($applicationResultSeminar) && $applicationResultSeminar->documentation)
          var files = {!! json_encode($applicationResultSeminar->documentation) !!}
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
</script>
@endsection