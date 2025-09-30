@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.keilmuan.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.keilmuans.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.keilmuan.fields.name') }}
                        </th>
                        <td>
                            {{ $keilmuan->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.keilmuan.fields.slug') }}
                        </th>
                        <td>
                            {{ $keilmuan->slug }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.keilmuan.fields.description') }}
                        </th>
                        <td>
                            {{ $keilmuan->description }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.keilmuans.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection