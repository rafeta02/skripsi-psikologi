@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.user.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.users.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.name') }}
                        </th>
                        <td>
                            {{ $user->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.email') }}
                        </th>
                        <td>
                            {{ $user->email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.email_verified_at') }}
                        </th>
                        <td>
                            {{ $user->email_verified_at }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.roles') }}
                        </th>
                        <td>
                            @foreach($user->roles as $key => $roles)
                                <span class="label label-info">{{ $roles->title }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.username') }}
                        </th>
                        <td>
                            {{ $user->username }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.no_hp') }}
                        </th>
                        <td>
                            {{ $user->no_hp }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.whatshapp') }}
                        </th>
                        <td>
                            {{ $user->whatshapp }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.level') }}
                        </th>
                        <td>
                            {{ App\Models\User::LEVEL_SELECT[$user->level] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.identity_number') }}
                        </th>
                        <td>
                            {{ $user->identity_number }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.alamat') }}
                        </th>
                        <td>
                            {{ $user->alamat }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.photo') }}
                        </th>
                        <td>
                            @if($user->photo)
                                <a href="{{ $user->photo->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $user->photo->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.mahasiswa') }}
                        </th>
                        <td>
                            {{ $user->mahasiswa->nama ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.dosen') }}
                        </th>
                        <td>
                            {{ $user->dosen->nama ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.users.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection