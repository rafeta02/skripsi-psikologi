@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.faculty.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.faculties.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label for="code">{{ trans('cruds.faculty.fields.code') }}</label>
                            <input class="form-control" type="text" name="code" id="code" value="{{ old('code', '') }}">
                            @if($errors->has('code'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('code') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.faculty.fields.code_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="name">{{ trans('cruds.faculty.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', '') }}">
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.faculty.fields.name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="slug">{{ trans('cruds.faculty.fields.slug') }}</label>
                            <input class="form-control" type="text" name="slug" id="slug" value="{{ old('slug', '') }}">
                            @if($errors->has('slug'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('slug') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.faculty.fields.slug_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="description">{{ trans('cruds.faculty.fields.description') }}</label>
                            <textarea class="form-control" name="description" id="description">{{ old('description') }}</textarea>
                            @if($errors->has('description'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('description') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.faculty.fields.description_helper') }}</span>
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