@if(count($activityTimeline ?? []) > 0)
<div class="mhs-card">
    <div class="mhs-card-header">
        <i class="fas fa-history text-muted"></i> Aktivitas Terbaru
    </div>
    <div class="mhs-card-body">
        <ul class="mhs-timeline">
            @foreach($activityTimeline as $step)
                <li class="mhs-timeline-item">
                    <div class="mhs-timeline-dot {{ $step['status'] }}">
                        @if($step['status'] === 'done')
                            <i class="fas fa-check"></i>
                        @elseif($step['status'] === 'failed')
                            <i class="fas fa-times"></i>
                        @else
                            <i class="fas {{ $step['icon'] }}"></i>
                        @endif
                    </div>
                    <div class="mhs-timeline-content">
                        <a href="{{ $step['url'] }}">{{ $step['label'] }}</a>
                        <div class="mhs-timeline-meta">
                            <span class="badge badge-{{ $step['badge'] }} badge-sm">{{ $step['sublabel'] }}</span>
                            <span class="ml-1">{{ $step['date'] }}</span>
                        </div>
                        @if($step['detail'] ?? null)
                            <div class="small text-muted mt-1">{{ $step['detail'] }}</div>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endif
