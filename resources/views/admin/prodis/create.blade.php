@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.prodi.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.prodis.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="code">{{ trans('cruds.prodi.fields.code') }}</label>
                <input class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}" type="text" name="code" id="code" value="{{ old('code', '') }}">
                @if($errors->has('code'))
                    <span class="text-danger">{{ $errors->first('code') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.prodi.fields.code_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="name">{{ trans('cruds.prodi.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}">
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.prodi.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="slug">{{ trans('cruds.prodi.fields.slug') }}</label>
                <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text" name="slug" id="slug" value="{{ old('slug', '') }}">
                @if($errors->has('slug'))
                    <span class="text-danger">{{ $errors->first('slug') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.prodi.fields.slug_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="jenjang_id">{{ trans('cruds.prodi.fields.jenjang') }}</label>
                <select class="form-control select2 {{ $errors->has('jenjang') ? 'is-invalid' : '' }}" name="jenjang_id" id="jenjang_id">
                    @foreach($jenjangs as $id => $entry)
                        <option value="{{ $id }}" {{ old('jenjang_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('jenjang'))
                    <span class="text-danger">{{ $errors->first('jenjang') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.prodi.fields.jenjang_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="fakultas_id">{{ trans('cruds.prodi.fields.fakultas') }}</label>
                <select class="form-control select2 {{ $errors->has('fakultas') ? 'is-invalid' : '' }}" name="fakultas_id" id="fakultas_id">
                    @foreach($fakultas as $id => $entry)
                        <option value="{{ $id }}" {{ old('fakultas_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('fakultas'))
                    <span class="text-danger">{{ $errors->first('fakultas') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.prodi.fields.fakultas_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.prodi.fields.description') }}</label>
                <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{{ old('description') }}</textarea>
                @if($errors->has('description'))
                    <span class="text-danger">{{ $errors->first('description') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.prodi.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('status') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="status" value="0">
                    <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ old('status', 0) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="status">{{ trans('cruds.prodi.fields.status') }}</label>
                </div>
                @if($errors->has('status'))
                    <span class="text-danger">{{ $errors->first('status') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.prodi.fields.status_helper') }}</span>
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