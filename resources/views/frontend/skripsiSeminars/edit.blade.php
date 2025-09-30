@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.skripsiSeminar.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.skripsi-seminars.update", [$skripsiSeminar->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="application_id">{{ trans('cruds.skripsiSeminar.fields.application') }}</label>
                            <select class="form-control select2" name="application_id" id="application_id">
                                @foreach($applications as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('application_id') ? old('application_id') : $skripsiSeminar->application->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('application'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('application') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.skripsiSeminar.fields.application_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="title">{{ trans('cruds.skripsiSeminar.fields.title') }}</label>
                            <input class="form-control" type="text" name="title" id="title" value="{{ old('title', $skripsiSeminar->title) }}">
                            @if($errors->has('title'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('title') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.skripsiSeminar.fields.title_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="proposal_document">{{ trans('cruds.skripsiSeminar.fields.proposal_document') }}</label>
                            <div class="needsclick dropzone" id="proposal_document-dropzone">
                            </div>
                            @if($errors->has('proposal_document'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('proposal_document') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.skripsiSeminar.fields.proposal_document_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="approval_document">{{ trans('cruds.skripsiSeminar.fields.approval_document') }}</label>
                            <div class="needsclick dropzone" id="approval_document-dropzone">
                            </div>
                            @if($errors->has('approval_document'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('approval_document') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.skripsiSeminar.fields.approval_document_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="plagiarism_document">{{ trans('cruds.skripsiSeminar.fields.plagiarism_document') }}</label>
                            <div class="needsclick dropzone" id="plagiarism_document-dropzone">
                            </div>
                            @if($errors->has('plagiarism_document'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('plagiarism_document') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.skripsiSeminar.fields.plagiarism_document_helper') }}</span>
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
    Dropzone.options.proposalDocumentDropzone = {
    url: '{{ route('frontend.skripsi-seminars.storeMedia') }}',
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
@if(isset($skripsiSeminar) && $skripsiSeminar->proposal_document)
      var file = {!! json_encode($skripsiSeminar->proposal_document) !!}
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
    url: '{{ route('frontend.skripsi-seminars.storeMedia') }}',
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
@if(isset($skripsiSeminar) && $skripsiSeminar->approval_document)
      var file = {!! json_encode($skripsiSeminar->approval_document) !!}
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
    url: '{{ route('frontend.skripsi-seminars.storeMedia') }}',
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
@if(isset($skripsiSeminar) && $skripsiSeminar->plagiarism_document)
      var file = {!! json_encode($skripsiSeminar->plagiarism_document) !!}
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