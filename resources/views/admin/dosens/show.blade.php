@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.dosen.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.dosens.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.dosen.fields.nip') }}
                        </th>
                        <td>
                            {{ $dosen->nip }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.dosen.fields.nidn') }}
                        </th>
                        <td>
                            {{ $dosen->nidn }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.dosen.fields.nama') }}
                        </th>
                        <td>
                            {{ $dosen->nama }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.dosen.fields.tempat_lahir') }}
                        </th>
                        <td>
                            {{ $dosen->tempat_lahir }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.dosen.fields.tanggal_lahir') }}
                        </th>
                        <td>
                            {{ $dosen->tanggal_lahir }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.dosen.fields.gender') }}
                        </th>
                        <td>
                            {{ App\Models\Dosen::GENDER_SELECT[$dosen->gender] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.dosen.fields.prodi') }}
                        </th>
                        <td>
                            {{ $dosen->prodi->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.dosen.fields.jenjang') }}
                        </th>
                        <td>
                            {{ $dosen->jenjang->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.dosen.fields.fakultas') }}
                        </th>
                        <td>
                            {{ $dosen->fakultas->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.dosen.fields.keilmuan') }}
                        </th>
                        <td>
                            @foreach($dosen->keilmuans as $key => $keilmuan)
                                <span class="label label-info">{{ $keilmuan->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.dosen.fields.riset_grup') }}
                        </th>
                        <td>
                            {{ $dosen->riset_grup->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.dosens.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection