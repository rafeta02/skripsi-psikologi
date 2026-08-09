@props(['media', 'label', 'icon' => 'fa-file-pdf', 'iconColor' => 'text-danger'])

<div class="col-md-6 mb-3">
    <div class="card h-100 border shadow-sm">
        <div class="card-body text-center d-flex flex-column">
            <i class="fas {{ $icon }} fa-3x {{ $iconColor }} mb-3"></i>
            <h6 class="mb-2">{{ $label }}</h6>
            <small class="text-muted d-block mb-2 text-truncate px-2" title="{{ $media->file_name }}">
                {{ $media->file_name }}
            </small>
            <div class="mt-auto">
                @include('frontend.skripsiDefenses.partials.media-actions', ['media' => $media])
            </div>
        </div>
    </div>
</div>
