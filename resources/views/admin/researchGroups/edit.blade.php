@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.researchGroup.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.research-groups.update", [$researchGroup->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="name">{{ trans('cruds.researchGroup.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $researchGroup->name) }}">
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.researchGroup.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="slug">{{ trans('cruds.researchGroup.fields.slug') }}</label>
                <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text" name="slug" id="slug" value="{{ old('slug', $researchGroup->slug) }}">
                @if($errors->has('slug'))
                    <span class="text-danger">{{ $errors->first('slug') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.researchGroup.fields.slug_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.researchGroup.fields.description') }}</label>
                <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{{ old('description', $researchGroup->description) }}</textarea>
                @if($errors->has('description'))
                    <span class="text-danger">{{ $errors->first('description') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.researchGroup.fields.description_helper') }}</span>
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