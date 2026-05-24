@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.applicationScore.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.application-scores.update", [$applicationScore->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="application_result_defence_id">{{ trans('cruds.applicationScore.fields.application_result_defence') }}</label>
                <select class="form-control select2 {{ $errors->has('application_result_defence') ? 'is-invalid' : '' }}" name="application_result_defence_id" id="application_result_defence_id">
                    @foreach($application_result_defences as $id => $entry)
                        <option value="{{ $id }}" {{ (old('application_result_defence_id') ? old('application_result_defence_id') : $applicationScore->application_result_defence->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('application_result_defence'))
                    <span class="text-danger">{{ $errors->first('application_result_defence') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationScore.fields.application_result_defence_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="examiner_id">{{ trans('cruds.applicationScore.fields.examiner') }}</label>
                <select class="form-control select2 {{ $errors->has('examiner') ? 'is-invalid' : '' }}" name="examiner_id" id="examiner_id">
                    @foreach($examiners as $id => $entry)
                        <option value="{{ $id }}" {{ (old('examiner_id') ? old('examiner_id') : $applicationScore->examiner->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('examiner'))
                    <span class="text-danger">{{ $errors->first('examiner') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationScore.fields.examiner_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="penulisan">Penulisan</label>
                <input class="form-control {{ $errors->has('penulisan') ? 'is-invalid' : '' }}" type="number" name="penulisan" id="penulisan" value="{{ old('penulisan', $applicationScore->penulisan) }}">
                @if($errors->has('penulisan'))
                    <span class="text-danger">{{ $errors->first('penulisan') }}</span>
                @endif
            </div>
            <div class="form-group">
                <label for="isi">Isi</label>
                <input class="form-control {{ $errors->has('isi') ? 'is-invalid' : '' }}" type="number" name="isi" id="isi" value="{{ old('isi', $applicationScore->isi) }}">
                @if($errors->has('isi'))
                    <span class="text-danger">{{ $errors->first('isi') }}</span>
                @endif
            </div>
            <div class="form-group">
                <label for="analisis">Analisis</label>
                <input class="form-control {{ $errors->has('analisis') ? 'is-invalid' : '' }}" type="number" name="analisis" id="analisis" value="{{ old('analisis', $applicationScore->analisis) }}">
                @if($errors->has('analisis'))
                    <span class="text-danger">{{ $errors->first('analisis') }}</span>
                @endif
            </div>
            <div class="form-group">
                <label for="teoritis">Teoritis</label>
                <input class="form-control {{ $errors->has('teoritis') ? 'is-invalid' : '' }}" type="number" name="teoritis" id="teoritis" value="{{ old('teoritis', $applicationScore->teoritis) }}">
                @if($errors->has('teoritis'))
                    <span class="text-danger">{{ $errors->first('teoritis') }}</span>
                @endif
            </div>
            <div class="form-group">
                <label for="faktual">Faktual</label>
                <input class="form-control {{ $errors->has('faktual') ? 'is-invalid' : '' }}" type="number" name="faktual" id="faktual" value="{{ old('faktual', $applicationScore->faktual) }}">
                @if($errors->has('faktual'))
                    <span class="text-danger">{{ $errors->first('faktual') }}</span>
                @endif
            </div>
            <div class="form-group">
                <label for="pemecahan_masalah">Pemecahan Masalah</label>
                <input class="form-control {{ $errors->has('pemecahan_masalah') ? 'is-invalid' : '' }}" type="number" name="pemecahan_masalah" id="pemecahan_masalah" value="{{ old('pemecahan_masalah', $applicationScore->pemecahan_masalah) }}">
                @if($errors->has('pemecahan_masalah'))
                    <span class="text-danger">{{ $errors->first('pemecahan_masalah') }}</span>
                @endif
            </div>
            <div class="form-group">
                <label for="penyampaian">Penyampaian</label>
                <input class="form-control {{ $errors->has('penyampaian') ? 'is-invalid' : '' }}" type="number" name="penyampaian" id="penyampaian" value="{{ old('penyampaian', $applicationScore->penyampaian) }}">
                @if($errors->has('penyampaian'))
                    <span class="text-danger">{{ $errors->first('penyampaian') }}</span>
                @endif
            </div>
            <div class="form-group">
                <label for="sum">Sum</label>
                <input class="form-control {{ $errors->has('sum') ? 'is-invalid' : '' }}" type="number" name="sum" id="sum" value="{{ old('sum', $applicationScore->sum) }}" step="0.1">
                @if($errors->has('sum'))
                    <span class="text-danger">{{ $errors->first('sum') }}</span>
                @endif
            </div>
            <div class="form-group">
                <label for="score">{{ trans('cruds.applicationScore.fields.score') }}</label>
                <input class="form-control {{ $errors->has('score') ? 'is-invalid' : '' }}" type="number" name="score" id="score" value="{{ old('score', $applicationScore->score) }}" step="0.1">
                @if($errors->has('score'))
                    <span class="text-danger">{{ $errors->first('score') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationScore.fields.score_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="note">{{ trans('cruds.applicationScore.fields.note') }}</label>
                <textarea class="form-control {{ $errors->has('note') ? 'is-invalid' : '' }}" name="note" id="note">{{ old('note', $applicationScore->note) }}</textarea>
                @if($errors->has('note'))
                    <span class="text-danger">{{ $errors->first('note') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationScore.fields.note_helper') }}</span>
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