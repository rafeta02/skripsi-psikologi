@extends('layouts.frontend')
@section('content')
<link rel="stylesheet" href="{{ asset('css/modern-form.css') }}">
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="form-card">
                <div class="form-header">
                    <h2>Edit Pendaftaran Sidang Skripsi</h2>
                    <p>Perbarui data pendaftaran sidang skripsi Anda</p>
                </div>

                    <form method="POST" action="{{ route("frontend.skripsi-defenses.update", [$skripsiDefense->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                    
                    <div class="form-body">
                        <div class="info-box warning">
                            <div class="info-box-title">Mode Edit</div>
                            <div class="info-box-text">
                                Pastikan semua perubahan dokumen sudah dikonfirmasi dengan dosen pembimbing sebelum menyimpan.
                                </div>
                        </div>

                        @if($skripsiDefense->application)
                            <input type="hidden" name="application_id" value="{{ $skripsiDefense->application->id }}">
                            <div class="info-box info">
                                <div class="info-box-title"><i class="fas fa-info-circle"></i> Aplikasi Skripsi</div>
                                <div class="info-box-text">
                                    <div class="row">
                                        <div class="col-md-4"><strong>Type:</strong> <span class="badge badge-primary">{{ ucfirst($skripsiDefense->application->type) }}</span></div>
                                        <div class="col-md-4"><strong>Stage:</strong> <span class="badge badge-info">{{ ucfirst($skripsiDefense->application->stage) }}</span></div>
                                        <div class="col-md-4"><strong>Status:</strong> <span class="badge badge-success">{{ ucfirst($skripsiDefense->application->status) }}</span></div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Informasi Dasar -->
                        <h5 class="section-title">Informasi Dasar Skripsi</h5>
                        
                        <div class="form-group">
                            <label for="title">{{ trans('cruds.skripsiDefense.fields.title') }} <span class="required">*</span></label>
                            <input class="form-control" type="text" name="title" id="title" value="{{ old('title', $skripsiDefense->title) }}" required>
                            @if($errors->has('title'))
                                <span class="text-danger small">{{ $errors->first('title') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="abstract">{{ trans('cruds.skripsiDefense.fields.abstract') }} <span class="required">*</span></label>
                            <textarea class="form-control" name="abstract" id="abstract" rows="4" required>{{ old('abstract', $skripsiDefense->abstract) }}</textarea>
                            @if($errors->has('abstract'))
                                <span class="text-danger small">{{ $errors->first('abstract') }}</span>
                            @endif
                        </div>

                        <!-- Dokumen Sidang Utama -->
                        <h5 class="section-title mt-4">Dokumen Sidang Utama</h5>
                        
                        <div class="form-group">
                            <label for="defence_document">{{ trans('cruds.skripsiDefense.fields.defence_document') }} <span class="required">*</span></label>
                            <div class="needsclick dropzone" id="defence_document-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>PDF dokumen sidang (maksimal 25 MB)</small>
                                </div>
                            </div>
                            @if($errors->has('defence_document'))
                                <span class="text-danger small d-block mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('defence_document') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="plagiarism_report">{{ trans('cruds.skripsiDefense.fields.plagiarism_report') }} <span class="required">*</span></label>
                            <div class="needsclick dropzone" id="plagiarism_report-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>PDF laporan plagiarisme (maksimal 10 MB)</small>
                                </div>
                            </div>
                            @if($errors->has('plagiarism_report'))
                                <span class="text-danger small d-block mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('plagiarism_report') }}</span>
                            @endif
                        </div>

                        <!-- Dokumen Etika & Penelitian -->
                        <h5 class="section-title mt-4">Dokumen Etika & Penelitian</h5>
                        
                        <div class="form-group">
                            <label for="ethics_statement">{{ trans('cruds.skripsiDefense.fields.ethics_statement') }} <span class="required">*</span></label>
                            <div class="needsclick dropzone" id="ethics_statement-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>PDF pernyataan etika (maksimal 10 MB, multiple files)</small>
                                </div>
                            </div>
                            @if($errors->has('ethics_statement'))
                                <span class="text-danger small d-block mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('ethics_statement') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="research_instruments">{{ trans('cruds.skripsiDefense.fields.research_instruments') }} <span class="required">*</span></label>
                            <div class="needsclick dropzone" id="research_instruments-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>PDF instrumen penelitian (maksimal 10 MB, multiple files)</small>
                                </div>
                            </div>
                            @if($errors->has('research_instruments'))
                                <span class="text-danger small d-block mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('research_instruments') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="data_collection_letter">{{ trans('cruds.skripsiDefense.fields.data_collection_letter') }} <span class="required">*</span></label>
                            <div class="needsclick dropzone" id="data_collection_letter-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>PDF surat izin (maksimal 10 MB, multiple files)</small>
                                </div>
                            </div>
                            @if($errors->has('data_collection_letter'))
                                <span class="text-danger small d-block mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('data_collection_letter') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="research_module">{{ trans('cruds.skripsiDefense.fields.research_module') }} <span class="required">*</span></label>
                            <div class="needsclick dropzone" id="research_module-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>PDF modul penelitian (maksimal 10 MB, multiple files)</small>
                                </div>
                            </div>
                            @if($errors->has('research_module'))
                                <span class="text-danger small d-block mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('research_module') }}</span>
                            @endif
                        </div>

                        <!-- Dokumen Akademik -->
                        <h5 class="section-title mt-4">Dokumen Akademik</h5>
                        
                        <div class="form-group">
                            <label for="spp_receipt">{{ trans('cruds.skripsiDefense.fields.spp_receipt') }} <span class="required">*</span></label>
                            <div class="needsclick dropzone" id="spp_receipt-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>PDF bukti pembayaran SPP (maksimal 10 MB)</small>
                                </div>
                            </div>
                            @if($errors->has('spp_receipt'))
                                <span class="text-danger small d-block mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('spp_receipt') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="krs_latest">{{ trans('cruds.skripsiDefense.fields.krs_latest') }} <span class="required">*</span></label>
                            <div class="needsclick dropzone" id="krs_latest-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>PDF KRS terbaru (maksimal 10 MB)</small>
                                </div>
                            </div>
                            @if($errors->has('krs_latest'))
                                <span class="text-danger small d-block mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('krs_latest') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="eap_certificate">{{ trans('cruds.skripsiDefense.fields.eap_certificate') }} <span class="required">*</span></label>
                            <div class="needsclick dropzone" id="eap_certificate-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>PDF sertifikat EAP (maksimal 10 MB)</small>
                                </div>
                            </div>
                            @if($errors->has('eap_certificate'))
                                <span class="text-danger small d-block mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('eap_certificate') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="transcript">{{ trans('cruds.skripsiDefense.fields.transcript') }} <span class="required">*</span></label>
                            <div class="needsclick dropzone" id="transcript-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>PDF transkrip nilai (maksimal 10 MB)</small>
                                </div>
                            </div>
                            @if($errors->has('transcript'))
                                <span class="text-danger small d-block mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('transcript') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="siakad_supervisor_screenshot">{{ trans('cruds.skripsiDefense.fields.siakad_supervisor_screenshot') }} <span class="required">*</span></label>
                            <div class="needsclick dropzone" id="siakad_supervisor_screenshot-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>PDF/Image screenshot SIAKAD (maksimal 10 MB)</small>
                                </div>
                            </div>
                            @if($errors->has('siakad_supervisor_screenshot'))
                                <span class="text-danger small d-block mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('siakad_supervisor_screenshot') }}</span>
                            @endif
                        </div>

                        <!-- Dokumen Persetujuan -->
                        <h5 class="section-title mt-4">Dokumen Persetujuan & Pernyataan</h5>
                        
                        <div class="form-group">
                            <label for="publication_statement">{{ trans('cruds.skripsiDefense.fields.publication_statement') }} <span class="required">*</span></label>
                            <div class="needsclick dropzone" id="publication_statement-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>PDF pernyataan publikasi (maksimal 10 MB)</small>
                                </div>
                            </div>
                            @if($errors->has('publication_statement'))
                                <span class="text-danger small d-block mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('publication_statement') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="defense_approval_page">{{ trans('cruds.skripsiDefense.fields.defense_approval_page') }} <span class="required">*</span></label>
                            <div class="needsclick dropzone" id="defense_approval_page-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>PDF halaman persetujuan (maksimal 10 MB, multiple files)</small>
                                </div>
                            </div>
                            @if($errors->has('defense_approval_page'))
                                <span class="text-danger small d-block mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('defense_approval_page') }}</span>
                            @endif
                        </div>

                        <!-- Dokumen MBKM (Optional) -->
                        <h5 class="section-title mt-4">Dokumen MBKM <span class="badge badge-secondary ml-2">Opsional</span></h5>
                        
                        <div class="form-group">
                            <label for="mbkm_recommendation_letter">{{ trans('cruds.skripsiDefense.fields.mbkm_recommendation_letter') }}</label>
                            <div class="needsclick dropzone" id="mbkm_recommendation_letter-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>PDF surat rekomendasi MBKM (maksimal 10 MB)</small>
                                </div>
                            </div>
                            @if($errors->has('mbkm_recommendation_letter'))
                                <span class="text-danger small d-block mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('mbkm_recommendation_letter') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="mbkm_report">{{ trans('cruds.skripsiDefense.fields.mbkm_report') }}</label>
                            <div class="needsclick dropzone" id="mbkm_report-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>PDF laporan MBKM (maksimal 10 MB, multiple files)</small>
                                </div>
                            </div>
                            @if($errors->has('mbkm_report'))
                                <span class="text-danger small d-block mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('mbkm_report') }}</span>
                            @endif
                        </div>

                        <!-- Dokumen Pendukung -->
                        <h5 class="section-title mt-4">Dokumen Pendukung Lainnya</h5>
                        
                        <div class="form-group">
                            <label for="research_poster">{{ trans('cruds.skripsiDefense.fields.research_poster') }} <span class="required">*</span></label>
                            <div class="needsclick dropzone" id="research_poster-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>PDF/Image poster penelitian (maksimal 10 MB, multiple files)</small>
                                </div>
                            </div>
                            @if($errors->has('research_poster'))
                                <span class="text-danger small d-block mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('research_poster') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="supervision_logbook">{{ trans('cruds.skripsiDefense.fields.supervision_logbook') }} <span class="required">*</span></label>
                            <div class="needsclick dropzone" id="supervision_logbook-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>PDF logbook bimbingan (maksimal 10 MB, multiple files)</small>
                                </div>
                            </div>
                            @if($errors->has('supervision_logbook'))
                                <span class="text-danger small d-block mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('supervision_logbook') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('frontend.skripsi-defenses.index') }}" class="btn-back">
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
// Single file dropzones
var singleFileDropzones = [
    { id: 'defence_document', name: 'defence_document', maxSize: 25 },
    { id: 'plagiarism_report', name: 'plagiarism_report', maxSize: 10 },
    { id: 'mbkm_recommendation_letter', name: 'mbkm_recommendation_letter', maxSize: 10 },
    { id: 'publication_statement', name: 'publication_statement', maxSize: 10 },
    { id: 'spp_receipt', name: 'spp_receipt', maxSize: 10 },
    { id: 'krs_latest', name: 'krs_latest', maxSize: 10 },
    { id: 'eap_certificate', name: 'eap_certificate', maxSize: 10 },
    { id: 'transcript', name: 'transcript', maxSize: 10 },
    { id: 'siakad_supervisor_screenshot', name: 'siakad_supervisor_screenshot', maxSize: 10 }
];

singleFileDropzones.forEach(function(config) {
    var camelCaseId = config.id.replace(/_([a-z])/g, function(g) { return g[1].toUpperCase(); });
    Dropzone.options[camelCaseId + 'Dropzone'] = {
    url: '{{ route('frontend.skripsi-defenses.storeMedia') }}',
        maxFilesize: config.maxSize,
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
            size: config.maxSize
    },
    success: function (file, response) {
            $('form').find('input[name="' + config.name + '"]').remove()
            $('form').append('<input type="hidden" name="' + config.name + '" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
                $('form').find('input[name="' + config.name + '"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
            var existingFiles = {
                @if(isset($skripsiDefense) && $skripsiDefense->defence_document)
                'defence_document': {!! json_encode($skripsiDefense->defence_document) !!},
                @endif
                @if(isset($skripsiDefense) && $skripsiDefense->plagiarism_report)
                'plagiarism_report': {!! json_encode($skripsiDefense->plagiarism_report) !!},
                @endif
                @if(isset($skripsiDefense) && $skripsiDefense->mbkm_recommendation_letter)
                'mbkm_recommendation_letter': {!! json_encode($skripsiDefense->mbkm_recommendation_letter) !!},
                @endif
                @if(isset($skripsiDefense) && $skripsiDefense->publication_statement)
                'publication_statement': {!! json_encode($skripsiDefense->publication_statement) !!},
                @endif
                @if(isset($skripsiDefense) && $skripsiDefense->spp_receipt)
                'spp_receipt': {!! json_encode($skripsiDefense->spp_receipt) !!},
                @endif
                @if(isset($skripsiDefense) && $skripsiDefense->krs_latest)
                'krs_latest': {!! json_encode($skripsiDefense->krs_latest) !!},
                @endif
                @if(isset($skripsiDefense) && $skripsiDefense->eap_certificate)
                'eap_certificate': {!! json_encode($skripsiDefense->eap_certificate) !!},
                @endif
                @if(isset($skripsiDefense) && $skripsiDefense->transcript)
                'transcript': {!! json_encode($skripsiDefense->transcript) !!},
                @endif
                @if(isset($skripsiDefense) && $skripsiDefense->siakad_supervisor_screenshot)
                'siakad_supervisor_screenshot': {!! json_encode($skripsiDefense->siakad_supervisor_screenshot) !!},
                @endif
            };
            
            if (existingFiles[config.name]) {
                var file = existingFiles[config.name];
                this.options.addedfile.call(this, file);
                file.previewElement.classList.add('dz-complete');
                $('form').append('<input type="hidden" name="' + config.name + '" value="' + file.file_name + '">');
                this.options.maxFiles = this.options.maxFiles - 1;
            }
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
                var message = response
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
            var _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
            var _results = []
            for (var _i = 0, _len = _ref.length; _i < _len; _i++) {
                var node = _ref[_i]
             _results.push(node.textContent = message)
            }
         return _results
     }
    };
});

// Multiple file dropzones
var multipleFileDropzones = [
    'ethics_statement',
    'research_instruments',
    'data_collection_letter',
    'research_module',
    'defense_approval_page',
    'mbkm_report',
    'research_poster',
    'supervision_logbook'
];

// Prepare existing files data for multiple file uploads
var existingMultipleFiles = {
    'ethics_statement': @json($skripsiDefense->ethics_statement ?? []),
    'research_instruments': @json($skripsiDefense->research_instruments ?? []),
    'data_collection_letter': @json($skripsiDefense->data_collection_letter ?? []),
    'research_module': @json($skripsiDefense->research_module ?? []),
    'defense_approval_page': @json($skripsiDefense->defense_approval_page ?? []),
    'mbkm_report': @json($skripsiDefense->mbkm_report ?? []),
    'research_poster': @json($skripsiDefense->research_poster ?? []),
    'supervision_logbook': @json($skripsiDefense->supervision_logbook ?? [])
};

multipleFileDropzones.forEach(function(fieldName) {
    var uploadedMap = {};
    var camelCaseId = fieldName.replace(/_([a-z])/g, function(g) { return g[1].toUpperCase(); });
    
    Dropzone.options[camelCaseId + 'Dropzone'] = {
    url: '{{ route('frontend.skripsi-defenses.storeMedia') }}',
        maxFilesize: 10,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10
    },
    success: function (file, response) {
            $('form').append('<input type="hidden" name="' + fieldName + '[]" value="' + response.name + '">')
            uploadedMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
                name = uploadedMap[file.name]
            }
            $('form').find('input[name="' + fieldName + '[]"][value="' + name + '"]').remove()
    },
    init: function () {
            var files = existingMultipleFiles[fieldName] || [];
            if (files && files.length) {
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
                    $('form').append('<input type="hidden" name="' + fieldName + '[]" value="' + file.file_name + '">')
                }
            }
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
                var message = response
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
            var _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
            var _results = []
            for (var _i = 0, _len = _ref.length; _i < _len; _i++) {
                var node = _ref[_i]
             _results.push(node.textContent = message)
            }
         return _results
     }
    };
});
</script>
@endsection
