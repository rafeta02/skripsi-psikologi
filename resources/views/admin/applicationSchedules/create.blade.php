@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.applicationSchedule.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.application-schedules.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="application_id">{{ trans('cruds.applicationSchedule.fields.application') }}</label>
                <select class="form-control select2 {{ $errors->has('application') ? 'is-invalid' : '' }}" name="application_id" id="application_id">
                    @foreach($applications as $id => $entry)
                        <option value="{{ $id }}" {{ old('application_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('application'))
                    <span class="text-danger">{{ $errors->first('application') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationSchedule.fields.application_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.applicationSchedule.fields.schedule_type') }}</label>
                <select class="form-control {{ $errors->has('schedule_type') ? 'is-invalid' : '' }}" name="schedule_type" id="schedule_type">
                    <option value disabled {{ old('schedule_type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\ApplicationSchedule::SCHEDULE_TYPE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('schedule_type', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('schedule_type'))
                    <span class="text-danger">{{ $errors->first('schedule_type') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationSchedule.fields.schedule_type_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="waktu">{{ trans('cruds.applicationSchedule.fields.waktu') }}</label>
                <input class="form-control datetime {{ $errors->has('waktu') ? 'is-invalid' : '' }}" type="text" name="waktu" id="waktu" value="{{ old('waktu') }}">
                @if($errors->has('waktu'))
                    <span class="text-danger">{{ $errors->first('waktu') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationSchedule.fields.waktu_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="ruang_id">{{ trans('cruds.applicationSchedule.fields.ruang') }}</label>
                <select class="form-control select2 {{ $errors->has('ruang') ? 'is-invalid' : '' }}" name="ruang_id" id="ruang_id">
                    @foreach($ruangs as $id => $entry)
                        <option value="{{ $id }}" {{ old('ruang_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('ruang'))
                    <span class="text-danger">{{ $errors->first('ruang') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationSchedule.fields.ruang_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="custom_place">{{ trans('cruds.applicationSchedule.fields.custom_place') }}</label>
                <input class="form-control {{ $errors->has('custom_place') ? 'is-invalid' : '' }}" type="text" name="custom_place" id="custom_place" value="{{ old('custom_place', '') }}">
                @if($errors->has('custom_place'))
                    <span class="text-danger">{{ $errors->first('custom_place') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationSchedule.fields.custom_place_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="online_meeting">{{ trans('cruds.applicationSchedule.fields.online_meeting') }}</label>
                <input class="form-control {{ $errors->has('online_meeting') ? 'is-invalid' : '' }}" type="text" name="online_meeting" id="online_meeting" value="{{ old('online_meeting', '') }}">
                @if($errors->has('online_meeting'))
                    <span class="text-danger">{{ $errors->first('online_meeting') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationSchedule.fields.online_meeting_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="approval_form">{{ trans('cruds.applicationSchedule.fields.approval_form') }}</label>
                <div class="needsclick dropzone {{ $errors->has('approval_form') ? 'is-invalid' : '' }}" id="approval_form-dropzone">
                </div>
                @if($errors->has('approval_form'))
                    <span class="text-danger">{{ $errors->first('approval_form') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationSchedule.fields.approval_form_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="note">{{ trans('cruds.applicationSchedule.fields.note') }}</label>
                <textarea class="form-control {{ $errors->has('note') ? 'is-invalid' : '' }}" name="note" id="note">{{ old('note') }}</textarea>
                @if($errors->has('note'))
                    <span class="text-danger">{{ $errors->first('note') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationSchedule.fields.note_helper') }}</span>
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
    var uploadedApprovalFormMap = {}
Dropzone.options.approvalFormDropzone = {
    url: '{{ route('admin.application-schedules.storeMedia') }}',
    maxFilesize: 10, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="approval_form[]" value="' + response.name + '">')
      uploadedApprovalFormMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedApprovalFormMap[file.name]
      }
      $('form').find('input[name="approval_form[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($applicationSchedule) && $applicationSchedule->approval_form)
          var files =
            {!! json_encode($applicationSchedule->approval_form) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="approval_form[]" value="' + file.file_name + '">')
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