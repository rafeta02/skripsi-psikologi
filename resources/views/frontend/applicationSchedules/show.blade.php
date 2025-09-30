@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.applicationSchedule.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.application-schedules.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationSchedule.fields.application') }}
                                    </th>
                                    <td>
                                        {{ $applicationSchedule->application->status ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationSchedule.fields.schedule_type') }}
                                    </th>
                                    <td>
                                        {{ App\Models\ApplicationSchedule::SCHEDULE_TYPE_SELECT[$applicationSchedule->schedule_type] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationSchedule.fields.waktu') }}
                                    </th>
                                    <td>
                                        {{ $applicationSchedule->waktu }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationSchedule.fields.ruang') }}
                                    </th>
                                    <td>
                                        {{ $applicationSchedule->ruang->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationSchedule.fields.custom_place') }}
                                    </th>
                                    <td>
                                        {{ $applicationSchedule->custom_place }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationSchedule.fields.online_meeting') }}
                                    </th>
                                    <td>
                                        {{ $applicationSchedule->online_meeting }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationSchedule.fields.approval_form') }}
                                    </th>
                                    <td>
                                        @foreach($applicationSchedule->approval_form as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationSchedule.fields.note') }}
                                    </th>
                                    <td>
                                        {{ $applicationSchedule->note }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.application-schedules.index') }}">
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