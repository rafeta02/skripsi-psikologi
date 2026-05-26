@php
    $scheduleStatus = $schedule->adminValidationStatus();
@endphp
<span class="badge badge-{{ $scheduleStatus['badge'] }} badge-lg">
    <i class="fas fa-{{ $scheduleStatus['icon'] }}"></i> {{ $scheduleStatus['label'] }}
</span>
