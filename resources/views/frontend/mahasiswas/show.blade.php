@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.mahasiswa.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.mahasiswas.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mahasiswa.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $mahasiswa->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mahasiswa.fields.nim') }}
                                    </th>
                                    <td>
                                        {{ $mahasiswa->nim }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mahasiswa.fields.nama') }}
                                    </th>
                                    <td>
                                        {{ $mahasiswa->nama }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mahasiswa.fields.tahun_masuk') }}
                                    </th>
                                    <td>
                                        {{ $mahasiswa->tahun_masuk }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mahasiswa.fields.kelas') }}
                                    </th>
                                    <td>
                                        {{ App\Models\Mahasiswa::KELAS_SELECT[$mahasiswa->kelas] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mahasiswa.fields.prodi') }}
                                    </th>
                                    <td>
                                        {{ $mahasiswa->prodi->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mahasiswa.fields.jenjang') }}
                                    </th>
                                    <td>
                                        {{ $mahasiswa->jenjang->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mahasiswa.fields.fakultas') }}
                                    </th>
                                    <td>
                                        {{ $mahasiswa->fakultas->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mahasiswa.fields.tanggal_lahir') }}
                                    </th>
                                    <td>
                                        {{ $mahasiswa->tanggal_lahir }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mahasiswa.fields.tempat_lahir') }}
                                    </th>
                                    <td>
                                        {{ $mahasiswa->tempat_lahir }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mahasiswa.fields.gender') }}
                                    </th>
                                    <td>
                                        {{ App\Models\Mahasiswa::GENDER_SELECT[$mahasiswa->gender] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mahasiswa.fields.alamat') }}
                                    </th>
                                    <td>
                                        {{ $mahasiswa->alamat }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.mahasiswas.index') }}">
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