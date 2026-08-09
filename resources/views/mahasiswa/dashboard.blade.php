@extends('layouts.mahasiswa')

@section('content')
<div class="mhs-welcome">
    <h1>Halo, {{ $mahasiswa->nama }}</h1>
    <p>NIM {{ $mahasiswa->nim }} &middot; {{ now()->translatedFormat('l, d F Y') }}</p>
</div>

{{-- Phase indicator --}}
<div class="mhs-phase-pills">
    @php
        $phases = ['Pendaftaran', 'Seminar', 'Sidang', 'Nilai'];
    @endphp
    @foreach($phases as $i => $label)
        @php
            $phaseNum = $i + 1;
            $class = $currentPhase >= $phaseNum ? ($currentPhase > $phaseNum ? 'done' : 'current') : '';
        @endphp
        <span class="mhs-phase-pill {{ $class }}">
            @if($currentPhase > $phaseNum)
                <i class="fas fa-check"></i>
            @else
                {{ $phaseNum }}
            @endif
            {{ $label }}
        </span>
    @endforeach
</div>

<div class="mhs-next-step mb-4">
    <strong>{{ $phaseName }}</strong> — {{ $phaseDescription }}
    <br>
    <span class="text-muted">Langkah berikutnya:</span> {{ $nextStep }}
</div>

<div class="mhs-stats">
    <div class="mhs-stat">
        <div class="mhs-stat-value">{{ $totalApplications }}</div>
        <div class="mhs-stat-label">Total Aplikasi</div>
    </div>
    <div class="mhs-stat">
        <div class="mhs-stat-value">{{ $activeApplications }}</div>
        <div class="mhs-stat-label">Aktif</div>
    </div>
    <div class="mhs-stat">
        <div class="mhs-stat-value">{{ $completedApplications }}</div>
        <div class="mhs-stat-label">Selesai</div>
    </div>
</div>

@if($activeApplication && $activeApplication->status == 'revision')
<div class="alert alert-warning alert-dismissible fade show">
    <strong>Revisi diperlukan</strong> pada aplikasi {{ strtoupper($activeApplication->type) }}.
    @if($activeApplication->revision_notes ?? null)
        <br><small>{{ $activeApplication->revision_notes }}</small>
    @endif
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

@if(($graduationApplicationId ?? null))
    @include('partials.mahasiswa-graduation-documents', [
        'applicationId' => $graduationApplicationId,
        'finalScore' => $graduationFinalScore ?? null,
        'finalGradeLetter' => $graduationFinalGradeLetter ?? null,
        'class' => 'mb-4',
    ])
@endif

@include('shared.announcements._widget', [
    'recentAnnouncements' => $recentAnnouncements ?? collect(),
    'indexRoute' => route('mahasiswa.pengumuman'),
    'showRoute' => 'mahasiswa.pengumuman.show',
])

<div class="row">
    <div class="col-lg-12">
        @include('partials.mahasiswa.quick-actions')

        @if($activeApplication)
        <div class="mhs-card">
            <div class="mhs-card-header">
                <i class="fas fa-file-alt text-muted"></i> Aplikasi Aktif
            </div>
            <div class="mhs-card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="badge badge-light text-uppercase">{{ $activeApplication->type }}</span>
                        <span class="badge badge-light text-capitalize ml-1">{{ $activeApplication->stage }}</span>
                    </div>
                    @if($activeApplication->stage == 'registration')
                        @include('partials.mahasiswa-registration-status', ['application' => $activeApplication])
                    @else
                        <span class="badge badge-{{ ['approved'=>'success','submitted'=>'warning','scheduled'=>'info','rejected'=>'danger'][$activeApplication->status] ?? 'secondary' }}">
                            {{ ucfirst($activeApplication->status) }}
                        </span>
                    @endif
                </div>

                @if(count($assignments) > 0)
                <table class="table table-sm table-borderless mb-3">
                    <thead><tr><th>Peran</th><th>Dosen</th><th>Status</th></tr></thead>
                    <tbody>
                        @foreach($assignments as $a)
                        <tr>
                            <td class="text-capitalize">{{ $a->role }}</td>
                            <td>{{ $a->lecturer->nama ?? '-' }}</td>
                            <td><span class="badge badge-{{ $a->status == 'accepted' ? 'success' : ($a->status == 'rejected' ? 'danger' : 'warning') }}">{{ $a->status }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif

                <a href="{{ $activeApplication->stageDetailUrl() }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-eye"></i> Lihat Detail
                </a>
                <a href="{{ route('mahasiswa.aplikasi') }}" class="btn btn-sm btn-link">Semua aplikasi</a>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-12">
        @include('partials.mahasiswa.process-timeline')

        @if(count($schedules) > 0)
        <div class="mhs-card">
            <div class="mhs-card-header">
                <i class="fas fa-calendar text-muted"></i> Jadwal Terdekat
            </div>
            <div class="mhs-card-body p-0">
                <ul class="list-group list-group-flush">
                    @foreach($schedules as $schedule)
                    <li class="list-group-item">
                        <div class="font-weight-semibold small">{{ $schedule->schedule_type }}</div>
                        <div class="text-muted small"><i class="far fa-clock"></i> {{ $schedule->waktu }}</div>
                    </li>
                    @endforeach
                </ul>
                <div class="p-2 text-center">
                    <a href="{{ route('mahasiswa.jadwal') }}" class="btn btn-sm btn-link">Lihat semua jadwal</a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
