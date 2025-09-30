@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.mbkmSeminar.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.mbkm-seminars.update", [$mbkmSeminar->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="application_id">{{ trans('cruds.mbkmSeminar.fields.application') }}</label>
                <select class="form-control select2 {{ $errors->has('application') ? 'is-invalid' : '' }}" name="application_id" id="application_id">
                    @foreach($applications as $id => $entry)
                        <option value="{{ $id }}" {{ (old('application_id') ? old('application_id') : $mbkmSeminar->application->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('application'))
                    <span class="text-danger">{{ $errors->first('application') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.mbkmSeminar.fields.application_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="title">{{ trans('cruds.mbkmSeminar.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', $mbkmSeminar->title) }}">
                @if($errors->has('title'))
                    <span class="text-danger">{{ $errors->first('title') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.mbkmSeminar.fields.title_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="proposal_document">{{ trans('cruds.mbkmSeminar.fields.proposal_document') }}</label>
                <div class="needsclick dropzone {{ $errors->has('proposal_document') ? 'is-invalid' : '' }}" id="proposal_document-dropzone">
                </div>
                @if($errors->has('proposal_document'))
                    <span class="text-danger">{{ $errors->first('proposal_document') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.mbkmSeminar.fields.proposal_document_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="approval_document">{{ trans('cruds.mbkmSeminar.fields.approval_document') }}</label>
                <div class="needsclick dropzone {{ $errors->has('approval_document') ? 'is-invalid' : '' }}" id="approval_document-dropzone">
                </div>
                @if($errors->has('approval_document'))
                    <span class="text-danger">{{ $errors->first('approval_document') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.mbkmSeminar.fields.approval_document_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="plagiarism_document">{{ trans('cruds.mbkmSeminar.fields.plagiarism_document') }}</label>
                <div class="needsclick dropzone {{ $errors->has('plagiarism_document') ? 'is-invalid' : '' }}" id="plagiarism_document-dropzone">
                </div>
                @if($errors->has('plagiarism_document'))
                    <span class="text-danger">{{ $errors->first('plagiarism_document') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.mbkmSeminar.fields.plagiarism_document_helper') }}</span>
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
    Dropzone.options.proposalDocumentDropzone = {
    url: '{{ route('admin.mbkm-seminars.storeMedia') }}',
    maxFilesize: 25, // MB
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
    Dropzone.options.approvalDocumentDropzone = {
    url: '{{ route('admin.mbkm-seminars.storeMedia') }}',
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
    Dropzone.options.plagiarismDocumentDropzone = {
    url: '{{ route('admin.mbkm-seminars.storeMedia') }}',
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