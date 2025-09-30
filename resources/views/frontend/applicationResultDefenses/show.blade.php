@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.applicationResultDefense.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.application-result-defenses.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationResultDefense.fields.application') }}
                                    </th>
                                    <td>
                                        {{ $applicationResultDefense->application->status ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationResultDefense.fields.result') }}
                                    </th>
                                    <td>
                                        {{ App\Models\ApplicationResultDefense::RESULT_SELECT[$applicationResultDefense->result] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationResultDefense.fields.note') }}
                                    </th>
                                    <td>
                                        {{ $applicationResultDefense->note }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationResultDefense.fields.revision_deadline') }}
                                    </th>
                                    <td>
                                        {{ $applicationResultDefense->revision_deadline }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationResultDefense.fields.final_grade') }}
                                    </th>
                                    <td>
                                        {{ $applicationResultDefense->final_grade }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationResultDefense.fields.documentation') }}
                                    </th>
                                    <td>
                                        @foreach($applicationResultDefense->documentation as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationResultDefense.fields.invitation_document') }}
                                    </th>
                                    <td>
                                        @foreach($applicationResultDefense->invitation_document as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationResultDefense.fields.feedback_document') }}
                                    </th>
                                    <td>
                                        @foreach($applicationResultDefense->feedback_document as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationResultDefense.fields.minutes_document') }}
                                    </th>
                                    <td>
                                        @if($applicationResultDefense->minutes_document)
                                            <a href="{{ $applicationResultDefense->minutes_document->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationResultDefense.fields.latest_script') }}
                                    </th>
                                    <td>
                                        @if($applicationResultDefense->latest_script)
                                            <a href="{{ $applicationResultDefense->latest_script->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationResultDefense.fields.approval_page') }}
                                    </th>
                                    <td>
                                        @if($applicationResultDefense->approval_page)
                                            <a href="{{ $applicationResultDefense->approval_page->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationResultDefense.fields.report_document') }}
                                    </th>
                                    <td>
                                        @foreach($applicationResultDefense->report_document as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationResultDefense.fields.revision_approval_sheet') }}
                                    </th>
                                    <td>
                                        @foreach($applicationResultDefense->revision_approval_sheet as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.application-result-defenses.index') }}">
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