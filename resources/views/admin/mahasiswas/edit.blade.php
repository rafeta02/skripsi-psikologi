@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.mahasiswa.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.mahasiswas.update", [$mahasiswa->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="nim">{{ trans('cruds.mahasiswa.fields.nim') }}</label>
                <input class="form-control {{ $errors->has('nim') ? 'is-invalid' : '' }}" type="text" name="nim" id="nim" value="{{ old('nim', $mahasiswa->nim) }}">
                @if($errors->has('nim'))
                    <span class="text-danger">{{ $errors->first('nim') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.mahasiswa.fields.nim_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="nama">{{ trans('cruds.mahasiswa.fields.nama') }}</label>
                <input class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}" type="text" name="nama" id="nama" value="{{ old('nama', $mahasiswa->nama) }}">
                @if($errors->has('nama'))
                    <span class="text-danger">{{ $errors->first('nama') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.mahasiswa.fields.nama_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="tahun_masuk">{{ trans('cruds.mahasiswa.fields.tahun_masuk') }}</label>
                <input class="form-control {{ $errors->has('tahun_masuk') ? 'is-invalid' : '' }}" type="number" name="tahun_masuk" id="tahun_masuk" value="{{ old('tahun_masuk', $mahasiswa->tahun_masuk) }}" step="1">
                @if($errors->has('tahun_masuk'))
                    <span class="text-danger">{{ $errors->first('tahun_masuk') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.mahasiswa.fields.tahun_masuk_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.mahasiswa.fields.kelas') }}</label>
                <select class="form-control {{ $errors->has('kelas') ? 'is-invalid' : '' }}" name="kelas" id="kelas">
                    <option value disabled {{ old('kelas', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Mahasiswa::KELAS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('kelas', $mahasiswa->kelas) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('kelas'))
                    <span class="text-danger">{{ $errors->first('kelas') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.mahasiswa.fields.kelas_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="prodi_id">{{ trans('cruds.mahasiswa.fields.prodi') }}</label>
                <select class="form-control select2 {{ $errors->has('prodi') ? 'is-invalid' : '' }}" name="prodi_id" id="prodi_id">
                    @foreach($prodis as $id => $entry)
                        <option value="{{ $id }}" {{ (old('prodi_id') ? old('prodi_id') : $mahasiswa->prodi->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('prodi'))
                    <span class="text-danger">{{ $errors->first('prodi') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.mahasiswa.fields.prodi_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="jenjang_id">{{ trans('cruds.mahasiswa.fields.jenjang') }}</label>
                <select class="form-control select2 {{ $errors->has('jenjang') ? 'is-invalid' : '' }}" name="jenjang_id" id="jenjang_id">
                    @foreach($jenjangs as $id => $entry)
                        <option value="{{ $id }}" {{ (old('jenjang_id') ? old('jenjang_id') : $mahasiswa->jenjang->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('jenjang'))
                    <span class="text-danger">{{ $errors->first('jenjang') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.mahasiswa.fields.jenjang_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="fakultas_id">{{ trans('cruds.mahasiswa.fields.fakultas') }}</label>
                <select class="form-control select2 {{ $errors->has('fakultas') ? 'is-invalid' : '' }}" name="fakultas_id" id="fakultas_id">
                    @foreach($fakultas as $id => $entry)
                        <option value="{{ $id }}" {{ (old('fakultas_id') ? old('fakultas_id') : $mahasiswa->fakultas->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('fakultas'))
                    <span class="text-danger">{{ $errors->first('fakultas') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.mahasiswa.fields.fakultas_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="tanggal_lahir">{{ trans('cruds.mahasiswa.fields.tanggal_lahir') }}</label>
                <input class="form-control date {{ $errors->has('tanggal_lahir') ? 'is-invalid' : '' }}" type="text" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', $mahasiswa->tanggal_lahir) }}">
                @if($errors->has('tanggal_lahir'))
                    <span class="text-danger">{{ $errors->first('tanggal_lahir') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.mahasiswa.fields.tanggal_lahir_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="tempat_lahir">{{ trans('cruds.mahasiswa.fields.tempat_lahir') }}</label>
                <input class="form-control {{ $errors->has('tempat_lahir') ? 'is-invalid' : '' }}" type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir', $mahasiswa->tempat_lahir) }}">
                @if($errors->has('tempat_lahir'))
                    <span class="text-danger">{{ $errors->first('tempat_lahir') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.mahasiswa.fields.tempat_lahir_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.mahasiswa.fields.gender') }}</label>
                <select class="form-control {{ $errors->has('gender') ? 'is-invalid' : '' }}" name="gender" id="gender">
                    <option value disabled {{ old('gender', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Mahasiswa::GENDER_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('gender', $mahasiswa->gender) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('gender'))
                    <span class="text-danger">{{ $errors->first('gender') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.mahasiswa.fields.gender_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="alamat">{{ trans('cruds.mahasiswa.fields.alamat') }}</label>
                <textarea class="form-control {{ $errors->has('alamat') ? 'is-invalid' : '' }}" name="alamat" id="alamat">{{ old('alamat', $mahasiswa->alamat) }}</textarea>
                @if($errors->has('alamat'))
                    <span class="text-danger">{{ $errors->first('alamat') }}</span>
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



@endsection