@if(count($quickActions ?? []) > 0)
<div class="mhs-card">
    <div class="mhs-card-header">
        <i class="fas fa-bolt text-warning"></i> Aksi Cepat
    </div>
    <div class="mhs-card-body">
        <div class="mhs-actions">
            @foreach($quickActions as $action)
                <a href="{{ $action['url'] }}" class="mhs-action-btn {{ $action['color'] }}">
                    <i class="fas {{ $action['icon'] }}"></i>
                    {{ $action['label'] }}
                </a>
            @endforeach
        </div>
    </div>
</div>
@endif
