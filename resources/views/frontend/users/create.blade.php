@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.user.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.users.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="name">{{ trans('cruds.user.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="email">{{ trans('cruds.user.fields.email') }}</label>
                            <input class="form-control" type="email" name="email" id="email" value="{{ old('email') }}" required>
                            @if($errors->has('email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.email_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="password">{{ trans('cruds.user.fields.password') }}</label>
                            <input class="form-control" type="password" name="password" id="password" required>
                            @if($errors->has('password'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('password') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.password_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="roles">{{ trans('cruds.user.fields.roles') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2" name="roles[]" id="roles" multiple required>
                                @foreach($roles as $id => $role)
                                    <option value="{{ $id }}" {{ in_array($id, old('roles', [])) ? 'selected' : '' }}>{{ $role }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('roles'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('roles') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.roles_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="username">{{ trans('cruds.user.fields.username') }}</label>
                            <input class="form-control" type="text" name="username" id="username" value="{{ old('username', '') }}">
                            @if($errors->has('username'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('username') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.username_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="no_hp">{{ trans('cruds.user.fields.no_hp') }}</label>
                            <input class="form-control" type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', '') }}">
                            @if($errors->has('no_hp'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('no_hp') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.no_hp_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="whatshapp">{{ trans('cruds.user.fields.whatshapp') }}</label>
                            <input class="form-control" type="text" name="whatshapp" id="whatshapp" value="{{ old('whatshapp', '') }}">
                            @if($errors->has('whatshapp'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('whatshapp') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.whatshapp_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('cruds.user.fields.level') }}</label>
                            <select class="form-control" name="level" id="level">
                                <option value disabled {{ old('level', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\User::LEVEL_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('level', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('level'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('level') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.level_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="identity_number">{{ trans('cruds.user.fields.identity_number') }}</label>
                            <input class="form-control" type="text" name="identity_number" id="identity_number" value="{{ old('identity_number', '') }}">
                            @if($errors->has('identity_number'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('identity_number') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.identity_number_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="alamat">{{ trans('cruds.user.fields.alamat') }}</label>
                            <textarea class="form-control" name="alamat" id="alamat">{{ old('alamat') }}</textarea>
                            @if($errors->has('alamat'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('alamat') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.alamat_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="photo">{{ trans('cruds.user.fields.photo') }}</label>
                            <div class="needsclick dropzone" id="photo-dropzone">
                            </div>
                            @if($errors->has('photo'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('photo') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.photo_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="mahasiswa_id">{{ trans('cruds.user.fields.mahasiswa') }}</label>
                            <select class="form-control select2" name="mahasiswa_id" id="mahasiswa_id">
                                @foreach($mahasiswas as $id => $entry)
                                    <option value="{{ $id }}" {{ old('mahasiswa_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('mahasiswa'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('mahasiswa') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.mahasiswa_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="dosen_id">{{ trans('cruds.user.fields.dosen') }}</label>
                            <select class="form-control select2" name="dosen_id" id="dosen_id">
                                @foreach($dosens as $id => $entry)
                                    <option value="{{ $id }}" {{ old('dosen_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('dosen'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('dosen') }}
                                </div>
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

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    Dropzone.options.photoDropzone = {
    url: '{{ route('frontend.users.storeMedia') }}',
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