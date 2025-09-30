@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.ruang.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.ruangs.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.ruang.fields.code') }}
                        </th>
                        <td>
                            {{ $ruang->code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ruang.fields.name') }}
                        </th>
                        <td>
                            {{ $ruang->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ruang.fields.slug') }}
                        </th>
                        <td>
                            {{ $ruang->slug }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ruang.fields.location') }}
                        </th>
                        <td>
                            {{ $ruang->location }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ruang.fields.capacity') }}
                        </th>
                        <td>
                            {{ $ruang->capacity }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ruang.fields.facility') }}
                        </th>
                        <td>
                            {{ $ruang->facility }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ruang.fields.description') }}
                        </th>
                        <td>
                            {{ $ruang->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ruang.fields.image') }}
                        </th>
                        <td>
                            @if($ruang->image)
                                <a href="{{ $ruang->image->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $ruang->image->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.ruangs.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection