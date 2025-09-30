@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.applicationReport.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.application-reports.update", [$applicationReport->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="application_id">{{ trans('cruds.applicationReport.fields.application') }}</label>
                            <select class="form-control select2" name="application_id" id="application_id">
                                @foreach($applications as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('application_id') ? old('application_id') : $applicationReport->application->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('application'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('application') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationReport.fields.application_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="report_text">{{ trans('cruds.applicationReport.fields.report_text') }}</label>
                            <textarea class="form-control ckeditor" name="report_text" id="report_text">{!! old('report_text', $applicationReport->report_text) !!}</textarea>
                            @if($errors->has('report_text'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('report_text') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationReport.fields.report_text_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="report_document">{{ trans('cruds.applicationReport.fields.report_document') }}</label>
                            <div class="needsclick dropzone" id="report_document-dropzone">
                            </div>
                            @if($errors->has('report_document'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('report_document') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationReport.fields.report_document_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="period">{{ trans('cruds.applicationReport.fields.period') }}</label>
                            <input class="form-control" type="text" name="period" id="period" value="{{ old('period', $applicationReport->period) }}">
                            @if($errors->has('period'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('period') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationReport.fields.period_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('cruds.applicationReport.fields.status') }}</label>
                            <select class="form-control" name="status" id="status">
                                <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\ApplicationReport::STATUS_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('status', $applicationReport->status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('status'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('status') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationReport.fields.status_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="note">{{ trans('cruds.applicationReport.fields.note') }}</label>
                            <textarea class="form-control" name="note" id="note">{{ old('note', $applicationReport->note) }}</textarea>
                            @if($errors->has('note'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('note') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationReport.fields.note_helper') }}</span>
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
    $(document).ready(function () {
  function SimpleUploadAdapter(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
      return {
        upload: function() {
          return loader.file
            .then(function (file) {
              return new Promise(function(resolve, reject) {
                // Init request
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('frontend.application-reports.storeCKEditorImages') }}', true);
                xhr.setRequestHeader('x-csrf-token', window._token);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.responseType = 'json';

                // Init listeners
                var genericErrorText = `Couldn't upload file: ${ file.name }.`;
                xhr.addEventListener('error', function() { reject(genericErrorText) });
                xhr.addEventListener('abort', function() { reject() });
                xhr.addEventListener('load', function() {
                  var response = xhr.response;

                  if (!response || xhr.status !== 201) {
                    return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                  }

                  $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');

                  resolve({ default: response.url });
                });

                if (xhr.upload) {
                  xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                      loader.uploadTotal = e.total;
                      loader.uploaded = e.loaded;
                    }
                  });
                }

                // Send request
                var data = new FormData();
                data.append('upload', file);
                data.append('crud_id', '{{ $applicationReport->id ?? 0 }}');
                xhr.send(data);
              });
            })
        }
      };
    }
  }

  var allEditors = document.querySelectorAll('.ckeditor');
  for (var i = 0; i < allEditors.length; ++i) {
    ClassicEditor.create(
      allEditors[i], {
        extraPlugins: [SimpleUploadAdapter]
      }
    );
  }
});
</script>

<script>
    var uploadedReportDocumentMap = {}
Dropzone.options.reportDocumentDropzone = {
    url: '{{ route('frontend.application-reports.storeMedia') }}',
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
@if(isset($applicationReport) && $applicationReport->report_document)
          var files =
            {!! json_encode($applicationReport->report_document) !!}
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
@endsection