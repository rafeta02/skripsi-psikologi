@php
    /** @var \App\Models\Announcement|null $announcement */
    $announcement = $announcement ?? null;
@endphp

<div class="form-group">
    <label class="required" for="title">{{ trans('cruds.announcement.fields.title') }}</label>
    <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', $announcement?->title ?? '') }}" required>
    @if($errors->has('title'))
        <span class="text-danger">{{ $errors->first('title') }}</span>
    @endif
</div>

<div class="form-group">
    <label class="required" for="body">{{ trans('cruds.announcement.fields.body') }}</label>
    <textarea class="form-control ckeditor {{ $errors->has('body') ? 'is-invalid' : '' }}" name="body" id="body">{!! old('body', $announcement?->body ?? '') !!}</textarea>
    @if($errors->has('body'))
        <span class="text-danger">{{ $errors->first('body') }}</span>
    @endif
    <span class="help-block">{{ trans('cruds.announcement.fields.body_helper') }}</span>
</div>

<div class="form-group">
    <label class="required" for="audience">{{ trans('cruds.announcement.fields.audience') }}</label>
    <select class="form-control {{ $errors->has('audience') ? 'is-invalid' : '' }}" name="audience" id="audience" required>
        @foreach(\App\Models\Announcement::AUDIENCE_SELECT as $key => $label)
            <option value="{{ $key }}" {{ old('audience', $announcement?->audience ?? 'all') === $key ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
    @if($errors->has('audience'))
        <span class="text-danger">{{ $errors->first('audience') }}</span>
    @endif
</div>

<div class="form-group">
    <label class="required" for="status">{{ trans('cruds.announcement.fields.status') }}</label>
    <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status" required>
        @foreach(\App\Models\Announcement::STATUS_SELECT as $key => $label)
            <option value="{{ $key }}" {{ old('status', $announcement?->status ?? 'draft') === $key ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
    @if($errors->has('status'))
        <span class="text-danger">{{ $errors->first('status') }}</span>
    @endif
</div>

<div class="form-group">
    <label for="published_at">{{ trans('cruds.announcement.fields.published_at') }}</label>
    <input class="form-control datetime {{ $errors->has('published_at') ? 'is-invalid' : '' }}" type="text" name="published_at" id="published_at" value="{{ old('published_at', $announcement && $announcement->published_at ? $announcement->published_at->format('Y-m-d H:i:s') : '') }}">
    @if($errors->has('published_at'))
        <span class="text-danger">{{ $errors->first('published_at') }}</span>
    @endif
    <span class="help-block">{{ trans('cruds.announcement.fields.published_at_helper') }}</span>
</div>

<div class="form-group">
    <label for="expires_at">{{ trans('cruds.announcement.fields.expires_at') }}</label>
    <input class="form-control datetime {{ $errors->has('expires_at') ? 'is-invalid' : '' }}" type="text" name="expires_at" id="expires_at" value="{{ old('expires_at', $announcement && $announcement->expires_at ? $announcement->expires_at->format('Y-m-d H:i:s') : '') }}">
    @if($errors->has('expires_at'))
        <span class="text-danger">{{ $errors->first('expires_at') }}</span>
    @endif
    <span class="help-block">{{ trans('cruds.announcement.fields.expires_at_helper') }}</span>
</div>

<div class="form-group">
    <div class="form-check">
        <input type="hidden" name="is_pinned" value="0">
        <input class="form-check-input" type="checkbox" name="is_pinned" id="is_pinned" value="1" {{ old('is_pinned', $announcement?->is_pinned ?? false) ? 'checked' : '' }}>
        <label class="form-check-label" for="is_pinned">{{ trans('cruds.announcement.fields.is_pinned') }}</label>
    </div>
</div>

<div class="form-group">
    <button class="btn btn-danger" type="submit">
        {{ trans('global.save') }}
    </button>
</div>
