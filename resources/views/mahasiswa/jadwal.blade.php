@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <h2><i class="fas fa-calendar-alt"></i> Jadwal Saya</h2>
        <p class="text-muted">Jadwal seminar, sidang, dan bimbingan</p>
    </div>

    @if(count($schedules) > 0)
        <div class="row">
            @foreach($schedules as $schedule)
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header" style="background: linear-gradient(135deg, #22004C 0%, #4A0080 100%); color: white;">
                            <h5 class="mb-0">
                                <i class="fas fa-calendar-check"></i> 
                                {{ ucfirst($schedule->schedule_type) }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6 class="text-muted mb-1">Aplikasi</h6>
                                <p class="mb-0">
                                    <strong class="text-uppercase">{{ $schedule->application->type ?? 'N/A' }}</strong> - 
                                    <span class="text-capitalize">{{ $schedule->application->stage ?? 'N/A' }}</span>
                                </p>
                            </div>

                            <div class="mb-3">
                                <h6 class="text-muted mb-1"><i class="far fa-clock"></i> Waktu</h6>
                                <p class="mb-0"><strong>{{ $schedule->waktu }}</strong></p>
                            </div>

                            <div class="mb-3">
                                <h6 class="text-muted mb-1"><i class="fas fa-map-marker-alt"></i> Tempat</h6>
                                <p class="mb-0">
                                    @if($schedule->ruang)
                                        <strong>{{ $schedule->ruang->name }}</strong>
                                        <br><small class="text-muted">{{ $schedule->ruang->location ?? '' }}</small>
                                    @elseif($schedule->custom_place)
                                        <strong>{{ $schedule->custom_place }}</strong>
                                    @else
                                        <strong>Online Meeting</strong>
                                    @endif
                                </p>
                            </div>

                            @if($schedule->online_meeting)
                                <div class="mb-3">
                                    <h6 class="text-muted mb-1"><i class="fas fa-video"></i> Link Meeting</h6>
                                    <a href="{{ $schedule->online_meeting }}" target="_blank" class="btn btn-sm btn-success">
                                        <i class="fas fa-external-link-alt"></i> Join Meeting
                                    </a>
                                </div>
                            @endif

                            @if($schedule->note)
                                <div class="mb-3">
                                    <h6 class="text-muted mb-1"><i class="fas fa-sticky-note"></i> Catatan</h6>
                                    <p class="mb-0 text-muted small">{{ $schedule->note }}</p>
                                </div>
                            @endif

                            <div class="mt-3">
                                <span class="badge badge-info">{{ $schedule->application->status ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="card-footer text-muted small">
                            <i class="fas fa-calendar-plus"></i> Dibuat: {{ $schedule->created_at->format('d M Y H:i') }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                <h3>Belum Ada Jadwal</h3>
                <p class="text-muted mb-4">Anda belum memiliki jadwal. Jadwal akan muncul setelah aplikasi Anda disetujui dan dijadwalkan oleh admin.</p>
                <a href="{{ route('mahasiswa.aplikasi') }}" class="btn btn-primary">
                    <i class="fas fa-file-alt"></i> Lihat Aplikasi Saya
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
