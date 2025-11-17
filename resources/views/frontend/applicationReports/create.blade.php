@extends('layouts.frontend')
@section('content')
<style>
    .form-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    
    .form-header {
        background: linear-gradient(135deg, #22004C 0%, #4A0080 100%);
        color: white;
        padding: 2rem;
        text-align: center;
    }
    
    .form-header h2 {
        margin: 0 0 0.5rem;
        font-size: 1.75rem;
        font-weight: 600;
    }
    
    .form-header p {
        margin: 0;
        opacity: 0.9;
        font-size: 0.95rem;
    }
    
    .form-body {
        padding: 2.5rem 3rem;
    }
    
    .info-box {
        background: #ebf8ff;
        border-left: 4px solid #4299e1;
        padding: 1rem 1.25rem;
        border-radius: 6px;
        margin-bottom: 2rem;
    }
    
    .info-box-title {
        font-weight: 600;
        color: #2c5282;
        margin-bottom: 0.25rem;
    }
    
    .info-box-text {
        font-size: 0.9rem;
        color: #2d3748;
        margin: 0;
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
        min-height: 150px;
    }
    
    .dropzone:hover {
        border-color: #22004C;
        background: #edf2f7;
    }
    
    .dropzone .dz-message {
        font-size: 0.95rem;
        color: #718096;
        margin: 0;
    }
    
    .form-actions {
        display: flex;
        gap: 1rem;
        padding: 2rem 3rem;
        background: #f8f9fa;
        border-top: 1px solid #e9ecef;
    }
    
    .btn-submit {
        padding: 0.75rem 2rem;
        background: linear-gradient(135deg, #22004C 0%, #4A0080 100%);
        color: white;
        border-radius: 8px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 1rem;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(34, 0, 76, 0.4);
    }
    
    .btn-back {
        padding: 0.75rem 2rem;
        background: #e2e8f0;
        color: #4a5568;
        border-radius: 8px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 1rem;
        text-decoration: none;
    }
    
    .btn-back:hover {
        background: #cbd5e0;
        text-decoration: none;
        color: #4a5568;
    }
    
    textarea.form-control {
        min-height: 120px;
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="form-card">
                <!-- Header -->
                <div class="form-header">
                    <h2>Laporan Kendala</h2>
                    <p>Laporkan kendala atau masalah yang Anda hadapi selama proses skripsi</p>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route("frontend.application-reports.store") }}" enctype="multipart/form-data">
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

                        <div class="info-box">
                            <div class="info-box-title">Informasi Penting</div>
                            <div class="info-box-text">
                                Gunakan form ini untuk melaporkan kendala atau masalah yang Anda hadapi. Admin dan dosen pembimbing akan menerima laporan Anda dan memberikan bantuan yang diperlukan.
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="period">
                                Periode Laporan
                            </label>
                            <input class="form-control" type="text" name="period" id="period" value="{{ old('period', '') }}" placeholder="Contoh: Minggu ke-2 Bulan Januari 2024">
                            @if($errors->has('period'))
                                <span class="text-danger small">{{ $errors->first('period') }}</span>
                            @endif
                            <span class="help-block">Periode waktu terjadinya kendala</span>
                        </div>

                        <div class="form-group">
                            <label for="report_text">
                                Deskripsi Kendala <span class="required">*</span>
                            </label>
                            <textarea class="form-control ckeditor" name="report_text" id="report_text">{!! old('report_text') !!}</textarea>
                            @if($errors->has('report_text'))
                                <span class="text-danger small">{{ $errors->first('report_text') }}</span>
                            @endif
                            <span class="help-block">Jelaskan kendala yang Anda hadapi secara detail</span>
                        </div>

                        <div class="form-group">
                            <label for="report_document">
                                Dokumen Pendukung
                            </label>
                            <div class="needsclick dropzone" id="report_document-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>PDF, gambar, atau dokumen pendukung lainnya (maksimal 10 MB)</small>
                                </div>
                            </div>
                            @if($errors->has('report_document'))
                                <span class="text-danger small">{{ $errors->first('report_document') }}</span>
                            @endif
                            <span class="help-block">Upload dokumen pendukung jika ada (opsional)</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="form-actions">
                        <a href="{{ route('frontend.application-reports.index') }}" class="btn-back">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                        <button type="submit" class="btn-submit" {{ !$activeApplication ? 'disabled' : '' }}>
                            <i class="fas fa-paper-plane mr-2"></i> Kirim Laporan
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
    $(document).ready(function () {
  // Form validation for CKEditor
  $('form').on('submit', function(e) {
    var reportText = $('#report_text').val().trim();
    
    // Check if CKEditor content is empty (after stripping HTML tags)
    var tempDiv = document.createElement('div');
    tempDiv.innerHTML = reportText;
    var textContent = (tempDiv.textContent || tempDiv.innerText || '').trim();
    
    if (!textContent) {
      e.preventDefault();
      alert('Deskripsi Kendala harus diisi!');
      
      // Try to focus on CKEditor or fallback textarea
      var editorElement = document.querySelector('.ck-editor');
      if (editorElement) {
        editorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
      } else {
        $('#report_text').focus();
      }
      return false;
    }
  });

  function SimpleUploadAdapter(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
      return {
        upload: function() {
          return loader.file
            .then(function (file) {
              return new Promise(function(resolve, reject) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('frontend.application-reports.storeCKEditorImages') }}', true);
                xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.responseType = 'json';

                var genericErrorText = `Couldn't upload file: ${ file.name }.`;
                xhr.addEventListener('error', function() { reject(genericErrorText) });
                xhr.addEventListener('abort', function() { reject() });
                xhr.addEventListener('load', function() {
                  var response = xhr.response;

                  if (!response || xhr.status !== 201) {
                    return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                  }

                  $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');
                  resolve({ default: response.url });
                });

                if (xhr.upload) {
                  xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                      loader.uploadTotal = e.total;
                      loader.uploaded = e.loaded;
                    }
                  });
                }

                var data = new FormData();
                data.append('upload', file);
                data.append('crud_id', '{{ $applicationReport->id ?? 0 }}');
                xhr.send(data);
              });
            })
        }
      };
    }
  }

  var allEditors = document.querySelectorAll('.ckeditor');
  for (var i = 0; i < allEditors.length; ++i) {
    ClassicEditor.create(
      allEditors[i], {
        extraPlugins: [SimpleUploadAdapter]
      }
    ).catch(error => {
      console.error('CKEditor initialization error:', error);
      // Fallback: show the textarea if CKEditor fails to initialize
      allEditors[i].style.display = 'block';
      allEditors[i].style.minHeight = '200px';
    });
  }
});
</script>

<script>
    var uploadedReportDocumentMap = {}
Dropzone.options.reportDocumentDropzone = {
    url: '{{ route('frontend.application-reports.storeMedia') }}',
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
@if(isset($applicationReport) && $applicationReport->report_document)
          var files = {!! json_encode($applicationReport->report_document) !!}
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
</script>
@endsection