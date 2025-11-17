@extends('layouts.frontend')
@section('content')
<link rel="stylesheet" href="{{ asset('css/modern-form.css') }}">
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="form-card">
                <div class="form-header">
                    <h2>Pendaftaran Seminar Proposal MBKM</h2>
                    <p>Daftar seminar proposal untuk program MBKM Anda</p>
                </div>

                <form method="POST" action="{{ route("frontend.mbkm-seminars.store") }}" enctype="multipart/form-data">
                    @method('POST')
                    @csrf
                    
                    <div class="form-body">
                        <div class="info-box info">
                            <div class="info-box-title">Persyaratan Seminar Proposal MBKM</div>
                            <div class="info-box-text">
                                <ul class="mb-0">
                                    <li>Proposal sudah disetujui oleh dosen pembimbing</li>
                                    <li>Upload dokumen proposal dalam format PDF (maksimal 25 MB)</li>
                                    <li>Sertakan form persetujuan yang sudah ditandatangani</li>
                                    <li>Lampirkan hasil cek plagiarisme (maksimal 20%)</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Informasi Aplikasi Aktif -->
                        <div class="info-box success mb-4">
                            <div class="info-box-title">
                                <i class="fas fa-check-circle mr-2"></i> Aplikasi MBKM Aktif
                            </div>
                            <div class="info-box-text">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Mahasiswa:</strong> {{ $activeApplication->mahasiswa->nama ?? '-' }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>NIM:</strong> {{ $activeApplication->mahasiswa->nim ?? '-' }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Type:</strong> <span class="badge badge-info">{{ strtoupper($activeApplication->type) }}</span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Status:</strong> <span class="badge badge-success">{{ ucfirst($activeApplication->status) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="title">Judul Proposal <span class="required">*</span></label>
                            <input class="form-control" type="text" name="title" id="title" value="{{ old('title', '') }}" placeholder="Masukkan judul proposal skripsi" required>
                            @if($errors->has('title'))
                                <span class="text-danger small">{{ $errors->first('title') }}</span>
                            @endif
                            <span class="help-block">Judul proposal yang akan dipresentasikan</span>
                        </div>

                        <div class="form-group">
                            <label for="proposal_document">Dokumen Proposal <span class="required">*</span></label>
                            <div class="needsclick dropzone" id="proposal_document-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>PDF dokumen proposal (maksimal 25 MB)</small>
                                </div>
                            </div>
                            @if($errors->has('proposal_document'))
                                <span class="text-danger small">{{ $errors->first('proposal_document') }}</span>
                            @endif
                            <span class="help-block">Upload dokumen proposal skripsi lengkap</span>
                        </div>

                        <div class="form-group">
                            <label for="approval_document">Form Persetujuan Pembimbing <span class="required">*</span></label>
                            <div class="needsclick dropzone" id="approval_document-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>PDF form persetujuan (maksimal 10 MB)</small>
                                </div>
                            </div>
                            @if($errors->has('approval_document'))
                                <span class="text-danger small">{{ $errors->first('approval_document') }}</span>
                            @endif
                            <span class="help-block">Upload form persetujuan yang sudah ditandatangani dosen pembimbing</span>
                        </div>

                        <div class="form-group">
                            <label for="plagiarism_document">Hasil Cek Plagiarisme <span class="required">*</span></label>
                            <div class="needsclick dropzone" id="plagiarism_document-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>PDF hasil cek plagiarisme (maksimal 10 MB)</small>
                                </div>
                            </div>
                            @if($errors->has('plagiarism_document'))
                                <span class="text-danger small">{{ $errors->first('plagiarism_document') }}</span>
                            @endif
                            <span class="help-block">Upload hasil cek plagiarisme dari Turnitin/software sejenis (maksimal 20%)</span>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('frontend.mbkm-seminars.index') }}" class="btn-back">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-paper-plane mr-2"></i> Daftar Seminar
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
Dropzone.options.proposalDocumentDropzone = {
    url: '{{ route('frontend.mbkm-seminars.storeMedia') }}',
    maxFilesize: 25,
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 25
    },
    success: function (file, response) {
      $('form').find('input[name="proposal_document"]').remove()
      $('form').append('<input type="hidden" name="proposal_document" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="proposal_document"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($mbkmSeminar) && $mbkmSeminar->proposal_document)
      var file = {!! json_encode($mbkmSeminar->proposal_document) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="proposal_document" value="' + file.file_name + '">')
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

Dropzone.options.approvalDocumentDropzone = {
    url: '{{ route('frontend.mbkm-seminars.storeMedia') }}',
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
      $('form').find('input[name="approval_document"]').remove()
      $('form').append('<input type="hidden" name="approval_document" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="approval_document"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($mbkmSeminar) && $mbkmSeminar->approval_document)
      var file = {!! json_encode($mbkmSeminar->approval_document) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="approval_document" value="' + file.file_name + '">')
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

Dropzone.options.plagiarismDocumentDropzone = {
    url: '{{ route('frontend.mbkm-seminars.storeMedia') }}',
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
      $('form').find('input[name="plagiarism_document"]').remove()
      $('form').append('<input type="hidden" name="plagiarism_document" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="plagiarism_document"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($mbkmSeminar) && $mbkmSeminar->plagiarism_document)
      var file = {!! json_encode($mbkmSeminar->plagiarism_document) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="plagiarism_document" value="' + file.file_name + '">')
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
@endsection