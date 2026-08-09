@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.announcement.title_singular') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <a class="btn btn-default" href="{{ route('admin.announcements.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th>{{ trans('cruds.announcement.fields.title') }}</th>
                    <td>{{ $announcement->title }}</td>
                </tr>
                <tr>
                    <th>{{ trans('cruds.announcement.fields.audience') }}</th>
                    <td>{{ $announcement->audienceLabel() }}</td>
                </tr>
                <tr>
                    <th>{{ trans('cruds.announcement.fields.status') }}</th>
                    <td>{{ $announcement->statusLabel() }}</td>
                </tr>
                <tr>
                    <th>{{ trans('cruds.announcement.fields.is_pinned') }}</th>
                    <td>{{ $announcement->is_pinned ? 'Ya' : 'Tidak' }}</td>
                </tr>
                <tr>
                    <th>{{ trans('cruds.announcement.fields.published_at') }}</th>
                    <td>{{ $announcement->published_at?->format('d M Y H:i') ?? '-' }}</td>
                </tr>
                <tr>
                    <th>{{ trans('cruds.announcement.fields.expires_at') }}</th>
                    <td>{{ $announcement->expires_at?->format('d M Y H:i') ?? '-' }}</td>
                </tr>
                <tr>
                    <th>{{ trans('cruds.announcement.fields.created_by') }}</th>
                    <td>{{ $announcement->created_by?->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>{{ trans('cruds.announcement.fields.body') }}</th>
                    <td>
                        <div class="announcement-body">
                            {!! $announcement->body !!}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="form-group">
            <a class="btn btn-default" href="{{ route('admin.announcements.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>
    </div>
</div>

@endsection
