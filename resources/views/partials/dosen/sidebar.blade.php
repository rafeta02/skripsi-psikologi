<aside class="mhs-sidebar" id="mhsSidebar">
    <a href="{{ route('dosen.dashboard') }}" class="mhs-sidebar-brand">
        <img src="{{ asset('img/logo-uns.png') }}" alt="UNS">
        <span>SIMSKRIPSI</span>
    </a>

    <nav class="flex-grow-1">
        @foreach($portalNav ?? [] as $group)
            <div class="mhs-nav-group">
                <div class="mhs-nav-group-title">{{ $group['title'] }}</div>
                @foreach($group['items'] as $item)
                    <a href="{{ $item['url'] }}" class="mhs-nav-link {{ $item['active'] ? 'active' : '' }}"
                       @if(($item['badge'] ?? 0) > 0) title="{{ $item['badge'] }} perlu ditanggapi" @endif>
                        <i class="fas {{ $item['icon'] }}"></i>
                        <span class="mhs-nav-label">{{ $item['label'] }}</span>
                        @if(($item['badge'] ?? 0) > 0)
                            <span class="mhs-nav-badge">{{ $item['badge'] }}</span>
                        @endif
                    </a>
                @endforeach
            </div>
        @endforeach
    </nav>

    @if(($portalStats['menunggu_respons'] ?? 0) > 0 || ($portalStats['penilaian_pending'] ?? 0) > 0)
    <div class="px-3 pb-2">
        @if(($portalStats['menunggu_respons'] ?? 0) > 0)
            <a href="{{ route('dosen.task-assignments') }}" class="d-block small text-warning mb-1">
                <i class="fas fa-exclamation-circle"></i> {{ $portalStats['menunggu_respons'] }} penugasan menunggu
            </a>
        @endif
        @if(($portalStats['penilaian_pending'] ?? 0) > 0)
            <a href="{{ route('dosen.scores') }}" class="d-block small text-info">
                <i class="fas fa-star"></i> {{ $portalStats['penilaian_pending'] }} penilaian belum diisi
            </a>
        @endif
    </div>
    @endif

    <div class="mhs-sidebar-footer">
        Portal Dosen<br>
        <small>&copy; {{ date('Y') }}</small>
    </div>
</aside>
<div class="mhs-sidebar-overlay" id="mhsSidebarOverlay"></div>
