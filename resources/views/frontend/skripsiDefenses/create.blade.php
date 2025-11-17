@extends('layouts.frontend')
@section('content')
<link rel="stylesheet" href="{{ asset('css/modern-form.css') }}">
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="form-card">
                <div class="form-header">
                    <h2>Pendaftaran Sidang Skripsi</h2>
                    <p>Daftar sidang skripsi reguler Anda</p>
                </div>

                <form method="POST" action="{{ route("frontend.skripsi-defenses.store") }}" enctype="multipart/form-data">
                    @method('POST')
                    @csrf
                    
                    <div class="form-body">
                        <div class="info-box info">
                            <div class="info-box-title">Persyaratan Sidang Skripsi</div>
                            <div class="info-box-text">
                                <ul class="mb-0">
                                    <li>Skripsi sudah disetujui oleh dosen pembimbing</li>
                                    <li>Telah menyelesaikan seminar proposal dan seminar hasil</li>
                                    <li>Upload semua dokumen yang diperlukan dalam format PDF</li>
                                    <li>Pastikan semua dokumen sudah ditandatangani</li>
                                    <li>Hasil cek plagiarisme maksimal 20%</li>
                                </ul>
                            </div>
                        </div>

                        @if($activeApplication)
                            <input type="hidden" name="application_id" value="{{ $activeApplication->id }}">
                        @else
                            <div class="alert alert-warning mb-4">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Anda belum memiliki aplikasi skripsi yang aktif. Silakan buat aplikasi terlebih dahulu.
                            </div>
                        @endif

                        <!-- Informasi Dasar -->
                        <h5 class="section-title">Informasi Dasar Skripsi</h5>
                        
                        <div class="form-group">
                            <label for="title">{{ trans('cruds.skripsiDefense.fields.title') }} <span class="required">*</span></label>
                            <input class="form-control" type="text" name="title" id="title" value="{{ old('title', '') }}" placeholder="Masukkan judul skripsi" required>
                            @if($errors->has('title'))
                                <span class="text-danger small">{{ $errors->first('title') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.skripsiDefense.fields.title_helper') }}</span>
                        </div>

                        <div class="form-group">
                            <label for="abstract">{{ trans('cruds.skripsiDefense.fields.abstract') }} <span class="required">*</span></label>
                            <textarea class="form-control" name="abstract" id="abstract" rows="4" placeholder="Masukkan abstrak skripsi" required>{{ old('abstract', '') }}</textarea>
                            @if($errors->has('abstract'))
                                <span class="text-danger small">{{ $errors->first('abstract') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.skripsiDefense.fields.abstract_helper') }}</span>
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
                            <span class="help-block">{{ trans('cruds.skripsiDefense.fields.defence_document_helper') }}</span>
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
                            <span class="help-block">{{ trans('cruds.skripsiDefense.fields.plagiarism_report_helper') }}</span>
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
                            <span class="help-block">{{ trans('cruds.skripsiDefense.fields.ethics_statement_helper') }}</span>
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
                            <span class="help-block">{{ trans('cruds.skripsiDefense.fields.research_instruments_helper') }}</span>
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
                            <span class="help-block">{{ trans('cruds.skripsiDefense.fields.data_collection_letter_helper') }}</span>
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
                            <span class="help-block">{{ trans('cruds.skripsiDefense.fields.research_module_helper') }}</span>
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
                            <span class="help-block">{{ trans('cruds.skripsiDefense.fields.spp_receipt_helper') }}</span>
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
                            <span class="help-block">{{ trans('cruds.skripsiDefense.fields.krs_latest_helper') }}</span>
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
                            <span class="help-block">{{ trans('cruds.skripsiDefense.fields.eap_certificate_helper') }}</span>
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
                            <span class="help-block">{{ trans('cruds.skripsiDefense.fields.transcript_helper') }}</span>
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
                            <span class="help-block">{{ trans('cruds.skripsiDefense.fields.siakad_supervisor_screenshot_helper') }}</span>
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
                            <span class="help-block">{{ trans('cruds.skripsiDefense.fields.publication_statement_helper') }}</span>
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
                            <span class="help-block">{{ trans('cruds.skripsiDefense.fields.defense_approval_page_helper') }}</span>
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
                            <span class="help-block">{{ trans('cruds.skripsiDefense.fields.mbkm_recommendation_letter_helper') }}</span>
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
                            <span class="help-block">{{ trans('cruds.skripsiDefense.fields.mbkm_report_helper') }}</span>
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
                            <span class="help-block">{{ trans('cruds.skripsiDefense.fields.research_poster_helper') }}</span>
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
                            <span class="help-block">{{ trans('cruds.skripsiDefense.fields.supervision_logbook_helper') }}</span>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('frontend.skripsi-defenses.index') }}" class="btn-back">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                        <button type="submit" class="btn-submit" {{ !$activeApplication ? 'disabled' : '' }}>
                            <i class="fas fa-paper-plane mr-2"></i> Daftar Sidang
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
