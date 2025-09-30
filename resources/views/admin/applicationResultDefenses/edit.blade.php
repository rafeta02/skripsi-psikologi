@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.applicationResultDefense.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.application-result-defenses.update", [$applicationResultDefense->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="application_id">{{ trans('cruds.applicationResultDefense.fields.application') }}</label>
                <select class="form-control select2 {{ $errors->has('application') ? 'is-invalid' : '' }}" name="application_id" id="application_id">
                    @foreach($applications as $id => $entry)
                        <option value="{{ $id }}" {{ (old('application_id') ? old('application_id') : $applicationResultDefense->application->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('application'))
                    <span class="text-danger">{{ $errors->first('application') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationResultDefense.fields.application_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.applicationResultDefense.fields.result') }}</label>
                <select class="form-control {{ $errors->has('result') ? 'is-invalid' : '' }}" name="result" id="result">
                    <option value disabled {{ old('result', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\ApplicationResultDefense::RESULT_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('result', $applicationResultDefense->result) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('result'))
                    <span class="text-danger">{{ $errors->first('result') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationResultDefense.fields.result_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="note">{{ trans('cruds.applicationResultDefense.fields.note') }}</label>
                <textarea class="form-control {{ $errors->has('note') ? 'is-invalid' : '' }}" name="note" id="note">{{ old('note', $applicationResultDefense->note) }}</textarea>
                @if($errors->has('note'))
                    <span class="text-danger">{{ $errors->first('note') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationResultDefense.fields.note_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="revision_deadline">{{ trans('cruds.applicationResultDefense.fields.revision_deadline') }}</label>
                <input class="form-control date {{ $errors->has('revision_deadline') ? 'is-invalid' : '' }}" type="text" name="revision_deadline" id="revision_deadline" value="{{ old('revision_deadline', $applicationResultDefense->revision_deadline) }}">
                @if($errors->has('revision_deadline'))
                    <span class="text-danger">{{ $errors->first('revision_deadline') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationResultDefense.fields.revision_deadline_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="final_grade">{{ trans('cruds.applicationResultDefense.fields.final_grade') }}</label>
                <input class="form-control {{ $errors->has('final_grade') ? 'is-invalid' : '' }}" type="number" name="final_grade" id="final_grade" value="{{ old('final_grade', $applicationResultDefense->final_grade) }}" step="0.01" max="10">
                @if($errors->has('final_grade'))
                    <span class="text-danger">{{ $errors->first('final_grade') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationResultDefense.fields.final_grade_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="documentation">{{ trans('cruds.applicationResultDefense.fields.documentation') }}</label>
                <div class="needsclick dropzone {{ $errors->has('documentation') ? 'is-invalid' : '' }}" id="documentation-dropzone">
                </div>
                @if($errors->has('documentation'))
                    <span class="text-danger">{{ $errors->first('documentation') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationResultDefense.fields.documentation_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="invitation_document">{{ trans('cruds.applicationResultDefense.fields.invitation_document') }}</label>
                <div class="needsclick dropzone {{ $errors->has('invitation_document') ? 'is-invalid' : '' }}" id="invitation_document-dropzone">
                </div>
                @if($errors->has('invitation_document'))
                    <span class="text-danger">{{ $errors->first('invitation_document') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationResultDefense.fields.invitation_document_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="feedback_document">{{ trans('cruds.applicationResultDefense.fields.feedback_document') }}</label>
                <div class="needsclick dropzone {{ $errors->has('feedback_document') ? 'is-invalid' : '' }}" id="feedback_document-dropzone">
                </div>
                @if($errors->has('feedback_document'))
                    <span class="text-danger">{{ $errors->first('feedback_document') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationResultDefense.fields.feedback_document_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="minutes_document">{{ trans('cruds.applicationResultDefense.fields.minutes_document') }}</label>
                <div class="needsclick dropzone {{ $errors->has('minutes_document') ? 'is-invalid' : '' }}" id="minutes_document-dropzone">
                </div>
                @if($errors->has('minutes_document'))
                    <span class="text-danger">{{ $errors->first('minutes_document') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationResultDefense.fields.minutes_document_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="latest_script">{{ trans('cruds.applicationResultDefense.fields.latest_script') }}</label>
                <div class="needsclick dropzone {{ $errors->has('latest_script') ? 'is-invalid' : '' }}" id="latest_script-dropzone">
                </div>
                @if($errors->has('latest_script'))
                    <span class="text-danger">{{ $errors->first('latest_script') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationResultDefense.fields.latest_script_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="approval_page">{{ trans('cruds.applicationResultDefense.fields.approval_page') }}</label>
                <div class="needsclick dropzone {{ $errors->has('approval_page') ? 'is-invalid' : '' }}" id="approval_page-dropzone">
                </div>
                @if($errors->has('approval_page'))
                    <span class="text-danger">{{ $errors->first('approval_page') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationResultDefense.fields.approval_page_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="report_document">{{ trans('cruds.applicationResultDefense.fields.report_document') }}</label>
                <div class="needsclick dropzone {{ $errors->has('report_document') ? 'is-invalid' : '' }}" id="report_document-dropzone">
                </div>
                @if($errors->has('report_document'))
                    <span class="text-danger">{{ $errors->first('report_document') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationResultDefense.fields.report_document_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="revision_approval_sheet">{{ trans('cruds.applicationResultDefense.fields.revision_approval_sheet') }}</label>
                <div class="needsclick dropzone {{ $errors->has('revision_approval_sheet') ? 'is-invalid' : '' }}" id="revision_approval_sheet-dropzone">
                </div>
                @if($errors->has('revision_approval_sheet'))
                    <span class="text-danger">{{ $errors->first('revision_approval_sheet') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationResultDefense.fields.revision_approval_sheet_helper') }}</span>
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
    var uploadedDocumentationMap = {}
Dropzone.options.documentationDropzone = {
    url: '{{ route('admin.application-result-defenses.storeMedia') }}',
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
@if(isset($applicationResultDefense) && $applicationResultDefense->documentation)
          var files =
            {!! json_encode($applicationResultDefense->documentation) !!}
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
<script>
    var uploadedInvitationDocumentMap = {}
Dropzone.options.invitationDocumentDropzone = {
    url: '{{ route('admin.application-result-defenses.storeMedia') }}',
    maxFilesize: 10, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="invitation_document[]" value="' + response.name + '">')
      uploadedInvitationDocumentMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedInvitationDocumentMap[file.name]
      }
      $('form').find('input[name="invitation_document[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($applicationResultDefense) && $applicationResultDefense->invitation_document)
          var files =
            {!! json_encode($applicationResultDefense->invitation_document) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="invitation_document[]" value="' + file.file_name + '">')
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
    var uploadedFeedbackDocumentMap = {}
Dropzone.options.feedbackDocumentDropzone = {
    url: '{{ route('admin.application-result-defenses.storeMedia') }}',
    maxFilesize: 10, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="feedback_document[]" value="' + response.name + '">')
      uploadedFeedbackDocumentMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedFeedbackDocumentMap[file.name]
      }
      $('form').find('input[name="feedback_document[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($applicationResultDefense) && $applicationResultDefense->feedback_document)
          var files =
            {!! json_encode($applicationResultDefense->feedback_document) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="feedback_document[]" value="' + file.file_name + '">')
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
    Dropzone.options.minutesDocumentDropzone = {
    url: '{{ route('admin.application-result-defenses.storeMedia') }}',
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
      $('form').find('input[name="minutes_document"]').remove()
      $('form').append('<input type="hidden" name="minutes_document" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="minutes_document"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($applicationResultDefense) && $applicationResultDefense->minutes_document)
      var file = {!! json_encode($applicationResultDefense->minutes_document) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="minutes_document" value="' + file.file_name + '">')
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
    Dropzone.options.latestScriptDropzone = {
    url: '{{ route('admin.application-result-defenses.storeMedia') }}',
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
    Dropzone.options.approvalPageDropzone = {
    url: '{{ route('admin.application-result-defenses.storeMedia') }}',
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
      $('form').find('input[name="approval_page"]').remove()
      $('form').append('<input type="hidden" name="approval_page" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="approval_page"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($applicationResultDefense) && $applicationResultDefense->approval_page)
      var file = {!! json_encode($applicationResultDefense->approval_page) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="approval_page" value="' + file.file_name + '">')
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
    var uploadedReportDocumentMap = {}
Dropzone.options.reportDocumentDropzone = {
    url: '{{ route('admin.application-result-defenses.storeMedia') }}',
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
@if(isset($applicationResultDefense) && $applicationResultDefense->report_document)
          var files =
            {!! json_encode($applicationResultDefense->report_document) !!}
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
    var uploadedRevisionApprovalSheetMap = {}
Dropzone.options.revisionApprovalSheetDropzone = {
    url: '{{ route('admin.application-result-defenses.storeMedia') }}',
    maxFilesize: 10, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="revision_approval_sheet[]" value="' + response.name + '">')
      uploadedRevisionApprovalSheetMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedRevisionApprovalSheetMap[file.name]
      }
      $('form').find('input[name="revision_approval_sheet[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($applicationResultDefense) && $applicationResultDefense->revision_approval_sheet)
          var files =
            {!! json_encode($applicationResultDefense->revision_approval_sheet) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="revision_approval_sheet[]" value="' + file.file_name + '">')
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