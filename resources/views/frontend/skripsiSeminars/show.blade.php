@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.skripsiSeminar.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.skripsi-seminars.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.skripsiSeminar.fields.application') }}
                                    </th>
                                    <td>
                                        {{ $skripsiSeminar->application->status ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.skripsiSeminar.fields.title') }}
                                    </th>
                                    <td>
                                        {{ $skripsiSeminar->title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.skripsiSeminar.fields.proposal_document') }}
                                    </th>
                                    <td>
                                        @if($skripsiSeminar->proposal_document)
                                            <a href="{{ $skripsiSeminar->proposal_document->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.skripsiSeminar.fields.approval_document') }}
                                    </th>
                                    <td>
                                        @if($skripsiSeminar->approval_document)
                                            <a href="{{ $skripsiSeminar->approval_document->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.skripsiSeminar.fields.plagiarism_document') }}
                                    </th>
                                    <td>
                                        @if($skripsiSeminar->plagiarism_document)
                                            <a href="{{ $skripsiSeminar->plagiarism_document->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.skripsi-seminars.index') }}">
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