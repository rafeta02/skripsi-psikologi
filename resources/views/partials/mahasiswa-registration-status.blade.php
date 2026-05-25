@php
    $regStatus = $application->getRegistrationStatusForMahasiswa();
    $badgeClass = match ($regStatus['badge']) {
        'success' => 'badge-success',
        'danger' => 'badge-danger',
        'info' => 'badge-info',
        'secondary' => 'badge-secondary',
        default => 'badge-warning',
    };
@endphp
<span class="badge {{ $badgeClass }} badge-lg px-3 py-2" style="font-size: 14px;">
    <i class="fas fa-{{ $regStatus['icon'] ?? 'info-circle' }}"></i> {{ $regStatus['label'] }}
</span>
