@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.applicationAssignment.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.application-assignments.update", [$applicationAssignment->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="application_id">{{ trans('cruds.applicationAssignment.fields.application') }}</label>
                            <select class="form-control select2" name="application_id" id="application_id">
                                @foreach($applications as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('application_id') ? old('application_id') : $applicationAssignment->application->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('application'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('application') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationAssignment.fields.application_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="lecturer_id">{{ trans('cruds.applicationAssignment.fields.lecturer') }}</label>
                            <select class="form-control select2" name="lecturer_id" id="lecturer_id">
                                @foreach($lecturers as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('lecturer_id') ? old('lecturer_id') : $applicationAssignment->lecturer->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('lecturer'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('lecturer') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationAssignment.fields.lecturer_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('cruds.applicationAssignment.fields.role') }}</label>
                            <select class="form-control" name="role" id="role">
                                <option value disabled {{ old('role', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\ApplicationAssignment::ROLE_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('role', $applicationAssignment->role) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('role'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('role') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationAssignment.fields.role_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('cruds.applicationAssignment.fields.status') }}</label>
                            <select class="form-control" name="status" id="status">
                                <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\ApplicationAssignment::STATUS_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('status', $applicationAssignment->status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('status'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('status') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationAssignment.fields.status_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="assigned_at">{{ trans('cruds.applicationAssignment.fields.assigned_at') }}</label>
                            <input class="form-control datetime" type="text" name="assigned_at" id="assigned_at" value="{{ old('assigned_at', $applicationAssignment->assigned_at) }}">
                            @if($errors->has('assigned_at'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('assigned_at') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationAssignment.fields.assigned_at_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="responded_at">{{ trans('cruds.applicationAssignment.fields.responded_at') }}</label>
                            <input class="form-control datetime" type="text" name="responded_at" id="responded_at" value="{{ old('responded_at', $applicationAssignment->responded_at) }}">
                            @if($errors->has('responded_at'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('responded_at') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationAssignment.fields.responded_at_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="note">{{ trans('cruds.applicationAssignment.fields.note') }}</label>
                            <textarea class="form-control" name="note" id="note">{{ old('note', $applicationAssignment->note) }}</textarea>
                            @if($errors->has('note'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('note') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationAssignment.fields.note_helper') }}</span>
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