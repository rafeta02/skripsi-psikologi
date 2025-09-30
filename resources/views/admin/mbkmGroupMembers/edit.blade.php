@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.mbkmGroupMember.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.mbkm-group-members.update", [$mbkmGroupMember->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="mbkm_registration_id">{{ trans('cruds.mbkmGroupMember.fields.mbkm_registration') }}</label>
                <select class="form-control select2 {{ $errors->has('mbkm_registration') ? 'is-invalid' : '' }}" name="mbkm_registration_id" id="mbkm_registration_id">
                    @foreach($mbkm_registrations as $id => $entry)
                        <option value="{{ $id }}" {{ (old('mbkm_registration_id') ? old('mbkm_registration_id') : $mbkmGroupMember->mbkm_registration->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('mbkm_registration'))
                    <span class="text-danger">{{ $errors->first('mbkm_registration') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.mbkmGroupMember.fields.mbkm_registration_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="mahasiswa_id">{{ trans('cruds.mbkmGroupMember.fields.mahasiswa') }}</label>
                <select class="form-control select2 {{ $errors->has('mahasiswa') ? 'is-invalid' : '' }}" name="mahasiswa_id" id="mahasiswa_id">
                    @foreach($mahasiswas as $id => $entry)
                        <option value="{{ $id }}" {{ (old('mahasiswa_id') ? old('mahasiswa_id') : $mbkmGroupMember->mahasiswa->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('mahasiswa'))
                    <span class="text-danger">{{ $errors->first('mahasiswa') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.mbkmGroupMember.fields.mahasiswa_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.mbkmGroupMember.fields.role') }}</label>
                <select class="form-control {{ $errors->has('role') ? 'is-invalid' : '' }}" name="role" id="role">
                    <option value disabled {{ old('role', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\MbkmGroupMember::ROLE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('role', $mbkmGroupMember->role) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('role'))
                    <span class="text-danger">{{ $errors->first('role') }}</span>
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



@endsection