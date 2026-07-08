<aside class="mhs-sidebar" id="mhsSidebar">
    <a href="{{ route('mahasiswa.dashboard') }}" class="mhs-sidebar-brand">
        <img src="{{ asset('img/logo-uns.png') }}" alt="UNS">
        <span>SIMSKRIPSI</span>
    </a>

    <nav class="flex-grow-1">
        @foreach($portalNav ?? [] as $group)
            <div class="mhs-nav-group">
                <div class="mhs-nav-group-title">{{ $group['title'] }}</div>
                @foreach($group['items'] as $item)
                    <a href="{{ $item['url'] }}" class="mhs-nav-link {{ $item['active'] ? 'active' : '' }}">
                        <i class="fas {{ $item['icon'] }}"></i>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>
        @endforeach
    </nav>

    <div class="mhs-sidebar-footer">
        Fakultas Psikologi UNS<br>
        <small>&copy; {{ date('Y') }}</small>
    </div>
</aside>
<div class="mhs-sidebar-overlay" id="mhsSidebarOverlay"></div>
