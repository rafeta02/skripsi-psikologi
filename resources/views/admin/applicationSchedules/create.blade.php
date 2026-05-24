@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.applicationSchedule.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.application-schedules.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="application_id">{{ trans('cruds.applicationSchedule.fields.application') }}</label>
                <select class="form-control select2 {{ $errors->has('application') ? 'is-invalid' : '' }}" name="application_id" id="application_id">
                    @foreach($applications as $id => $entry)
                        <option value="{{ $id }}" {{ old('application_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('application'))
                    <span class="text-danger">{{ $errors->first('application') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationSchedule.fields.application_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.applicationSchedule.fields.schedule_type') }}</label>
                <select class="form-control {{ $errors->has('schedule_type') ? 'is-invalid' : '' }}" name="schedule_type" id="schedule_type">
                    <option value disabled {{ old('schedule_type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\ApplicationSchedule::SCHEDULE_TYPE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('schedule_type', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('schedule_type'))
                    <span class="text-danger">{{ $errors->first('schedule_type') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationSchedule.fields.schedule_type_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="waktu">{{ trans('cruds.applicationSchedule.fields.waktu') }}</label>
                <input class="form-control datetime {{ $errors->has('waktu') ? 'is-invalid' : '' }}" type="text" name="waktu" id="waktu" value="{{ old('waktu') }}">
                @if($errors->has('waktu'))
                    <span class="text-danger">{{ $errors->first('waktu') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationSchedule.fields.waktu_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="ruang_id">{{ trans('cruds.applicationSchedule.fields.ruang') }}</label>
                <select class="form-control select2 {{ $errors->has('ruang') ? 'is-invalid' : '' }}" name="ruang_id" id="ruang_id">
                    @foreach($ruangs as $id => $entry)
                        <option value="{{ $id }}" {{ old('ruang_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('ruang'))
                    <span class="text-danger">{{ $errors->first('ruang') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationSchedule.fields.ruang_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="custom_place">{{ trans('cruds.applicationSchedule.fields.custom_place') }}</label>
                <input class="form-control {{ $errors->has('custom_place') ? 'is-invalid' : '' }}" type="text" name="custom_place" id="custom_place" value="{{ old('custom_place', '') }}">
                @if($errors->has('custom_place'))
                    <span class="text-danger">{{ $errors->first('custom_place') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationSchedule.fields.custom_place_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="online_meeting">{{ trans('cruds.applicationSchedule.fields.online_meeting') }}</label>
                <input class="form-control {{ $errors->has('online_meeting') ? 'is-invalid' : '' }}" type="text" name="online_meeting" id="online_meeting" value="{{ old('online_meeting', '') }}">
                @if($errors->has('online_meeting'))
                    <span class="text-danger">{{ $errors->first('online_meeting') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationSchedule.fields.online_meeting_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="note">{{ trans('cruds.applicationSchedule.fields.note') }}</label>
                <textarea class="form-control {{ $errors->has('note') ? 'is-invalid' : '' }}" name="note" id="note">{{ old('note') }}</textarea>
                @if($errors->has('note'))
                    <span class="text-danger">{{ $errors->first('note') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.applicationSchedule.fields.note_helper') }}</span>
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

@section('scripts')
@endsection