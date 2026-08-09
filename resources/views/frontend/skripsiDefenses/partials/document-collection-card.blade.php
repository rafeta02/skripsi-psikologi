@props(['items', 'label', 'icon' => 'fa-file-pdf', 'iconColor' => 'text-danger'])

@if(count($items) > 0)
    <div class="col-md-6 mb-3">
        <div class="card h-100 border shadow-sm">
            <div class="card-body d-flex flex-column">
                <div class="text-center mb-3">
                    <i class="fas {{ $icon }} fa-2x {{ $iconColor }} mb-2"></i>
                    <h6 class="mb-0">{{ $label }}</h6>
                </div>
                @foreach($items as $index => $media)
                    <div class="{{ $loop->first ? '' : 'border-top pt-3 mt-1' }}">
                        <small class="text-muted d-block mb-2">
                            File {{ $index + 1 }}: {{ $media->file_name }}
                        </small>
                        @include('frontend.skripsiDefenses.partials.media-actions', ['media' => $media])
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
