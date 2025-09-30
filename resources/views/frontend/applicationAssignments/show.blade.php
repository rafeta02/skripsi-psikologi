@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.applicationAssignment.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.application-assignments.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationAssignment.fields.application') }}
                                    </th>
                                    <td>
                                        {{ $applicationAssignment->application->status ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationAssignment.fields.lecturer') }}
                                    </th>
                                    <td>
                                        {{ $applicationAssignment->lecturer->nidn ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationAssignment.fields.role') }}
                                    </th>
                                    <td>
                                        {{ App\Models\ApplicationAssignment::ROLE_SELECT[$applicationAssignment->role] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationAssignment.fields.status') }}
                                    </th>
                                    <td>
                                        {{ App\Models\ApplicationAssignment::STATUS_SELECT[$applicationAssignment->status] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationAssignment.fields.assigned_at') }}
                                    </th>
                                    <td>
                                        {{ $applicationAssignment->assigned_at }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationAssignment.fields.responded_at') }}
                                    </th>
                                    <td>
                                        {{ $applicationAssignment->responded_at }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationAssignment.fields.note') }}
                                    </th>
                                    <td>
                                        {{ $applicationAssignment->note }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.application-assignments.index') }}">
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