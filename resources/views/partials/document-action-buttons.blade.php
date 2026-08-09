@props(['media', 'label' => 'Dokumen'])

@if($media)
    <div class="list-group-item">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div class="mb-2 mb-md-0">
                <i class="fas fa-file-pdf text-danger mr-2"></i>
                <strong>{{ $label }}</strong>
                <br><small class="text-muted">{{ $media->file_name }}</small>
            </div>
            <div class="btn-group btn-group-sm">
                <a href="{{ $media->getUrl() }}" target="_blank" class="btn btn-primary">
                    <i class="fas fa-eye"></i> View
                </a>
                @if(str_ends_with(strtolower($media->file_name), '.pdf'))
                    <button type="button" class="btn btn-info preview-doc" data-url="{{ $media->getUrl() }}">
                        <i class="fas fa-expand"></i> Preview
                    </button>
                @endif
                <a href="{{ $media->getUrl() }}" download class="btn btn-success">
                    <i class="fas fa-download"></i> Download
                </a>
            </div>
        </div>
    </div>
@endif
