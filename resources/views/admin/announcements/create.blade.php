@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.announcement.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.announcements.store') }}">
            @csrf
            @include('admin.announcements._form')
        </form>
    </div>
</div>

@endsection

@section('scripts')
@parent
@include('admin.announcements._ckeditor')
@endsection
