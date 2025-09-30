@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.skripsiRegistration.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.skripsi-registrations.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.skripsiRegistration.fields.application') }}
                        </th>
                        <td>
                            {{ $skripsiRegistration->application->status ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.skripsiRegistration.fields.theme') }}
                        </th>
                        <td>
                            {{ $skripsiRegistration->theme->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.skripsiRegistration.fields.title') }}
                        </th>
                        <td>
                            {{ $skripsiRegistration->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.skripsiRegistration.fields.abstract') }}
                        </th>
                        <td>
                            {{ $skripsiRegistration->abstract }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.skripsiRegistration.fields.tps_lecturer') }}
                        </th>
                        <td>
                            {{ $skripsiRegistration->tps_lecturer->nama ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.skripsiRegistration.fields.preference_supervision') }}
                        </th>
                        <td>
                            {{ $skripsiRegistration->preference_supervision->nama ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.skripsiRegistration.fields.khs_all') }}
                        </th>
                        <td>
                            @foreach($skripsiRegistration->khs_all as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.skripsiRegistration.fields.krs_latest') }}
                        </th>
                        <td>
                            @if($skripsiRegistration->krs_latest)
                                <a href="{{ $skripsiRegistration->krs_latest->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.skripsi-registrations.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection