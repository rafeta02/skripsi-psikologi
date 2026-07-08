@php
    $title = $title ?? 'Halaman';
    $subtitle = $subtitle ?? null;
@endphp
<div class="mb-4">
    <h2 class="h4 font-weight-bold mb-1">{{ $title }}</h2>
    @if($subtitle)
        <p class="text-muted mb-0">{{ $subtitle }}</p>
    @endif
</div>
