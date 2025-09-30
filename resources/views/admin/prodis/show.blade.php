@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.prodi.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.prodis.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.prodi.fields.code') }}
                        </th>
                        <td>
                            {{ $prodi->code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.prodi.fields.name') }}
                        </th>
                        <td>
                            {{ $prodi->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.prodi.fields.slug') }}
                        </th>
                        <td>
                            {{ $prodi->slug }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.prodi.fields.jenjang') }}
                        </th>
                        <td>
                            {{ $prodi->jenjang->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.prodi.fields.fakultas') }}
                        </th>
                        <td>
                            {{ $prodi->fakultas->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.prodi.fields.description') }}
                        </th>
                        <td>
                            {{ $prodi->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.prodi.fields.status') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $prodi->status ? 'checked' : '' }}>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.prodis.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection