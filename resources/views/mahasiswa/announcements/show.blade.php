@extends('layouts.mahasiswa')

@section('content')
<div class="mb-4">
    <a href="{{ route('mahasiswa.pengumuman') }}" class="btn btn-sm btn-outline-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
    <h2 class="h4 font-weight-bold mb-1">{{ $announcement->title }}</h2>
    <p class="text-muted mb-0">
        {{ ($announcement->published_at ?? $announcement->created_at)?->format('d M Y H:i') }}
        @if($announcement->is_pinned)
            &middot; <span class="badge badge-warning"><i class="fas fa-thumbtack"></i> Pin</span>
        @endif
    </p>
</div>

<div class="card">
    <div class="card-body">
        @include('shared.announcements._body', ['announcement' => $announcement])
    </div>
</div>
@endsection
