@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.user.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.users.update", [$user->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.user.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="email">{{ trans('cruds.user.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
                @if($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="password">{{ trans('cruds.user.fields.password') }}</label>
                <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password" name="password" id="password">
                @if($errors->has('password'))
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.password_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="roles">{{ trans('cruds.user.fields.roles') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('roles') ? 'is-invalid' : '' }}" name="roles[]" id="roles" multiple required>
                    @foreach($roles as $id => $role)
                        <option value="{{ $id }}" {{ (in_array($id, old('roles', [])) || $user->roles->contains($id)) ? 'selected' : '' }}>{{ $role }}</option>
                    @endforeach
                </select>
                @if($errors->has('roles'))
                    <span class="text-danger">{{ $errors->first('roles') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.roles_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="username">{{ trans('cruds.user.fields.username') }}</label>
                <input class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}" type="text" name="username" id="username" value="{{ old('username', $user->username) }}">
                @if($errors->has('username'))
                    <span class="text-danger">{{ $errors->first('username') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.username_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="no_hp">{{ trans('cruds.user.fields.no_hp') }}</label>
                <input class="form-control {{ $errors->has('no_hp') ? 'is-invalid' : '' }}" type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', $user->no_hp) }}">
                @if($errors->has('no_hp'))
                    <span class="text-danger">{{ $errors->first('no_hp') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.no_hp_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="whatshapp">{{ trans('cruds.user.fields.whatshapp') }}</label>
                <input class="form-control {{ $errors->has('whatshapp') ? 'is-invalid' : '' }}" type="text" name="whatshapp" id="whatshapp" value="{{ old('whatshapp', $user->whatshapp) }}">
                @if($errors->has('whatshapp'))
                    <span class="text-danger">{{ $errors->first('whatshapp') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.whatshapp_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.user.fields.level') }}</label>
                <select class="form-control {{ $errors->has('level') ? 'is-invalid' : '' }}" name="level" id="level">
                    <option value disabled {{ old('level', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\User::LEVEL_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('level', $user->level) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('level'))
                    <span class="text-danger">{{ $errors->first('level') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.level_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="identity_number">{{ trans('cruds.user.fields.identity_number') }}</label>
                <input class="form-control {{ $errors->has('identity_number') ? 'is-invalid' : '' }}" type="text" name="identity_number" id="identity_number" value="{{ old('identity_number', $user->identity_number) }}">
                @if($errors->has('identity_number'))
                    <span class="text-danger">{{ $errors->first('identity_number') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.identity_number_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="alamat">{{ trans('cruds.user.fields.alamat') }}</label>
                <textarea class="form-control {{ $errors->has('alamat') ? 'is-invalid' : '' }}" name="alamat" id="alamat">{{ old('alamat', $user->alamat) }}</textarea>
                @if($errors->has('alamat'))
                    <span class="text-danger">{{ $errors->first('alamat') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.alamat_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="photo">{{ trans('cruds.user.fields.photo') }}</label>
                <div class="needsclick dropzone {{ $errors->has('photo') ? 'is-invalid' : '' }}" id="photo-dropzone">
                </div>
                @if($errors->has('photo'))
                    <span class="text-danger">{{ $errors->first('photo') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.photo_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="mahasiswa_id">{{ trans('cruds.user.fields.mahasiswa') }}</label>
                <select class="form-control select2 {{ $errors->has('mahasiswa') ? 'is-invalid' : '' }}" name="mahasiswa_id" id="mahasiswa_id">
                    @foreach($mahasiswas as $id => $entry)
                        <option value="{{ $id }}" {{ (old('mahasiswa_id') ? old('mahasiswa_id') : $user->mahasiswa->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('mahasiswa'))
                    <span class="text-danger">{{ $errors->first('mahasiswa') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.mahasiswa_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="dosen_id">{{ trans('cruds.user.fields.dosen') }}</label>
                <select class="form-control select2 {{ $errors->has('dosen') ? 'is-invalid' : '' }}" name="dosen_id" id="dosen_id">
                    @foreach($dosens as $id => $entry)
                        <option value="{{ $id }}" {{ (old('dosen_id') ? old('dosen_id') : $user->dosen->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('dosen'))
                    <span class="text-danger">{{ $errors->first('dosen') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.dosen_helper') }}</span>
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
    Dropzone.options.photoDropzone = {
    url: '{{ route('admin.users.storeMedia') }}',
    maxFilesize: 5, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 5,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="photo"]').remove()
      $('form').append('<input type="hidden" name="photo" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="photo"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($user) && $user->photo)
      var file = {!! json_encode($user->photo) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="photo" value="' + file.file_name + '">')
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