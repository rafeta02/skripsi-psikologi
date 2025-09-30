@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.mbkmSeminar.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.mbkm-seminars.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mbkmSeminar.fields.application') }}
                                    </th>
                                    <td>
                                        {{ $mbkmSeminar->application->status ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mbkmSeminar.fields.title') }}
                                    </th>
                                    <td>
                                        {{ $mbkmSeminar->title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mbkmSeminar.fields.proposal_document') }}
                                    </th>
                                    <td>
                                        @if($mbkmSeminar->proposal_document)
                                            <a href="{{ $mbkmSeminar->proposal_document->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mbkmSeminar.fields.approval_document') }}
                                    </th>
                                    <td>
                                        @if($mbkmSeminar->approval_document)
                                            <a href="{{ $mbkmSeminar->approval_document->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mbkmSeminar.fields.plagiarism_document') }}
                                    </th>
                                    <td>
                                        @if($mbkmSeminar->plagiarism_document)
                                            <a href="{{ $mbkmSeminar->plagiarism_document->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.mbkm-seminars.index') }}">
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