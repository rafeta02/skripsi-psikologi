@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.mbkmRegistration.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.mbkm-registrations.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.mbkmRegistration.fields.application') }}
                        </th>
                        <td>
                            {{ $mbkmRegistration->application->status ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.mbkmRegistration.fields.research_group') }}
                        </th>
                        <td>
                            {{ $mbkmRegistration->research_group->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.mbkmRegistration.fields.preference_supervision') }}
                        </th>
                        <td>
                            {{ $mbkmRegistration->preference_supervision->nip ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.mbkmRegistration.fields.theme') }}
                        </th>
                        <td>
                            {{ $mbkmRegistration->theme->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.mbkmRegistration.fields.title_mbkm') }}
                        </th>
                        <td>
                            {{ $mbkmRegistration->title_mbkm }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.mbkmRegistration.fields.title') }}
                        </th>
                        <td>
                            {{ $mbkmRegistration->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.mbkmRegistration.fields.khs_all') }}
                        </th>
                        <td>
                            @foreach($mbkmRegistration->khs_all as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.mbkmRegistration.fields.krs_latest') }}
                        </th>
                        <td>
                            @if($mbkmRegistration->krs_latest)
                                <a href="{{ $mbkmRegistration->krs_latest->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.mbkmRegistration.fields.spp') }}
                        </th>
                        <td>
                            @if($mbkmRegistration->spp)
                                <a href="{{ $mbkmRegistration->spp->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.mbkmRegistration.fields.proposal_mbkm') }}
                        </th>
                        <td>
                            @if($mbkmRegistration->proposal_mbkm)
                                <a href="{{ $mbkmRegistration->proposal_mbkm->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.mbkmRegistration.fields.recognition_form') }}
                        </th>
                        <td>
                            @if($mbkmRegistration->recognition_form)
                                <a href="{{ $mbkmRegistration->recognition_form->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.mbkmRegistration.fields.total_sks_taken') }}
                        </th>
                        <td>
                            {{ $mbkmRegistration->total_sks_taken }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.mbkmRegistration.fields.nilai_mk_kuantitatif') }}
                        </th>
                        <td>
                            {{ $mbkmRegistration->nilai_mk_kuantitatif }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.mbkmRegistration.fields.nilai_mk_kualitatif') }}
                        </th>
                        <td>
                            {{ $mbkmRegistration->nilai_mk_kualitatif }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.mbkmRegistration.fields.nilai_mk_statistika_dasar') }}
                        </th>
                        <td>
                            {{ $mbkmRegistration->nilai_mk_statistika_dasar }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.mbkmRegistration.fields.nilai_mk_statistika_lanjutan') }}
                        </th>
                        <td>
                            {{ $mbkmRegistration->nilai_mk_statistika_lanjutan }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.mbkmRegistration.fields.nilai_mk_konstruksi_tes') }}
                        </th>
                        <td>
                            {{ $mbkmRegistration->nilai_mk_konstruksi_tes }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.mbkmRegistration.fields.nilai_mk_tps') }}
                        </th>
                        <td>
                            {{ $mbkmRegistration->nilai_mk_tps }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.mbkmRegistration.fields.sks_mkp_taken') }}
                        </th>
                        <td>
                            {{ $mbkmRegistration->sks_mkp_taken }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.mbkmRegistration.fields.note') }}
                        </th>
                        <td>
                            {{ $mbkmRegistration->note }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.mbkm-registrations.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection