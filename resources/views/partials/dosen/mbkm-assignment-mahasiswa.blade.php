{{-- Expects $application (Application) --}}
@php
    $isMbkm = ($application->type ?? null) === 'mbkm';
    $members = $isMbkm
        ? ($application->mbkmRegistration->groupMembers ?? collect())
        : collect();
    $showGroup = $isMbkm && $members->count() > 0;
@endphp
<div class="dosen-mhs-cell">
    <strong>{{ $application->mahasiswa->nama ?? 'N/A' }}</strong>
    @if($showGroup)
        <span class="badge badge-secondary ml-1">Ketua</span>
    @endif
    <br><small class="text-muted">{{ $application->mahasiswa->nim ?? 'N/A' }}</small>
    @if($showGroup)
        <div class="mt-1">
            <small class="text-muted d-block">Kelompok MBKM ({{ $members->count() }} anggota)</small>
            <ul class="list-unstyled mb-0 small pl-2 border-left">
                @foreach($members->sortByDesc(fn ($m) => $m->role === 'ketua') as $member)
                    <li>
                        {{ $member->mahasiswa->nama ?? '-' }}
                        <span class="text-muted">({{ $member->role ?? 'anggota' }})</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
