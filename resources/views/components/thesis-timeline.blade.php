{{--
    Timeline Component for Thesis Progress

    Props:
    - $application: Application model instance
    - $type: 'skripsi' or 'mbkm' (optional)
    - $compact: boolean (optional)
--}}

@php
    $compact = $compact ?? false;
    $type = $type ?? $application->type ?? 'skripsi';
    if (!in_array($type, ['skripsi', 'mbkm'], true)) {
        $type = 'skripsi';
    }

    // Tahap UI diselaraskan dengan stage DB (registration / seminar / defense)
    // + inferensi status penugasan pembimbing.
    $stages = [
        'skripsi' => [
            ['key' => 'registration', 'label' => 'Pendaftaran', 'icon' => 'file-alt'],
            ['key' => 'supervisor_assignment', 'label' => 'Persetujuan Pembimbing', 'icon' => 'user-tie'],
            ['key' => 'seminar', 'label' => 'Seminar / Review Proposal', 'icon' => 'chalkboard-teacher'],
            ['key' => 'defense', 'label' => 'Sidang Skripsi', 'icon' => 'graduation-cap'],
            ['key' => 'scoring', 'label' => 'Penilaian Akhir', 'icon' => 'star'],
        ],
        'mbkm' => [
            ['key' => 'registration', 'label' => 'Pendaftaran MBKM', 'icon' => 'file-alt'],
            ['key' => 'supervisor_assignment', 'label' => 'Persetujuan Pembimbing', 'icon' => 'user-tie'],
            ['key' => 'seminar', 'label' => 'Review Kelayakan Proposal', 'icon' => 'chalkboard-teacher'],
            ['key' => 'defense', 'label' => 'Sidang Skripsi', 'icon' => 'graduation-cap'],
            ['key' => 'scoring', 'label' => 'Penilaian Akhir', 'icon' => 'star'],
        ],
    ];

    $currentStages = $stages[$type];
    $dbStage = $application->stage ?? 'registration';
    $currentStatus = $application->status ?? 'submitted';

    $assignments = $application->relationLoaded('assignments')
        ? $application->assignments
        : $application->assignments()->get();

    $hasAcceptedSupervisor = $assignments
        ->where('role', 'supervisor')
        ->where('status', 'accepted')
        ->isNotEmpty();

    $hasAssignedSupervisor = $assignments
        ->where('role', 'supervisor')
        ->isNotEmpty();

    // Hitung index progress berdasarkan stage DB + status + penugasan
    $stageIndex = 0;
    $displayStatus = $currentStatus;

    if ($dbStage === 'defense') {
        if (in_array($currentStatus, ['done', 'result'], true)) {
            $stageIndex = 4; // scoring
            $displayStatus = $currentStatus === 'done' ? 'done' : 'result';
        } else {
            $stageIndex = 3; // defense
        }
    } elseif ($dbStage === 'seminar') {
        $stageIndex = 2;
    } else {
        // registration
        if ($hasAcceptedSupervisor) {
            $stageIndex = 2;
            $displayStatus = 'ready_next';
        } elseif ($hasAssignedSupervisor) {
            $stageIndex = 1;
            $displayStatus = $currentStatus === 'rejected' ? 'rejected' : 'waiting_supervisor';
        } elseif ($currentStatus === 'approved') {
            $stageIndex = 1;
            $displayStatus = 'waiting_supervisor';
        } elseif ($currentStatus === 'rejected') {
            $stageIndex = 0;
            $displayStatus = 'rejected';
        } elseif ($currentStatus === 'revision') {
            $stageIndex = 0;
            $displayStatus = 'revision';
        } else {
            $stageIndex = 0;
            $displayStatus = $currentStatus;
        }
    }
@endphp

