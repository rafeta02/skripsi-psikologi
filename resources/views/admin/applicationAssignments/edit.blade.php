@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.applicationAssignment.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.application-assignments.update", [$applicationAssignment->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="application_id">{{ trans('cruds.applicationAssignment.fields.application') }}</label>
                <select class="form-control select2 {{ $errors->has('application') ? 'is-invalid' : '' }}" name="application_id" id="application_id">
                    @foreach($applications as $id => $entry)
                        <option value="{{ $id }}" {{ (old('application_id') ? old('application_id') : $applicationAssignment->application->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('application'))
                    <span class="text-danger">{{ $errors->first('application') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationAssignment.fields.application_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="lecturer_id">{{ trans('cruds.applicationAssignment.fields.lecturer') }}</label>
                <select class="form-control select2 {{ $errors->has('lecturer') ? 'is-invalid' : '' }}" name="lecturer_id" id="lecturer_id">
                    @foreach($lecturers as $id => $entry)
                        <option value="{{ $id }}" {{ (old('lecturer_id') ? old('lecturer_id') : $applicationAssignment->lecturer->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('lecturer'))
                    <span class="text-danger">{{ $errors->first('lecturer') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationAssignment.fields.lecturer_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.applicationAssignment.fields.role') }}</label>
                <select class="form-control {{ $errors->has('role') ? 'is-invalid' : '' }}" name="role" id="role">
                    <option value disabled {{ old('role', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\ApplicationAssignment::ROLE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('role', $applicationAssignment->role) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('role'))
                    <span class="text-danger">{{ $errors->first('role') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationAssignment.fields.role_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.applicationAssignment.fields.status') }}</label>
                <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status">
                    <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\ApplicationAssignment::STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('status', $applicationAssignment->status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('status'))
                    <span class="text-danger">{{ $errors->first('status') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationAssignment.fields.status_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="assigned_at">{{ trans('cruds.applicationAssignment.fields.assigned_at') }}</label>
                <input class="form-control datetime {{ $errors->has('assigned_at') ? 'is-invalid' : '' }}" type="text" name="assigned_at" id="assigned_at" value="{{ old('assigned_at', $applicationAssignment->assigned_at) }}">
                @if($errors->has('assigned_at'))
                    <span class="text-danger">{{ $errors->first('assigned_at') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationAssignment.fields.assigned_at_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="responded_at">{{ trans('cruds.applicationAssignment.fields.responded_at') }}</label>
                <input class="form-control datetime {{ $errors->has('responded_at') ? 'is-invalid' : '' }}" type="text" name="responded_at" id="responded_at" value="{{ old('responded_at', $applicationAssignment->responded_at) }}">
                @if($errors->has('responded_at'))
                    <span class="text-danger">{{ $errors->first('responded_at') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationAssignment.fields.responded_at_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="note">{{ trans('cruds.applicationAssignment.fields.note') }}</label>
                <textarea class="form-control {{ $errors->has('note') ? 'is-invalid' : '' }}" name="note" id="note">{{ old('note', $applicationAssignment->note) }}</textarea>
                @if($errors->has('note'))
                    <span class="text-danger">{{ $errors->first('note') }}</span>
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



@endsection