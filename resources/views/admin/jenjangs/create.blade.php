@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.jenjang.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.jenjangs.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="code">{{ trans('cruds.jenjang.fields.code') }}</label>
                <input class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}" type="text" name="code" id="code" value="{{ old('code', '') }}">
                @if($errors->has('code'))
                    <span class="text-danger">{{ $errors->first('code') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.jenjang.fields.code_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="name">{{ trans('cruds.jenjang.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}">
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.jenjang.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="slug">{{ trans('cruds.jenjang.fields.slug') }}</label>
                <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text" name="slug" id="slug" value="{{ old('slug', '') }}">
                @if($errors->has('slug'))
                    <span class="text-danger">{{ $errors->first('slug') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.jenjang.fields.slug_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.jenjang.fields.description') }}</label>
                <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{{ old('description') }}</textarea>
                @if($errors->has('description'))
                    <span class="text-danger">{{ $errors->first('description') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.jenjang.fields.description_helper') }}</span>
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