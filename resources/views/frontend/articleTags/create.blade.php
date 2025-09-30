@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.articleTag.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.article-tags.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="name">{{ trans('cruds.articleTag.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.articleTag.fields.name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="description">{{ trans('cruds.articleTag.fields.description') }}</label>
                            <textarea class="form-control" name="description" id="description">{{ old('description') }}</textarea>
                            @if($errors->has('description'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('description') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.articleTag.fields.description_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <div>
                                <input type="hidden" name="featured" value="0">
                                <input type="checkbox" name="featured" id="featured" value="1" {{ old('featured', 0) == 1 ? 'checked' : '' }}>
                                <label for="featured">{{ trans('cruds.articleTag.fields.featured') }}</label>
                            </div>
                            @if($errors->has('featured'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('featured') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.articleTag.fields.featured_helper') }}</span>
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