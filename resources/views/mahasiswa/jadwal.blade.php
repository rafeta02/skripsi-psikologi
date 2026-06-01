@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="mb-4">
        <h2 class="mb-1"><i class="fas fa-calendar-alt text-primary"></i> Jadwal Saya</h2>
        <p class="text-muted mb-2">Jadwal seminar, sidang, dan bimbingan Anda</p>
        <small class="text-muted">
            <i class="fas fa-info-circle"></i> Total {{ count($schedules) }} jadwal
        </small>
    </div>

    <!-- Info Card -->
    @if(count($schedules) > 0)
    <div class="alert alert-warning border-left" style="border-left: 4px solid #ffc107 !important;">
        <div class="d-flex align-items-center">
            <i class="fas fa-bell fa-2x mr-3"></i>
            <div>
                <h5 class="mb-1">Pengingat Jadwal</h5>
                <p class="mb-0">Pastikan Anda hadir tepat waktu pada jadwal yang telah ditentukan. Hubungi admin jika ada perubahan mendadak.</p>
            </div>
        </div>
    </div>
    @endif

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
                                @if($schedule->isApprovedByAdmin() && ($allowedForms['defense_result']['allowed'] ?? false))
                                    @can('application_result_defense_create')
                                        <a href="{{ route('frontend.application-result-defenses.create') }}" class="btn btn-success btn-sm btn-block mt-2">
                                            <i class="fas fa-award"></i> Laporkan Hasil Sidang
                                        </a>
                                    @endcan
                                @elseif($schedule->isApprovedByAdmin() && ($schedule->application->resultDefense ?? null))
                                    <a href="{{ route('frontend.application-result-defenses.show', $schedule->application->resultDefense->id) }}" class="btn btn-info btn-sm btn-block mt-2">
                                        <i class="fas fa-eye"></i> Lihat Hasil Sidang
                                    </a>
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
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                <h3>Belum Ada Jadwal</h3>
                <p class="text-muted mb-4">Anda belum memiliki jadwal. Setelah seminar MBKM/skripsi disetujui atau pendaftaran sidang diterima admin, ajukan jadwal dan tunggu verifikasi admin.</p>
                @if(($allowedForms['application_schedule']['allowed'] ?? false))
                    @can('application_schedule_create')
                        <a href="{{ route('frontend.application-schedules.create') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-calendar-plus"></i> Ajukan Jadwal
                        </a>
                    @endcan
                @endif
                <a href="{{ route('mahasiswa.aplikasi') }}" class="btn btn-primary">
                    <i class="fas fa-file-alt"></i> Lihat Aplikasi Saya
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
