@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.post.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.posts.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.title') }}
                                    </th>
                                    <td>
                                        {{ $post->title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.slug') }}
                                    </th>
                                    <td>
                                        {{ $post->slug }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.content') }}
                                    </th>
                                    <td>
                                        {!! $post->content !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.excerpt') }}
                                    </th>
                                    <td>
                                        {!! $post->excerpt !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.meta_title') }}
                                    </th>
                                    <td>
                                        {{ $post->meta_title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.meta_description') }}
                                    </th>
                                    <td>
                                        {{ $post->meta_description }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.category') }}
                                    </th>
                                    <td>
                                        @foreach($post->categories as $key => $category)
                                            <span class="label label-info">{{ $category->name }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.tag') }}
                                    </th>
                                    <td>
                                        @foreach($post->tags as $key => $tag)
                                            <span class="label label-info">{{ $tag->name }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.image') }}
                                    </th>
                                    <td>
                                        @if($post->image)
                                            <a href="{{ $post->image->getUrl() }}" target="_blank" style="display: inline-block">
                                                <img src="{{ $post->image->getUrl('thumb') }}">
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.status') }}
                                    </th>
                                    <td>
                                        {{ App\Models\Post::STATUS_SELECT[$post->status] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.featured') }}
                                    </th>
                                    <td>
                                        <input type="checkbox" disabled="disabled" {{ $post->featured ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.author') }}
                                    </th>
                                    <td>
                                        {{ $post->author->name ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.posts.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection