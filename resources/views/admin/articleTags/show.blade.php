@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.articleTag.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.article-tags.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.articleTag.fields.name') }}
                        </th>
                        <td>
                            {{ $articleTag->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.articleTag.fields.slug') }}
                        </th>
                        <td>
                            {{ $articleTag->slug }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.articleTag.fields.description') }}
                        </th>
                        <td>
                            {{ $articleTag->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.articleTag.fields.featured') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $articleTag->featured ? 'checked' : '' }}>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.article-tags.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection