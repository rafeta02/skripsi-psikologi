{{-- 
    Timeline Component for Thesis Progress
    
    Props:
    - $application: Application model instance
    - $type: 'skripsi' or 'mbkm'
    - $compact: boolean (optional, for smaller display)
--}}

@php
    $compact = $compact ?? false;
    $type = $type ?? $application->type ?? 'skripsi';
    
    // Define stages for each type
    $stages = [
        'skripsi' => [
            ['key' => 'registration', 'label' => 'Pendaftaran', 'icon' => 'file-alt'],
            ['key' => 'supervisor_assignment', 'label' => 'Penugasan Pembimbing', 'icon' => 'user-tie'],
            ['key' => 'proposal_development', 'label' => 'Penyusunan Proposal', 'icon' => 'edit'],
            ['key' => 'reviewer_registration', 'label' => 'Pendaftaran Reviewer', 'icon' => 'users'],
            ['key' => 'proposal_review', 'label' => 'Review Proposal', 'icon' => 'search'],
            ['key' => 'research', 'label' => 'Penelitian', 'icon' => 'microscope'],
            ['key' => 'defense_registration', 'label' => 'Pendaftaran Sidang', 'icon' => 'clipboard-list'],
            ['key' => 'defense_schedule', 'label' => 'Penjadwalan Sidang', 'icon' => 'calendar-alt'],
            ['key' => 'defense', 'label' => 'Sidang Skripsi', 'icon' => 'graduation-cap'],
            ['key' => 'scoring', 'label' => 'Penilaian Akhir', 'icon' => 'star'],
        ],
        'mbkm' => [
            ['key' => 'registration', 'label' => 'Pendaftaran MBKM', 'icon' => 'file-alt'],
            ['key' => 'supervisor_assignment', 'label' => 'Penugasan Pembimbing', 'icon' => 'user-tie'],
            ['key' => 'seminar_registration', 'label' => 'Pendaftaran Seminar', 'icon' => 'presentation'],
            ['key' => 'reviewer_assignment', 'label' => 'Penetapan Reviewer', 'icon' => 'users'],
            ['key' => 'seminar_schedule', 'label' => 'Penjadwalan Seminar', 'icon' => 'calendar-check'],
            ['key' => 'seminar', 'label' => 'Seminar MBKM', 'icon' => 'chalkboard-teacher'],
            ['key' => 'research', 'label' => 'Penelitian', 'icon' => 'microscope'],
            ['key' => 'defense_registration', 'label' => 'Pendaftaran Sidang', 'icon' => 'clipboard-list'],
            ['key' => 'defense_schedule', 'label' => 'Penjadwalan Sidang', 'icon' => 'calendar-alt'],
            ['key' => 'defense', 'label' => 'Sidang Skripsi', 'icon' => 'graduation-cap'],
            ['key' => 'scoring', 'label' => 'Penilaian Akhir', 'icon' => 'star'],
        ],
    ];
    
    $currentStages = $stages[$type];
    
    // Determine current stage and completion status
    $currentStage = $application->stage ?? 'registration';
    $currentStatus = $application->status ?? 'submitted';
    
    // Map stage to index
    $stageIndex = collect($currentStages)->search(function($stage) use ($currentStage) {
        return $stage['key'] === $currentStage;
    });
    
    if ($stageIndex === false) $stageIndex = 0;
@endphp

