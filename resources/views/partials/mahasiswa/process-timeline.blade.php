@if(count($processTimeline ?? []) > 0)
<div class="mhs-card">
    <div class="mhs-card-header">
        <i class="fas fa-history text-muted"></i> Riwayat Proses
    </div>
    <div class="mhs-card-body">
        <ul class="mhs-timeline">
            @foreach($processTimeline as $step)
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
                            @if($step['date'])
                                <span class="ml-1">{{ $step['date'] }}</span>
                            @endif
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@elseif(!empty($activeApplication))
<div class="mhs-card">
    <div class="mhs-card-body text-center text-muted py-4">
        <i class="fas fa-spinner fa-2x mb-2 opacity-50"></i>
        <p class="mb-0">Aplikasi {{ strtoupper($activeApplication->type) }} sedang berjalan.</p>
        <a href="{{ $activeApplication->stageDetailUrl() }}" class="btn btn-sm btn-outline-primary mt-2">
            Lihat Detail Aplikasi
        </a>
    </div>
</div>
@else
<div class="mhs-card">
    <div class="mhs-card-body text-center text-muted py-4">
        <i class="fas fa-route fa-2x mb-2 opacity-50"></i>
        <p class="mb-0">Belum ada riwayat proses. Mulai dengan mendaftar skripsi.</p>
        <a href="{{ route('frontend.choose-path') }}" class="btn btn-sm btn-primary mt-2">Pilih Jalur</a>
    </div>
</div>
@endif
