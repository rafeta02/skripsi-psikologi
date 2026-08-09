@props(['media'])

<div class="btn-group btn-group-sm flex-wrap justify-content-center">
    <a href="{{ $media->getUrl() }}" target="_blank" rel="noopener" class="btn btn-primary">
        <i class="fas fa-eye"></i> View
    </a>
    @if(\Illuminate\Support\Str::endsWith(strtolower($media->file_name ?? ''), '.pdf'))
        <button type="button" class="btn btn-info preview-doc" data-url="{{ $media->getUrl() }}">
            <i class="fas fa-expand"></i> Preview
        </button>
    @endif
    <a href="{{ $media->getUrl() }}" download class="btn btn-success">
        <i class="fas fa-download"></i> Download
    </a>
</div>
