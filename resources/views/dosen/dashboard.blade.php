@extends('layouts.dosen')

@section('content')
<div class="mhs-welcome">
    <h1>Halo, {{ $dosen->nama }}</h1>
    <p>NIDN {{ $dosen->nidn }} &middot; {{ now()->translatedFormat('l, d F Y') }}</p>
</div>

<div class="mhs-stats">
    <div class="mhs-stat">
        <div class="mhs-stat-value">{{ $totalMahasiswaBimbingan }}</div>
        <div class="mhs-stat-label">Mahasiswa Bimbingan</div>
    </div>
    <a href="{{ route('dosen.task-assignments') }}" class="mhs-stat text-decoration-none {{ $pendingReviews > 0 ? 'mhs-stat-alert' : '' }}" style="color: inherit;">
        <div class="mhs-stat-value">{{ $pendingReviews }}</div>
        <div class="mhs-stat-label">Penugasan Baru</div>
    </a>
    <a href="{{ route('dosen.scores') }}" class="mhs-stat text-decoration-none {{ $pendingDefenseScores > 0 ? 'mhs-stat-alert' : '' }}" style="color: inherit;">
        <div class="mhs-stat-value">{{ $pendingDefenseScores }}</div>
        <div class="mhs-stat-label">Penilaian Pending</div>
    </a>
</div>

@if($pendingReviews > 0)
<div class="alert alert-warning alert-dismissible fade show">
    <strong>{{ $pendingReviews }} penugasan baru</strong> menunggu persetujuan atau penolakan Anda.
    <a href="{{ route('dosen.task-assignments') }}" class="alert-link font-weight-bold">Lihat penugasan</a>
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

<div class="row">
    <div class="col-lg-12">
        @include('partials.dosen.quick-actions')

        <div class="mhs-card">
            <div class="mhs-card-header">
                <i class="fas fa-clipboard-list text-muted"></i> Penugasan Terbaru
            </div>
            <div class="mhs-card-body p-0">
                @if(count($recentAssignments) > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0 text-center">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">Mahasiswa</th>
                                    <th class="text-center">Peran</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentAssignments as $assignment)
                                <tr>
                                    <td>
                                        @include('partials.dosen.mbkm-assignment-mahasiswa', [
                                            'application' => $assignment->application,
                                        ])
                                    </td>
                                    <td class="text-capitalize">{{ $assignment->role }}</td>
                                    <td>
                                        <span class="badge badge-{{ $assignment->status === 'accepted' ? 'success' : ($assignment->status === 'rejected' ? 'danger' : 'warning') }}">
                                            {{ $assignment->status }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($assignment->status === 'assigned')
                                            <a href="{{ route('dosen.review-proposal', $assignment->id) }}" class="btn btn-sm btn-primary">Tinjau</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-2 text-center border-top">
                        <a href="{{ route('dosen.task-assignments') }}" class="btn btn-sm btn-link">Lihat semua penugasan</a>
                    </div>
                @else
                    <div class="text-center text-muted py-4">Belum ada penugasan.</div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        @include('partials.dosen.activity-timeline')
    </div>
</div>
@endsection
