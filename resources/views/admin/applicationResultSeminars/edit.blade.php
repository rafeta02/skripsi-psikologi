@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.applicationResultSeminar.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.application-result-seminars.update", [$applicationResultSeminar->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="application_id">{{ trans('cruds.applicationResultSeminar.fields.application') }}</label>
                <select class="form-control select2 {{ $errors->has('application') ? 'is-invalid' : '' }}" name="application_id" id="application_id">
                    @foreach($applications as $id => $entry)
                        <option value="{{ $id }}" {{ (old('application_id') ? old('application_id') : $applicationResultSeminar->application->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('application'))
                    <span class="text-danger">{{ $errors->first('application') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationResultSeminar.fields.application_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.applicationResultSeminar.fields.result') }}</label>
                <select class="form-control {{ $errors->has('result') ? 'is-invalid' : '' }}" name="result" id="result">
                    <option value disabled {{ old('result', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\ApplicationResultSeminar::RESULT_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('result', $applicationResultSeminar->result) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('result'))
                    <span class="text-danger">{{ $errors->first('result') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationResultSeminar.fields.result_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="note">{{ trans('cruds.applicationResultSeminar.fields.note') }}</label>
                <textarea class="form-control {{ $errors->has('note') ? 'is-invalid' : '' }}" name="note" id="note">{{ old('note', $applicationResultSeminar->note) }}</textarea>
                @if($errors->has('note'))
                    <span class="text-danger">{{ $errors->first('note') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationResultSeminar.fields.note_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="revision_deadline">{{ trans('cruds.applicationResultSeminar.fields.revision_deadline') }}</label>
                <input class="form-control date {{ $errors->has('revision_deadline') ? 'is-invalid' : '' }}" type="text" name="revision_deadline" id="revision_deadline" value="{{ old('revision_deadline', $applicationResultSeminar->revision_deadline) }}">
                @if($errors->has('revision_deadline'))
                    <span class="text-danger">{{ $errors->first('revision_deadline') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationResultSeminar.fields.revision_deadline_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="report_document">{{ trans('cruds.applicationResultSeminar.fields.report_document') }}</label>
                <div class="needsclick dropzone {{ $errors->has('report_document') ? 'is-invalid' : '' }}" id="report_document-dropzone">
                </div>
                @if($errors->has('report_document'))
                    <span class="text-danger">{{ $errors->first('report_document') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationResultSeminar.fields.report_document_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="attendance_document">{{ trans('cruds.applicationResultSeminar.fields.attendance_document') }}</label>
                <div class="needsclick dropzone {{ $errors->has('attendance_document') ? 'is-invalid' : '' }}" id="attendance_document-dropzone">
                </div>
                @if($errors->has('attendance_document'))
                    <span class="text-danger">{{ $errors->first('attendance_document') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationResultSeminar.fields.attendance_document_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="form_document">{{ trans('cruds.applicationResultSeminar.fields.form_document') }}</label>
                <div class="needsclick dropzone {{ $errors->has('form_document') ? 'is-invalid' : '' }}" id="form_document-dropzone">
                </div>
                @if($errors->has('form_document'))
                    <span class="text-danger">{{ $errors->first('form_document') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationResultSeminar.fields.form_document_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="latest_script">{{ trans('cruds.applicationResultSeminar.fields.latest_script') }}</label>
                <div class="needsclick dropzone {{ $errors->has('latest_script') ? 'is-invalid' : '' }}" id="latest_script-dropzone">
                </div>
                @if($errors->has('latest_script'))
                    <span class="text-danger">{{ $errors->first('latest_script') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationResultSeminar.fields.latest_script_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="documentation">{{ trans('cruds.applicationResultSeminar.fields.documentation') }}</label>
                <div class="needsclick dropzone {{ $errors->has('documentation') ? 'is-invalid' : '' }}" id="documentation-dropzone">
                </div>
                @if($errors->has('documentation'))
                    <span class="text-danger">{{ $errors->first('documentation') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationResultSeminar.fields.documentation_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection

@section('scripts')
<script>
    var uploadedReportDocumentMap = {}
Dropzone.options.reportDocumentDropzone = {
    url: '{{ route('admin.application-result-seminars.storeMedia') }}',
    maxFilesize: 10, // MB
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
          var files =
            {!! json_encode($applicationResultSeminar->report_document) !!}
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
    Dropzone.options.attendanceDocumentDropzone = {
    url: '{{ route('admin.application-result-seminars.storeMedia') }}',
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
    var uploadedFormDocumentMap = {}
Dropzone.options.formDocumentDropzone = {
    url: '{{ route('admin.application-result-seminars.storeMedia') }}',
    maxFilesize: 10, // MB
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
          var files =
            {!! json_encode($applicationResultSeminar->form_document) !!}
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
    Dropzone.options.latestScriptDropzone = {
    url: '{{ route('admin.application-result-seminars.storeMedia') }}',
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
    var uploadedDocumentationMap = {}
Dropzone.options.documentationDropzone = {
    url: '{{ route('admin.application-result-seminars.storeMedia') }}',
    maxFilesize: 10, // MB
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
          var files =
            {!! json_encode($applicationResultSeminar->documentation) !!}
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