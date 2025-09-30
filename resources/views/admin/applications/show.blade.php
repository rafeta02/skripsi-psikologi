@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.application.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.applications.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.mahasiswa') }}
                        </th>
                        <td>
                            {{ $application->mahasiswa->nama ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.type') }}
                        </th>
                        <td>
                            {{ App\Models\Application::TYPE_SELECT[$application->type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.stage') }}
                        </th>
                        <td>
                            {{ App\Models\Application::STAGE_SELECT[$application->stage] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\Application::STATUS_SELECT[$application->status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.submitted_at') }}
                        </th>
                        <td>
                            {{ $application->submitted_at }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.notes') }}
                        </th>
                        <td>
                            {!! $application->notes !!}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.applications.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection