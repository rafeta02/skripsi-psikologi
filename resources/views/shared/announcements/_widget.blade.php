@if(($recentAnnouncements ?? collect())->count() > 0)
<div class="mhs-card mb-4">
    <div class="mhs-card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-bullhorn text-muted"></i> Pengumuman Terbaru</span>
        @if($recentAnnouncements->count() > 0)
            <a href="{{ $indexRoute }}" class="btn btn-sm btn-link">Lihat semua</a>
        @endif
    </div>
    <div class="mhs-card-body p-0">
        <ul class="list-group list-group-flush">
            @foreach($recentAnnouncements as $announcement)
                <li class="list-group-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="pr-2">
                            @if($announcement->is_pinned)
                                <span class="badge badge-warning badge-sm mr-1"><i class="fas fa-thumbtack"></i></span>
                            @endif
                            <a href="{{ route($showRoute, $announcement) }}" class="font-weight-semibold text-dark">
                                {{ $announcement->title }}
                            </a>
                        </div>
                        <small class="text-muted text-nowrap">
                            {{ ($announcement->published_at ?? $announcement->created_at)?->format('d M Y') }}
                        </small>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endif
