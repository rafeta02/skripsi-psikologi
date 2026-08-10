@extends('layouts.dosen')

@section('content')
@include('partials.dosen.page-header', [
    'title' => 'Mahasiswa Bimbingan',
    'subtitle' => $dosen->nama . ' · ' . $mahasiswaBimbingan->count() . ' mahasiswa aktif',
])

<div class="row">
    <div class="col-12">
        <div class="mhs-card">
            <div class="mhs-card-body p-0">
                @if($mahasiswaBimbingan->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0 text-center">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Mahasiswa</th>
                                    <th class="text-center">Program Studi</th>
                                    <th class="text-center">Jenis</th>
                                    <th class="text-center">Tahap</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Tanggal</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mahasiswaBimbingan as $index => $bimbingan)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @include('partials.dosen.mbkm-assignment-mahasiswa', [
                                                'application' => $bimbingan->application,
                                            ])
                                        </td>
                                        <td>
                                            {{ $bimbingan->application->mahasiswa->prodi->name ?? 'N/A' }}
                                            <br><small class="text-muted">{{ $bimbingan->application->mahasiswa->jenjang->name ?? '' }}</small>
                                        </td>
                                        <td><span class="badge badge-primary">{{ strtoupper($bimbingan->application->type) }}</span></td>
                                        <td><span class="badge badge-info">{{ ucfirst($bimbingan->application->stage) }}</span></td>
                                        <td>
                                            @php
                                                $statusMap = [
                                                    'submitted' => 'warning',
                                                    'approved' => 'success',
                                                    'rejected' => 'danger',
                                                    'scheduled' => 'info',
                                                    'revision' => 'warning',
                                                    'done' => 'secondary',
                                                ];
                                                $badge = $statusMap[$bimbingan->application->status] ?? 'secondary';
                                            @endphp
                                            <span class="badge badge-{{ $badge }}">{{ ucfirst($bimbingan->application->status) }}</span>
                                        </td>
                                        <td>{{ $bimbingan->assigned_at ? \Carbon\Carbon::parse($bimbingan->assigned_at)->format('d M Y') : '-' }}</td>
                                        <td class="text-nowrap">
                                            @php
                                                $mahasiswaId = $bimbingan->application->mahasiswa_id ?? null;
                                                $defenseManuscript = $mahasiswaId ? ($defenseManuscripts[$mahasiswaId] ?? null) : null;
                                            @endphp
                                            @if($defenseManuscript)
                                                <a href="{{ route('dosen.skripsi-defenses.show', $defenseManuscript->id) }}" class="btn btn-sm btn-primary mb-1">
                                                    <i class="fas fa-book-open"></i> Naskah Sidang
                                                </a>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-outline-primary {{ $defenseManuscript ? 'mb-1' : '' }}" data-toggle="modal" data-target="#timelineModal{{ $bimbingan->application->id }}">
                                                Timeline
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-users fa-2x mb-2 d-block"></i>
                        Belum ada mahasiswa bimbingan aktif.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-12 mt-3">
        <div class="row">
            <div class="col-lg-6">
                @include('partials.dosen.quick-actions')
            </div>
            <div class="col-lg-6">
                @include('partials.dosen.activity-timeline')
            </div>
        </div>
    </div>
</div>

@foreach($mahasiswaBimbingan as $bimbingan)
<div class="modal fade" id="timelineModal{{ $bimbingan->application->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">
                    Timeline — {{ $bimbingan->application->mahasiswa->nama }}
                </h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                @include('components.thesis-timeline', [
                    'application' => $bimbingan->application,
                    'compact' => false
                ])
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
