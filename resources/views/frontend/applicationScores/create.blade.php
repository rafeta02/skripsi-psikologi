@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.applicationScore.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.application-scores.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label for="application_result_defence_id">{{ trans('cruds.applicationScore.fields.application_result_defence') }}</label>
                            <select class="form-control select2" name="application_result_defence_id" id="application_result_defence_id">
                                @foreach($application_result_defences as $id => $entry)
                                    <option value="{{ $id }}" {{ old('application_result_defence_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('application_result_defence'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('application_result_defence') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationScore.fields.application_result_defence_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="examiner_id">{{ trans('cruds.applicationScore.fields.examiner') }}</label>
                            <select class="form-control select2" name="examiner_id" id="examiner_id">
                                @foreach($examiners as $id => $entry)
                                    <option value="{{ $id }}" {{ old('examiner_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('examiner'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('examiner') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationScore.fields.examiner_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="score">{{ trans('cruds.applicationScore.fields.score') }}</label>
                            <input class="form-control" type="number" name="score" id="score" value="{{ old('score', '') }}" step="0.01" max="10">
                            @if($errors->has('score'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('score') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationScore.fields.score_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="note">{{ trans('cruds.applicationScore.fields.note') }}</label>
                            <textarea class="form-control" name="note" id="note">{{ old('note') }}</textarea>
                            @if($errors->has('note'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('note') }}
                                </div>
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

        </div>
    </div>
</div>
@endsection