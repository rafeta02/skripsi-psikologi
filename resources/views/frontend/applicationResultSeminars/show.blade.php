@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.applicationResultSeminar.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.application-result-seminars.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationResultSeminar.fields.application') }}
                                    </th>
                                    <td>
                                        {{ $applicationResultSeminar->application->status ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationResultSeminar.fields.result') }}
                                    </th>
                                    <td>
                                        {{ App\Models\ApplicationResultSeminar::RESULT_SELECT[$applicationResultSeminar->result] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationResultSeminar.fields.note') }}
                                    </th>
                                    <td>
                                        {{ $applicationResultSeminar->note }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationResultSeminar.fields.revision_deadline') }}
                                    </th>
                                    <td>
                                        {{ $applicationResultSeminar->revision_deadline }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationResultSeminar.fields.report_document') }}
                                    </th>
                                    <td>
                                        @foreach($applicationResultSeminar->report_document as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationResultSeminar.fields.attendance_document') }}
                                    </th>
                                    <td>
                                        @if($applicationResultSeminar->attendance_document)
                                            <a href="{{ $applicationResultSeminar->attendance_document->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationResultSeminar.fields.form_document') }}
                                    </th>
                                    <td>
                                        @foreach($applicationResultSeminar->form_document as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationResultSeminar.fields.latest_script') }}
                                    </th>
                                    <td>
                                        @if($applicationResultSeminar->latest_script)
                                            <a href="{{ $applicationResultSeminar->latest_script->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationResultSeminar.fields.documentation') }}
                                    </th>
                                    <td>
                                        @foreach($applicationResultSeminar->documentation as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.application-result-seminars.index') }}">
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