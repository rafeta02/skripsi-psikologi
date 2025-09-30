@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.ruang.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.ruangs.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="code">{{ trans('cruds.ruang.fields.code') }}</label>
                <input class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}" type="text" name="code" id="code" value="{{ old('code', '') }}">
                @if($errors->has('code'))
                    <span class="text-danger">{{ $errors->first('code') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.ruang.fields.code_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="name">{{ trans('cruds.ruang.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}">
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.ruang.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="slug">{{ trans('cruds.ruang.fields.slug') }}</label>
                <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text" name="slug" id="slug" value="{{ old('slug', '') }}">
                @if($errors->has('slug'))
                    <span class="text-danger">{{ $errors->first('slug') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.ruang.fields.slug_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="location">{{ trans('cruds.ruang.fields.location') }}</label>
                <input class="form-control {{ $errors->has('location') ? 'is-invalid' : '' }}" type="text" name="location" id="location" value="{{ old('location', '') }}">
                @if($errors->has('location'))
                    <span class="text-danger">{{ $errors->first('location') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.ruang.fields.location_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="capacity">{{ trans('cruds.ruang.fields.capacity') }}</label>
                <input class="form-control {{ $errors->has('capacity') ? 'is-invalid' : '' }}" type="number" name="capacity" id="capacity" value="{{ old('capacity', '') }}" step="1">
                @if($errors->has('capacity'))
                    <span class="text-danger">{{ $errors->first('capacity') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.ruang.fields.capacity_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="facility">{{ trans('cruds.ruang.fields.facility') }}</label>
                <textarea class="form-control {{ $errors->has('facility') ? 'is-invalid' : '' }}" name="facility" id="facility">{{ old('facility') }}</textarea>
                @if($errors->has('facility'))
                    <span class="text-danger">{{ $errors->first('facility') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.ruang.fields.facility_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.ruang.fields.description') }}</label>
                <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{{ old('description') }}</textarea>
                @if($errors->has('description'))
                    <span class="text-danger">{{ $errors->first('description') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.ruang.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="image">{{ trans('cruds.ruang.fields.image') }}</label>
                <div class="needsclick dropzone {{ $errors->has('image') ? 'is-invalid' : '' }}" id="image-dropzone">
                </div>
                @if($errors->has('image'))
                    <span class="text-danger">{{ $errors->first('image') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.ruang.fields.image_helper') }}</span>
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
    Dropzone.options.imageDropzone = {
    url: '{{ route('admin.ruangs.storeMedia') }}',
    maxFilesize: 10, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="image"]').remove()
      $('form').append('<input type="hidden" name="image" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="image"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($ruang) && $ruang->image)
      var file = {!! json_encode($ruang->image) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="image" value="' + file.file_name + '">')
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