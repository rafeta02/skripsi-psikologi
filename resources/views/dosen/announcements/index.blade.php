@extends('layouts.dosen')

@section('content')
<div class="mb-4">
    <h2 class="h4 font-weight-bold mb-1">Pengumuman</h2>
    <p class="text-muted mb-0">Informasi resmi dari admin SIMSKRIPSI</p>
</div>

@if($announcements->count() > 0)
    <div class="row">
        @foreach($announcements as $announcement)
            <div class="col-12 mb-3">
                <div class="card h-100 {{ $announcement->is_pinned ? 'border-warning' : '' }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">
                                @if($announcement->is_pinned)
                                    <span class="badge badge-warning mr-1"><i class="fas fa-thumbtack"></i> Pin</span>
                                @endif
                                <a href="{{ route('dosen.pengumuman.show', $announcement) }}" class="text-dark">
                                    {{ $announcement->title }}
                                </a>
                            </h5>
                            <small class="text-muted text-nowrap ml-3">
                                {{ ($announcement->published_at ?? $announcement->created_at)?->format('d M Y') }}
                            </small>
                        </div>
                        <p class="text-muted mb-2">
                            <span class="badge badge-light">{{ $announcement->audienceLabel() }}</span>
                        </p>
                        <a href="{{ route('dosen.pengumuman.show', $announcement) }}" class="btn btn-sm btn-outline-primary">
                            Baca selengkapnya
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center">
        {{ $announcements->links() }}
    </div>
@else
    <div class="alert alert-info mb-0">
        <i class="fas fa-info-circle"></i> Belum ada pengumuman saat ini.
    </div>
@endif
@endsection