<div class="thesis-timeline {{ $compact ? 'timeline-compact' : '' }}">
    @if(!$compact)
        <div class="timeline-header mb-4">
            <h5 class="font-weight-bold mb-2">
                <i class="fas fa-route mr-2"></i> Progress {{ strtoupper($type) }}
            </h5>
            <div class="d-flex align-items-center">
                <div class="progress flex-grow-1 mr-3" style="height: 8px;">
                    <div class="progress-bar" style="width: {{ max(5, ($stageIndex + 1) / count($currentStages) * 100) }}%; background: linear-gradient(90deg, var(--primary-500, #22004C), var(--secondary-500, #4A0080))"></div>
                </div>
                <span class="badge badge-primary">{{ min($stageIndex + 1, count($currentStages)) }}/{{ count($currentStages) }}</span>
            </div>
            <p class="small text-muted mb-0 mt-2">
                Tahap sistem: <strong class="text-capitalize">{{ $dbStage }}</strong>
                · Status: <strong class="text-capitalize">{{ $currentStatus }}</strong>
            </p>
        </div>
    @endif

    <div class="timeline-steps {{ $compact ? 'timeline-horizontal' : 'timeline-vertical' }}">
        @foreach($currentStages as $index => $stage)
            @php
                $isCompleted = $index < $stageIndex;
                $isCurrent = $index === $stageIndex;
                $statusClass = $isCompleted ? 'completed' : ($isCurrent ? 'current' : 'pending');
                $iconColor = $isCompleted ? 'success' : ($isCurrent ? 'primary' : 'secondary');
            @endphp

            <div class="timeline-step {{ $statusClass }}" data-stage="{{ $stage['key'] }}">
                <div class="step-marker">
                    <div class="step-icon bg-{{ $iconColor }}">
                        @if($isCompleted)
                            <i class="fas fa-check"></i>
                        @else
                            <i class="fas fa-{{ $stage['icon'] }}"></i>
                        @endif
                    </div>
                    @if(!$compact && $index < count($currentStages) - 1)
                        <div class="step-connector {{ $isCompleted ? 'completed' : '' }}"></div>
                    @endif
                </div>

                <div class="step-content">
                    <div class="step-title font-weight-semibold {{ $isCurrent ? 'text-primary' : '' }}">
                        {{ $stage['label'] }}
                    </div>

                    @if(!$compact)
                        @if($isCurrent)
                            <div class="step-status mt-1">
                                @if($displayStatus === 'submitted')
                                    <span class="badge badge-warning"><i class="fas fa-clock"></i> Menunggu Verifikasi</span>
                                @elseif($displayStatus === 'waiting_supervisor')
                                    <span class="badge badge-warning"><i class="fas fa-user-clock"></i> Menunggu Respons Dosen</span>
                                @elseif($displayStatus === 'ready_next')
                                    <span class="badge badge-info"><i class="fas fa-arrow-right"></i> Siap ke tahap berikutnya</span>
                                @elseif($displayStatus === 'approved')
                                    <span class="badge badge-success"><i class="fas fa-check"></i> Disetujui</span>
                                @elseif($displayStatus === 'revision')
                                    <span class="badge badge-warning"><i class="fas fa-edit"></i> Revisi Diperlukan</span>
                                @elseif($displayStatus === 'rejected')
                                    <span class="badge badge-danger"><i class="fas fa-times"></i> Ditolak</span>
                                @elseif($displayStatus === 'scheduled')
                                    <span class="badge badge-info"><i class="fas fa-calendar-check"></i> Terjadwal</span>
                                @elseif($displayStatus === 'result')
                                    <span class="badge badge-info"><i class="fas fa-clipboard-check"></i> Hasil keluar</span>
                                @elseif($displayStatus === 'done')
                                    <span class="badge badge-secondary"><i class="fas fa-flag-checkered"></i> Selesai</span>
                                @else
                                    <span class="badge badge-secondary text-capitalize">{{ $displayStatus }}</span>
                                @endif
                            </div>
                        @elseif($isCompleted)
                            <div class="step-status mt-1">
                                <small class="text-success"><i class="fas fa-check-circle"></i> Selesai</small>
                            </div>
                        @else
                            <div class="step-status mt-1">
                                <small class="text-muted"><i class="fas fa-hourglass-half"></i> Belum Dimulai</small>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

@once
<style>
    .thesis-timeline { position: relative; }
    .thesis-timeline .timeline-vertical {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .thesis-timeline .timeline-vertical .timeline-step {
        display: flex;
        gap: 1rem;
        position: relative;
    }
    .thesis-timeline .timeline-vertical .step-marker {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
    }
    .thesis-timeline .timeline-vertical .step-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 18px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        z-index: 2;
        position: relative;
        flex-shrink: 0;
    }
    .thesis-timeline .timeline-vertical .step-connector {
        width: 3px;
        flex-grow: 1;
        background: #dee2e6;
        margin-top: 8px;
        margin-bottom: 8px;
        min-height: 28px;
    }
    .thesis-timeline .timeline-vertical .step-connector.completed { background: #28a745; }
    .thesis-timeline .timeline-vertical .step-content {
        flex-grow: 1;
        padding-top: 10px;
        text-align: left;
    }
    .thesis-timeline .timeline-vertical .step-title { font-size: 15px; }
    .thesis-timeline .timeline-horizontal {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 0.5rem;
        overflow-x: auto;
        padding-bottom: 0.5rem;
    }
    .thesis-timeline .timeline-horizontal .timeline-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        min-width: 72px;
        text-align: center;
    }
    .thesis-timeline .timeline-horizontal .step-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 14px;
        margin-bottom: 0.35rem;
    }
    .thesis-timeline .timeline-horizontal .step-title {
        font-size: 11px;
        line-height: 1.2;
    }
    .thesis-timeline .step-icon.bg-success {
        background: linear-gradient(135deg, #28a745, #20c997) !important;
    }
    .thesis-timeline .step-icon.bg-primary {
        background: linear-gradient(135deg, #22004C, #4A0080) !important;
    }
    .thesis-timeline .step-icon.bg-secondary {
        background: #adb5bd !important;
    }
    .thesis-timeline .timeline-step.pending .step-content { opacity: 0.65; }
    .thesis-timeline .timeline-step.current .step-icon {
        animation: thesis-timeline-pulse 2s infinite;
    }
    @keyframes thesis-timeline-pulse {
        0%, 100% { box-shadow: 0 2px 8px rgba(0,0,0,0.15); }
        50% { box-shadow: 0 2px 16px rgba(74, 0, 128, 0.45); }
    }
</style>
@endonce
