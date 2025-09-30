@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.researchGroup.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.research-groups.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.researchGroup.fields.name') }}
                                    </th>
                                    <td>
                                        {{ $researchGroup->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.researchGroup.fields.slug') }}
                                    </th>
                                    <td>
                                        {{ $researchGroup->slug }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.researchGroup.fields.description') }}
                                    </th>
                                    <td>
                                        {{ $researchGroup->description }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.research-groups.index') }}">
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