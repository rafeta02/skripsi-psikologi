@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.skripsiDefense.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.skripsi-defenses.update", [$skripsiDefense->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="application_id">{{ trans('cruds.skripsiDefense.fields.application') }}</label>
                <select class="form-control select2 {{ $errors->has('application') ? 'is-invalid' : '' }}" name="application_id" id="application_id">
                    @foreach($applications as $id => $entry)
                        <option value="{{ $id }}" {{ (old('application_id') ? old('application_id') : $skripsiDefense->application->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('application'))
                    <span class="text-danger">{{ $errors->first('application') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.skripsiDefense.fields.application_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="title">{{ trans('cruds.skripsiDefense.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', $skripsiDefense->title) }}">
                @if($errors->has('title'))
                    <span class="text-danger">{{ $errors->first('title') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.skripsiDefense.fields.title_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="abstract">{{ trans('cruds.skripsiDefense.fields.abstract') }}</label>
                <input class="form-control {{ $errors->has('abstract') ? 'is-invalid' : '' }}" type="text" name="abstract" id="abstract" value="{{ old('abstract', $skripsiDefense->abstract) }}">
                @if($errors->has('abstract'))
                    <span class="text-danger">{{ $errors->first('abstract') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.skripsiDefense.fields.abstract_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="defence_document">{{ trans('cruds.skripsiDefense.fields.defence_document') }}</label>
                <div class="needsclick dropzone {{ $errors->has('defence_document') ? 'is-invalid' : '' }}" id="defence_document-dropzone">
                </div>
                @if($errors->has('defence_document'))
                    <span class="text-danger">{{ $errors->first('defence_document') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.skripsiDefense.fields.defence_document_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="plagiarism_report">{{ trans('cruds.skripsiDefense.fields.plagiarism_report') }}</label>
                <div class="needsclick dropzone {{ $errors->has('plagiarism_report') ? 'is-invalid' : '' }}" id="plagiarism_report-dropzone">
                </div>
                @if($errors->has('plagiarism_report'))
                    <span class="text-danger">{{ $errors->first('plagiarism_report') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.skripsiDefense.fields.plagiarism_report_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="ethics_statement">{{ trans('cruds.skripsiDefense.fields.ethics_statement') }}</label>
                <div class="needsclick dropzone {{ $errors->has('ethics_statement') ? 'is-invalid' : '' }}" id="ethics_statement-dropzone">
                </div>
                @if($errors->has('ethics_statement'))
                    <span class="text-danger">{{ $errors->first('ethics_statement') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.skripsiDefense.fields.ethics_statement_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="research_instruments">{{ trans('cruds.skripsiDefense.fields.research_instruments') }}</label>
                <div class="needsclick dropzone {{ $errors->has('research_instruments') ? 'is-invalid' : '' }}" id="research_instruments-dropzone">
                </div>
                @if($errors->has('research_instruments'))
                    <span class="text-danger">{{ $errors->first('research_instruments') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.skripsiDefense.fields.research_instruments_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="data_collection_letter">{{ trans('cruds.skripsiDefense.fields.data_collection_letter') }}</label>
                <div class="needsclick dropzone {{ $errors->has('data_collection_letter') ? 'is-invalid' : '' }}" id="data_collection_letter-dropzone">
                </div>
                @if($errors->has('data_collection_letter'))
                    <span class="text-danger">{{ $errors->first('data_collection_letter') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.skripsiDefense.fields.data_collection_letter_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="research_module">{{ trans('cruds.skripsiDefense.fields.research_module') }}</label>
                <div class="needsclick dropzone {{ $errors->has('research_module') ? 'is-invalid' : '' }}" id="research_module-dropzone">
                </div>
                @if($errors->has('research_module'))
                    <span class="text-danger">{{ $errors->first('research_module') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.skripsiDefense.fields.research_module_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="mbkm_recommendation_letter">{{ trans('cruds.skripsiDefense.fields.mbkm_recommendation_letter') }}</label>
                <div class="needsclick dropzone {{ $errors->has('mbkm_recommendation_letter') ? 'is-invalid' : '' }}" id="mbkm_recommendation_letter-dropzone">
                </div>
                @if($errors->has('mbkm_recommendation_letter'))
                    <span class="text-danger">{{ $errors->first('mbkm_recommendation_letter') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.skripsiDefense.fields.mbkm_recommendation_letter_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="publication_statement">{{ trans('cruds.skripsiDefense.fields.publication_statement') }}</label>
                <div class="needsclick dropzone {{ $errors->has('publication_statement') ? 'is-invalid' : '' }}" id="publication_statement-dropzone">
                </div>
                @if($errors->has('publication_statement'))
                    <span class="text-danger">{{ $errors->first('publication_statement') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.skripsiDefense.fields.publication_statement_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="defense_approval_page">{{ trans('cruds.skripsiDefense.fields.defense_approval_page') }}</label>
                <div class="needsclick dropzone {{ $errors->has('defense_approval_page') ? 'is-invalid' : '' }}" id="defense_approval_page-dropzone">
                </div>
                @if($errors->has('defense_approval_page'))
                    <span class="text-danger">{{ $errors->first('defense_approval_page') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.skripsiDefense.fields.defense_approval_page_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="spp_receipt">{{ trans('cruds.skripsiDefense.fields.spp_receipt') }}</label>
                <div class="needsclick dropzone {{ $errors->has('spp_receipt') ? 'is-invalid' : '' }}" id="spp_receipt-dropzone">
                </div>
                @if($errors->has('spp_receipt'))
                    <span class="text-danger">{{ $errors->first('spp_receipt') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.skripsiDefense.fields.spp_receipt_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="krs_latest">{{ trans('cruds.skripsiDefense.fields.krs_latest') }}</label>
                <div class="needsclick dropzone {{ $errors->has('krs_latest') ? 'is-invalid' : '' }}" id="krs_latest-dropzone">
                </div>
                @if($errors->has('krs_latest'))
                    <span class="text-danger">{{ $errors->first('krs_latest') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.skripsiDefense.fields.krs_latest_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="eap_certificate">{{ trans('cruds.skripsiDefense.fields.eap_certificate') }}</label>
                <div class="needsclick dropzone {{ $errors->has('eap_certificate') ? 'is-invalid' : '' }}" id="eap_certificate-dropzone">
                </div>
                @if($errors->has('eap_certificate'))
                    <span class="text-danger">{{ $errors->first('eap_certificate') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.skripsiDefense.fields.eap_certificate_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="transcript">{{ trans('cruds.skripsiDefense.fields.transcript') }}</label>
                <div class="needsclick dropzone {{ $errors->has('transcript') ? 'is-invalid' : '' }}" id="transcript-dropzone">
                </div>
                @if($errors->has('transcript'))
                    <span class="text-danger">{{ $errors->first('transcript') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.skripsiDefense.fields.transcript_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="mbkm_report">{{ trans('cruds.skripsiDefense.fields.mbkm_report') }}</label>
                <div class="needsclick dropzone {{ $errors->has('mbkm_report') ? 'is-invalid' : '' }}" id="mbkm_report-dropzone">
                </div>
                @if($errors->has('mbkm_report'))
                    <span class="text-danger">{{ $errors->first('mbkm_report') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.skripsiDefense.fields.mbkm_report_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="research_poster">{{ trans('cruds.skripsiDefense.fields.research_poster') }}</label>
                <div class="needsclick dropzone {{ $errors->has('research_poster') ? 'is-invalid' : '' }}" id="research_poster-dropzone">
                </div>
                @if($errors->has('research_poster'))
                    <span class="text-danger">{{ $errors->first('research_poster') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.skripsiDefense.fields.research_poster_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="siakad_supervisor_screenshot">{{ trans('cruds.skripsiDefense.fields.siakad_supervisor_screenshot') }}</label>
                <div class="needsclick dropzone {{ $errors->has('siakad_supervisor_screenshot') ? 'is-invalid' : '' }}" id="siakad_supervisor_screenshot-dropzone">
                </div>
                @if($errors->has('siakad_supervisor_screenshot'))
                    <span class="text-danger">{{ $errors->first('siakad_supervisor_screenshot') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.skripsiDefense.fields.siakad_supervisor_screenshot_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="supervision_logbook">{{ trans('cruds.skripsiDefense.fields.supervision_logbook') }}</label>
                <div class="needsclick dropzone {{ $errors->has('supervision_logbook') ? 'is-invalid' : '' }}" id="supervision_logbook-dropzone">
                </div>
                @if($errors->has('supervision_logbook'))
                    <span class="text-danger">{{ $errors->first('supervision_logbook') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.skripsiDefense.fields.supervision_logbook_helper') }}</span>
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
    Dropzone.options.defenceDocumentDropzone = {
    url: '{{ route('admin.skripsi-defenses.storeMedia') }}',
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
      $('form').find('input[name="defence_document"]').remove()
      $('form').append('<input type="hidden" name="defence_document" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="defence_document"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($skripsiDefense) && $skripsiDefense->defence_document)
      var file = {!! json_encode($skripsiDefense->defence_document) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="defence_document" value="' + file.file_name + '">')
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
    Dropzone.options.plagiarismReportDropzone = {
    url: '{{ route('admin.skripsi-defenses.storeMedia') }}',
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
      $('form').find('input[name="plagiarism_report"]').remove()
      $('form').append('<input type="hidden" name="plagiarism_report" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="plagiarism_report"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($skripsiDefense) && $skripsiDefense->plagiarism_report)
      var file = {!! json_encode($skripsiDefense->plagiarism_report) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="plagiarism_report" value="' + file.file_name + '">')
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
    var uploadedEthicsStatementMap = {}
Dropzone.options.ethicsStatementDropzone = {
    url: '{{ route('admin.skripsi-defenses.storeMedia') }}',
    maxFilesize: 10, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="ethics_statement[]" value="' + response.name + '">')
      uploadedEthicsStatementMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedEthicsStatementMap[file.name]
      }
      $('form').find('input[name="ethics_statement[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($skripsiDefense) && $skripsiDefense->ethics_statement)
          var files =
            {!! json_encode($skripsiDefense->ethics_statement) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="ethics_statement[]" value="' + file.file_name + '">')
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
    var uploadedResearchInstrumentsMap = {}
Dropzone.options.researchInstrumentsDropzone = {
    url: '{{ route('admin.skripsi-defenses.storeMedia') }}',
    maxFilesize: 10, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="research_instruments[]" value="' + response.name + '">')
      uploadedResearchInstrumentsMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedResearchInstrumentsMap[file.name]
      }
      $('form').find('input[name="research_instruments[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($skripsiDefense) && $skripsiDefense->research_instruments)
          var files =
            {!! json_encode($skripsiDefense->research_instruments) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="research_instruments[]" value="' + file.file_name + '">')
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
    var uploadedDataCollectionLetterMap = {}
Dropzone.options.dataCollectionLetterDropzone = {
    url: '{{ route('admin.skripsi-defenses.storeMedia') }}',
    maxFilesize: 10, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="data_collection_letter[]" value="' + response.name + '">')
      uploadedDataCollectionLetterMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedDataCollectionLetterMap[file.name]
      }
      $('form').find('input[name="data_collection_letter[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($skripsiDefense) && $skripsiDefense->data_collection_letter)
          var files =
            {!! json_encode($skripsiDefense->data_collection_letter) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="data_collection_letter[]" value="' + file.file_name + '">')
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
    var uploadedResearchModuleMap = {}
Dropzone.options.researchModuleDropzone = {
    url: '{{ route('admin.skripsi-defenses.storeMedia') }}',
    maxFilesize: 10, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="research_module[]" value="' + response.name + '">')
      uploadedResearchModuleMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedResearchModuleMap[file.name]
      }
      $('form').find('input[name="research_module[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($skripsiDefense) && $skripsiDefense->research_module)
          var files =
            {!! json_encode($skripsiDefense->research_module) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="research_module[]" value="' + file.file_name + '">')
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
    Dropzone.options.mbkmRecommendationLetterDropzone = {
    url: '{{ route('admin.skripsi-defenses.storeMedia') }}',
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
      $('form').find('input[name="mbkm_recommendation_letter"]').remove()
      $('form').append('<input type="hidden" name="mbkm_recommendation_letter" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="mbkm_recommendation_letter"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($skripsiDefense) && $skripsiDefense->mbkm_recommendation_letter)
      var file = {!! json_encode($skripsiDefense->mbkm_recommendation_letter) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="mbkm_recommendation_letter" value="' + file.file_name + '">')
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
    Dropzone.options.publicationStatementDropzone = {
    url: '{{ route('admin.skripsi-defenses.storeMedia') }}',
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
      $('form').find('input[name="publication_statement"]').remove()
      $('form').append('<input type="hidden" name="publication_statement" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="publication_statement"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($skripsiDefense) && $skripsiDefense->publication_statement)
      var file = {!! json_encode($skripsiDefense->publication_statement) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="publication_statement" value="' + file.file_name + '">')
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
    var uploadedDefenseApprovalPageMap = {}
Dropzone.options.defenseApprovalPageDropzone = {
    url: '{{ route('admin.skripsi-defenses.storeMedia') }}',
    maxFilesize: 10, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="defense_approval_page[]" value="' + response.name + '">')
      uploadedDefenseApprovalPageMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedDefenseApprovalPageMap[file.name]
      }
      $('form').find('input[name="defense_approval_page[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($skripsiDefense) && $skripsiDefense->defense_approval_page)
          var files =
            {!! json_encode($skripsiDefense->defense_approval_page) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="defense_approval_page[]" value="' + file.file_name + '">')
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
    Dropzone.options.sppReceiptDropzone = {
    url: '{{ route('admin.skripsi-defenses.storeMedia') }}',
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
      $('form').find('input[name="spp_receipt"]').remove()
      $('form').append('<input type="hidden" name="spp_receipt" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="spp_receipt"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($skripsiDefense) && $skripsiDefense->spp_receipt)
      var file = {!! json_encode($skripsiDefense->spp_receipt) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="spp_receipt" value="' + file.file_name + '">')
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
    Dropzone.options.krsLatestDropzone = {
    url: '{{ route('admin.skripsi-defenses.storeMedia') }}',
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
@if(isset($skripsiDefense) && $skripsiDefense->krs_latest)
      var file = {!! json_encode($skripsiDefense->krs_latest) !!}
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
    Dropzone.options.eapCertificateDropzone = {
    url: '{{ route('admin.skripsi-defenses.storeMedia') }}',
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
      $('form').find('input[name="eap_certificate"]').remove()
      $('form').append('<input type="hidden" name="eap_certificate" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="eap_certificate"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($skripsiDefense) && $skripsiDefense->eap_certificate)
      var file = {!! json_encode($skripsiDefense->eap_certificate) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="eap_certificate" value="' + file.file_name + '">')
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
    Dropzone.options.transcriptDropzone = {
    url: '{{ route('admin.skripsi-defenses.storeMedia') }}',
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
      $('form').find('input[name="transcript"]').remove()
      $('form').append('<input type="hidden" name="transcript" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="transcript"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($skripsiDefense) && $skripsiDefense->transcript)
      var file = {!! json_encode($skripsiDefense->transcript) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="transcript" value="' + file.file_name + '">')
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
    var uploadedMbkmReportMap = {}
Dropzone.options.mbkmReportDropzone = {
    url: '{{ route('admin.skripsi-defenses.storeMedia') }}',
    maxFilesize: 10, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="mbkm_report[]" value="' + response.name + '">')
      uploadedMbkmReportMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedMbkmReportMap[file.name]
      }
      $('form').find('input[name="mbkm_report[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($skripsiDefense) && $skripsiDefense->mbkm_report)
          var files =
            {!! json_encode($skripsiDefense->mbkm_report) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="mbkm_report[]" value="' + file.file_name + '">')
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
    var uploadedResearchPosterMap = {}
Dropzone.options.researchPosterDropzone = {
    url: '{{ route('admin.skripsi-defenses.storeMedia') }}',
    maxFilesize: 10, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="research_poster[]" value="' + response.name + '">')
      uploadedResearchPosterMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedResearchPosterMap[file.name]
      }
      $('form').find('input[name="research_poster[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($skripsiDefense) && $skripsiDefense->research_poster)
          var files =
            {!! json_encode($skripsiDefense->research_poster) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="research_poster[]" value="' + file.file_name + '">')
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
    Dropzone.options.siakadSupervisorScreenshotDropzone = {
    url: '{{ route('admin.skripsi-defenses.storeMedia') }}',
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
      $('form').find('input[name="siakad_supervisor_screenshot"]').remove()
      $('form').append('<input type="hidden" name="siakad_supervisor_screenshot" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="siakad_supervisor_screenshot"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($skripsiDefense) && $skripsiDefense->siakad_supervisor_screenshot)
      var file = {!! json_encode($skripsiDefense->siakad_supervisor_screenshot) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="siakad_supervisor_screenshot" value="' + file.file_name + '">')
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
    var uploadedSupervisionLogbookMap = {}
Dropzone.options.supervisionLogbookDropzone = {
    url: '{{ route('admin.skripsi-defenses.storeMedia') }}',
    maxFilesize: 10, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="supervision_logbook[]" value="' + response.name + '">')
      uploadedSupervisionLogbookMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedSupervisionLogbookMap[file.name]
      }
      $('form').find('input[name="supervision_logbook[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($skripsiDefense) && $skripsiDefense->supervision_logbook)
          var files =
            {!! json_encode($skripsiDefense->supervision_logbook) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="supervision_logbook[]" value="' + file.file_name + '">')
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