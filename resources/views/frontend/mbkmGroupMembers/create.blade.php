@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.mbkmGroupMember.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.mbkm-group-members.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label for="mbkm_registration_id">{{ trans('cruds.mbkmGroupMember.fields.mbkm_registration') }}</label>
                            <select class="form-control select2" name="mbkm_registration_id" id="mbkm_registration_id">
                                @foreach($mbkm_registrations as $id => $entry)
                                    <option value="{{ $id }}" {{ old('mbkm_registration_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('mbkm_registration'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('mbkm_registration') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mbkmGroupMember.fields.mbkm_registration_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="mahasiswa_id">{{ trans('cruds.mbkmGroupMember.fields.mahasiswa') }}</label>
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
                            <span class="help-block">{{ trans('cruds.mbkmGroupMember.fields.mahasiswa_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('cruds.mbkmGroupMember.fields.role') }}</label>
                            <select class="form-control" name="role" id="role">
                                <option value disabled {{ old('role', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\MbkmGroupMember::ROLE_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('role', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('role'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('role') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mbkmGroupMember.fields.role_helper') }}</span>
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