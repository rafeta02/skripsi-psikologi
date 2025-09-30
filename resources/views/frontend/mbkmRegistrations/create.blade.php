@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.mbkmRegistration.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.mbkm-registrations.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label for="application_id">{{ trans('cruds.mbkmRegistration.fields.application') }}</label>
                            <select class="form-control select2" name="application_id" id="application_id">
                                @foreach($applications as $id => $entry)
                                    <option value="{{ $id }}" {{ old('application_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('application'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('application') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mbkmRegistration.fields.application_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="research_group_id">{{ trans('cruds.mbkmRegistration.fields.research_group') }}</label>
                            <select class="form-control select2" name="research_group_id" id="research_group_id">
                                @foreach($research_groups as $id => $entry)
                                    <option value="{{ $id }}" {{ old('research_group_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('research_group'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('research_group') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mbkmRegistration.fields.research_group_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="preference_supervision_id">{{ trans('cruds.mbkmRegistration.fields.preference_supervision') }}</label>
                            <select class="form-control select2" name="preference_supervision_id" id="preference_supervision_id">
                                @foreach($preference_supervisions as $id => $entry)
                                    <option value="{{ $id }}" {{ old('preference_supervision_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('preference_supervision'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('preference_supervision') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mbkmRegistration.fields.preference_supervision_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="theme_id">{{ trans('cruds.mbkmRegistration.fields.theme') }}</label>
                            <select class="form-control select2" name="theme_id" id="theme_id">
                                @foreach($themes as $id => $entry)
                                    <option value="{{ $id }}" {{ old('theme_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('theme'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('theme') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mbkmRegistration.fields.theme_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="title_mbkm">{{ trans('cruds.mbkmRegistration.fields.title_mbkm') }}</label>
                            <input class="form-control" type="text" name="title_mbkm" id="title_mbkm" value="{{ old('title_mbkm', '') }}">
                            @if($errors->has('title_mbkm'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('title_mbkm') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mbkmRegistration.fields.title_mbkm_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="title">{{ trans('cruds.mbkmRegistration.fields.title') }}</label>
                            <input class="form-control" type="text" name="title" id="title" value="{{ old('title', '') }}">
                            @if($errors->has('title'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('title') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mbkmRegistration.fields.title_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="khs_all">{{ trans('cruds.mbkmRegistration.fields.khs_all') }}</label>
                            <div class="needsclick dropzone" id="khs_all-dropzone">
                            </div>
                            @if($errors->has('khs_all'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('khs_all') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mbkmRegistration.fields.khs_all_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="krs_latest">{{ trans('cruds.mbkmRegistration.fields.krs_latest') }}</label>
                            <div class="needsclick dropzone" id="krs_latest-dropzone">
                            </div>
                            @if($errors->has('krs_latest'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('krs_latest') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mbkmRegistration.fields.krs_latest_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="spp">{{ trans('cruds.mbkmRegistration.fields.spp') }}</label>
                            <div class="needsclick dropzone" id="spp-dropzone">
                            </div>
                            @if($errors->has('spp'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('spp') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mbkmRegistration.fields.spp_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="proposal_mbkm">{{ trans('cruds.mbkmRegistration.fields.proposal_mbkm') }}</label>
                            <div class="needsclick dropzone" id="proposal_mbkm-dropzone">
                            </div>
                            @if($errors->has('proposal_mbkm'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('proposal_mbkm') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mbkmRegistration.fields.proposal_mbkm_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="recognition_form">{{ trans('cruds.mbkmRegistration.fields.recognition_form') }}</label>
                            <div class="needsclick dropzone" id="recognition_form-dropzone">
                            </div>
                            @if($errors->has('recognition_form'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('recognition_form') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mbkmRegistration.fields.recognition_form_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="total_sks_taken">{{ trans('cruds.mbkmRegistration.fields.total_sks_taken') }}</label>
                            <input class="form-control" type="number" name="total_sks_taken" id="total_sks_taken" value="{{ old('total_sks_taken', '') }}" step="1">
                            @if($errors->has('total_sks_taken'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('total_sks_taken') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mbkmRegistration.fields.total_sks_taken_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="nilai_mk_kuantitatif">{{ trans('cruds.mbkmRegistration.fields.nilai_mk_kuantitatif') }}</label>
                            <input class="form-control" type="text" name="nilai_mk_kuantitatif" id="nilai_mk_kuantitatif" value="{{ old('nilai_mk_kuantitatif', '') }}">
                            @if($errors->has('nilai_mk_kuantitatif'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('nilai_mk_kuantitatif') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mbkmRegistration.fields.nilai_mk_kuantitatif_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="nilai_mk_kualitatif">{{ trans('cruds.mbkmRegistration.fields.nilai_mk_kualitatif') }}</label>
                            <input class="form-control" type="text" name="nilai_mk_kualitatif" id="nilai_mk_kualitatif" value="{{ old('nilai_mk_kualitatif', '') }}">
                            @if($errors->has('nilai_mk_kualitatif'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('nilai_mk_kualitatif') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mbkmRegistration.fields.nilai_mk_kualitatif_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="nilai_mk_statistika_dasar">{{ trans('cruds.mbkmRegistration.fields.nilai_mk_statistika_dasar') }}</label>
                            <input class="form-control" type="text" name="nilai_mk_statistika_dasar" id="nilai_mk_statistika_dasar" value="{{ old('nilai_mk_statistika_dasar', '') }}">
                            @if($errors->has('nilai_mk_statistika_dasar'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('nilai_mk_statistika_dasar') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mbkmRegistration.fields.nilai_mk_statistika_dasar_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="nilai_mk_statistika_lanjutan">{{ trans('cruds.mbkmRegistration.fields.nilai_mk_statistika_lanjutan') }}</label>
                            <input class="form-control" type="text" name="nilai_mk_statistika_lanjutan" id="nilai_mk_statistika_lanjutan" value="{{ old('nilai_mk_statistika_lanjutan', '') }}">
                            @if($errors->has('nilai_mk_statistika_lanjutan'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('nilai_mk_statistika_lanjutan') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mbkmRegistration.fields.nilai_mk_statistika_lanjutan_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="nilai_mk_konstruksi_tes">{{ trans('cruds.mbkmRegistration.fields.nilai_mk_konstruksi_tes') }}</label>
                            <input class="form-control" type="text" name="nilai_mk_konstruksi_tes" id="nilai_mk_konstruksi_tes" value="{{ old('nilai_mk_konstruksi_tes', '') }}">
                            @if($errors->has('nilai_mk_konstruksi_tes'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('nilai_mk_konstruksi_tes') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mbkmRegistration.fields.nilai_mk_konstruksi_tes_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="nilai_mk_tps">{{ trans('cruds.mbkmRegistration.fields.nilai_mk_tps') }}</label>
                            <input class="form-control" type="text" name="nilai_mk_tps" id="nilai_mk_tps" value="{{ old('nilai_mk_tps', '') }}">
                            @if($errors->has('nilai_mk_tps'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('nilai_mk_tps') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mbkmRegistration.fields.nilai_mk_tps_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="sks_mkp_taken">{{ trans('cruds.mbkmRegistration.fields.sks_mkp_taken') }}</label>
                            <input class="form-control" type="number" name="sks_mkp_taken" id="sks_mkp_taken" value="{{ old('sks_mkp_taken', '') }}" step="1">
                            @if($errors->has('sks_mkp_taken'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('sks_mkp_taken') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mbkmRegistration.fields.sks_mkp_taken_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="note">{{ trans('cruds.mbkmRegistration.fields.note') }}</label>
                            <textarea class="form-control" name="note" id="note">{{ old('note') }}</textarea>
                            @if($errors->has('note'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('note') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mbkmRegistration.fields.note_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                {{ trans('global.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    var uploadedKhsAllMap = {}
Dropzone.options.khsAllDropzone = {
    url: '{{ route('frontend.mbkm-registrations.storeMedia') }}',
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
@if(isset($mbkmRegistration) && $mbkmRegistration->khs_all)
          var files =
            {!! json_encode($mbkmRegistration->khs_all) !!}
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
    url: '{{ route('frontend.mbkm-registrations.storeMedia') }}',
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
    Dropzone.options.sppDropzone = {
    url: '{{ route('frontend.mbkm-registrations.storeMedia') }}',
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
    Dropzone.options.proposalMbkmDropzone = {
    url: '{{ route('frontend.mbkm-registrations.storeMedia') }}',
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
    Dropzone.options.recognitionFormDropzone = {
    url: '{{ route('frontend.mbkm-registrations.storeMedia') }}',
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