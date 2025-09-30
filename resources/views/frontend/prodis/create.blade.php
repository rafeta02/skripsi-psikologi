@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.prodi.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.prodis.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label for="code">{{ trans('cruds.prodi.fields.code') }}</label>
                            <input class="form-control" type="text" name="code" id="code" value="{{ old('code', '') }}">
                            @if($errors->has('code'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('code') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.prodi.fields.code_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="name">{{ trans('cruds.prodi.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', '') }}">
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.prodi.fields.name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="slug">{{ trans('cruds.prodi.fields.slug') }}</label>
                            <input class="form-control" type="text" name="slug" id="slug" value="{{ old('slug', '') }}">
                            @if($errors->has('slug'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('slug') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.prodi.fields.slug_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="jenjang_id">{{ trans('cruds.prodi.fields.jenjang') }}</label>
                            <select class="form-control select2" name="jenjang_id" id="jenjang_id">
                                @foreach($jenjangs as $id => $entry)
                                    <option value="{{ $id }}" {{ old('jenjang_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('jenjang'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('jenjang') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.prodi.fields.jenjang_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="fakultas_id">{{ trans('cruds.prodi.fields.fakultas') }}</label>
                            <select class="form-control select2" name="fakultas_id" id="fakultas_id">
                                @foreach($fakultas as $id => $entry)
                                    <option value="{{ $id }}" {{ old('fakultas_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('fakultas'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('fakultas') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.prodi.fields.fakultas_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="description">{{ trans('cruds.prodi.fields.description') }}</label>
                            <textarea class="form-control" name="description" id="description">{{ old('description') }}</textarea>
                            @if($errors->has('description'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('description') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.prodi.fields.description_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <div>
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" name="status" id="status" value="1" {{ old('status', 0) == 1 ? 'checked' : '' }}>
                                <label for="status">{{ trans('cruds.prodi.fields.status') }}</label>
                            </div>
                            @if($errors->has('status'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('status') }}
                                </div>
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

        </div>
    </div>
</div>
@endsection