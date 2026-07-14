@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} Laporan Hasil Review Kelayakan Proposal
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.application-result-seminars.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="application_id">{{ trans('cruds.applicationResultSeminar.fields.application') }}</label>
                <select class="form-control select2 {{ $errors->has('application') ? 'is-invalid' : '' }}" name="application_id" id="application_id">
                    @foreach($applications as $id => $entry)
                        <option value="{{ $id }}" {{ old('application_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('application'))
                    <span class="text-danger">{{ $errors->first('application') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label>{{ trans('cruds.applicationResultSeminar.fields.result') }} <span class="text-danger">*</span></label>
                <select class="form-control select2 {{ $errors->has('result') ? 'is-invalid' : '' }}" name="result" id="result" required>
                    <option value="" disabled {{ old('result', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\ApplicationResultSeminar::RESULT_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('result', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('result'))
                    <span class="text-danger">{{ $errors->first('result') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label for="revision_deadline">{{ trans('cruds.applicationResultSeminar.fields.revision_deadline') }}</label>
                <input class="form-control date {{ $errors->has('revision_deadline') ? 'is-invalid' : '' }}" type="text" name="revision_deadline" id="revision_deadline" value="{{ old('revision_deadline') }}">
                @if($errors->has('revision_deadline'))
                    <span class="text-danger">{{ $errors->first('revision_deadline') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label for="note">{{ trans('cruds.applicationResultSeminar.fields.note') }}</label>
                <textarea class="form-control {{ $errors->has('note') ? 'is-invalid' : '' }}" name="note" id="note" rows="3">{{ old('note') }}</textarea>
                @if($errors->has('note'))
                    <span class="text-danger">{{ $errors->first('note') }}</span>
                @endif
            </div>

            <hr>
            <h5 class="mb-3">Dokumen</h5>

            <div class="form-group">
                <label for="form_document">1. {{ trans('cruds.applicationResultSeminar.fields.form_document') }} <span class="text-danger">*</span></label>
                <div class="needsclick dropzone {{ $errors->has('form_document') ? 'is-invalid' : '' }}" id="form_document-dropzone"></div>
                @if($errors->has('form_document'))
                    <span class="text-danger">{{ $errors->first('form_document') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label for="attendance_document">2. {{ trans('cruds.applicationResultSeminar.fields.attendance_document') }} <span class="text-danger">*</span></label>
                <div class="needsclick dropzone {{ $errors->has('attendance_document') ? 'is-invalid' : '' }}" id="attendance_document-dropzone"></div>
                @if($errors->has('attendance_document'))
                    <span class="text-danger">{{ $errors->first('attendance_document') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label for="documentation">3. {{ trans('cruds.applicationResultSeminar.fields.documentation') }} <span class="text-danger">*</span></label>
                <div class="needsclick dropzone {{ $errors->has('documentation') ? 'is-invalid' : '' }}" id="documentation-dropzone"></div>
                @if($errors->has('documentation'))
                    <span class="text-danger">{{ $errors->first('documentation') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label for="meeting_recording_link">4. {{ trans('cruds.applicationResultSeminar.fields.meeting_recording_link') }} <span class="text-muted">(opsional jika online)</span></label>
                <input class="form-control {{ $errors->has('meeting_recording_link') ? 'is-invalid' : '' }}" type="url" name="meeting_recording_link" id="meeting_recording_link" value="{{ old('meeting_recording_link') }}" placeholder="https://...">
                @if($errors->has('meeting_recording_link'))
                    <span class="text-danger">{{ $errors->first('meeting_recording_link') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationResultSeminar.fields.meeting_recording_link_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="latest_script">5. {{ trans('cruds.applicationResultSeminar.fields.latest_script') }} <span class="text-danger">*</span></label>
                <div class="needsclick dropzone {{ $errors->has('latest_script') ? 'is-invalid' : '' }}" id="latest_script-dropzone"></div>
                @if($errors->has('latest_script'))
                    <span class="text-danger">{{ $errors->first('latest_script') }}</span>
                @endif
            </div>

            <div class="form-group">
                <button class="btn btn-danger" type="submit">{{ trans('global.save') }}</button>
                <a href="{{ route('admin.application-result-seminars.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
Dropzone.options.attendanceDocumentDropzone = {
    url: '{{ route('admin.application-result-seminars.storeMedia') }}',
    maxFilesize: 10,
    maxFiles: 1,
    acceptedFiles: '.pdf',
    addRemoveLinks: true,
    headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
    params: { size: 10 },
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
    error: function (file, response) {
         var message = $.type(response) === 'string' ? response : response.errors.file
         file.previewElement.classList.add('dz-error')
         file.previewElement.querySelectorAll('[data-dz-errormessage]').forEach(function (node) { node.textContent = message })
    }
}

var uploadedFormDocumentMap = {}
Dropzone.options.formDocumentDropzone = {
    url: '{{ route('admin.application-result-seminars.storeMedia') }}',
    maxFilesize: 10,
    acceptedFiles: '.pdf',
    addRemoveLinks: true,
    headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
    params: { size: 10 },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="form_document[]" value="' + response.name + '">')
      uploadedFormDocumentMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = typeof file.file_name !== 'undefined' ? file.file_name : uploadedFormDocumentMap[file.name]
      $('form').find('input[name="form_document[]"][value="' + name + '"]').remove()
    },
    error: function (file, response) {
         var message = $.type(response) === 'string' ? response : response.errors.file
         file.previewElement.classList.add('dz-error')
         file.previewElement.querySelectorAll('[data-dz-errormessage]').forEach(function (node) { node.textContent = message })
    }
}

Dropzone.options.latestScriptDropzone = {
    url: '{{ route('admin.application-result-seminars.storeMedia') }}',
    maxFilesize: 10,
    maxFiles: 1,
    acceptedFiles: '.pdf',
    addRemoveLinks: true,
    headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
    params: { size: 10 },
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
    error: function (file, response) {
         var message = $.type(response) === 'string' ? response : response.errors.file
         file.previewElement.classList.add('dz-error')
         file.previewElement.querySelectorAll('[data-dz-errormessage]').forEach(function (node) { node.textContent = message })
    }
}

var uploadedDocumentationMap = {}
Dropzone.options.documentationDropzone = {
    url: '{{ route('admin.application-result-seminars.storeMedia') }}',
    maxFilesize: 5,
    acceptedFiles: 'image/*,.jpg,.jpeg,.png,.webp',
    addRemoveLinks: true,
    headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
    params: { size: 5 },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="documentation[]" value="' + response.name + '">')
      uploadedDocumentationMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = typeof file.file_name !== 'undefined' ? file.file_name : uploadedDocumentationMap[file.name]
      $('form').find('input[name="documentation[]"][value="' + name + '"]').remove()
    },
    error: function (file, response) {
         var message = $.type(response) === 'string' ? response : response.errors.file
         file.previewElement.classList.add('dz-error')
         file.previewElement.querySelectorAll('[data-dz-errormessage]').forEach(function (node) { node.textContent = message })
    }
}
</script>
@endsection