<div class="thesis-timeline {{ $compact ? 'timeline-compact' : '' }}">
    @if(!$compact)
        <div class="timeline-header mb-4">
            <h5 class="font-weight-bold mb-2">
                <i class="fas fa-route mr-2"></i> Progress {{ ucfirst($type) }}
            </h5>
            <div class="d-flex align-items-center">
                <div class="progress flex-grow-1 mr-3" style="height: 8px;">
                    <div class="progress-bar bg-gradient" style="width: {{ ($stageIndex + 1) / count($currentStages) * 100 }}%; background: linear-gradient(90deg, var(--primary-500), var(--secondary-500))"></div>
                </div>
                <span class="badge badge-primary">{{ $stageIndex + 1 }}/{{ count($currentStages) }}</span>
            </div>
        </div>
    @endif
    
    <div class="timeline-steps {{ $compact ? 'timeline-horizontal' : 'timeline-vertical' }}">
        @foreach($currentStages as $index => $stage)
            @php
                $isCompleted = $index < $stageIndex;
                $isCurrent = $index === $stageIndex;
                $isPending = $index > $stageIndex;
                
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
                                @if($currentStatus === 'submitted')
                                    <span class="badge badge-warning"><i class="fas fa-clock"></i> Menunggu Verifikasi</span>
                                @elseif($currentStatus === 'approved')
                                    <span class="badge badge-success"><i class="fas fa-check"></i> Disetujui</span>
                                @elseif($currentStatus === 'revision')
                                    <span class="badge badge-warning"><i class="fas fa-edit"></i> Revisi Diperlukan</span>
                                @elseif($currentStatus === 'rejected')
                                    <span class="badge badge-danger"><i class="fas fa-times"></i> Ditolak</span>
                                @elseif($currentStatus === 'scheduled')
                                    <span class="badge badge-info"><i class="fas fa-calendar-check"></i> Terjadwal</span>
                                @elseif($currentStatus === 'done')
                                    <span class="badge badge-secondary"><i class="fas fa-flag-checkered"></i> Selesai</span>
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

@push('styles')
<style>
    .thesis-timeline {
        position: relative;
    }
    
    /* Vertical Timeline (default) */
    .timeline-vertical {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .timeline-vertical .timeline-step {
        display: flex;
        gap: 1rem;
        position: relative;
    }
    
    .timeline-vertical .step-marker {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
    }
    
    .timeline-vertical .step-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        z-index: 2;
        position: relative;
    }
    
    .timeline-vertical .step-connector {
        width: 3px;
        flex-grow: 1;
        background: var(--gray-300);
        margin-top: 8px;
        margin-bottom: 8px;
        min-height: 30px;
    }
    
    .timeline-vertical .step-connector.completed {
        background: var(--success);
    }
    
    .timeline-vertical .step-content {
        flex-grow: 1;
        padding-top: 12px;
    }
    
    .timeline-vertical .step-title {
        font-size: 16px;
    }
    
    /* Horizontal Timeline (compact) */
    .timeline-horizontal {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 0.5rem;
        overflow-x: auto;
        padding-bottom: 1rem;
    }
    
    .timeline-horizontal .timeline-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        min-width: 80px;
        text-align: center;
    }
    
    .timeline-horizontal .step-marker {
        position: relative;
        margin-bottom: 0.5rem;
    }
    
    .timeline-horizontal .step-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    
    .timeline-horizontal .step-content {
        max-width: 80px;
    }
    
    .timeline-horizontal .step-title {
        font-size: 11px;
        line-height: 1.2;
    }
    
    /* Status Colors */
    .bg-success {
        background: linear-gradient(135deg, #28a745, #20c997) !important;
    }
    
    .bg-primary {
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500)) !important;
    }
    
    .bg-secondary {
        background: var(--gray-400) !important;
    }
    
    /* Step States */
    .timeline-step.completed .step-content {
        opacity: 0.8;
    }
    
    .timeline-step.current .step-icon {
        animation: pulse 2s infinite;
    }
    
    .timeline-step.pending .step-content {
        opacity: 0.6;
    }
    
    @keyframes pulse {
        0%, 100% {
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        50% {
            box-shadow: 0 2px 16px rgba(var(--primary-500-rgb, 127, 0, 255), 0.4);
        }
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .timeline-vertical .step-icon {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }
        
        .timeline-vertical .step-title {
            font-size: 14px;
        }
    }
</style>
@endpush
