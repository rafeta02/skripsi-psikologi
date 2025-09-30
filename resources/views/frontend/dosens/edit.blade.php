@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.dosen.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.dosens.update", [$dosen->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="nip">{{ trans('cruds.dosen.fields.nip') }}</label>
                            <input class="form-control" type="text" name="nip" id="nip" value="{{ old('nip', $dosen->nip) }}">
                            @if($errors->has('nip'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('nip') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.dosen.fields.nip_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="nidn">{{ trans('cruds.dosen.fields.nidn') }}</label>
                            <input class="form-control" type="text" name="nidn" id="nidn" value="{{ old('nidn', $dosen->nidn) }}">
                            @if($errors->has('nidn'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('nidn') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.dosen.fields.nidn_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="nama">{{ trans('cruds.dosen.fields.nama') }}</label>
                            <input class="form-control" type="text" name="nama" id="nama" value="{{ old('nama', $dosen->nama) }}">
                            @if($errors->has('nama'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('nama') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.dosen.fields.nama_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="tempat_lahir">{{ trans('cruds.dosen.fields.tempat_lahir') }}</label>
                            <input class="form-control" type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir', $dosen->tempat_lahir) }}">
                            @if($errors->has('tempat_lahir'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('tempat_lahir') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.dosen.fields.tempat_lahir_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_lahir">{{ trans('cruds.dosen.fields.tanggal_lahir') }}</label>
                            <input class="form-control date" type="text" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', $dosen->tanggal_lahir) }}">
                            @if($errors->has('tanggal_lahir'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('tanggal_lahir') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.dosen.fields.tanggal_lahir_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('cruds.dosen.fields.gender') }}</label>
                            <select class="form-control" name="gender" id="gender">
                                <option value disabled {{ old('gender', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\Dosen::GENDER_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('gender', $dosen->gender) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('gender'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('gender') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.dosen.fields.gender_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="prodi_id">{{ trans('cruds.dosen.fields.prodi') }}</label>
                            <select class="form-control select2" name="prodi_id" id="prodi_id">
                                @foreach($prodis as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('prodi_id') ? old('prodi_id') : $dosen->prodi->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('prodi'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('prodi') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.dosen.fields.prodi_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="jenjang_id">{{ trans('cruds.dosen.fields.jenjang') }}</label>
                            <select class="form-control select2" name="jenjang_id" id="jenjang_id">
                                @foreach($jenjangs as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('jenjang_id') ? old('jenjang_id') : $dosen->jenjang->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('jenjang'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('jenjang') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.dosen.fields.jenjang_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="fakultas_id">{{ trans('cruds.dosen.fields.fakultas') }}</label>
                            <select class="form-control select2" name="fakultas_id" id="fakultas_id">
                                @foreach($fakultas as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('fakultas_id') ? old('fakultas_id') : $dosen->fakultas->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('fakultas'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('fakultas') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.dosen.fields.fakultas_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="keilmuans">{{ trans('cruds.dosen.fields.keilmuan') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2" name="keilmuans[]" id="keilmuans" multiple>
                                @foreach($keilmuans as $id => $keilmuan)
                                    <option value="{{ $id }}" {{ (in_array($id, old('keilmuans', [])) || $dosen->keilmuans->contains($id)) ? 'selected' : '' }}>{{ $keilmuan }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('keilmuans'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('keilmuans') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.dosen.fields.keilmuan_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="riset_grup_id">{{ trans('cruds.dosen.fields.riset_grup') }}</label>
                            <select class="form-control select2" name="riset_grup_id" id="riset_grup_id">
                                @foreach($riset_grups as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('riset_grup_id') ? old('riset_grup_id') : $dosen->riset_grup->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('riset_grup'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('riset_grup') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.dosen.fields.riset_grup_helper') }}</span>
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