@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.jenjang.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.jenjangs.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.jenjang.fields.code') }}
                                    </th>
                                    <td>
                                        {{ $jenjang->code }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.jenjang.fields.name') }}
                                    </th>
                                    <td>
                                        {{ $jenjang->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.jenjang.fields.slug') }}
                                    </th>
                                    <td>
                                        {{ $jenjang->slug }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.jenjang.fields.description') }}
                                    </th>
                                    <td>
                                        {{ $jenjang->description }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.jenjangs.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection