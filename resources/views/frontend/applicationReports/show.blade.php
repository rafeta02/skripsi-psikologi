@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.applicationReport.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.application-reports.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationReport.fields.application') }}
                                    </th>
                                    <td>
                                        {{ $applicationReport->application->type ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationReport.fields.report_text') }}
                                    </th>
                                    <td>
                                        {!! $applicationReport->report_text !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationReport.fields.report_document') }}
                                    </th>
                                    <td>
                                        @foreach($applicationReport->report_document as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationReport.fields.period') }}
                                    </th>
                                    <td>
                                        {{ $applicationReport->period }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationReport.fields.status') }}
                                    </th>
                                    <td>
                                        @if($applicationReport->status == 'submitted')
                                            <span class="badge badge-warning">Submitted</span>
                                        @else
                                            <span class="badge badge-success">Reviewed</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        @if($applicationReport->note)
                        <div class="alert alert-info mt-3">
                            <h5><i class="fas fa-comment-alt"></i> Catatan dari Admin</h5>
                            <hr>
                            <p class="mb-0">{{ $applicationReport->note }}</p>
                        </div>
                        @endif

                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.application-reports.index') }}">
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