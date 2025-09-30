@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.mahasiswa.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.mahasiswas.update", [$mahasiswa->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="nim">{{ trans('cruds.mahasiswa.fields.nim') }}</label>
                            <input class="form-control" type="text" name="nim" id="nim" value="{{ old('nim', $mahasiswa->nim) }}">
                            @if($errors->has('nim'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('nim') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mahasiswa.fields.nim_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="nama">{{ trans('cruds.mahasiswa.fields.nama') }}</label>
                            <input class="form-control" type="text" name="nama" id="nama" value="{{ old('nama', $mahasiswa->nama) }}">
                            @if($errors->has('nama'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('nama') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mahasiswa.fields.nama_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="tahun_masuk">{{ trans('cruds.mahasiswa.fields.tahun_masuk') }}</label>
                            <input class="form-control" type="number" name="tahun_masuk" id="tahun_masuk" value="{{ old('tahun_masuk', $mahasiswa->tahun_masuk) }}" step="1">
                            @if($errors->has('tahun_masuk'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('tahun_masuk') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mahasiswa.fields.tahun_masuk_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('cruds.mahasiswa.fields.kelas') }}</label>
                            <select class="form-control" name="kelas" id="kelas">
                                <option value disabled {{ old('kelas', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\Mahasiswa::KELAS_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('kelas', $mahasiswa->kelas) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('kelas'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('kelas') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mahasiswa.fields.kelas_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="prodi_id">{{ trans('cruds.mahasiswa.fields.prodi') }}</label>
                            <select class="form-control select2" name="prodi_id" id="prodi_id">
                                @foreach($prodis as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('prodi_id') ? old('prodi_id') : $mahasiswa->prodi->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('prodi'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('prodi') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mahasiswa.fields.prodi_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="jenjang_id">{{ trans('cruds.mahasiswa.fields.jenjang') }}</label>
                            <select class="form-control select2" name="jenjang_id" id="jenjang_id">
                                @foreach($jenjangs as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('jenjang_id') ? old('jenjang_id') : $mahasiswa->jenjang->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('jenjang'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('jenjang') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mahasiswa.fields.jenjang_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="fakultas_id">{{ trans('cruds.mahasiswa.fields.fakultas') }}</label>
                            <select class="form-control select2" name="fakultas_id" id="fakultas_id">
                                @foreach($fakultas as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('fakultas_id') ? old('fakultas_id') : $mahasiswa->fakultas->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('fakultas'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('fakultas') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mahasiswa.fields.fakultas_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_lahir">{{ trans('cruds.mahasiswa.fields.tanggal_lahir') }}</label>
                            <input class="form-control date" type="text" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', $mahasiswa->tanggal_lahir) }}">
                            @if($errors->has('tanggal_lahir'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('tanggal_lahir') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mahasiswa.fields.tanggal_lahir_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="tempat_lahir">{{ trans('cruds.mahasiswa.fields.tempat_lahir') }}</label>
                            <input class="form-control" type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir', $mahasiswa->tempat_lahir) }}">
                            @if($errors->has('tempat_lahir'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('tempat_lahir') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mahasiswa.fields.tempat_lahir_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('cruds.mahasiswa.fields.gender') }}</label>
                            <select class="form-control" name="gender" id="gender">
                                <option value disabled {{ old('gender', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\Mahasiswa::GENDER_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('gender', $mahasiswa->gender) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('gender'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('gender') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mahasiswa.fields.gender_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="alamat">{{ trans('cruds.mahasiswa.fields.alamat') }}</label>
                            <textarea class="form-control" name="alamat" id="alamat">{{ old('alamat', $mahasiswa->alamat) }}</textarea>
                            @if($errors->has('alamat'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('alamat') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mahasiswa.fields.alamat_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                {{ trans('global.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection