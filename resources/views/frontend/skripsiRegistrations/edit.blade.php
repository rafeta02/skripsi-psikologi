@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.skripsiRegistration.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.skripsi-registrations.update", [$skripsiRegistration->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="application_id">{{ trans('cruds.skripsiRegistration.fields.application') }}</label>
                            <select class="form-control select2" name="application_id" id="application_id">
                                @foreach($applications as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('application_id') ? old('application_id') : $skripsiRegistration->application->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('application'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('application') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.skripsiRegistration.fields.application_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="theme_id">{{ trans('cruds.skripsiRegistration.fields.theme') }}</label>
                            <select class="form-control select2" name="theme_id" id="theme_id">
                                @foreach($themes as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('theme_id') ? old('theme_id') : $skripsiRegistration->theme->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('theme'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('theme') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.skripsiRegistration.fields.theme_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="title">{{ trans('cruds.skripsiRegistration.fields.title') }}</label>
                            <input class="form-control" type="text" name="title" id="title" value="{{ old('title', $skripsiRegistration->title) }}">
                            @if($errors->has('title'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('title') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.skripsiRegistration.fields.title_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="abstract">{{ trans('cruds.skripsiRegistration.fields.abstract') }}</label>
                            <textarea class="form-control" name="abstract" id="abstract">{{ old('abstract', $skripsiRegistration->abstract) }}</textarea>
                            @if($errors->has('abstract'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('abstract') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.skripsiRegistration.fields.abstract_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="tps_lecturer_id">{{ trans('cruds.skripsiRegistration.fields.tps_lecturer') }}</label>
                            <select class="form-control select2" name="tps_lecturer_id" id="tps_lecturer_id">
                                @foreach($tps_lecturers as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('tps_lecturer_id') ? old('tps_lecturer_id') : $skripsiRegistration->tps_lecturer->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('tps_lecturer'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('tps_lecturer') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.skripsiRegistration.fields.tps_lecturer_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="preference_supervision_id">{{ trans('cruds.skripsiRegistration.fields.preference_supervision') }}</label>
                            <select class="form-control select2" name="preference_supervision_id" id="preference_supervision_id">
                                @foreach($preference_supervisions as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('preference_supervision_id') ? old('preference_supervision_id') : $skripsiRegistration->preference_supervision->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('preference_supervision'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('preference_supervision') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.skripsiRegistration.fields.preference_supervision_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="khs_all">{{ trans('cruds.skripsiRegistration.fields.khs_all') }}</label>
                            <div class="needsclick dropzone" id="khs_all-dropzone">
                            </div>
                            @if($errors->has('khs_all'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('khs_all') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.skripsiRegistration.fields.khs_all_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="krs_latest">{{ trans('cruds.skripsiRegistration.fields.krs_latest') }}</label>
                            <div class="needsclick dropzone" id="krs_latest-dropzone">
                            </div>
                            @if($errors->has('krs_latest'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('krs_latest') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.skripsiRegistration.fields.krs_latest_helper') }}</span>
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