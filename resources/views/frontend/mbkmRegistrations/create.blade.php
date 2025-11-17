@extends('layouts.frontend')
@section('content')
<link rel="stylesheet" href="{{ asset('css/modern-form.css') }}">
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="form-card">
                <div class="form-header">
                    <h2>Pendaftaran Skripsi MBKM</h2>
                    <p>Lengkapi formulir pendaftaran skripsi program MBKM dengan data yang akurat</p>
                </div>

                <form method="POST" action="{{ route("frontend.mbkm-registrations.store") }}" enctype="multipart/form-data">
                    @method('POST')
                    @csrf
                    
                    <div class="form-body">
                        <div class="info-box info">
                            <div class="info-box-title">Informasi Pendaftaran MBKM</div>
                            <div class="info-box-text">
                                <ul class="mb-0">
                                    <li><strong>Semua dokumen WAJIB diupload</strong> (bertanda <span class="text-danger">*</span>)</li>
                                    <li>Pastikan semua dokumen dalam format PDF</li>
                                    <li>Pilih dosen pembimbing sesuai dengan bidang keilmuan yang diminati</li>
                                    <li>Maksimal ukuran file: 10 MB per dokumen</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Informasi Dasar -->
                        <h3 class="form-section-title">Informasi Dasar</h3>

                        <div class="form-group">
                            <label for="research_group_id">Kelompok Riset</label>
                            <select class="form-control select2" name="research_group_id" id="research_group_id">
                                <option value="">-- Pilih Kelompok Riset --</option>
                                @foreach($research_groups as $id => $entry)
                                    <option value="{{ $id }}" {{ old('research_group_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('research_group'))
                                <span class="text-danger small">{{ $errors->first('research_group') }}</span>
                            @endif
                            <span class="help-block">Pilih kelompok riset MBKM yang akan diikuti</span>
                        </div>

                        <div class="form-group">
                            <label for="preference_supervision_id">Dosen Pembimbing Pilihan</label>
                            <select class="form-control select2" name="preference_supervision_id" id="preference_supervision_id">
                                <option value="">-- Pilih Dosen Pembimbing --</option>
                                @foreach($preference_supervisions as $id => $entry)
                                    <option value="{{ $id }}" {{ old('preference_supervision_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('preference_supervision'))
                                <span class="text-danger small">{{ $errors->first('preference_supervision') }}</span>
                            @endif
                            <span class="help-block">Pilih dosen pembimbing sesuai bidang keilmuan</span>
                        </div>

                        <div class="form-group">
                            <label for="theme_id">Tema Penelitian</label>
                            <select class="form-control select2" name="theme_id" id="theme_id">
                                <option value="">-- Pilih Tema --</option>
                                @foreach($themes as $id => $entry)
                                    <option value="{{ $id }}" {{ old('theme_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('theme'))
                                <span class="text-danger small">{{ $errors->first('theme') }}</span>
                            @endif
                            <span class="help-block">Pilih tema penelitian yang sesuai</span>
                        </div>

                        <!-- Judul Penelitian -->
                        <h3 class="form-section-title">Judul Penelitian</h3>

                        <div class="form-group">
                            <label for="title_mbkm">Judul Kegiatan MBKM</label>
                            <input class="form-control" type="text" name="title_mbkm" id="title_mbkm" value="{{ old('title_mbkm', '') }}" placeholder="Masukkan judul kegiatan MBKM">
                            @if($errors->has('title_mbkm'))
                                <span class="text-danger small">{{ $errors->first('title_mbkm') }}</span>
                            @endif
                            <span class="help-block">Judul kegiatan MBKM yang telah dilaksanakan</span>
                        </div>

                        <div class="form-group">
                            <label for="title">Judul Skripsi</label>
                            <input class="form-control" type="text" name="title" id="title" value="{{ old('title', '') }}" placeholder="Masukkan judul skripsi">
                            @if($errors->has('title'))
                                <span class="text-danger small">{{ $errors->first('title') }}</span>
                            @endif
                            <span class="help-block">Judul skripsi yang akan diajukan</span>
                        </div>

                        <!-- Anggota Kelompok MBKM -->
                        <h3 class="form-section-title">Anggota Kelompok MBKM</h3>

                        <div class="info-box info mb-3">
                            <div class="info-box-text">
                                Tambahkan anggota kelompok MBKM. Pilih mahasiswa dan tentukan perannya (Ketua/Anggota).
                            </div>
                        </div>

                        <div id="group-members-container">
                            <div class="group-member-item mb-3 p-3" style="border: 1px solid #e2e8f0; border-radius: 8px; background: #f8fafc;">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group mb-0">
                                            <label>Mahasiswa</label>
                                            <select class="form-control select2" name="group_members[0][mahasiswa_id]">
                                                <option value="">-- Pilih Mahasiswa --</option>
                                                @foreach($mahasiswas as $id => $entry)
                                                    <option value="{{ $id }}">{{ $entry }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-0">
                                            <label>Peran</label>
                                            <select class="form-control" name="group_members[0][role]">
                                                <option value="">-- Pilih Peran --</option>
                                                <option value="ketua">Ketua</option>
                                                <option value="anggota">Anggota</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger btn-sm remove-member" style="display: none;">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <button type="button" id="add-member" class="btn btn-success btn-sm">
                                <i class="fas fa-plus"></i> Tambah Anggota
                            </button>
                        </div>

                        <!-- Dokumen Akademik -->
                        <h3 class="form-section-title">Dokumen Akademik</h3>

                        <div class="form-group">
                            <label for="khs_all">KHS Semua Semester <span class="text-danger">*</span></label>
                            <div class="needsclick dropzone" id="khs_all-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>PDF KHS semua semester (maksimal 10 MB)</small>
                                </div>
                            </div>
                            @if($errors->has('khs_all'))
                                <span class="text-danger small">{{ $errors->first('khs_all') }}</span>
                            @endif
                            <span class="help-block">Upload KHS dari semester 1 hingga terakhir</span>
                        </div>

                        <div class="form-group">
                            <label for="krs_latest">KRS Semester Terakhir <span class="text-danger">*</span></label>
                            <div class="needsclick dropzone" id="krs_latest-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>PDF KRS semester terakhir (maksimal 10 MB)</small>
                                </div>
                            </div>
                            @if($errors->has('krs_latest'))
                                <span class="text-danger small">{{ $errors->first('krs_latest') }}</span>
                            @endif
                            <span class="help-block">Upload KRS semester yang sedang berjalan</span>
                        </div>

                        <div class="form-group">
                            <label for="spp">Bukti Pembayaran SPP <span class="text-danger">*</span></label>
                            <div class="needsclick dropzone" id="spp-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>PDF bukti pembayaran SPP (maksimal 10 MB)</small>
                                </div>
                            </div>
                            @if($errors->has('spp'))
                                <span class="text-danger small">{{ $errors->first('spp') }}</span>
                            @endif
                            <span class="help-block">Upload bukti pembayaran SPP terakhir</span>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="total_sks_taken">Total SKS yang Telah Diambil</label>
                                    <input class="form-control" type="number" name="total_sks_taken" id="total_sks_taken" value="{{ old('total_sks_taken', '') }}" step="1" placeholder="Contoh: 120">
                                    @if($errors->has('total_sks_taken'))
                                        <span class="text-danger small">{{ $errors->first('total_sks_taken') }}</span>
                                    @endif
                                    <span class="help-block">Jumlah SKS yang sudah diambil</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sks_mkp_taken">Jumlah SKS MKP yang Sudah Diambil</label>
                                    <input class="form-control" type="number" name="sks_mkp_taken" id="sks_mkp_taken" value="{{ old('sks_mkp_taken', '') }}" step="1" placeholder="Contoh: 12">
                                    @if($errors->has('sks_mkp_taken'))
                                        <span class="text-danger small">{{ $errors->first('sks_mkp_taken') }}</span>
                                    @endif
                                    <span class="help-block">Jumlah SKS Mata Kuliah Prodi (MKP) yang telah diambil</span>
                                </div>
                            </div>
                        </div>

                        <!-- Nilai Mata Kuliah -->
                        <h3 class="form-section-title">Nilai Mata Kuliah</h3>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nilai_mk_kuantitatif">Nilai MK Kuantitatif</label>
                                    <input class="form-control" type="text" name="nilai_mk_kuantitatif" id="nilai_mk_kuantitatif" value="{{ old('nilai_mk_kuantitatif', '') }}" placeholder="Contoh: A">
                                    @if($errors->has('nilai_mk_kuantitatif'))
                                        <span class="text-danger small">{{ $errors->first('nilai_mk_kuantitatif') }}</span>
                                    @endif
                                    <span class="help-block">Nilai mata kuliah Kuantitatif</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nilai_mk_kualitatif">Nilai MK Kualitatif</label>
                                    <input class="form-control" type="text" name="nilai_mk_kualitatif" id="nilai_mk_kualitatif" value="{{ old('nilai_mk_kualitatif', '') }}" placeholder="Contoh: A">
                                    @if($errors->has('nilai_mk_kualitatif'))
                                        <span class="text-danger small">{{ $errors->first('nilai_mk_kualitatif') }}</span>
                                    @endif
                                    <span class="help-block">Nilai mata kuliah Kualitatif</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nilai_mk_statistika_dasar">Nilai MK Statistika Dasar</label>
                                    <input class="form-control" type="text" name="nilai_mk_statistika_dasar" id="nilai_mk_statistika_dasar" value="{{ old('nilai_mk_statistika_dasar', '') }}" placeholder="Contoh: A">
                                    @if($errors->has('nilai_mk_statistika_dasar'))
                                        <span class="text-danger small">{{ $errors->first('nilai_mk_statistika_dasar') }}</span>
                                    @endif
                                    <span class="help-block">Nilai mata kuliah Statistika Dasar</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nilai_mk_statistika_lanjutan">Nilai MK Statistika Lanjutan</label>
                                    <input class="form-control" type="text" name="nilai_mk_statistika_lanjutan" id="nilai_mk_statistika_lanjutan" value="{{ old('nilai_mk_statistika_lanjutan', '') }}" placeholder="Contoh: A">
                                    @if($errors->has('nilai_mk_statistika_lanjutan'))
                                        <span class="text-danger small">{{ $errors->first('nilai_mk_statistika_lanjutan') }}</span>
                                    @endif
                                    <span class="help-block">Nilai mata kuliah Statistika Lanjutan</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nilai_mk_konstruksi_tes">Nilai MK Konstruksi Tes</label>
                                    <input class="form-control" type="text" name="nilai_mk_konstruksi_tes" id="nilai_mk_konstruksi_tes" value="{{ old('nilai_mk_konstruksi_tes', '') }}" placeholder="Contoh: A">
                                    @if($errors->has('nilai_mk_konstruksi_tes'))
                                        <span class="text-danger small">{{ $errors->first('nilai_mk_konstruksi_tes') }}</span>
                                    @endif
                                    <span class="help-block">Nilai mata kuliah Konstruksi Tes</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nilai_mk_tps">Nilai MK TPS</label>
                                    <input class="form-control" type="text" name="nilai_mk_tps" id="nilai_mk_tps" value="{{ old('nilai_mk_tps', '') }}" placeholder="Contoh: A">
                                    @if($errors->has('nilai_mk_tps'))
                                        <span class="text-danger small">{{ $errors->first('nilai_mk_tps') }}</span>
                                    @endif
                                    <span class="help-block">Nilai mata kuliah TPS (Tes Psikologi)</span>
                                </div>
                            </div>
                        </div>

                        <!-- Dokumen MBKM -->
                        <h3 class="form-section-title">Dokumen MBKM</h3>

                        <div class="form-group">
                            <label for="proposal_mbkm">Proposal MBKM <span class="text-danger">*</span></label>
                            <div class="needsclick dropzone" id="proposal_mbkm-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>PDF proposal MBKM (maksimal 10 MB)</small>
                                </div>
                            </div>
                            @if($errors->has('proposal_mbkm'))
                                <span class="text-danger small">{{ $errors->first('proposal_mbkm') }}</span>
                            @endif
                            <span class="help-block">Upload proposal kegiatan MBKM</span>
                        </div>

                        <div class="form-group">
                            <label for="recognition_form">Form Konversi SKS <span class="text-danger">*</span></label>
                            <div class="needsclick dropzone" id="recognition_form-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>PDF form konversi SKS (maksimal 10 MB)</small>
                                </div>
                            </div>
                            @if($errors->has('recognition_form'))
                                <span class="text-danger small">{{ $errors->first('recognition_form') }}</span>
                            @endif
                            <span class="help-block">Upload form konversi SKS MBKM</span>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('frontend.mbkm-registrations.index') }}" class="btn-back">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-paper-plane mr-2"></i> Daftar Sekarang
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
// Dropzone configurations for all file uploads
var uploadedKhsAllMap = {}
Dropzone.options.khsAllDropzone = {
    url: '{{ route('frontend.mbkm-registrations.storeMedia') }}',
    maxFilesize: 10,
    addRemoveLinks: true,
    headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
    params: { size: 10 },
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
@if(isset($mbkmRegistration) && $mbkmRegistration->khs_all)
          var files = {!! json_encode($mbkmRegistration->khs_all) !!}
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

// KRS Latest (single file)
Dropzone.options.krsLatestDropzone = {
    url: '{{ route('frontend.mbkm-registrations.storeMedia') }}',
    maxFilesize: 10,
    maxFiles: 1,
    addRemoveLinks: true,
    headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
    params: { size: 10 },
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
@if(isset($mbkmRegistration) && $mbkmRegistration->krs_latest)
      var file = {!! json_encode($mbkmRegistration->krs_latest) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="krs_latest" value="' + file.file_name + '">')
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

// SPP (single file)
Dropzone.options.sppDropzone = {
    url: '{{ route('frontend.mbkm-registrations.storeMedia') }}',
    maxFilesize: 10,
    maxFiles: 1,
    addRemoveLinks: true,
    headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
    params: { size: 10 },
    success: function (file, response) {
      $('form').find('input[name="spp"]').remove()
      $('form').append('<input type="hidden" name="spp" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="spp"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($mbkmRegistration) && $mbkmRegistration->spp)
      var file = {!! json_encode($mbkmRegistration->spp) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="spp" value="' + file.file_name + '">')
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

// Proposal MBKM (single file)
Dropzone.options.proposalMbkmDropzone = {
    url: '{{ route('frontend.mbkm-registrations.storeMedia') }}',
    maxFilesize: 10,
    maxFiles: 1,
    addRemoveLinks: true,
    headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
    params: { size: 10 },
    success: function (file, response) {
      $('form').find('input[name="proposal_mbkm"]').remove()
      $('form').append('<input type="hidden" name="proposal_mbkm" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="proposal_mbkm"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($mbkmRegistration) && $mbkmRegistration->proposal_mbkm)
      var file = {!! json_encode($mbkmRegistration->proposal_mbkm) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="proposal_mbkm" value="' + file.file_name + '">')
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

// Recognition Form (single file)
Dropzone.options.recognitionFormDropzone = {
    url: '{{ route('frontend.mbkm-registrations.storeMedia') }}',
    maxFilesize: 10,
    maxFiles: 1,
    addRemoveLinks: true,
    headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
    params: { size: 10 },
    success: function (file, response) {
      $('form').find('input[name="recognition_form"]').remove()
      $('form').append('<input type="hidden" name="recognition_form" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="recognition_form"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($mbkmRegistration) && $mbkmRegistration->recognition_form)
      var file = {!! json_encode($mbkmRegistration->recognition_form) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="recognition_form" value="' + file.file_name + '">')
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

// Dynamic Group Members Management
let memberIndex = 1;

$('#add-member').on('click', function() {
    const newMember = `
        <div class="group-member-item mb-3 p-3" style="border: 1px solid #e2e8f0; border-radius: 8px; background: #f8fafc;">
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group mb-0">
                        <label>Mahasiswa</label>
                        <select class="form-control select2" name="group_members[${memberIndex}][mahasiswa_id]">
                            <option value="">-- Pilih Mahasiswa --</option>
                            @foreach($mahasiswas as $id => $entry)
                                <option value="{{ $id }}">{{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-0">
                        <label>Peran</label>
                        <select class="form-control" name="group_members[${memberIndex}][role]">
                            <option value="">-- Pilih Peran --</option>
                            <option value="ketua">Ketua</option>
                            <option value="anggota">Anggota</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-member">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    `;
    
    $('#group-members-container').append(newMember);
    
    // Re-initialize select2 for new element
    $('#group-members-container').find('.select2').last().select2();
    
    memberIndex++;
    updateRemoveButtons();
});

$(document).on('click', '.remove-member', function() {
    $(this).closest('.group-member-item').remove();
    updateRemoveButtons();
});

function updateRemoveButtons() {
    const memberCount = $('.group-member-item').length;
    if (memberCount > 1) {
        $('.remove-member').show();
    } else {
        $('.remove-member').hide();
    }
}

// Initialize on page load
$(document).ready(function() {
    updateRemoveButtons();
});
</script>
@endsection