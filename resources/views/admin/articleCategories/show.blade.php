@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.articleCategory.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.article-categories.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.articleCategory.fields.name') }}
                        </th>
                        <td>
                            {{ $articleCategory->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.articleCategory.fields.slug') }}
                        </th>
                        <td>
                            {{ $articleCategory->slug }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.articleCategory.fields.description') }}
                        </th>
                        <td>
                            {{ $articleCategory->description }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.article-categories.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection