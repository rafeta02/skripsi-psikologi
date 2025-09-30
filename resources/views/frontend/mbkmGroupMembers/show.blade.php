@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.mbkmGroupMember.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.mbkm-group-members.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mbkmGroupMember.fields.mbkm_registration') }}
                                    </th>
                                    <td>
                                        {{ $mbkmGroupMember->mbkm_registration->title_mbkm ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mbkmGroupMember.fields.mahasiswa') }}
                                    </th>
                                    <td>
                                        {{ $mbkmGroupMember->mahasiswa->nama ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mbkmGroupMember.fields.role') }}
                                    </th>
                                    <td>
                                        {{ App\Models\MbkmGroupMember::ROLE_SELECT[$mbkmGroupMember->role] ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.mbkm-group-members.index') }}">
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