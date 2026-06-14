@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, #8e44ad 0%, #3498db 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-1 text-white font-weight-bold">
                                <i class="fas fa-calendar-alt mr-2"></i> Jadwal Seminar & Sidang
                            </h2>
                            <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                                Kelola jadwal seminar MBKM dan sidang skripsi Anda
                            </p>
                        </div>
                        <div class="col-md-4 text-right">
                            @can('application_schedule_create')
                                <a href="{{ route('frontend.application-schedules.create') }}" class="btn btn-light btn-lg shadow">
                                    <i class="fas fa-plus-circle"></i> Buat Jadwal
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedules List -->
    <div class="row">
        <div class="col-lg-12">
            @if($applicationSchedules->count() > 0)
                @foreach($applicationSchedules as $schedule)
                    <div class="card-modern mb-4">
                        <div class="card-modern-body">
                            <div class="row align-items-start">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-start mb-3">
                                        <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #8e44ad, #3498db); border-radius: var(--radius-base); display: flex; align-items: center; justify-content: center; margin-right: var(--spacing-3);">
                                            @if($schedule->schedule_type === 'mbkm_seminar')
                                                <i class="fas fa-chalkboard-teacher" style="font-size: 20px; color: white;"></i>
                                            @else
                                                <i class="fas fa-graduation-cap" style="font-size: 20px; color: white;"></i>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <h4 class="mb-1 font-weight-bold">
                                                @if($schedule->schedule_type === 'mbkm_seminar')
                                                    Seminar MBKM
                                                @elseif($schedule->schedule_type === 'skripsi_defense')
                                                    Sidang Skripsi
                                                @else
                                                    {{ ucfirst(str_replace('_', ' ', $schedule->schedule_type)) }}
                                                @endif
                                            </h4>
                                            <div class="mb-2">
                                                <p class="mb-1">
                                                    <i class="far fa-calendar"></i> 
                                                    <strong>{{ \Carbon\Carbon::parse($schedule->waktu)->translatedFormat('l, d F Y') }}</strong>
                                                </p>
                                                <p class="mb-1">
                                                    <i class="far fa-clock"></i> 
                                                    {{ \Carbon\Carbon::parse($schedule->waktu)->format('H:i') }} WIB
                                                </p>
                                                @if($schedule->ruang)
                                                    <p class="mb-1">
                                                        <i class="fas fa-map-marker-alt"></i> 
                                                        {{ $schedule->ruang->name }}
                                                    </p>
                                                @elseif($schedule->online_link)
                                                    <p class="mb-1">
                                                        <i class="fas fa-video"></i> 
                                                        <a href="{{ $schedule->online_link }}" target="_blank">Link Meeting Online</a>
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="d-flex flex-wrap gap-2">
                                                @php $scheduleStatus = $schedule->adminValidationStatus(); @endphp
                                                <span class="badge-modern badge-modern-{{ $scheduleStatus['badge'] === 'success' ? 'success' : ($scheduleStatus['badge'] === 'danger' ? 'danger' : 'warning') }}">
                                                    <i class="fas fa-{{ $scheduleStatus['icon'] }}"></i> {{ $scheduleStatus['label'] }}
                                                </span>
                                                @if($schedule->application && $schedule->application->status == 'done')
                                                    <span class="badge-modern badge-modern-secondary">
                                                        <i class="fas fa-flag-checkered"></i> Selesai
                                                    </span>
                                                @endif
                                                
                                                @if($schedule->schedule_type === 'mbkm_seminar')
                                                    <span class="badge-modern badge-modern-primary">MBKM</span>
                                                @else
                                                    <span class="badge-modern badge-modern-success">Skripsi</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @if($schedule->notes)
                                        <div class="mb-3">
                                            <label class="text-muted mb-1">Catatan:</label>
                                            <p class="text-muted mb-0">{{ $schedule->notes }}</p>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-4 text-right">
                                    <div class="d-flex flex-column gap-2">
                                        @can('application_schedule_show')
                                            <a href="{{ route('frontend.application-schedules.show', $schedule->id) }}" class="btn-modern btn-modern-primary">
                                                <i class="fas fa-eye"></i> Lihat Detail
                                            </a>
                                        @endcan
                                        
                                        @can('application_schedule_edit')
                                            @if(!$schedule->isApprovedByAdmin() && !$schedule->isRejectedByAdmin())
                                                <a href="{{ route('frontend.application-schedules.edit', $schedule->id) }}" class="btn-modern btn-modern-outline">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                            @endif
                                        @endcan

                                        @if($schedule->isRejectedByAdmin() && ($scheduleAccess['allowed'] ?? false))
                                            @can('application_schedule_create')
                                                <a href="{{ route('frontend.application-schedules.create') }}" class="btn-modern btn-modern-primary">
                                                    <i class="fas fa-calendar-plus"></i> Ajukan Jadwal Baru
                                                </a>
                                            @endcan
                                        @endif

                                        @if(in_array($schedule->schedule_type, ['mbkm_seminar', 'skripsi_seminar']) && $schedule->isReadyForResultReport())
                                            @can('application_result_seminar_access')
                                                <a href="{{ route('frontend.application-result-seminars.index') }}" class="btn-modern btn-modern-success">
                                                    <i class="fas fa-clipboard-check"></i> Laporan Hasil Seminar
                                                </a>
                                            @endcan
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="card-modern">
                    <div class="card-modern-body text-center py-5">
                        <div style="width: 100px; height: 100px; background: var(--gray-100); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--spacing-4);">
                            <i class="fas fa-calendar-alt fa-3x text-muted"></i>
                        </div>
                        <h4 class="text-muted mb-3">Belum Ada Jadwal</h4>
                        <p class="text-muted mb-4">Anda belum membuat jadwal seminar atau sidang</p>
                        @can('application_schedule_create')
                            <a href="{{ route('frontend.application-schedules.create') }}" class="btn-modern btn-modern-primary btn-modern-lg">
                                <i class="fas fa-plus-circle"></i> Buat Jadwal Sekarang
                            </a>
                        @endcan
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
