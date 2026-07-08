@extends('layouts.mahasiswa')

@section('content')
<div class="mb-4">
    <h2 class="h4 font-weight-bold mb-1">Jadwal Saya</h2>
    <p class="text-muted mb-0">Jadwal seminar, sidang, dan bimbingan Anda</p>
</div>

<div class="row">
    <div class="col-lg-8">
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
                                @include('partials.schedule-validation-status', ['schedule' => $schedule])
                                <p class="small text-muted mb-0 mt-2">{{ $schedule->adminValidationStatus()['detail'] }}</p>
                                @if(in_array($schedule->schedule_type, ['skripsi_defense', 'defense']) && $schedule->isDefenseScheduleVerified() && ($allowedForms['defense_result']['allowed'] ?? false))
                                    @can('application_result_defense_create')
                                        <a href="{{ route('frontend.application-result-defenses.create') }}" class="btn btn-success btn-sm btn-block mt-2">
                                            <i class="fas fa-award"></i> Laporkan Hasil Sidang
                                        </a>
                                    @endcan
                                @elseif(in_array($schedule->schedule_type, ['skripsi_defense', 'defense']) && $schedule->isDefenseScheduleVerified() && ($schedule->application->resultDefense ?? null))
                                    <a href="{{ route('frontend.application-result-defenses.show', $schedule->application->resultDefense->id) }}" class="btn btn-info btn-sm btn-block mt-2">
                                        <i class="fas fa-eye"></i> Lihat Hasil Sidang
                                    </a>
                                @endif

                                @if(in_array($schedule->schedule_type, ['mbkm_seminar', 'skripsi_seminar']) && $schedule->isReadyForResultReport())
                                    @can('application_result_seminar_access')
                                        <a href="{{ route('frontend.application-result-seminars.index') }}" class="btn btn-success btn-sm btn-block mt-2">
                                            <i class="fas fa-clipboard-check"></i> Laporan Hasil Seminar
                                        </a>
                                    @endcan
                                @endif
                            </div>
                        </div>
                        <div class="card-footer text-muted small">
                            <i class="fas fa-calendar-plus"></i> Dibuat: {{ $schedule->created_at->format('d M Y H:i') }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if(($scheduleAccess['allowed'] ?? false))
            <div class="text-center mt-4 mb-4">
                @can('application_schedule_create')
                    <a href="{{ route('frontend.application-schedules.create') }}" class="btn btn-primary">
                        <i class="fas fa-calendar-plus"></i> Ajukan Jadwal Baru
                    </a>
                @endcan
            </div>
        @endif
    @else
        <div class="mhs-card">
            <div class="mhs-card-body text-center py-5">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <h5>Belum Ada Jadwal</h5>
                <p class="text-muted mb-4">Ajukan jadwal setelah pendaftaran seminar/sidang disetujui admin.</p>
                @if(($allowedForms['application_schedule']['allowed'] ?? false))
                    @can('application_schedule_create')
                        <a href="{{ route('frontend.application-schedules.create') }}" class="btn btn-primary">
                            <i class="fas fa-calendar-plus"></i> Ajukan Jadwal
                        </a>
                    @endcan
                @endif
            </div>
        </div>
    @endif
    </div>

    <div class="col-lg-4">
        @include('partials.mahasiswa.quick-actions')
        @include('partials.mahasiswa.process-timeline')
    </div>
</div>
@endsection
